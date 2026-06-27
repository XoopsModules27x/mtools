<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\StandardConfig;

#[CoversClass(\XoopsModules\Mtools\Common\StandardConfig::class)]
final class StandardConfigTest extends TestCase
{
    public function testAppendsAllItemsInOrderWithConventionConstants(): void
    {
        $modversion = ['config' => []];
        StandardConfig::append($modversion, 'pedigree');

        $names = \array_column($modversion['config'], 'name');
        self::assertSame(['socialBookmarks', 'displaySampleButton', 'displayDeveloperTools'], $names);

        // Title/description are the module's own constant NAMES (XOOPS resolves them).
        $first = $modversion['config'][0];
        self::assertSame('_MI_PEDIGREE_SOCIAL_BOOKMARKS', $first['title']);
        self::assertSame('_MI_PEDIGREE_SOCIAL_BOOKMARKS_DESC', $first['description']);
        self::assertSame('yesno', $first['formtype']);
        self::assertSame('int', $first['valuetype']);

        $sample = $modversion['config'][1];
        self::assertSame('_MI_PEDIGREE_SHOW_SAMPLE_BUTTON', $sample['title']);
        $dev = $modversion['config'][2];
        self::assertSame('_MI_PEDIGREE_SHOW_DEV_TOOLS', $dev['title']);
    }

    public function testIncludeSubsetAndDefaultOverride(): void
    {
        $modversion = ['config' => []];
        StandardConfig::append($modversion, 'mymod', ['displaySampleButton'], ['displaySampleButton' => 0]);

        self::assertCount(1, $modversion['config']);
        self::assertSame('displaySampleButton', $modversion['config'][0]['name']);
        self::assertSame('_MI_MYMOD_SHOW_SAMPLE_BUTTON', $modversion['config'][0]['title']);
        self::assertSame(0, $modversion['config'][0]['default']);
    }

    public function testUnknownItemIsSkipped(): void
    {
        $modversion = ['config' => []];
        StandardConfig::append($modversion, 'mymod', ['nope', 'socialBookmarks']);
        self::assertCount(1, $modversion['config']);
        self::assertSame('socialBookmarks', $modversion['config'][0]['name']);
    }

    public function testAppendsToExistingConfig(): void
    {
        $modversion = ['config' => [['name' => 'existing']]];
        StandardConfig::append($modversion, 'mymod', ['socialBookmarks']);
        self::assertSame('existing', $modversion['config'][0]['name']);
        self::assertSame('socialBookmarks', $modversion['config'][1]['name']);
    }
}
