<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

/**
 * Class coreTest.
 */
#[CoversClass(\MtoolsCorePreload::class)]
#[Group('legacy')]
final class coreTest extends TestCase
{
    use \RequiresXoops;

    private \MtoolsCorePreload $mtoolsCorePreload;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->requiresXoops();

        /** @todo Correctly instantiate tested object to use it. */
        $this->mtoolsCorePreload = new \MtoolsCorePreload();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->mtoolsCorePreload);
    }

    public function testEventCoreFooterStart(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testEventCoreIncludeCommonEnd(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
