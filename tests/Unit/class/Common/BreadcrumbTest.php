<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\Breadcrumb;

/**
 * Class BreadcrumbTest.
 */
#[CoversClass(\XoopsModules\Mtools\Common\Breadcrumb::class)]
#[Group('legacy')]
final class BreadcrumbTest extends TestCase
{
    private Breadcrumb $breadcrumb;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->breadcrumb = new Breadcrumb();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->breadcrumb);
    }

    public function testAddLink(): void
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
