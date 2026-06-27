<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\SocialBookmarks;

#[CoversClass(\XoopsModules\Mtools\Common\SocialBookmarks::class)]
final class SocialBookmarksTest extends TestCase
{
    public function testEmptyUrlReturnsEmptyString(): void
    {
        self::assertSame('', SocialBookmarks::render('', 'Title'));
        self::assertSame('', SocialBookmarks::render('   ', 'Title'));
    }

    public function testRendersDefaultNetworks(): void
    {
        $html = SocialBookmarks::render('https://example.test/animal.php?id=7', 'Walle');
        self::assertStringContainsString('mtools-social-bookmarks', $html);
        self::assertStringContainsString('twitter.com/intent/tweet', $html);
        self::assertStringContainsString('facebook.com/sharer', $html);
        self::assertStringContainsString('linkedin.com/sharing', $html);
        self::assertStringContainsString('api.whatsapp.com/send', $html);
        self::assertStringContainsString('mailto:', $html);
        self::assertStringContainsString('mtools-share-copy', $html);
    }

    public function testUrlAndTitleAreEncoded(): void
    {
        $html = SocialBookmarks::render('https://example.test/a.php?id=7&x=1', 'A & B');
        // raw URL-encoding of the shared link inside the share hrefs
        self::assertStringContainsString('id%3D7%26x%3D1', $html);
        // title encoded into the tweet text / mail subject
        self::assertStringContainsString('A%20%26%20B', $html);
        // no raw ampersand from our title leaks unescaped into an attribute
        self::assertStringNotContainsString('text=A & B', $html);
    }

    public function testNetworkSubsetAndOrderRespected(): void
    {
        $html = SocialBookmarks::render('https://example.test/p', 'P', ['email', 'x']);
        self::assertStringContainsString('mailto:', $html);
        self::assertStringContainsString('twitter.com', $html);
        self::assertStringNotContainsString('facebook.com', $html);
        self::assertStringNotContainsString('linkedin.com', $html);
        // email appears before x (order preserved)
        self::assertLessThan(\strpos($html, 'twitter.com'), \strpos($html, 'mailto:'));
    }

    public function testUnknownNetworkIsIgnored(): void
    {
        $html = SocialBookmarks::render('https://example.test/p', 'P', ['bogus', 'email']);
        self::assertStringContainsString('mailto:', $html);
        self::assertStringNotContainsString('bogus', $html);
    }
}
