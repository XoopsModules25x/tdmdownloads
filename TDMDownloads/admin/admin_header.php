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
include_once '../../../include/cp_header.php';

include_once(XOOPS_ROOT_PATH."/kernel/module.php");
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH."/class/tree.php";
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';

include_once '../include/functions.php';

if ($xoopsUser) {
    $xoopsModule = XoopsModule::getByDirname("TDMDownloads");
    if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
        redirect_header(XOOPS_URL."/",3,_NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL."/",3,_NOPERM);
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
$uploaddir = XOOPS_ROOT_PATH . '/uploads/TDMDownloads/images/cats/';
$uploadurl = XOOPS_URL . '/uploads/TDMDownloads/images/cats/';
// pour les fichiers
$uploaddir_downloads = XOOPS_ROOT_PATH . '/uploads/TDMDownloads/downloads/';
$uploadurl_downloads = XOOPS_URL . '/uploads/TDMDownloads/downloads/';
// pour les captures d'�cran fichiers
$uploaddir_shots = XOOPS_ROOT_PATH . '/uploads/TDMDownloads/images/shots/';
$uploadurl_shots = XOOPS_URL . '/uploads/TDMDownloads/images/shots/';
// pour les images des champs:
$uploaddir_field = XOOPS_ROOT_PATH . '/uploads/TDMDownloads/images/field/';
$uploadurl_field = XOOPS_URL . '/uploads/TDMDownloads/images/field/';
/////////////

//appel des class
$downloadscat_Handler =& xoops_getModuleHandler('tdmdownloads_cat', 'TDMDownloads');
$downloads_Handler =& xoops_getModuleHandler('tdmdownloads_downloads', 'TDMDownloads');
$downloadsvotedata_Handler =& xoops_getModuleHandler('tdmdownloads_votedata', 'TDMDownloads');
$downloadsfield_Handler =& xoops_getModuleHandler('tdmdownloads_field', 'TDMDownloads');
$downloadsfielddata_Handler =& xoops_getModuleHandler('tdmdownloads_fielddata', 'TDMDownloads');
$downloadsbroken_Handler =& xoops_getModuleHandler('tdmdownloads_broken', 'TDMDownloads');
$downloadsmod_Handler =& xoops_getModuleHandler('tdmdownloads_mod', 'TDMDownloads');
$downloadsfieldmoddata_Handler =& xoops_getModuleHandler('tdmdownloads_modfielddata', 'TDMDownloads');
