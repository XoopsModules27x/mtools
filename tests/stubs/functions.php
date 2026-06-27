<?php

declare(strict_types=1);

/**
 * Minimal XOOPS global functions for unit-only test runs.
 *
 * Loaded by tests/bootstrap.php only when no bootable XOOPS is present. These are
 * no-op / inert shims so that constructing classes whose constructors touch the XOOPS
 * procedural API (e.g. xoops_loadLanguage()) does not fatal in a bare unit run.
 * Guarded with function_exists so the real runtime always wins in integration mode.
 *
 * xoops-overlay:profile=core27
 */

if (!function_exists('xoops_loadLanguage')) {
    function xoops_loadLanguage(string $name, string $domain = '', string $language = '')
    {
        return false;
    }
}
