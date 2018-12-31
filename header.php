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

use XoopsModules\Tdmdownloads;

//require  dirname(dirname(__DIR__)) . '/mainfile.php';
//require_once XOOPS_ROOT_PATH.'/class/pagenav.php';
//require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
//require_once XOOPS_ROOT_PATH . '/class/tree.php';
//require_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';

require  dirname(dirname(__DIR__)) .'/mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';

$moduleDirName = basename(__DIR__);

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

$modulePath = XOOPS_ROOT_PATH . '/modules/' . $moduleDirName;
require __DIR__ . '/include/config.php';
$myts = \MyTextSanitizer::getInstance();

require_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname') . '/include/functions.php';

//permission
$gpermHandler = xoops_getHandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}

// Load language files
$helper->loadLanguage('main');
$helper->loadLanguage('admin');

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new XoopsTpl();
}

$perm_submit = ($gpermHandler->checkRight('tdmdownloads_ac', 4, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_modif = ($gpermHandler->checkRight('tdmdownloads_ac', 8, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_vote = ($gpermHandler->checkRight('tdmdownloads_ac', 16, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_upload = ($gpermHandler->checkRight('tdmdownloads_ac', 32, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
$perm_autoapprove = ($gpermHandler->checkRight('tdmdownloads_ac', 64, $groups, $xoopsModule->getVar('mid'))) ? true : false ;

//paramètres:
// pour les images des catégories:
$uploaddir = XOOPS_ROOT_PATH . '/uploads/tdmdownloads/images/cats/';
$uploadurl = XOOPS_URL . '/uploads/tdmdownloads/images/cats/';
// pour les fichiers
$uploaddir_downloads = XOOPS_ROOT_PATH . '/uploads/tdmdownloads/downloads/';
$uploadurl_downloads = XOOPS_URL . '/uploads/tdmdownloads/downloads/';
// pour les logos
$uploaddir_shots = XOOPS_ROOT_PATH . '/uploads/tdmdownloads/images/shots/';
$uploadurl_shots = XOOPS_URL . '/uploads/tdmdownloads/images/shots/';
// pour les images des champs:
$uploaddir_field = XOOPS_ROOT_PATH . '/uploads/tdmdownloads/images/field/';
$uploadurl_field = XOOPS_URL . '/uploads/tdmdownloads/images/field/';
/////////////

//appel des class
$categoryHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Category');
$downloadsHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Downloads');
$ratingHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Rating');
$fieldHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Field');
$fielddataHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Fielddata');
$brokenHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Broken');
$modifiedHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Modified');
$modifieddataHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Modifiedfielddata');
