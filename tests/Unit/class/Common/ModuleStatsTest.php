<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\ModuleStats;

/**
 * Class ModuleStatsTest.
 *
 * @copyright 2000-2026 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Michael Beck <mambax7@gmail.com>
 */
#[CoversClass(\XoopsModules\Mtools\Common\ModuleStats::class)]
#[Group('legacy')]
final class ModuleStatsTest extends TestCase
{
    private object $moduleStats;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->moduleStats = new class {
            use ModuleStats;
        };
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->moduleStats);
    }

    public function testGetModuleStats(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
