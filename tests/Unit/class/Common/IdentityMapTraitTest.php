<?php

namespace Tests\Unit;

use XoopsModules\Mtools\Lab\IdentityMapTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

/**
 * Class IdentityMapTraitTest.
 */
#[CoversClass(\XoopsModules\Mtools\Lab\IdentityMapTrait::class)]
#[Group('legacy')]
final class IdentityMapTraitTest extends TestCase
{
    private object $identityMapTrait;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->identityMapTrait = new class {
            use IdentityMapTrait;
        };
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->identityMapTrait);
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
