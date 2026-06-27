<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\FilesManagement;

/**
 * Class FilesManagementTest.
 *
 * @copyright XOOPS Project (https://xoops.org)
 * @license GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author mamba <mambax7@gmail.com>
 */
#[CoversClass(\XoopsModules\Mtools\Common\FilesManagement::class)]
#[Group('legacy')]
final class FilesManagementTest extends TestCase
{
    private object $filesManagement;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->filesManagement = new class {
            use FilesManagement;
        };
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->filesManagement);
    }

    public function testCreateFolder(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testCopyFile(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testRecurseCopy(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testXcopy(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testDeleteDirectory(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testRrmdir(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testRmove(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testRcopy(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
