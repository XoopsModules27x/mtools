<?php declare(strict_types=1);

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\ModuleConfig;

/**
 * Pure tests for {@see ModuleConfig::fromObject()} — type coercion and safe defaults.
 */
#[CoversClass(ModuleConfig::class)]
final class ModuleConfigTest extends TestCase
{
    public function testFromObjectMapsProvidedFields(): void
    {
        $config = ModuleConfig::fromObject((object) [
            'name'          => 'Quotes',
            'uploadFolders' => ['/uploads/quotes'],
            'modCopyright'  => '(c) XOOPS',
        ]);

        self::assertSame('Quotes', $config->name);
        self::assertSame(['/uploads/quotes'], $config->uploadFolders);
        self::assertSame('(c) XOOPS', $config->modCopyright);
    }

    public function testMissingFieldsDefaultSafely(): void
    {
        // A partial config (only name) must not warn; arrays default to [], strings to ''.
        $config = ModuleConfig::fromObject((object) ['name' => 'Mini']);

        self::assertSame('Mini', $config->name);
        self::assertSame([], $config->uploadFolders);
        self::assertSame([], $config->copyTestFolders);
        self::assertSame([], $config->renameColumns);
        self::assertSame('', $config->modCopyright);
    }

    public function testEmptyObjectDefaultsEverything(): void
    {
        $config = ModuleConfig::fromObject((object) []);

        self::assertSame('', $config->name);
        self::assertSame([], $config->moduleStats);
        self::assertSame([], $config->oldFolders);
    }
}
