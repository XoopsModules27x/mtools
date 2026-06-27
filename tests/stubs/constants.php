<?php

declare(strict_types=1);

/**
 * Minimal XOOPS constants for unit-only test runs.
 *
 * Loaded by tests/bootstrap.php ONLY when no bootable XOOPS is present
 * (XOOPS_OVERLAY_INTEGRATION === false). In integration mode the real runtime
 * defines these, so this file is skipped.
 *
 * XOOPS_ROOT_PATH is deliberately NOT defined here: several classes (e.g.
 * Common\ObjectTree) switch on `defined('XOOPS_ROOT_PATH')` to require core files
 * that do not exist in a bare unit run. Tests that genuinely need the core use the
 * RequiresXoops trait to self-skip instead.
 *
 * xoops-overlay:profile=core27
 */

if (!defined('XOOPS_VERSION')) {
    define('XOOPS_VERSION', 'XOOPS 2.5.11');
}

if (!defined('XOOPS_URL')) {
    define('XOOPS_URL', 'http://localhost');
}

if (!defined('XOOPS_UPLOAD_URL')) {
    define('XOOPS_UPLOAD_URL', 'http://localhost/uploads');
}

if (!defined('XOOPS_UPLOAD_PATH')) {
    define('XOOPS_UPLOAD_PATH', sys_get_temp_dir() . '/xoops_uploads');
}
