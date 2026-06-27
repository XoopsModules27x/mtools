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

/**
 * Public, side-effect-free bootstrap for modules that consume mtools helpers.
 *
 * This file registers the mtools namespace and exposes the API version contract.
 * It must not load XOOPS mainfile, inject assets, redirect, write to the
 * database, or depend on preload execution order.
 */

if (!defined('MTOOLS_PATH')) {
    define('MTOOLS_PATH', __DIR__);
}

if (defined('XOOPS_URL') && !defined('MTOOLS_URL')) {
    define('MTOOLS_URL', XOOPS_URL . '/modules/mtools');
}

require_once __DIR__ . '/preloads/autoloader.php';

if (!defined('MTOOLS_API_VERSION')) {
    define('MTOOLS_API_VERSION', \XoopsModules\Mtools\Bootstrap::API_VERSION);
}

// Showcase dependency: the xoops/helpers utility library (Xoops\Helpers\*) is
// expected from the core library set (loaded via include/common.php's vendor
// autoloader). Expose a side-effect-free flag so consumers/blocks can degrade
// gracefully if it is ever absent, rather than fataling on a missing class.
if (!defined('MTOOLS_HELPERS_AVAILABLE')) {
    define('MTOOLS_HELPERS_AVAILABLE', class_exists(\Xoops\Helpers\Utility\Str::class));
}

