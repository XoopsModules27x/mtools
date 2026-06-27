<?php declare(strict_types=1);

/**
 * scan-common.php — READ-ONLY mtools adoption scanner.
 *
 * Reports, per XOOPS module, how far it has been converted to consume mtools:
 * which local class/Common/ copies still shadow mtools' canonical classes, whether the
 * consumer scaffold (bootstrap, min_modules, Utility adapter, dependency shim) is in
 * place, Configurator no-argument traps, remaining local-Common references, and tests.
 *
 * It writes NOTHING — it only reads files. Run it before/after converting a module.
 *
 *   php htdocs/modules/mtools/tools/scan-common.php            # scan every module
 *   php htdocs/modules/mtools/tools/scan-common.php quotes     # scan one module
 *
 * @category  Module
 * @package   mtools
 * @author    Mamba <mambax7@gmail.com>
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

if ('cli' !== PHP_SAPI) {
    fwrite(STDERR, "scan-common.php must be run from the command line.\n");
    exit(1);
}

$modulesRoot  = \dirname(__DIR__, 2);          // .../htdocs/modules
$mtoolsCommon = \dirname(__DIR__) . '/class/Common';
$selfDirname  = \basename(\dirname(__DIR__));  // 'mtools'

// Canonical mtools Common class basenames (skip interfaces — they aren't "copies").
$canonical = [];
foreach (\glob($mtoolsCommon . '/*.php') ?: [] as $file) {
    $base = \basename($file, '.php');
    if (!\str_ends_with($base, 'Interface')) {
        $canonical[$base] = true;
    }
}

$arg     = $argv[1] ?? null;
$targets = $arg !== null
    ? [$modulesRoot . '/' . $arg]
    : (\glob($modulesRoot . '/*', \GLOB_ONLYDIR) ?: []);

$summary = [];
foreach ($targets as $dir) {
    $name = \basename($dir);
    if (\in_array($name, [$selfDirname, 'system'], true)) {
        continue;
    }
    if (!\is_file($dir . '/xoops_version.php')) {
        if ($arg !== null) {
            fwrite(STDERR, "Not a module (no xoops_version.php): {$dir}\n");
            exit(1);
        }
        continue;
    }
    $summary[$name] = scanModule($name, $dir, $canonical);
}

printSummary($summary);

// ----------------------------------------------------------------------------

/**
 * @param array<string,bool> $canonical
 * @return array{verdict:string, localShadow:int, refs:int, traps:int}
 */
function scanModule(string $name, string $dir, array $canonical): array
{
    $phpFiles = collectPhpFiles($dir);

    // --- consumer scaffold checks ---
    $hasBootstrap  = \is_file($dir . '/bootstrap.php');
    $hasMinModules = fileMatches($dir . '/xoops_version.php', "/min_modules/")
                     && fileMatches($dir . '/xoops_version.php', "/mtools/");
    $hasAdapter    = fileMatches($dir . '/class/Utility.php', "/extends\s+[\\\\\\w]*SysUtility/");
    $hasDepGuard   = [] !== \glob($dir . '/include/*_mtools_dependency.php')
                     || anyFileMatches($phpFiles, "/_mtools_dependency_error|ConsumerRuntime/");

    // --- local Common copies ---
    $localCommon = [];
    foreach (\glob($dir . '/class/Common/*.php') ?: [] as $f) {
        $b = \basename($f, '.php');
        if ('index' !== $b) {
            $localCommon[] = $b;
        }
    }
    $shadow   = \array_values(\array_intersect($localCommon, \array_keys($canonical)));
    $localOnly = \array_values(\array_diff($localCommon, \array_keys($canonical)));

    // --- remaining local-Common references (heuristic: Common\ refs not via Mtools\) ---
    $refFiles = [];
    foreach ($phpFiles as $f) {
        $code = (string)@\file_get_contents($f);
        $all  = \preg_match_all('/\bCommon\\\\[A-Z]\w+/', $code);
        $mt   = \preg_match_all('/Mtools\\\\Common\\\\[A-Z]\w+/', $code);
        if ($all > $mt) {
            $refFiles[] = relPath($f, $dir);
        }
    }

    // --- Configurator no-argument traps ---
    $trapFiles = [];
    foreach ($phpFiles as $f) {
        if (fileMatches($f, '/new\s+[\\\\\\w]*Configurator\s*\(\s*\)/')) {
            $trapFiles[] = relPath($f, $dir);
        }
    }

    // --- tests ---
    $hasTests = [] !== glob_recursive($dir . '/tests', '*Test.php');

    $verdict = verdict($hasBootstrap, $hasAdapter, $localCommon);

    // --- print ---
    $C = colors();
    echo "\n{$C['hdr']}== {$name} " . str_repeat('=', max(3, 56 - strlen($name))) . "{$C['off']}\n";
    echo '  ' . mark($hasBootstrap) . " bootstrap.php" . pad(28, 'bootstrap.php')
        . mark($hasMinModules) . " min_modules => mtools\n";
    echo '  ' . mark($hasAdapter) . " Utility extends SysUtility" . pad(28, 'Utility extends SysUtility')
        . mark($hasDepGuard) . " dependency shim\n";

    if ([] !== $localCommon) {
        echo "  local class/Common/ (" . count($localCommon) . " files, " . count($shadow) . " shadow mtools):\n";
        foreach ($shadow as $b) {
            echo "      {$C['warn']}{$b}.php{$C['off']}  <- shadows mtools (delete; use Mtools\\Common)\n";
        }
        foreach ($localOnly as $b) {
            echo "      {$b}.php  <- local-only (no mtools equivalent)\n";
        }
    } else {
        echo "  local class/Common/: {$C['ok']}none{$C['off']}\n";
    }

    echo "  local Common\\ references: " . count($refFiles) . (count($refFiles) ? ' files' : '') . "\n";
    if ([] !== $trapFiles) {
        echo "  {$C['warn']}Configurator no-arg traps: " . count($trapFiles) . " ({$C['off']}"
            . implode(', ', $trapFiles) . ")\n";
    } else {
        echo "  Configurator no-arg traps: 0\n";
    }
    echo '  tests: ' . ($hasTests ? "{$C['ok']}present{$C['off']}" : 'none') . "\n";
    echo "  VERDICT: " . verdictColored($verdict) . verdictDetail($verdict, $shadow, $refFiles) . "\n";

    return ['verdict' => $verdict, 'localShadow' => count($shadow), 'refs' => count($refFiles), 'traps' => count($trapFiles)];
}

