<?php declare(strict_types=1);

namespace XoopsModules\Mtools\Tests\Fixtures\Consumer;

use XoopsModules\Mtools\Common\SysUtility;

/**
 * In-repo stand-in for a downstream module that consumes mtools.
 *
 * It mirrors the exact shape of a real consumer (e.g. quotes' own Utility):
 * a module-local Utility extending mtools' public {@see SysUtility}, alongside a
 * sibling {@see Helper}. Using this fixture keeps the contract test self-contained
 * — the mtools suite no longer reaches into the sibling `quotes` module on disk.
 */
class Utility extends SysUtility
{
    //--------------- Custom module methods -----------------------------
}
