<?php declare(strict_types=1);

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
/** @var Helper $helper */

error_reporting(0);
require __DIR__ . '/header.php';

$helper = Helper::getInstance();

$lid = \Xmf\Request::getInt('lid', 0, 'REQUEST');
$cid = \Xmf\Request::getInt('cid', 0, 'REQUEST');
// redirection si le téléchargement n'existe pas
$viewDownloads = $downloadsHandler->get($lid);
if (empty($viewDownloads)) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
}
//redirection si pas de permission (cat)
$categories = $utility->getItemIds('tdmdownloads_view', $moduleDirName);
if (!in_array($viewDownloads->getVar('cid'), $categories)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
}
//redirection si pas de permission (télécharger)
if (2 == $helper->getConfig('permission_download')) {
    $item = $utility->getItemIds('tdmdownloads_download_item', $moduleDirName);

    if (!in_array($viewDownloads->getVar('lid'), $item)) {
        redirect_header('singlefile.php?lid=' . $viewDownloads->getVar('lid'), 2, _MD_TDMDOWNLOADS_SINGLEFILE_NOPERMDOWNLOAD);
    }
} else {
    $categories = $utility->getItemIds('tdmdownloads_download', $moduleDirName);

    if (!in_array($viewDownloads->getVar('cid'), $categories)) {
        redirect_header('singlefile.php?lid=' . $viewDownloads->getVar('lid'), 2, _MD_TDMDOWNLOADS_SINGLEFILE_NOPERMDOWNLOAD);
    }
}
//check download limit option
if (1 == $helper->getConfig('downlimit')) {
    $limitlid = $helper->getConfig('limitlid');

    $limitglobal = $helper->getConfig('limitglobal');

    $yesterday = strtotime(formatTimestamp(time() - 86400));

    if ($limitlid > 0) {
        $criteria = new \CriteriaCompo();

        if ($xoopsUser) {
            $criteria->add(new \Criteria('downlimit_uid', $xoopsUser->getVar('uid'), '='));
        } else {
            $criteria->add(new \Criteria('downlimit_hostname', getenv('REMOTE_ADDR'), '='));
        }

        $criteria->add(new \Criteria('downlimit_lid', $lid, '='));

        $criteria->add(new \Criteria('downlimit_date', $yesterday, '>'));

        $numrows = $downlimitHandler->getCount($criteria);

        if ($numrows >= $limitlid) {
            redirect_header('singlefile.php?lid=' . $viewDownloads->getVar('lid'), 5, sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_LIMITLID, $numrows, $limitlid));
        }
    }

    if ($limitglobal > 0) {
        $criteria = new \CriteriaCompo();

        if ($xoopsUser) {
            $criteria->add(new \Criteria('downlimit_uid', $xoopsUser->getVar('uid'), '='));
        } else {
            $criteria->add(new \Criteria('downlimit_hostname', getenv('REMOTE_ADDR'), '='));
        }

        $criteria->add(new \Criteria('downlimit_date', $yesterday, '>'));

        $numrows = $downlimitHandler->getCount($criteria);

        if ($numrows >= $limitglobal) {
            redirect_header('singlefile.php?lid=' . $viewDownloads->getVar('lid'), 5, sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_LIMITGLOBAL, $numrows, $limitglobal));
        }
    }

    /** @var \XoopsModules\Tdmdownloads\Downlimit $obj */

    $obj = $downlimitHandler->create();

    $obj->setVar('downlimit_lid', $lid);

    $obj->setVar('downlimit_uid', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);

    $obj->setVar('downlimit_hostname', getenv('REMOTE_ADDR'));

    $obj->setVar('downlimit_date', strtotime(formatTimestamp(time())));

    $downlimitHandler->insert($obj) || $obj->getHtmlErrors();

    // purge

    $criteria = new \CriteriaCompo();

    $criteria->add(new \Criteria('downlimit_date', time() - 172800, '<'));

    $numrows = $downlimitHandler->getCount($criteria);

    echo 'a détruire: ' . $numrows . '<br>';

    $downlimitHandler->deleteAll($criteria);
}

@$xoopsLogger->activated = false;
error_reporting(0);
if ($helper->getConfig('check_host')) {
    $goodhost = 0;

    $referer = parse_url(xoops_getenv('HTTP_REFERER'));

    $refererHost = $referer['host'];

    foreach ($helper->getConfig('referers') as $ref) {
        if (!empty($ref) && preg_match('/' . $ref . '/i', $refererHost)) {
            $goodhost = '1';

            break;
        }
    }

    if (!$goodhost) {
        redirect_header(XOOPS_URL . "/modules/$moduleDirName/singlefile.php?cid=$cid&amp;lid=$lid", 30, _MD_TDMDOWNLOADS_NOPERMISETOLINK);
    }
}

// ajout +1 pour les hits
$sql = sprintf('UPDATE %s SET hits = hits+1 WHERE lid = %u AND status > 0', $xoopsDB->prefix('tdmdownloads_downloads'), $lid);
$xoopsDB->queryF($sql);

$url           = $viewDownloads->getVar('url', 'n');
$contentLength = $utility::convertStringToSize($viewDownloads->getVar('size'));
if (!preg_match("/^ed2k*:\/\//i", $url)) {
    header("Content-Length: $contentLength");

    header("Location: $url");
}
echo '<html><head><meta http-equiv="Refresh" content="0; URL=' . $url . '"></meta></head><body></body></html>';
exit();
