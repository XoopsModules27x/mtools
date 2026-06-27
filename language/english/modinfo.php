<?php declare(strict_types=1);
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    2000-2026 XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 */

// The name of this module
define('_MI_MTOOLS_NAME', 'mTools');
define('_MI_MTOOLS_DESC', 'Module Tools');

//Menu
define('_MI_MTOOLS_MENU_HOME', 'Home');
define('_MI_MTOOLS_MENU_01', 'Admin');
define('_MI_MTOOLS_MENU_ABOUT', 'About');

//Config
define('MI_MTOOLS_EDITOR_ADMIN', 'Editor: Admin');
define('MI_MTOOLS_EDITOR_ADMIN_DESC', 'Select the Editor to use by the Admin');
define('MI_MTOOLS_EDITOR_USER', 'Editor: User');
define('MI_MTOOLS_EDITOR_USER_DESC', 'Select the Editor to use by the User');

//Help
define('_MI_MTOOLS_DIRNAME', basename(dirname(__DIR__, 2)));
define('_MI_MTOOLS_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_MTOOLS_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_MTOOLS_OVERVIEW', 'Overview');

//define('_MI_MTOOLS_HELP_DIR', __DIR__);

//help multipage
define('_MI_MTOOLS_DISCLAIMER', 'Disclaimer');
define('_MI_MTOOLS_LICENSE', 'License');
define('_MI_MTOOLS_SUPPORT', 'Support');

define('_MI_MTOOLS_QRCODE_BLOCK_NAME', 'This page QR Code');
define('_MI_MTOOLS_QRCODE_BLOCK_DESC', 'This page QR Code block (mtools_qrcode)');
define('_MI_MTOOLS_APP_BLOCK_NAME', 'This site App download settings');
define('_MI_MTOOLS_APP_BLOCK_DESC', 'This site App download settings block (mtools_app)');
