<?php declare(strict_types=1);

namespace Tests\Unit\XoopsModules\Mtools\Module;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Module\ModuleContext;

/**
 * Pure tests for {@see ModuleContext}. The dirname handling is XOOPS-free and runs in
 * unit mode; the path()/url()/config() accessors delegate to the xoops/helpers services
 * (which need a booted XOOPS) and are exercised in integration / the browser.
 */
#[CoversClass(ModuleContext::class)]
final class ModuleContextTest extends TestCase
{
    public function testForStoresTheDirname(): void
    {
        self::assertSame('quotes', ModuleContext::for('quotes')->dirname);
    }

    public function testForBasenamesAPath(): void
    {
        // A defensive basename so a stray path can't leak into the dirname.
        self::assertSame('quotes', ModuleContext::for('some/path/quotes')->dirname);
    }

    public function testDirnameIsReadonly(): void
    {
        $context = ModuleContext::for('mymod');
        self::assertSame('mymod', $context->dirname);
    }
}
