<?php declare(strict_types=1);

namespace XoopsModules\Mtools\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.
*/

/**
 * @category        Module
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       2000-2026 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */

use Xmf\Module\Helper;
use Xmf\Request;

/**
 * Reusable "are you sure?" confirmation form for delete (and similar) actions.
 *
 * Centralises the per-module copy of `Common\Confirm` that ~18 modules carried. Those copies
 * shared a bug: they derived the module directory with `basename(__DIR__)`, which — because the
 * class lives in `<module>/class/Common/` — resolved to the literal string `"Common"`, so the
 * `_CO_<MODULE>_DELETE_*` language constants were never found and the dialog silently fell back
 * to hardcoded English (untranslated in non-English installs).
 *
 * This shared version takes the consumer's module directory EXPLICITLY (a class living in mtools
 * cannot guess its consumer), so the translated constants resolve correctly. Pass it via
 * {@see self::forModule()} or the `$moduleDirName` constructor argument.
 *
 * Usage:
 *   $confirm = Confirm::forModule(
 *       $helper,
 *       ['ok' => 1, 'id' => $id, 'op' => 'delete'],
 *       $_SERVER['REQUEST_URI'],
 *       \sprintf(\_CO_MYMODULE_FORM_SURE_DELETE, $obj->getTitle())
 *   );
 *   $GLOBALS['xoopsTpl']->assign('form', $confirm->getFormConfirm()->render());
 *
 * Admin/UI tier (legacy): it builds a `\XoopsThemeForm`, so it requires a booted XOOPS and is
 * NOT an XMF-graduation candidate.
 *
 * @api Stable Common-tier API (Lab\* is experimental, module-local code is private).
 * @since 1.2.0
 */
class Confirm
{
    /**
     * @param array<string, mixed> $hiddens       hidden fields carried through the confirm POST
     * @param string               $action        form action; defaults to the current REQUEST_URI
     * @param string               $object        the message body shown next to the label
     * @param string               $title         explicit form title (wins over the language constant)
     * @param string               $label         explicit label (wins over the language constant)
     * @param string|null          $moduleDirName consumer module dir for `_CO_<MODULE>_DELETE_*` lookups
     */
    public function __construct(
        private readonly array $hiddens,
        private string $action = '',
        private readonly string $object = '',
        private readonly string $title = '',
        private readonly string $label = '',
        private readonly ?string $moduleDirName = null
    ) {
    }

    /**
     * Named constructor: derive the consumer module directory from its Helper.
     *
     * @param array<string, mixed> $hiddens
     */
    public static function forModule(
        Helper $helper,
        array $hiddens,
        string $action = '',
        string $object = '',
        string $title = '',
        string $label = ''
    ): self {
        return new self($hiddens, $action, $object, $title, $label, $helper->getDirname());
    }

    /**
     * Build the confirmation form.
     */
    public function getFormConfirm(): \XoopsThemeForm
    {
        [$title, $label] = $this->resolveTitleAndLabel();

        $action = '' === $this->action
            ? Request::getString('REQUEST_URI', '', 'SERVER')
            : $this->action;

        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'formConfirm', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new \XoopsFormLabel($label, $this->object));
        foreach ($this->hiddens as $key => $value) {
            $form->addElement(new \XoopsFormHidden($key, $value));
        }
        $form->addElement(new \XoopsFormHidden('ok', 1));
        $buttonTray = new \XoopsFormElementTray('');
        $buttonTray->addElement(new \XoopsFormButton('', 'confirm_submit', \_YES, 'submit'));
        $buttonBack = new \XoopsFormButton('', 'confirm_back', \_NO, 'button');
        $buttonBack->setExtra('onclick="history.go(-1);return true;"');
        $buttonTray->addElement($buttonBack);
        $form->addElement($buttonTray);

        return $form;
    }

    /**
     * Resolve the confirm title and label, preferring explicit values, then the consumer's
     * `_CO_<MODULE>_DELETE_*` language constants, then a hardcoded English fallback.
     *
     * @return array{0: string, 1: string}
     */
    private function resolveTitleAndLabel(): array
    {
        $title = $this->title;
        $label = $this->label;

        if (null !== $this->moduleDirName && '' !== $this->moduleDirName) {
            $upper = \mb_strtoupper($this->moduleDirName);
            // Define fallbacks so constant() never fails; modules should place the real
            // (translatable) values in their language common.php.
            if (!\defined('_CO_' . $upper . '_DELETE_CONFIRM')) {
                \define('_CO_' . $upper . '_DELETE_CONFIRM', 'Confirm delete');
                \define('_CO_' . $upper . '_DELETE_LABEL', 'Do you really want to delete:');
            }
            if ('' === $title) {
                $title = (string)\constant('_CO_' . $upper . '_DELETE_CONFIRM');
            }
            if ('' === $label) {
                $label = (string)\constant('_CO_' . $upper . '_DELETE_LABEL');
            }
        }

        if ('' === $title) {
            $title = 'Confirm delete';
        }
        if ('' === $label) {
            $label = 'Do you really want to delete:';
        }

        return [$title, $label];
    }
}
