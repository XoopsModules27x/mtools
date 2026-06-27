<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\VersionChecks;

/**
 * Class VersionChecksTest.
 *
 * @copyright XOOPS Project (https://xoops.org)
 * @license GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author mamba <mambax7@gmail.com>
 */
#[CoversClass(\XoopsModules\Mtools\Common\VersionChecks::class)]
#[Group('legacy')]
final class VersionChecksTest extends TestCase
{
    private object $versionChecks;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->versionChecks = new class {
            use VersionChecks;
        };
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->versionChecks);
    }

    public function testCheckVerXoops(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testCheckVerPhp(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testCheckVerModule(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
