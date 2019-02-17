<?php
/**
 * TDMDownload
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   Gregory Mage (Aka Mage)
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage)
 * @param $queryarray
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 * @return array
 */

//use XoopsModules\Tdmdownloads;

function tdmdownloads_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;
    $moduleDirName = basename(dirname(__DIR__));

    $sql = 'SELECT lid, cid, title, description, submitter, date FROM ' . $xoopsDB->prefix('tdmdownloads_downloads') . ' WHERE status != 0';

    if (0 !== $userid) {
        $sql .= ' AND submitter=' . (int)$userid . ' ';
    }

    $utility    = new \XoopsModules\Tdmdownloads\Utility();
    $categories = $utility->getItemIds('tdmdownloads_view', $moduleDirName);
//        if (is_array($categories) && count($categories) > 0) {
    if ($categories && is_array($categories)) {
        $sql .= ' AND cid IN (' . implode(',', $categories) . ') ';
    } else {
        return null;
    }

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((title LIKE '%$queryarray[0]%' OR description LIKE '%$queryarray[0]%')";

        for ($i = 1; $i < $count; ++$i) {
            $sql .= " $andor ";
            $sql .= "(title LIKE '%$queryarray[$i]%' OR description LIKE '%$queryarray[$i]%')";
        }
        $sql .= ')';
    }

    $sql    .= ' ORDER BY date DESC';
    $result = $xoopsDB->query($sql, $limit, $offset);
    $ret    = [];
    $i      = 0;
    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[$i]['image'] = 'assets/images/deco/tdmdownloads_search.png';
        $ret[$i]['link']  = 'singlefile.php?cid=' . $myrow['cid'] . '&lid=' . $myrow['lid'] . '';
        $ret[$i]['title'] = $myrow['title'];
        $ret[$i]['time']  = $myrow['date'];
        $ret[$i]['uid']   = $myrow['submitter'];
        ++$i;
    }

    return $ret;
}
