<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\FileChecker;

/**
 * Class FileCheckerTest.
 */
#[CoversClass(\XoopsModules\Mtools\Common\FileChecker::class)]
#[Group('legacy')]
final class FileCheckerTest extends TestCase
{
    private FileChecker $fileChecker;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->fileChecker = new FileChecker();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->fileChecker);
    }

    public function testGetFileStatus(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testCopyFile(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testCompareFiles(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testFileExists(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testSetFilePermissions(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
