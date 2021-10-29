<?php

declare(strict_types=1);

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
 * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage)
 */

use XoopsModules\Tdmdownloads\Helper;

require_once __DIR__ . '/header.php';
$xoopsLogger->activated = false;
require_once XOOPS_ROOT_PATH . '/class/template.php';
global $xoopsModuleConfig;
$helper     = Helper::getInstance();
$itemsCount = $helper->getConfig('perpagerss');
$cid        = \Xmf\Request::getInt('cid', 0, 'GET');
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
//header ('Content-Type:text/xml; charset=UTF-8');
$xoopsModuleConfig['utf8'] = false;
$moduleDirName             = basename(__DIR__);
//$xoopsTpl          = new \XoopsTpl();
$xoopsTpl->caching = 2; //1 = Cache global, 2 = Cache individuel (par template)
$xoopsTpl->xoops_setCacheTime($helper->getConfig('timecacherss') * 60); // Temps de cache en secondes
$categories = $utility->getItemIds('tdmdownloads_view', $moduleDirName);
$criteria   = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 0, '!='));
$criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
if (0 !== $cid) {
    $criteria->add(new \Criteria('cid', $cid));
    $cat   = $categoryHandler->get($cid);
    $title = $xoopsConfig['sitename'] . ' - ' . $xoopsModule->getVar('name') . ' - ' . $cat->getVar('cat_title');
} else {
    $title = $xoopsConfig['sitename'] . ' - ' . $xoopsModule->getVar('name');
}
$criteria->setLimit($helper->getConfig('perpagerss'));
$criteria->setSort('date');
$criteria->setOrder('DESC');
$downloadsArray = $downloadsHandler->getAll($criteria);
if (!$xoopsTpl->is_cached('db:tdmdownloads_rss.tpl', $cid)) {
    $xoopsTpl->assign('channel_title', htmlspecialchars($title, ENT_QUOTES));
    $xoopsTpl->assign('channel_link', XOOPS_URL . '/');
    $xoopsTpl->assign('channel_desc', htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES));
    $xoopsTpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
    $xoopsTpl->assign('channel_webmaster', $xoopsConfig['adminmail']);
    $xoopsTpl->assign('channel_editor', $xoopsConfig['adminmail']);
    $xoopsTpl->assign('channel_category', 'Event');
    $xoopsTpl->assign('channel_generator', 'XOOPS - ' . htmlspecialchars($xoopsModule->getVar('name'), ENT_QUOTES));
    $xoopsTpl->assign('channel_language', _LANGCODE);
    if (_LANGCODE === 'fr') {
        $xoopsTpl->assign('docs', 'http://www.scriptol.fr/rss/RSS-2.0.html');
    } else {
        $xoopsTpl->assign('docs', 'http://cyber.law.harvard.edu/rss/rss.html');
    }
    $xoopsTpl->assign('image_url', XOOPS_URL . $helper->getConfig('logorss'));
    $dimention = getimagesize(XOOPS_ROOT_PATH . $helper->getConfig('logorss'));
    if (empty($dimention[0])) {
        $width = 88;
    } else {
        $width = $dimention[0] > 144 ? 144 : $dimention[0];
    }
    if (empty($dimention[1])) {
        $height = 31;
    } else {
        $height = $dimention[1] > 400 ? 400 : $dimention[1];
    }
    $xoopsTpl->assign('image_width', $width);
    $xoopsTpl->assign('image_height', $height);
    foreach (array_keys($downloadsArray) as $i) {
        /** @var \XoopsModules\Tdmdownloads\Downloads[] $downloadsArray */
        $description = $downloadsArray[$i]->getVar('description');
        //permet d'afficher uniquement la description courte
        if (false === mb_strpos($description, '[pagebreak]')) {
            $descriptionShort = $description;
        } else {
            $descriptionShort = mb_substr($description, 0, mb_strpos($description, '[pagebreak]'));
        }
        $xoopsTpl->append('items', [
            'title'       => htmlspecialchars($downloadsArray[$i]->getVar('title'), ENT_QUOTES),
            'link'        => XOOPS_URL . '/modules/' . $moduleDirName . '/singlefile.php?cid=' . $downloadsArray[$i]->getVar('cid') . '&amp;lid=' . $downloadsArray[$i]->getVar('lid'),
            'guid'        => XOOPS_URL . '/modules/' . $moduleDirName . '/singlefile.php?cid=' . $downloadsArray[$i]->getVar('cid') . '&amp;lid=' . $downloadsArray[$i]->getVar('lid'),
            'pubdate'     => formatTimestamp($downloadsArray[$i]->getVar('date'), 'rss'),
            'description' => htmlspecialchars($descriptionShort, ENT_QUOTES),
        ]);
    }
}
header('Content-Type:text/xml; charset=' . _CHARSET);
$xoopsTpl->display('db:tdmdownloads_rss.tpl', $cid);
