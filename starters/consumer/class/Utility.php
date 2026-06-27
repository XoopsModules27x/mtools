<?php declare(strict_types=1);

namespace XoopsModules\Mymodule;

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

use XoopsModules\Mtools;

/**
 * Thin consumer adapter for shared mTools utility behavior.
 *
 * Keep module-specific methods here. Do not copy `class/Common/SysUtility.php`
 * into the consumer once this adapter is in place.
 */
class Utility extends Mtools\Common\SysUtility
{
}

