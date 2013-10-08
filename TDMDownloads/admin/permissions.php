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

include 'admin_header.php';

xoops_cp_header();
if (TDMDownloads_checkModuleAdmin()) {
    $permissions_admin = new ModuleAdmin();
    echo $permissions_admin->addNavigation('permissions.php');
}

$permission = isset($_POST['permission']) ? intval($_POST['permission']) : 1;
$tab_perm = array(1 => _AM_TDMDOWNLOADS_PERM_VIEW, 2 => _AM_TDMDOWNLOADS_PERM_SUBMIT, 3 => _AM_TDMDOWNLOADS_PERM_DOWNLOAD, 4 => _AM_TDMDOWNLOADS_PERM_AUTRES);
echo "<form method='post' name='fselperm' action='permissions.php'>\n";
echo "<table border='0'>\n<tr>\n<td>\n";
echo "<select name='permission' onChange='javascript: document.fselperm.submit()'>\n";
foreach (array_keys($tab_perm) as $i) {
    if ($permission == $i) {
        $selected = ' selected';
    } else {
        $selected = '';
    }
    echo "<option value='" . $i . "'" . $selected . ">" . $tab_perm[$i] . "</option>";
}
echo "</select>\n";
echo "</td>\n</tr>\n<tr>\n<td>\n";
echo "<input type='submit' name='go'>\n";
echo "</td>\n</tr>\n</table>\n";
echo "</form>\n";

$moduleId = $xoopsModule->getVar('mid');

switch ($permission) {
    case 1:    // View permission
        $formTitle = _AM_TDMDOWNLOADS_PERM_VIEW;
        $permissionName = 'tdmdownloads_view';
        $permissionDescription = _AM_TDMDOWNLOADS_PERM_VIEW_DSC;
        break;
    case 2:    // Submit Permission
        $formTitle = _AM_TDMDOWNLOADS_PERM_SUBMIT;
        $permissionName = 'tdmdownloads_submit';
        $permissionDescription = _AM_TDMDOWNLOADS_PERM_SUBMIT_DSC;
        break;
    case 3:    // Download Permission
        $formTitle = _AM_TDMDOWNLOADS_PERM_DOWNLOAD;
        if ($xoopsModuleConfig['permission_download'] == 1) {
            $permissionDescription = _AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC;
            $permissionName = 'tdmdownloads_download';
        } else {
            $permissionDescription = _AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC2;
            $permissionName = 'tdmdownloads_download_item';
        }
        break;
    case 4:
        $formTitle = _AM_TDMDOWNLOADS_PERM_AUTRES;
        $permissionName = "tdmdownloads_ac";
        $permissionDescription = _AM_TDMDOWNLOADS_PERM_AUTRES_DSC;
        $global_perms_array = array(
        '4' => _AM_TDMDOWNLOADS_PERMISSIONS_4 ,
        '8' => _AM_TDMDOWNLOADS_PERMISSIONS_8 ,
        '16' => _AM_TDMDOWNLOADS_PERMISSIONS_16 ,
        '32' => _AM_TDMDOWNLOADS_PERMISSIONS_32 ,
        '64' => _AM_TDMDOWNLOADS_PERMISSIONS_64
         );
        break;
}

$permissionsForm = new XoopsGroupPermForm($formTitle, $moduleId, $permissionName, $permissionDescription, 'admin/permissions.php');
if ($permission == 4) {
    foreach ($global_perms_array as $perm_id => $permissionName) {
        $permissionsForm->addItem($perm_id , $permissionName) ;
    }
} else {
    if ($xoopsModuleConfig['permission_download'] == 2 && $permission == 3) {
        $sql = "SELECT lid, cid, title FROM ".$xoopsDB->prefix("tdmdownloads_downloads")." ORDER BY title";
        $result = $xoopsDB->query($sql);
        if ($result) {
            while ($row = $xoopsDB->fetchArray($result)) {
                $permissionsForm->addItem($row['lid'], $row['title']);
            }
        }
    } else {
        $sql = 'SELECT cat_cid, cat_pid, cat_title FROM '.$xoopsDB->prefix('tdmdownloads_cat').' ORDER BY cat_title';
        $result = $xoopsDB->query($sql);
        if ($result) {
            while ($row = $xoopsDB->fetchArray($result)) {
                $permissionsForm->addItem($row['cat_cid'], $row['cat_title'], $row['cat_pid']);
            }
        }
    }
}

if ($downloadscat_Handler->getCount()) {
    echo $permissionsForm->render();
} else {
    redirect_header("category.php", 2, _AM_TDMDOWNLOADS_NOPERMSSET, false);
}

echo "<br /><br /><br /><br />\n";
unset ($permissionsForm);

xoops_cp_footer();
