<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use XoopsDatabase;
use XoopsModules\Mtools\Common\Blocksadmin;
use XoopsModules\Mtools\Helper;

/**
 * Class BlocksadminTest.
 */
#[CoversClass(\XoopsModules\Mtools\Common\Blocksadmin::class)]
#[Group('legacy')]
final class BlocksadminTest extends TestCase
{
    private Blocksadmin $blocksadmin;

    private XoopsDatabase|MockObject $db;

    private Helper|MockObject $helper;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Use PHPUnit's native test doubles so the suite has no undeclared Mockery dependency.
        $this->db = $this->createMock(XoopsDatabase::class);
        $this->helper = $this->createMock(Helper::class);
        $this->blocksadmin = new Blocksadmin($this->db, $this->helper);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->blocksadmin);
        unset($this->db);
        unset($this->helper);
    }

    public function testListBlocks(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testDeleteBlock(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testCloneBlock(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testIsBlockCloned(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testSetOrder(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testEditBlock(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testUpdateBlock(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testOrderBlock(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testRender(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
