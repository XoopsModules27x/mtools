<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\ModuleFeedback;

/**
 * Class ModuleFeedbackTest.
 */
#[CoversClass(\XoopsModules\Mtools\Common\ModuleFeedback::class)]
#[Group('legacy')]
final class ModuleFeedbackTest extends TestCase
{
    private ModuleFeedback $moduleFeedback;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->moduleFeedback = new ModuleFeedback();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->moduleFeedback);
    }

    public function testGetInstance(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetFormFeedback(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
