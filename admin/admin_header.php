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

use Xmf\Module\Admin;
use XoopsModules\Tdmdownloads\{
    Helper,
    Tree};

// Include xoops admin header
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

require_once XOOPS_ROOT_PATH . '/kernel/module.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
//require_once XOOPS_ROOT_PATH . '/class/tree.php';
//require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

require dirname(__DIR__) . '/include/common.php';

$moduleDirName = basename(dirname(__DIR__));

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = Helper::getInstance();

/** @var \Xmf\Module\Admin $adminObject */
$adminObject = Admin::getInstance();

$myts = \MyTextSanitizer::getInstance();

if ($xoopsUser) {
    $xoopsModule = \XoopsModule::getByDirname($moduleDirName);

    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 3, _NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/', 3, _NOPERM);
}

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');

    $xoopsTpl = new \XoopsTpl();
}

// Include language file
xoops_loadLanguage('admin', 'system');
// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');
$helper->loadLanguage('common');

if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
    require_once $GLOBALS['xoops']->path('class/theme.php');

    $GLOBALS['xoTheme'] = new \xos_opal_Theme();
}

//paramétres:
// pour les images des catégories:
$uploaddir = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/cats/';
$uploadurl = XOOPS_URL . '/uploads/' . $moduleDirName . '/images/cats/';
// pour les fichiers
$uploaddir_downloads = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/downloads/';
$uploadurl_downloads = XOOPS_URL . '/uploads/' . $moduleDirName . '/downloads/';
// pour les captures d'�cran fichiers
$uploaddir_shots = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/shots/';
$uploadurl_shots = XOOPS_URL . '/uploads/' . $moduleDirName . '/images/shots/';
// pour les images des champs:
$uploaddir_field = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/field/';
$uploadurl_field = XOOPS_URL . '/uploads/' . $moduleDirName . '/images/field/';

//permission
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
$groups           = XOOPS_GROUP_ANONYMOUS;
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
}
$perm_submit      = $grouppermHandler->checkRight('tdmdownloads_ac', 4, $groups, $xoopsModule->getVar('mid')) ? true : false;
$perm_modif       = $grouppermHandler->checkRight('tdmdownloads_ac', 8, $groups, $xoopsModule->getVar('mid')) ? true : false;
$perm_vote        = $grouppermHandler->checkRight('tdmdownloads_ac', 16, $groups, $xoopsModule->getVar('mid')) ? true : false;
$perm_upload      = $grouppermHandler->checkRight('tdmdownloads_ac', 32, $groups, $xoopsModule->getVar('mid')) ? true : false;
$perm_autoapprove = $grouppermHandler->checkRight('tdmdownloads_ac', 64, $groups, $xoopsModule->getVar('mid')) ? true : false;
