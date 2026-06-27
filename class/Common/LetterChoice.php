<?php declare(strict_types=1);

namespace XoopsModules\Mtools\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.
*/

/**
 * @copyright 2000-2026 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author    XOOPS Development Team
 */


use XoopsModules\Mtools\Helper;

/** @var Helper $helper */

/**
 * Class LetterChoice
 *
 * @api Stable Common-tier API (Lab\* is experimental, module-local code is private).
 */
class LetterChoice
{
    public $helper;
    /**
     * *#@+
     */
    public  $modHelper;
    private $objHandler;
    private $criteria;
    private $field_name;
    private $alphabet;
    private $arg_name;
    private $url;
    private $extra;
    private $caseSensitive;
    /**
     * *#@-
     * @param mixed      $modHelper
     * @param mixed      $objHandler
     * @param null|mixed $criteria
     * @param null|mixed $field_name
     * @param mixed      $arg_name
     * @param null|mixed $url
     * @param mixed      $extra_arg
     * @param mixed      $caseSensitive
     */

    /**
     * Constructor
     *
     * @param                                $modHelper
     * @param \XoopsPersistableObjectHandler $objHandler {@link \XoopsPersistableObjectHandler}
     * @param null                           $criteria   {@link \CriteriaElement}
     * @param null                           $field_name search by field
     * @param array                          $alphabet   array of alphabet letters
     * @param string                         $arg_name   item on the current page
     * @param null                           $url
     * @param string                         $extra_arg  Additional arguments to pass in the URL
     * @param bool                           $caseSensitive
     */
    public function __construct(
        $modHelper,
        $objHandler,
        $criteria = null,
        $field_name = null,
        array $alphabet = [],
        $arg_name = 'letter',
        $url = null,
        $extra_arg = '',
        $caseSensitive = false
    ) {
        $this->modHelper  = $modHelper;
        $this->objHandler = $objHandler;
        $this->criteria   = null === $criteria ? new \CriteriaCompo() : $criteria;
        $this->field_name = null === $field_name ? $this->objHandler->identifierName : $field_name;
        //        $this->alphabet   = (count($alphabet) > 0) ? $alphabet : range('a', 'z'); // is there a way to get locale alphabet?
        //        $this->alphabet       = getLocalAlphabet();
        $languageFile = $this->modHelper->path('language/' . $GLOBALS['xoopsConfig']['language'] . '/alphabet.php');
        if (!is_file($languageFile)) {
            $languageFile = $this->modHelper->path('language/english/alphabet.php');
        }
        $this->alphabet = is_file($languageFile) ? require $languageFile : range('a', 'z');
        //        $this->modHelper->loadLanguage('alphabet');
        $this->arg_name = $arg_name;
        $this->url      = null === $url ? $_SERVER['SCRIPT_NAME'] : $url;
        if ('' !== $extra_arg && ('&amp;' !== \mb_substr($extra_arg, -5) || '&' !== \mb_substr($extra_arg, -1))) {
            $this->extra = '&amp;' . $extra_arg;
        }
        $this->caseSensitive = $caseSensitive;
    }

    /**
     * Create choice by letter
     *
     * @param null|int $alphaCount
     * @param null|int $howmanyother
     */
    public function render($alphaCount = null, $howmanyother = null): string
    {
        $moduleDirName      = $this->modHelper->getDirname();
        $moduleDirNameUpper = \mb_strtoupper($moduleDirName);
        \xoops_loadLanguage('common', $moduleDirName);
        \xoops_loadLanguage('alphabet', $moduleDirName);
        $all   = \constant('_CO_MTOOLS_ALL');
        $other = \constant('_CO_MTOOLS_OTHER');

        $ret = '';

        if (!$this->caseSensitive) {
            $this->criteria->setGroupBy('UPPER(LEFT(' . $this->field_name . ',1))');
        } else {
            $this->criteria->setGroupBy('LEFT(' . $this->field_name . ',1)');
        }
        $countsByLetters = $this->objHandler->getCounts($this->criteria);
        // fill alphabet array
        $alphabetArray = [];
        $letter_array  = [];

        $letter                 = 'All';
        $letter_array['letter'] = $all;
        $letter_array['count']  = $alphaCount;
        $letter_array['url']    = $this->url;
        $alphabetArray[$letter] = $letter_array;

        foreach ($this->alphabet as $letter) {
            $letter_array           = [];
            $letter_array['letter'] = $letter;
            if (!$this->caseSensitive) {
                if (isset($countsByLetters[\mb_strtoupper($letter)])) {
                    $letter_array['count'] = $countsByLetters[\mb_strtoupper($letter)];
                    $letter_array['url']   = $this->url . '?' . $this->arg_name . '=' . $letter . $this->extra;
                } else {
                    $letter_array['count'] = 0;
                    $letter_array['url']   = '';
                }
            } else {
                if (isset($countsByLetters[$letter])) {
                    $letter_array['count'] = $countsByLetters[$letter];
                    $letter_array['url']   = $this->url . '?' . $this->arg_name . '=' . $letter . $this->extra;
                } else {
                    $letter_array['count'] = 0;
                    $letter_array['url']   = '';
                }
            }
            $alphabetArray[$letter] = $letter_array;
            unset($letter_array);
        }

        $letter_array['letter'] = $other;
        $letter_array['count']  = $howmanyother;
        $letter_array['url']    = $this->url . '?init=Other';
        $alphabetArray[$letter] = $letter_array;

        // render output
        if (!isset($GLOBALS['xoTheme']) || !\is_object($GLOBALS['xoTheme'])) {
            require_once $GLOBALS['xoops']->path('/class/theme.php');
            $GLOBALS['xoTheme'] = new \xos_opal_Theme();
        }
        require_once $GLOBALS['xoops']->path('/class/template.php');
        $choiceByLetterTpl          = new \XoopsTpl();
        $choiceByLetterTpl->caching = 0; // Disable cache
        $choiceByLetterTpl->assign('alphabet', $alphabetArray);
        $ret .= $choiceByLetterTpl->fetch("db:{$this->modHelper->getDirname()}_letterschoice.tpl");
        unset($choiceByLetterTpl);

        return $ret;
    }
}
