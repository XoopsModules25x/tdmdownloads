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

error_reporting(0);
include 'header.php';

$lid = TDMDownloads_CleanVars($_REQUEST, 'lid', 0, 'int');
$cid = TDMDownloads_CleanVars($_REQUEST, 'cid', 0, 'int');
// redirection si le téléchargement n'existe pas
$view_downloads = $downloads_Handler->get($lid);
if (count($view_downloads) == 0) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
    exit();
}
//redirection si pas de permission (cat)
$categories = TDMDownloads_MygetItemIds('tdmdownloads_view', 'TDMDownloads');
if (!in_array($view_downloads->getVar('cid'), $categories)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
    exit();
}
//redirection si pas de permission (télécharger)
if ($xoopsModuleConfig['permission_download'] == 2) {
    $item = TDMDownloads_MygetItemIds('tdmdownloads_download_item', 'TDMDownloads');
    if (!in_array($view_downloads->getVar('lid'), $item)) {
        redirect_header('singlefile.php?lid=' . $view_downloads->getVar('lid'), 2, _MD_TDMDOWNLOADS_SINGLEFILE_NOPERMDOWNLOAD);
        exit();
    }
} else {
    $categories = TDMDownloads_MygetItemIds('tdmdownloads_download', 'TDMDownloads');
    if (!in_array($view_downloads->getVar('cid'), $categories)) {
        redirect_header('singlefile.php?lid=' . $view_downloads->getVar('lid'), 2, _MD_TDMDOWNLOADS_SINGLEFILE_NOPERMDOWNLOAD);
        exit();
    }
}
//check download limit option
if ($xoopsModuleConfig['downlimit'] == 1) {
    $limitlid = $xoopsModuleConfig['limitlid'];
    $limitglobal = $xoopsModuleConfig['limitglobal'];
    $yesterday = strtotime(formatTimestamp(time()-86400));
    if ($limitlid > 0) {
        $criteria = new CriteriaCompo();
        if ($xoopsUser) {
            $criteria->add(new Criteria('downlimit_uid', $xoopsUser->getVar('uid'), '='));
        } else {
            $criteria->add(new Criteria('downlimit_hostname', getenv("REMOTE_ADDR"), '='));
        }
        $criteria->add(new Criteria('downlimit_lid', $lid, '='));
        $criteria->add(new Criteria('downlimit_date', $yesterday, '>'));
        $numrows = $downloadslimit_Handler->getCount($criteria);
        if ($numrows >= $limitlid) {
            redirect_header('singlefile.php?lid=' . $view_downloads->getVar('lid'), 5, sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_LIMITLID, $numrows, $limitlid));
            exit();
        }
    }
    if ($limitglobal > 0) {
        $criteria = new CriteriaCompo();
        if ($xoopsUser) {
            $criteria->add(new Criteria('downlimit_uid', $xoopsUser->getVar('uid'), '='));
        } else {
            $criteria->add(new Criteria('downlimit_hostname', getenv("REMOTE_ADDR"), '='));
        }
        $criteria->add(new Criteria('downlimit_date', $yesterday, '>'));
        $numrows = $downloadslimit_Handler->getCount($criteria);
        if ($numrows >= $limitglobal) {
            redirect_header('singlefile.php?lid=' . $view_downloads->getVar('lid'), 5, sprintf(_MD_TDMDOWNLOADS_SINGLEFILE_LIMITGLOBAL, $numrows, $limitglobal));
            exit();
        }
    }

    $obj = $downloadslimit_Handler->create();
    $obj->setVar('downlimit_lid', $lid);
    $obj->setVar('downlimit_uid', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);
    $obj->setVar('downlimit_hostname', getenv("REMOTE_ADDR"));
    $obj->setVar('downlimit_date', strtotime(formatTimestamp(time())));
    $downloadslimit_Handler->insert($obj) or $obj->getHtmlErrors();
    // purge
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('downlimit_date', (time() - 172800), '<'));
    $numrows = $downloadslimit_Handler->getCount($criteria);
    echo 'a détruire: ' . $numrows . '<br/>';
    $downloadslimit_Handler->deleteAll($criteria);
}

@$xoopsLogger->activated = false;
error_reporting(0);
if ($xoopsModuleConfig['check_host']) {
    $goodhost      = 0;
    $referer       = parse_url(xoops_getenv('HTTP_REFERER'));
    $referer_host  = $referer['host'];
    foreach ($xoopsModuleConfig['referers'] as $ref) {
        if (!empty($ref) && preg_match("/".$ref."/i", $referer_host)) {
            $goodhost = "1";
            break;
        }
    }
    if (!$goodhost) {
        redirect_header(XOOPS_URL . "/modules/TDMDownloads/singlefile.php?cid=$cid&amp;lid=$lid", 30, _MD_TDMDOWNLOADS_NOPERMISETOLINK);
        exit();
    }
}

// ajout +1 pour les hits
$sql = sprintf("UPDATE %s SET hits = hits+1 WHERE lid = %u AND status > 0", $xoopsDB->prefix("tdmdownloads_downloads"), $lid);
$xoopsDB->queryF($sql);

$url = $view_downloads->getVar('url', 'n');
if (!preg_match("/^ed2k*:\/\//i", $url)) {
    Header("Location: $url");
}
echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=" . $url . "\"></meta></head><body></body></html>";
exit();
