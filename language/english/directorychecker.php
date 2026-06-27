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


$moduleDirName      = \basename(\dirname(__DIR__, 2));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

define('_CO_MTOOLS_DC_AVAILABLE', "<span style='color: green;'>Available</span>");
define('_CO_MTOOLS_DC_NOTAVAILABLE', "<span style='color: red;'>Not available</span>");
define('_CO_MTOOLS_DC_NOTWRITABLE', "<span style='color: red;'>Should have permission ( %d ), but it has ( %d )</span>");
define('_CO_MTOOLS_DC_CREATETHEDIR', 'Create it');
define('_CO_MTOOLS_DC_SETMPERM', 'Set the permission');
define('_CO_MTOOLS_DC_DIRCREATED', 'The directory has been created');
define('_CO_MTOOLS_DC_DIRNOTCREATED', 'The directory cannot be created');
define('_CO_MTOOLS_DC_PERMSET', 'The permission has been set');
define('_CO_MTOOLS_DC_PERMNOTSET', 'The permission cannot be set');
