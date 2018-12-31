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

use Xmf\Request;
use XoopsModules\Tdmdownloads\Tree;
use XoopsModules\Tdmdownloads;

// Include xoops admin header
require_once dirname(__DIR__) . '/../../include/cp_header.php';

include_once(XOOPS_ROOT_PATH . '/kernel/module.php');
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/tree.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH.'/class/pagenav.php';
require_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';

require_once dirname(__DIR__) . '/include/functions.php';

$moduleDirName = basename(dirname(__DIR__));

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

/** @var \Xmf\Module\Admin $adminObject */
$adminObject = \Xmf\Module\Admin::getInstance();

if ($xoopsUser) {
    $xoopsModule = \XoopsModule::getByDirname('TDMDownloads');
    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
    exit();
}

// Include language file
xoops_loadLanguage('admin', 'system');
// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('common');

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
$categoryHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Category');
$downloadsHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Downloads');
$ratingHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Rating');
$fieldHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Field');
$fielddataHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Fielddata');
$brokenHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Broken');
$modifiedHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Modified');
$modifieddataHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Modifieddata');
