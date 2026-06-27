<?php

namespace Tests\Unit;

use XoopsModules\Mtools\Lab\IdentityMap;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

/**
 * Class IdentityMapTest.
 */
#[CoversClass(\XoopsModules\Mtools\Lab\IdentityMap::class)]
#[Group('legacy')]
final class IdentityMapTest extends TestCase
{
    private IdentityMap $identityMap;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->identityMap = new IdentityMap();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->identityMap);
    }

    public function testSet(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetId(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testHasId(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testHasObject(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetObject(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
