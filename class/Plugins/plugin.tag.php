<?php declare(strict_types=1);

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.
*/

/**
 * @author         Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       2000-2026 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */


/**
 * Get item fields:
 * title
 * content
 * time
 * link
 * uid
 * uname
 * tags
 *
 * @return bool
 * @var array $items associative array of items: [modid][catid][itemid]
 */
function lexikon_tag_iteminfo(&$items)
{
    if (empty($items) || !is_array($items)) {
        return false;
    }

    global $xoopsDB;
    $myts = \MyTextSanitizer::getInstance();

    $items_id = [];

    foreach (array_keys($items) as $cat_id) {
        // Some handling here to build the link upon catid
        // If catid is not used, just skip it
        foreach (array_keys($items[$cat_id]) as $item_id) {
            // In article, the item_id is "art_id"
            $items_id[] = (int)$item_id;
        }
    }

    foreach (array_keys($items) as $cat_id) {
        foreach (array_keys($items[$cat_id]) as $item_id) {
            $sql = 'SELECT  l.entryID, l.categoryID, l.term AS ltitle, l.definition, l.uid, l.datesub, l.offline, c.name AS cname FROM '
                   . $xoopsDB->prefix('lxentries')
                   . ' l, '
                   . $xoopsDB->prefix('lxcategories')
                   . ' c WHERE l.entryID='
                   . $item_id
                   . ' AND l.categoryID=c.categoryID AND l.offline=0 ORDER BY l.datesub DESC';
            //$sql = "SELECT  l.entryID, l.categoryID, l.term as ltitle, l.definition, l.uid, l.datesub, l.offline,l.item_tag, c.name as cname FROM ".$xoopsDB->prefix('lxentries')." l, ".$xoopsDB->prefix('lxcategories')." c WHERE l.entryID=".$item_id." AND l.categoryID=c.categoryID AND l.offline=0 ORDER BY l.datesub DESC";
            $result                   = $xoopsDB->query($sql);
            $row                      = (($xoopsDB->isResultSet($result) && ($result instanceof \mysqli_result)) ? $xoopsDB->fetchArray($result) : false);
            $items[$cat_id][$item_id] = [
                'title'   => $row['ltitle'],
                'uid'     => $row['uid'],
                'link'    => "entry.php?entryID=$item_id",
                'time'    => $row['datesub'],
                //"tags"       => $row['item_tag'], // optional
                //"content"    => $myts->displayTarea( $row['definition'], 0 ),
                'content' => $row['definition'],
            ];
        }
    }
}
