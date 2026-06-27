<?php declare(strict_types=1);

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\SysUtility;
use XoopsModules\Mtools\Common\Text;

/**
 * Golden test for the pure {@see Text} helper extracted from SysUtility.
 *
 * Text has no XOOPS/global/database dependency, so this runs in unit-only mode and is
 * the reference shape for the rest of the decomposition. It also asserts the
 * {@see SysUtility::truncateHtml()} facade forwards here identically.
 */
#[CoversClass(Text::class)]
final class TextTest extends TestCase
{
    public function testShortPlainTextIsReturnedUnchanged(): void
    {
        self::assertSame('hello', Text::truncateHtml('hello', 100, '...', false, false));
    }

    public function testPlainTextIsTruncatedOnWordBoundary(): void
    {
        // 19 chars > 10; cut to 9 ("The quick"), then trimmed back to the last space.
        self::assertSame('The…', Text::truncateHtml('The quick brown fox', 10, '…', false, false));
    }

    public function testExactTruncationKeepsTheMidWordCut(): void
    {
        self::assertSame('The quick…', Text::truncateHtml('The quick brown fox', 10, '…', true, false));
    }

    public function testHtmlTagsAreClosedWhenConsiderHtml(): void
    {
        $out = Text::truncateHtml('<p>The quick brown fox jumps over</p>', 10, '...', false, true);

        self::assertStringStartsWith('<p>', $out);
        self::assertStringEndsWith('</p>', $out);
    }

    public function testFacadeForwardsToTextIdentically(): void
    {
        $args = ['The quick brown fox jumps over the lazy dog', 15, '...', false, false];

        self::assertSame(
            Text::truncateHtml(...$args),
            SysUtility::truncateHtml(...$args),
            'SysUtility::truncateHtml() must delegate to Text::truncateHtml() unchanged.'
        );
    }
}
