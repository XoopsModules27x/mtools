<?php declare(strict_types=1);

namespace XoopsModules\Mtools\Common;

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

use Xoops\Helpers\Service\Cache;
use Xoops\Helpers\Utility\Retry;

/**
 * Optional remote update checks. Keep this separate from local version checks.
 *
 * @api Stable Common-tier API (Lab\* is experimental, module-local code is private).
 */
final class UpdateChecker
{
    public static function checkVerModule(\Xmf\Module\Helper $helper, ?string $source = 'github', ?string $default = 'master'): ?array
    {
        $module = $helper->getModule();
        $moduleDirName = $module instanceof \XoopsModule
            ? basename((string)$module->getVar('dirname'))
            : basename(\dirname(__DIR__, 2));
        $moduleDirNameUpper = mb_strtoupper($moduleDirName);
        $repository         = 'XoopsModules25x/' . $moduleDirName;

        if ('github' !== $source || !function_exists('curl_init')) {
            return null;
        }

        // Cache the resolved update result for an hour to avoid hammering the
        // GitHub API on every admin page load. The whole computation is
        // deterministic given the remote release data, so caching the final
        // ?array preserves the existing return contract.
        return Cache::remember(
            'mtools_update_' . $moduleDirName,
            3600,
            static function () use ($repository, $module, $default): ?array {
                $infoReleasesUrl = "https://api.github.com/repos/$repository/releases";

                // Transient network read — retry twice before giving up. On
                // exhausted retries, rescue() emits the cURL error and yields
                // false, preserving the original "return null on failure" contract.
                $curlReturn = Retry::rescue(
                    static function () use ($infoReleasesUrl) {
                        return Retry::retry(2, static function () use ($infoReleasesUrl) {
                            $curlHandle = curl_init();
                            if (false === $curlHandle) {
                                throw new \RuntimeException('Unable to initialise cURL');
                            }

                            curl_setopt($curlHandle, CURLOPT_URL, $infoReleasesUrl);
                            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, true);
                            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, ['User-Agent: XOOPS mtools']);

                            $result = curl_exec($curlHandle);
                            if (false === $result) {
                                $error = curl_error($curlHandle);
                                curl_close($curlHandle);
                                throw new \RuntimeException($error);
                            }

                            curl_close($curlHandle);

                            return $result;
                        });
                    },
                    false,
                    static function (\Throwable $e): bool {
                        trigger_error($e->getMessage());

                        return true;
                    }
                );

                if (false === $curlReturn) {
                    return null;
                }

                if (false !== mb_strpos((string)$curlReturn, 'Not Found')) {
                    trigger_error('Repository Not Found: ' . $infoReleasesUrl);

                    return null;
                }

                $releases = json_decode((string)$curlReturn, false);
                if (!is_array($releases) || [] === $releases || !isset($releases[0]->tag_name)) {
                    return null;
                }

                $latestVersionLink = sprintf("https://github.com/$repository/archive/%s.zip", $releases ? reset($releases)->tag_name : $default);
                $latestVersion     = (string)$releases[0]->tag_name;
                $prerelease        = (bool)($releases[0]->prerelease ?? false);
                $updateLabel       = defined('_CO_MTOOLS_NEW_VERSION')
                    ? constant('_CO_MTOOLS_NEW_VERSION')
                    : 'New version: ';

                $normalizedLatest = self::normalizeVersion($latestVersion);
                $moduleVersion = $module instanceof \XoopsModule
                    ? self::normalizeVersion($module->getInfo('version') . '_' . $module->getInfo('module_status'))
                    : '0.0.0';

                if (!$prerelease && version_compare($moduleVersion, $normalizedLatest, '<')) {
                    return [
                        $updateLabel . $latestVersion,
                        $latestVersionLink,
                    ];
                }

                return null;
            }
        );
    }

    private static function normalizeVersion(string $version): string
    {
        $version = str_replace(' ', '', mb_strtolower($version));

        if (false !== mb_strpos($version, 'final')) {
            $version = str_replace(['_', 'final'], '', $version);
        }

        return $version;
    }
}
