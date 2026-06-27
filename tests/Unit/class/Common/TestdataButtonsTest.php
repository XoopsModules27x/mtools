<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\TestdataButtons;

/**
 * Class TestdataButtonsTest.
 */
#[CoversClass(\XoopsModules\Mtools\Common\TestdataButtons::class)]
#[Group('legacy')]
final class TestdataButtonsTest extends TestCase
{
    private TestdataButtons $testdataButtons;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->testdataButtons = new TestdataButtons();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->testdataButtons);
    }

    public function testLoadButtonConfig(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testHideButtons(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testShowButtons(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
