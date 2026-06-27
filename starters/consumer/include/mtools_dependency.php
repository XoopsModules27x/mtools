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

use XoopsModules\Mtools\Module\ConsumerRuntime;

// Rename `mymodule` to your module dirname. This is the ONLY per-module boilerplate
// left: a thin, mtools-absence-safe shim. All version-check and message logic lives
// in ConsumerRuntime — a class inside mtools cannot report its own non-existence,
// so this one guard stays consumer-side.
if (!function_exists('mymodule_mtools_dependency_error')) {
    function mymodule_mtools_dependency_error(): string
    {
        if (!class_exists(ConsumerRuntime::class)) {
            return 'The mtools module files are missing. Install mtools before installing or running this module.';
        }

        return ConsumerRuntime::dependencyError('1.0.0', '1.1.0');
    }
}

