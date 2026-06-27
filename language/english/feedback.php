<?php declare(strict_types=1);

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

$moduleDirName      = \basename(\dirname(__DIR__, 2));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

define('_CO_MTOOLS_FB_FORM_TITLE', 'Send a feedback');
define('_CO_MTOOLS_FB_RECIPIENT', 'Recipient');
define('_CO_MTOOLS_FB_NAME', 'Name');
define('_CO_MTOOLS_FB_NAME_PLACEHOLER', 'Please enter your name');
define('_CO_MTOOLS_FB_SITE', 'Website');
define('_CO_MTOOLS_FB_SITE_PLACEHOLER', 'Please enter your website');
define('_CO_MTOOLS_FB_MAIL', 'Email');
define('_CO_MTOOLS_FB_MAIL_PLACEHOLER', 'Please enter your email');
define('_CO_MTOOLS_FB_TYPE', 'Type of feedback');
define('_CO_MTOOLS_FB_TYPE_SUGGESTION', 'Suggestions');
define('_CO_MTOOLS_FB_TYPE_BUGS', 'Bugs');
define('_CO_MTOOLS_FB_TYPE_TESTIMONIAL', 'Testimonials');
define('_CO_MTOOLS_FB_TYPE_FEATURES', 'Features');
define('_CO_MTOOLS_FB_TYPE_OTHERS', 'Misc');
define('_CO_MTOOLS_FB_TYPE_CONTENT', 'Feedback content');
define('_CO_MTOOLS_FB_SEND_FOR', 'Feedback for module ');
define('_CO_MTOOLS_' . 'FB_SEND_SUCCESS', 'Feedback successfully sent');
define('_CO_MTOOLS_' . 'FB_SEND_ERROR', 'An errror occurred when feedback was sent!');
