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

include '../../mainfile.php';
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH."/class/tree.php";
include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar("dirname").'/include/functions.php';
//permission
$gperm_handler = xoops_gethandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
xoops_loadLanguage("admin", $xoopsModule->getVar("dirname", "e"));

$perm_submit = ($gperm_handler->checkRight('tdmdownloads_ac', 4, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_modif = ($gperm_handler->checkRight('tdmdownloads_ac', 8, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_vote = ($gperm_handler->checkRight('tdmdownloads_ac', 16, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_upload = ($gperm_handler->checkRight('tdmdownloads_ac', 32, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_autoapprove = ($gperm_handler->checkRight('tdmdownloads_ac', 64, $groups, $xoopsModule->getVar('mid'))) ? true : false ;

//paramètres:
// pour les images des catégories:
$uploaddir = XOOPS_ROOT_PATH . '/uploads/TDMDownloads/images/cats/';
$uploadurl = XOOPS_URL . '/uploads/TDMDownloads/images/cats/';
// pour les fichiers
$uploaddir_downloads = XOOPS_ROOT_PATH . '/uploads/TDMDownloads/downloads/';
$uploadurl_downloads = XOOPS_URL . '/uploads/TDMDownloads/downloads/';
// pour les logos
$uploaddir_shots = XOOPS_ROOT_PATH . '/uploads/TDMDownloads/images/shots/';
$uploadurl_shots = XOOPS_URL . '/uploads/TDMDownloads/images/shots/';
// pour les images des champs:
$uploaddir_field = XOOPS_ROOT_PATH . '/uploads/TDMDownloads/images/field/';
$uploadurl_field = XOOPS_URL . '/uploads/TDMDownloads/images/field/';
/////////////

//appel des class
$downloadscat_Handler = xoops_getModuleHandler('tdmdownloads_cat', 'TDMDownloads');
$downloads_Handler = xoops_getModuleHandler('tdmdownloads_downloads', 'TDMDownloads');
$downloadsvotedata_Handler = xoops_getModuleHandler('tdmdownloads_votedata', 'TDMDownloads');
$downloadsmod_Handler = xoops_getModuleHandler('tdmdownloads_mod', 'TDMDownloads');
$downloadsbroken_Handler = xoops_getModuleHandler('tdmdownloads_broken', 'TDMDownloads');
$downloadsfield_Handler = xoops_getModuleHandler('tdmdownloads_field', 'TDMDownloads');
$downloadsfielddata_Handler = xoops_getModuleHandler('tdmdownloads_fielddata', 'TDMDownloads');
$downloadsfieldmoddata_Handler = xoops_getModuleHandler('tdmdownloads_modfielddata', 'TDMDownloads');
$downloadslimit_Handler = xoops_getModuleHandler('tdmdownloads_downlimit', 'TDMDownloads');
