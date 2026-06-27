<?php declare(strict_types=1);

namespace XoopsModules\Mtools\Lab;

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
 * Experimental data-mapper contract. Not part of the stable Common API.
 *
 * @internal Experimental Lab tier — unstable, not part of the public mtools API. Use Common\* instead.
 */
interface DataMapperInterface
{
    public function create();

    public function get($int_id);

    public function insert(\XoopsObject $object);

    public function delete(\XoopsObject $object);
}

