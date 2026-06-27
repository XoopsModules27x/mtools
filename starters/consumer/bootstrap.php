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
 * Consumer module bootstrap for mTools.
 *
 * Replace this file's placeholders after copying:
 * - namespace autoload is inferred from the consumer dirname
 * - dependency helper function lives in include/mtools_dependency.php
 */

require_once __DIR__ . '/preloads/autoloader.php';

if (defined('XOOPS_ROOT_PATH')) {
    $mtoolsBootstrap = XOOPS_ROOT_PATH . '/modules/mtools/bootstrap.php';
    if (is_file($mtoolsBootstrap)) {
        require_once $mtoolsBootstrap;
    }
}

require_once __DIR__ . '/include/mtools_dependency.php';

