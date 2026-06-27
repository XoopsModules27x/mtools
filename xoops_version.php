<?php declare(strict_types=1);

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.
*/
/**
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       2000-2026 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

global $xoopsConfig;

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

// ------------------- Information -------------------
$modversion = [
    'version'             => '1.2.0',
    'module_status'       => 'Beta 1',
    'release_date'        => '2026/06/18',
    'name'                => _MI_MTOOLS_NAME,
    'description'         => _MI_MTOOLS_DESC,
    'official'            => 0,    //1 indicates official XOOPS module supported by XOOPS Dev Team, 0 means 3rd party supported
    'author'              => 'Mamba (https://github/mambax7)',
    'credits'             => 'Tad (https://github.com/tad0616/)',
    'author_mail'         => 'author-email',
    'author_website_url'  => 'https://xoops.org',
    'author_website_name' => 'XOOPS',
    'license'             => 'GPL 2.0 or later',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html/',
    // ------------------- Folders & Files -------------------
    'release_info'        => 'Changelog',
    'release_file'        => XOOPS_URL . "/modules/$moduleDirName/docs/changelog.txt",
    'manual'              => 'link to manual file',
    'manual_file'         => XOOPS_URL . "/modules/$moduleDirName/docs/install.txt",
    // images
    'image' => 'assets/images/logo' . ucfirst((string) ($xoopsConfig['language'] ?? 'english')) . '.png',
    'iconsmall'           => 'assets/images/iconsmall.png',
    'iconbig'             => 'assets/images/iconbig.png',
    'dirname'             => $moduleDirName,
    // Local path icons
    'modicons16'          => 'assets/images/icons/16',
    'modicons32'          => 'assets/images/icons/32',
    'demo_site_url'       => 'https://xoops.org',
    'demo_site_name'      => 'XOOPS Demo Site',
    'support_url'         => 'https://xoops.org/modules/newbb/viewforum.php?forum=28/',
    'support_name'        => 'Support Forum',
    'submit_bug'          => 'https://github.com/XoopsModules25x/' . $moduleDirName . '/issues',
    'module_website_url'  => 'www.xoops.org',
    'module_website_name' => 'XOOPS Project',
    // ------------------- Min Requirements -------------------
    'min_php'             => '8.2',
    'min_xoops'           => '2.7.0',
    'min_admin'           => '1.2',
    'min_db'              => ['mysql' => '5.5'],
    // ------------------- Admin Menu -------------------
    'system_menu'         => 1,
    'hasAdmin'            => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    // ------------------- Main Menu -------------------
    'hasMain'             => 0,
    // ------------------- Mysql -----------------------------
    'sqlfile'             => ['mysql' => 'sql/mysql.sql'],
    // ------------------- Tables ----------------------------
    'tables'              => [
        $moduleDirName . '_' . 'setup',
    ],
];

// ------------------- Help files ------------------- //
$modversion['help']        = 'page=help';
$modversion['helpsection'] = [
    ['name' => _MI_MTOOLS_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_MTOOLS_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_MTOOLS_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_MTOOLS_SUPPORT, 'link' => 'page=support'],
];

// ------------------- Templates -------------------
$modversion['templates'] = [
    ['file' => 'mtools_adm_index.tpl', 'description' => 'mtools_adm_index.tpl'],
];
