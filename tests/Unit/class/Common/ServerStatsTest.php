<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\ServerStats;

/**
 * Class ServerStatsTest.
 *
 * @copyright XOOPS Project (https://xoops.org)
 * @license GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author mamba <mambax7@gmail.com>
 */
#[CoversClass(\XoopsModules\Mtools\Common\ServerStats::class)]
#[Group('legacy')]
final class ServerStatsTest extends TestCase
{
    private object $serverStats;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->serverStats = new class {
            use ServerStats;
        };
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->serverStats);
    }

    public function testGetServerStats(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
