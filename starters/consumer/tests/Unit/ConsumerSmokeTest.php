<?php declare(strict_types=1);

namespace XoopsModules\Mymodule\Tests\Unit;

use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\SysUtility;
use XoopsModules\Mymodule\Utility;

/**
 * Consumer smoke test — verifies a module correctly consumes mTools.
 *
 * COPY this into your module at `tests/Unit/ConsumerSmokeTest.php` and replace
 * `Mymodule` (namespace segment) and `mymodule` (dirname) throughout. It checks the
 * three things every converted module must satisfy:
 *   1. the module bootstrap loads without fatal,
 *   2. the dependency shim returns a sane string (never fatals — absence-safe), and
 *   3. the local `Utility` adapter extends mTools' `Common\SysUtility`
 *      (so no local `Common\SysUtility` copy is needed).
 *
 * Runs in unit-only mode. The SysUtility-extension assertion self-skips when mtools is
 * not reachable in a bare run (no booted XOOPS); it is exercised in integration / on the
 * live site, where the module bootstrap loads mtools.
 *
 * Note: this file expects to live at `<module>/tests/Unit/` — `dirname(__DIR__, 2)` is the
 * module root. Adjust the depth if you place it elsewhere.
 */
final class ConsumerSmokeTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        // Module root bootstrap: registers the module autoloader and, when XOOPS is
        // present, loads mtools and the `mymodule_mtools_dependency_error()` shim.
        require_once \dirname(__DIR__, 2) . '/bootstrap.php';
    }

    public function testDependencyShimReturnsAString(): void
    {
        self::assertTrue(
            \function_exists('mymodule_mtools_dependency_error'),
            'include/mtools_dependency.php must define mymodule_mtools_dependency_error().'
        );

        // '' when ready, else a human message — but ALWAYS a string, never a fatal.
        self::assertIsString(mymodule_mtools_dependency_error());
    }

    public function testUtilityAdapterExtendsMtoolsSysUtility(): void
    {
        if (!\class_exists(SysUtility::class)) {
            self::markTestSkipped('mtools not reachable in this run; adapter contract is covered in integration.');
        }

        self::assertTrue(
            \is_subclass_of(Utility::class, SysUtility::class),
            'class/Utility.php must extend XoopsModules\\Mtools\\Common\\SysUtility — do not copy SysUtility locally.'
        );
    }
}
