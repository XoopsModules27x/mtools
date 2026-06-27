<?php declare(strict_types=1);

namespace XoopsModules\Mtools;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.
*/

/**
 * @copyright 2000-2026 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author    XOOPS Development Team
 */

use XoopsModules\Mtools\Module\Dependency;

/**
 * Stable runtime contract for modules consuming mtools as a shared helper layer.
 *
 * Dependency policy — mtools must be INSTALLED and meet the minimum version; it does NOT
 * need to be ACTIVE. A consumer loads mtools' helper classes directly via its own
 * bootstrap.php (the file path), so they are available whenever the files are on disk,
 * regardless of mtools' active state. Requiring "active" would falsely break a working
 * consumer when an admin merely deactivates mtools, and is stricter than XOOPS's own
 * `min_modules` (installed + version). Pass `$requireActive = true` to {@see checkRuntime()}
 * only if your module additionally needs an mtools feature that exists only while it is active.
 */
final class Bootstrap
{
    public const API_VERSION = '1.0.0';
    public const MIN_MODULE_VERSION = '1.1.0';
    public const MODULE_DIRNAME = 'mtools';

    public static function apiVersion(): string
    {
        return self::API_VERSION;
    }

    /**
     * Check that mtools is installed and version-compatible (the consumer runtime contract).
     *
     * @param string $minimumApiVersion    minimum mtools API version the consumer needs
     * @param string $minimumModuleVersion minimum mtools module version the consumer needs
     * @param bool   $requireActive        require mtools to be ACTIVE, not just installed
     *                                      (default false — see the class-level policy)
     *
     * @return array{ok: bool, errors: list<string>, module_version: string|null, api_version: string}
     */
    public static function checkRuntime(
        string $minimumApiVersion = self::API_VERSION,
        string $minimumModuleVersion = self::MIN_MODULE_VERSION,
        bool $requireActive = false
    ): array {
        $errors = [];

        $moduleStatus = Dependency::checkModule(self::MODULE_DIRNAME, $minimumModuleVersion, $requireActive);
        $errors = $moduleStatus['errors'];
        $moduleVersion = $moduleStatus['module_version'];

        if (version_compare(self::API_VERSION, self::normalizeVersion($minimumApiVersion), '<')) {
            $errors[] = sprintf(
                'mtools API %s is required; API %s is available.',
                $minimumApiVersion,
                self::API_VERSION
            );
        }

        return self::status($errors, $moduleVersion);
    }

    public static function assertRuntime(
        string $minimumApiVersion = self::API_VERSION,
        string $minimumModuleVersion = self::MIN_MODULE_VERSION,
        bool $requireActive = false
    ): void {
        $status = self::checkRuntime($minimumApiVersion, $minimumModuleVersion, $requireActive);

        if (!$status['ok']) {
            throw new \RuntimeException(implode(' ', $status['errors']));
        }
    }

    public static function statusMessage(array $status): string
    {
        return implode(' ', $status['errors'] ?? []);
    }

    /**
     * @param list<string> $errors
     *
     * @return array{ok: bool, errors: list<string>, module_version: string|null, api_version: string}
     */
    private static function status(array $errors, ?string $moduleVersion): array
    {
        return [
            'ok'             => [] === $errors,
            'errors'         => $errors,
            'module_version' => $moduleVersion,
            'api_version'    => self::API_VERSION,
        ];
    }

    private static function normalizeVersion(string $version): string
    {
        $version = trim($version);

        if ('' === $version) {
            return '0.0.0';
        }

        if (preg_match('/^\d+$/', $version) === 1 && (int)$version >= 100) {
            return number_format(((int)$version) / 100, 2, '.', '');
        }

        return preg_replace('/-(alpha|beta|rc)\d*/i', '', $version) ?? $version;
    }
}
