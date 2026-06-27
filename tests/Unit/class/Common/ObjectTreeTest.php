<?php

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\ObjectTree;

/**
 * Class ObjectTreeTest.
 */
#[CoversClass(\XoopsModules\Mtools\Common\ObjectTree::class)]
#[Group('legacy')]
final class ObjectTreeTest extends TestCase
{
    use \RequiresXoops;

    /**
     * ObjectTree extends \XoopsObjectTree, so it can only be exercised once a real
     * XOOPS runtime is booted; self-skip in unit-only mode rather than fataling.
     *
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->requiresXoops();

        /** @todo Instantiate with a populated object array once these tests are implemented. */
    }

    public function testMakeSelBoxOptionsArray(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testMakeSelBox(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
