<?php declare(strict_types=1);

namespace Tests\Unit\XoopsModules\Mtools\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use XoopsModules\Mtools\Common\Confirm;

/**
 * Tests for the centralised {@see Confirm} form.
 *
 * The constructor is pure (no XOOPS) and runs in unit-only mode. getFormConfirm() builds a
 * \XoopsThemeForm, so it self-skips via RequiresXoops until a runtime/stub provides the form
 * classes.
 */
#[CoversClass(Confirm::class)]
final class ConfirmTest extends TestCase
{
    use \RequiresXoops;

    public function testConstructsWithExplicitModuleDirName(): void
    {
        $confirm = new Confirm(['ok' => 1, 'op' => 'delete'], '/admin/x.php', 'message', '', '', 'demomodule');

        self::assertInstanceOf(Confirm::class, $confirm);
    }

    public function testGetFormConfirmBuildsThemeForm(): void
    {
        // Needs XoopsThemeForm / XoopsFormLoader; self-skips in unit-only mode.
        $this->requiresXoops();

        $confirm = new Confirm(['ok' => 1, 'op' => 'delete'], '/admin/x.php', 'message', '', '', 'demomodule');
        $form    = $confirm->getFormConfirm();

        self::assertInstanceOf(\XoopsThemeForm::class, $form);
    }
}
