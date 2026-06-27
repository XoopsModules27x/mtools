<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\DirectoryChecker;

/**
 * Class DirectoryCheckerTest.
 */
#[CoversClass(\XoopsModules\Mtools\Common\DirectoryChecker::class)]
#[Group('legacy')]
final class DirectoryCheckerTest extends TestCase
{
    private DirectoryChecker $directoryChecker;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->directoryChecker = new DirectoryChecker();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->directoryChecker);
    }

    public function testGetDirectoryStatus(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testCreateDirectory(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testSetDirectoryPermissions(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testDirExists(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
