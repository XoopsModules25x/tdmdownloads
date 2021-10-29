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

use Xmf\Module\Admin;
use XoopsModules\Tdmdownloads\Helper;

require __DIR__ . '/admin_header.php';
xoops_cp_header();
require_once $GLOBALS['xoops']->path('www/class/xoopsform/grouppermform.php');
/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper       = Helper::getInstance();
$adminObject  = Admin::getInstance();
$templateMain = 'tdmdownloads_admin_permissions.tpl';
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
$permission = \Xmf\Request::getInt('permission', 1, 'POST');
$tab_perm   = [
    1 => _AM_TDMDOWNLOADS_PERM_VIEW,
    2 => _AM_TDMDOWNLOADS_PERM_SUBMIT,
    3 => _AM_TDMDOWNLOADS_PERM_DOWNLOAD,
    4 => _AM_TDMDOWNLOADS_PERM_AUTRES,
];
xoops_load('XoopsFormLoader');
$permTableForm = new \XoopsSimpleForm('', 'fselperm', 'permissions.php', 'post');
$formSelect    = new \XoopsFormSelect('', 'permission', $permission);
$formSelect->setExtra('onchange="document.fselperm.submit()"');
foreach (array_keys($tab_perm) as $i) {
    $formSelect->addOption($i, $tab_perm[$i]);
}
$permTableForm->addElement($formSelect);
$GLOBALS['xoopsTpl']->assign('form_select', $permTableForm->render());
$moduleId = $xoopsModule->getVar('mid');
switch ($permission) {
    case 1:    // View permission
        $formTitle             = _AM_TDMDOWNLOADS_PERM_VIEW;
        $permissionName        = 'tdmdownloads_view';
        $permissionDescription = _AM_TDMDOWNLOADS_PERM_VIEW_DSC;
        break;
    case 2:    // Submit Permission
        $formTitle             = _AM_TDMDOWNLOADS_PERM_SUBMIT;
        $permissionName        = 'tdmdownloads_submit';
        $permissionDescription = _AM_TDMDOWNLOADS_PERM_SUBMIT_DSC;
        break;
    case 3:    // Download Permission
        $formTitle = _AM_TDMDOWNLOADS_PERM_DOWNLOAD;
        if (1 == $helper->getConfig('permission_download')) {
            $permissionDescription = _AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC;
            $permissionName        = 'tdmdownloads_download';
        } else {
            $permissionDescription = _AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC2;
            $permissionName        = 'tdmdownloads_download_item';
        }
        break;
    case 4:
        $formTitle             = _AM_TDMDOWNLOADS_PERM_AUTRES;
        $permissionName        = 'tdmdownloads_ac';
        $permissionDescription = _AM_TDMDOWNLOADS_PERM_AUTRES_DSC;
        $global_perms_array    = [
            '4'  => _AM_TDMDOWNLOADS_PERMISSIONS_4,
            '8'  => _AM_TDMDOWNLOADS_PERMISSIONS_8,
            '16' => _AM_TDMDOWNLOADS_PERMISSIONS_16,
            '32' => _AM_TDMDOWNLOADS_PERMISSIONS_32,
            '64' => _AM_TDMDOWNLOADS_PERMISSIONS_64,
        ];
        break;
}
$permissionsForm = new \XoopsGroupPermForm($formTitle, $moduleId, $permissionName, $permissionDescription, 'admin/permissions.php');
if (4 === $permission) {
    foreach ($global_perms_array as $perm_id => $permissionName) {
        $permissionsForm->addItem($perm_id, $permissionName);
    }
} else {
    if (3 === $permission && 2 === $helper->getConfig('permission_download')) {
        $sql    = 'SELECT lid, cid, title FROM ' . $xoopsDB->prefix('tdmdownloads_downloads') . ' ORDER BY title';
        $result = $xoopsDB->query($sql);
        if ($result instanceof \mysqli_result) {
            while (false !== ($row = $xoopsDB->fetchArray($result))) {
                $permissionsForm->addItem($row['lid'], $row['title']);
            }
        }
    } else {
        $sql    = 'SELECT cat_cid, cat_pid, cat_title FROM ' . $xoopsDB->prefix('tdmdownloads_cat') . ' ORDER BY cat_title';
        $result = $xoopsDB->query($sql);
        if ($result instanceof \mysqli_result) {
            while (false !== ($row = $xoopsDB->fetchArray($result))) {
                $permissionsForm->addItem($row['cat_cid'], $row['cat_title'], $row['cat_pid']);
            }
        }
    }
}
if ($categoryHandler->getCount()) {
    $GLOBALS['xoopsTpl']->assign('form_permissions', $permissionsForm->render());
} else {
    redirect_header('category.php', 2, _AM_TDMDOWNLOADS_NOPERMSSET, false);
}
echo "<br><br><br><br>\n";
unset($permissionsForm);
require __DIR__ . '/admin_footer.php';
