<?php

namespace Tests\Unit\XoopsModules\Mtools;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Utility;

/**
 * Class UtilityTest.
 */
#[CoversClass(\XoopsModules\Mtools\Utility::class)]
#[Group('legacy')]
final class UtilityTest extends TestCase
{
    use \RequiresXoops;

    private Utility $utility;

    /**
     * Utility's constructor reaches into the live XOOPS runtime (e.g.
     * $GLOBALS['xoopsDB']->prefix()), so these tests need a booted XOOPS and
     * self-skip in unit-only mode rather than erroring.
     *
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->requiresXoops();

        $this->utility = new Utility();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->utility);
    }

    public function testXoops_security_check(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testDd(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testAdd_migrate(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGet_version(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testMk_dir(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testDelete_directory(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testFull_copy(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testRename_win(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testTad_breadcrumb(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testSetup_meta(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGet_basename(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testHtml5(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testWeb_error(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGet_bootstrap(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGet_xoops_url(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGet_xoops_path(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testAuto_link(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testAutolink(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function test_autolink_find_URLS(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function test_autolink_create_html_tags(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testPush_url(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testFacebook_comments(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testMk_qrcode(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testMk_qrcode_name(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testChk_qrcode_url(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testChk(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testChk2(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testPower_chk(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testTxt_to_group_name(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGet_all_groups(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testTo_utf8(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testIs_utf8(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testAuto_charset(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testRandStr(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testRrmdir(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetPageBar(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testToolbar_bootstrap(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testMake_menu_json(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGet_jquery(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testMobile_device_detect(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
