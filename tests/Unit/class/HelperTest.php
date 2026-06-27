<?php

namespace Tests\Unit\XoopsModules\Mtools;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Helper;

/**
 * Class HelperTest.
 */
#[CoversClass(\XoopsModules\Mtools\Helper::class)]
#[Group('legacy')]
final class HelperTest extends TestCase
{
    use \RequiresXoops;

    private Helper $helper;

    private bool $debug;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->requiresXoops();

        $this->debug = true;
        $this->helper = new Helper($this->debug);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->helper);
        unset($this->debug);
    }

    public function testGetInstance(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetDirname(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetHandler(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
