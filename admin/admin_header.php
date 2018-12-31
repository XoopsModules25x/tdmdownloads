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

// Include xoops admin header
require_once dirname(__DIR__) . '/../../include/cp_header.php';

include_once(XOOPS_ROOT_PATH."/kernel/module.php");
require_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
require_once XOOPS_ROOT_PATH."/class/tree.php";
require_once XOOPS_ROOT_PATH."/class/xoopslists.php";
require_once XOOPS_ROOT_PATH.'/class/pagenav.php';
require_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';

require_once dirname(__DIR__) . '/include/functions.php';

if ($xoopsUser) {
    $xoopsModule = XoopsModule::getByDirname("TDMDownloads");
    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL."/", 3, _NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL."/", 3, _NOPERM);
    exit();
}

// Include language file
xoops_loadLanguage('admin', 'system');
xoops_loadLanguage('admin', $xoopsModule->getVar('dirname', 'e'));
xoops_loadLanguage('modinfo', $xoopsModule->getVar('dirname', 'e'));

$pathIcon16 = XOOPS_URL . '/' . $xoopsModule->getInfo('icons16');
$pathIcon32 = XOOPS_URL . '/' . $xoopsModule->getInfo('icons32');

//param�tres:
// pour les images des cat�gories:
$uploaddir = XOOPS_ROOT_PATH . '/uploads/tdmdownloads/images/cats/';
$uploadurl = XOOPS_URL . '/uploads/tdmdownloads/images/cats/';
// pour les fichiers
$uploaddir_downloads = XOOPS_ROOT_PATH . '/uploads/tdmdownloads/downloads/';
$uploadurl_downloads = XOOPS_URL . '/uploads/tdmdownloads/downloads/';
// pour les captures d'�cran fichiers
$uploaddir_shots = XOOPS_ROOT_PATH . '/uploads/tdmdownloads/images/shots/';
$uploadurl_shots = XOOPS_URL . '/uploads/tdmdownloads/images/shots/';
// pour les images des champs:
$uploaddir_field = XOOPS_ROOT_PATH . '/uploads/tdmdownloads/images/field/';
$uploadurl_field = XOOPS_URL . '/uploads/tdmdownloads/images/field/';
/////////////

//appel des class
$downloadscatHandler = xoops_getModuleHandler('tdmdownloads_cat', 'TDMDownloads');
$downloadsHandler = xoops_getModuleHandler('tdmdownloads_downloads', 'TDMDownloads');
$downloadsvotedataHandler = xoops_getModuleHandler('tdmdownloads_votedata', 'TDMDownloads');
$downloadsfieldHandler = xoops_getModuleHandler('tdmdownloads_field', 'TDMDownloads');
$downloadsfielddataHandler = xoops_getModuleHandler('tdmdownloads_fielddata', 'TDMDownloads');
$downloadsbrokenHandler = xoops_getModuleHandler('tdmdownloads_broken', 'TDMDownloads');
$downloadsmodHandler = xoops_getModuleHandler('tdmdownloads_mod', 'TDMDownloads');
$downloadsfieldmoddataHandler = xoops_getModuleHandler('tdmdownloads_modfielddata', 'TDMDownloads');
