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
 */

include_once 'header.php';
include_once XOOPS_ROOT_PATH.'/class/template.php';
$items_count = $xoopsModuleConfig['perpagerss'];
$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
//header ('Content-Type:text/xml; charset=UTF-8');
$xoopsModuleConfig["utf8"] = false;

$tpl = new XoopsTpl();
$tpl->caching=2; //1 = Cache global, 2 = Cache individuel (par template)
$tpl->xoops_setCacheTime($xoopsModuleConfig['timecacherss']*60); // Temps de cache en secondes
$categories = TDMDownloads_MygetItemIds('tdmdownloads_view', 'TDMDownloads');
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 0, '!='));
$criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')','IN'));
if ($cid != 0) {
    $criteria->add(new Criteria('cid', $cid));
    $cat = $downloadscat_Handler->get($cid);
    $title = $xoopsConfig['sitename'] . ' - ' . $xoopsModule->getVar('name') . ' - ' . $cat->getVar('cat_title');
} else {
    $title = $xoopsConfig['sitename'] . ' - ' . $xoopsModule->getVar('name');
}
$criteria->setLimit($xoopsModuleConfig['perpagerss']);
$criteria->setSort('date');
$criteria->setOrder('DESC');
$downloads_arr = $downloads_Handler->getall($criteria);

if (!$tpl->is_cached('db:tdmdownloads_rss.html', $cid)) {
    $tpl->assign('channel_title', htmlspecialchars($title, ENT_QUOTES));
    $tpl->assign('channel_link', XOOPS_URL.'/');
    $tpl->assign('channel_desc', htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES));
    $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
    $tpl->assign('channel_webmaster', $xoopsConfig['adminmail']);
    $tpl->assign('channel_editor', $xoopsConfig['adminmail']);
    $tpl->assign('channel_category', 'Event');
    $tpl->assign('channel_generator', 'XOOPS - ' . htmlspecialchars($xoopsModule->getVar('name'), ENT_QUOTES));
    $tpl->assign('channel_language', _LANGCODE);
    if (_LANGCODE == 'fr') {
        $tpl->assign('docs', 'http://www.scriptol.fr/rss/RSS-2.0.html');
    } else {
        $tpl->assign('docs', 'http://cyber.law.harvard.edu/rss/rss.html');
    }
    $tpl->assign('image_url', XOOPS_URL . $xoopsModuleConfig['logorss']);
    $dimention = getimagesize(XOOPS_ROOT_PATH . $xoopsModuleConfig['logorss']);
    if (empty($dimention[0])) {
        $width = 88;
    } else {
        $width = ($dimention[0] > 144) ? 144 : $dimention[0];
    }
    if (empty($dimention[1])) {
        $height = 31;
    } else {
        $height = ($dimention[1] > 400) ? 400 : $dimention[1];
    }
    $tpl->assign('image_width', $width);
    $tpl->assign('image_height', $height);
    foreach (array_keys($downloads_arr) as $i) {
        $description = $downloads_arr[$i]->getVar('description');
        //permet d'afficher uniquement la description courte
        if (strpos($description,'[pagebreak]')==false) {
            $description_short = $description;
        } else {
            $description_short = substr($description,0,strpos($description,'[pagebreak]'));
        }
        $tpl->append('items', array('title' => htmlspecialchars($downloads_arr[$i]->getVar('title'), ENT_QUOTES),
                                    'link' => XOOPS_URL . '/modules/TDMDownloads/singlefile.php?cid=' . $downloads_arr[$i]->getVar('cid') . '&amp;lid=' . $downloads_arr[$i]->getVar('lid'),
                                    'guid' => XOOPS_URL . '/modules/TDMDownloads/singlefile.php?cid=' . $downloads_arr[$i]->getVar('cid') . '&amp;lid=' . $downloads_arr[$i]->getVar('lid'),
                                    'pubdate' => formatTimestamp($downloads_arr[$i]->getVar('date'), 'rss'),
                                    'description' => htmlspecialchars($description_short, ENT_QUOTES)));
    }
}
header("Content-Type:text/xml; charset=" . _CHARSET);
$tpl->display('db:tdmdownloads_rss.html', $cid);