/** @param string[] $localCommon */
function verdict(bool $bootstrap, bool $adapter, array $localCommon): string
{
    if ([] === $localCommon) {
        return ($bootstrap && $adapter) ? 'converted' : 'not a consumer';
    }
    if ($bootstrap && $adapter) {
        return 'in progress';
    }
    return 'not started';
}

/** @param string[] $shadow @param string[] $refs */
function verdictDetail(string $verdict, array $shadow, array $refs): string
{
    if ('in progress' === $verdict) {
        return "  (remaining: swap " . count($refs) . " ref-files, delete " . count($shadow) . " shadow copies)";
    }
    if ('not started' === $verdict) {
        return "  (add bootstrap + Utility adapter + min_modules + dep shim, then swap refs)";
    }
    return '';
}

/** @param array<string,array{verdict:string,localShadow:int,refs:int,traps:int}> $summary */
function printSummary(array $summary): void
{
    if (count($summary) < 2) {
        return;
    }
    $C = colors();
    $buckets = ['converted' => [], 'in progress' => [], 'not started' => [], 'not a consumer' => []];
    foreach ($summary as $name => $row) {
        $buckets[$row['verdict']][] = $name;
    }
    echo "\n{$C['hdr']}== SUMMARY (" . count($summary) . " modules) " . str_repeat('=', 30) . "{$C['off']}\n";
    foreach (['converted', 'in progress', 'not started', 'not a consumer'] as $v) {
        echo '  ' . verdictColored($v) . ': ' . count($buckets[$v]) . "\n";
        if ('in progress' === $v || 'not started' === $v) {
            foreach ($buckets[$v] as $n) {
                echo "      - {$n}\n";
            }
        }
    }
}

// ---- small helpers ----

/** @return string[] */
function collectPhpFiles(string $dir): array
{
    $out = [];
    if (!\is_dir($dir)) {
        return $out;
    }
    $it = new \RecursiveIteratorIterator(
        new \RecursiveCallbackFilterIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            static function ($current) {
                $n = $current->getFilename();
                return !\in_array($n, ['vendor', 'node_modules', '.git'], true);
            }
        )
    );
    foreach ($it as $f) {
        if ($f->isFile() && 'php' === \strtolower($f->getExtension())) {
            $out[] = $f->getPathname();
        }
    }
    return $out;
}

/** @return string[] */
function glob_recursive(string $dir, string $pattern): array
{
    if (!\is_dir($dir)) {
        return [];
    }
    $out = [];
    $it  = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));
    foreach ($it as $f) {
        if ($f->isFile() && \fnmatch($pattern, $f->getFilename())) {
            $out[] = $f->getPathname();
        }
    }
    return $out;
}

function fileMatches(string $file, string $regex): bool
{
    return \is_file($file) && 1 === \preg_match($regex, (string)@\file_get_contents($file));
}

/** @param string[] $files */
function anyFileMatches(array $files, string $regex): bool
{
    foreach ($files as $f) {
        if (fileMatches($f, $regex)) {
            return true;
        }
    }
    return false;
}

function relPath(string $file, string $base): string
{
    return \ltrim(\str_replace('\\', '/', \substr($file, \strlen($base))), '/');
}

function pad(int $width, string $label): string
{
    return \str_repeat(' ', \max(1, $width - \strlen($label)));
}

function mark(bool $ok): string
{
    $C = colors();
    return $ok ? "{$C['ok']}[x]{$C['off']}" : "{$C['warn']}[ ]{$C['off']}";
}

function verdictColored(string $v): string
{
    $C   = colors();
    $col = ['converted' => $C['ok'], 'in progress' => $C['warn'], 'not started' => $C['err'], 'not a consumer' => $C['dim']][$v] ?? '';
    return "{$col}{$v}{$C['off']}";
}

/** @return array<string,string> */
function colors(): array
{
    static $c;
    if (null === $c) {
        $on = \function_exists('stream_isatty') && @\stream_isatty(STDOUT) && false === \getenv('NO_COLOR');
        $c  = $on
            ? ['ok' => "\033[32m", 'warn' => "\033[33m", 'err' => "\033[31m", 'dim' => "\033[2m", 'hdr' => "\033[1;36m", 'off' => "\033[0m"]
            : ['ok' => '', 'warn' => '', 'err' => '', 'dim' => '', 'hdr' => '', 'off' => ''];
    }
    return $c;
}
