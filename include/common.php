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

use XoopsModules\Mtools\Helper;
use XoopsModules\Mtools\Utility;

/** @var Helper $helper */
/** @var Utility $utility */
require \dirname(__DIR__) . '/bootstrap.php';

$moduleDirName      = \basename(\dirname(__DIR__));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

/** @var \XoopsDatabase $db */
$db      = \XoopsDatabaseFactory::getDatabaseConnection();
$debug   = false;
$helper  = Helper::getInstance($debug);
$utility = new Utility();

$helper->loadLanguage('common');

//handlers
//$categoryHandler     = new Mtools\CategoryHandler($db);
//$downloadHandler     = new Mtools\DownloadHandler($db);

$pathIcon16 = \Xmf\Module\Admin::iconUrl('', '16');
$pathIcon32 = \Xmf\Module\Admin::iconUrl('', '32');
if (is_object($helper->getModule())) {
    $pathModIcon16 = $helper->getModule()->getInfo('modicons16');
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}

if (!defined($moduleDirNameUpper . '_CONSTANTS_DEFINED')) {
    $modulePath = XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/';
    $moduleUrl  = \Xoops\Helpers\Service\Url::module($moduleDirName) . '/';
    $moduleConstants = [
        $moduleDirNameUpper . '_' . 'DIRNAME'     => basename(dirname(__DIR__)),
        $moduleDirNameUpper . '_ROOT_PATH'        => $modulePath,
        $moduleDirNameUpper . '_PATH'             => $modulePath,
        $moduleDirNameUpper . '_URL'              => $moduleUrl,
        $moduleDirNameUpper . '_IMAGE_URL'        => $moduleUrl . '/assets/images/',
        $moduleDirNameUpper . '_IMAGE_PATH'       => $modulePath . '/assets/images',
        $moduleDirNameUpper . '_ADMIN_URL'        => $moduleUrl . '/admin/',
        $moduleDirNameUpper . '_ADMIN_PATH'       => $modulePath . '/admin/',
        $moduleDirNameUpper . '_ADMIN'            => $moduleUrl . '/admin/index.php',
        $moduleDirNameUpper . '_UPLOAD_URL'       => XOOPS_UPLOAD_URL . '/' . $moduleDirName,
        $moduleDirNameUpper . '_UPLOAD_PATH'      => XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
        $moduleDirNameUpper . '_AUTHOR_LOGOIMG'   => $pathIcon32 . '/xoopsmicrobutton.gif',
    ];

    foreach ($moduleConstants as $constantName => $constantValue) {
        if (!defined($constantName)) {
            define($constantName, $constantValue);
        }
    }
    //    define($moduleDirNameUpper . '_AUTHOR_LOGOIMG', constant($moduleDirNameUpper . '_URL') . '/assets/images/logoModule.png');
    define($moduleDirNameUpper . '_CONSTANTS_DEFINED', 1);
}

$icons = [
    'edit'    => "<img src='" . $pathIcon16 . "/edit.png'  alt=" . _EDIT . "' align='middle'>",
    'delete'  => "<img src='" . $pathIcon16 . "/delete.png' alt='" . _DELETE . "' align='middle'>",
    'clone'   => "<img src='" . $pathIcon16 . "/editcopy.png' alt='" . _CLONE . "' align='middle'>",
    'preview' => "<img src='" . $pathIcon16 . "/view.png' alt='" . _PREVIEW . "' align='middle'>",
    'print'   => "<img src='" . $pathIcon16 . "/printer.png' alt='" . _CLONE . "' align='middle'>",
    'pdf'     => "<img src='" . $pathIcon16 . "/pdf.png' alt='" . _CLONE . "' align='middle'>",
    'add'     => "<img src='" . $pathIcon16 . "/add.png' alt='" . _ADD . "' align='middle'>",
    '0'       => "<img src='" . $pathIcon16 . "/0.png' alt='" . 0 . "' align='middle'>",
    '1'       => "<img src='" . $pathIcon16 . "/1.png' alt='" . 1 . "' align='middle'>",
];

$debug = false;

// MyTextSanitizer object
$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('mod_url', $helper->url());
// Local icons path
if (is_object($helper->getModule())) {
    $pathModIcon16 = $helper->getModule()->getInfo('modicons16');
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');

    $GLOBALS['xoopsTpl']->assign('pathModIcon16', \Xoops\Helpers\Service\Url::module($moduleDirName, (string) $pathModIcon16));
    $GLOBALS['xoopsTpl']->assign('pathModIcon32', $pathModIcon32);
}

xoops_loadLanguage('main', $moduleDirName);
if (class_exists('D3LanguageManager')) {
    require_once XOOPS_TRUST_PATH . '/libs/altsys/class/D3LanguageManager.class.php';
    $langman = D3LanguageManager::getInstance();
    $langman->read('main.php', $moduleDirName);
}

