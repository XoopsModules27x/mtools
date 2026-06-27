<?php declare(strict_types=1);

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

class MtoolsCorePreload extends \XoopsPreloadItem
{
    public static function eventCoreFooterStart($args): void
    {
        // Shared-helper consumers must not inherit UI assets, session writes, or
        // database writes just because mtools is installed. Theme/bootstrap
        // behavior belongs behind an explicit opt-in service.
    }

    // to add PSR-4 autoloader

    /**
     * @param $args
     */
    public static function eventCoreIncludeCommonEnd(array $args): void
    {
        require \dirname(__DIR__) . '/bootstrap.php';
    }
}
