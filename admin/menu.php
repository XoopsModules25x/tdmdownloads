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

//require_once  dirname(__DIR__) . '/include/common.php';
/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}


$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU1,
    'link'  => 'admin/index.php',
    'icon'  => 'assets/images/admin/home.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU2,
    'link'  => 'admin/category.php',
    'icon'  => 'assets/images/admin/category.png',
    //'menu' =>  'assets/images/admin/menu_category.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU3,
    'link'  => 'admin/downloads.php',
    'icon'  => 'assets/images/admin/downloads.png',
    //'menu' =>  'assets/images/admin/menu_downloads.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU4,
    'link'  => 'admin/broken.php',
    'icon'  => 'assets/images/admin/broken.png',
    //'menu' =>  'assets/images/admin/menu_broken.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU5,
    'link'  => 'admin/modified.php',
    'icon'  => 'assets/images/admin/modified.png',
    //'menu' =>  'assets/images/admin/menu_modified.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU6,
    'link'  => 'admin/field.php',
    'icon'  => 'assets/images/admin/field.png',
    //'menu' =>  'assets/images/admin/menu_field.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU7,
    'link'  => 'admin/import.php',
    'icon'  => 'assets/images/admin/import.png',
    //'menu' =>  'assets/images/admin/menu_import.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU8,
    'link'  => 'admin/permissions.php',
    'icon'  => 'assets/images/admin/permissions.png',
    //'menu' =>  'assets/images/admin/menu_permissions.png',
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU9,
    'link'  => 'admin/about.php',
    'icon'  => 'assets/images/admin/about.png',
    //'menu' =>  'assets/images/admin/menu_about.png',
];

/*
$adminmenu[1]['title'] = _MI_TDMDOWNLOADS_ADMENU1;
$adminmenu[1]['link'] = 'admin/index.php';
$adminmenu[1]['icon'] = 'assets/images/admin/home.png';
$adminmenu[2]['title'] = _MI_TDMDOWNLOADS_ADMENU2;
$adminmenu[2]['link'] = 'admin/category.php';
$adminmenu[2]['icon'] = 'assets/images/admin/category.png';
//$adminmenu[2]['menu'] = "assets/images/admin/menu_category.png";
$adminmenu[3]['title'] = _MI_TDMDOWNLOADS_ADMENU3;
$adminmenu[3]['link'] = 'admin/downloads.php';
$adminmenu[3]['icon'] = 'assets/images/admin/downloads.png';
//$adminmenu[3]['menu'] = "assets/images/admin/menu_downloads.png";
$adminmenu[4]['title'] = _MI_TDMDOWNLOADS_ADMENU4;
$adminmenu[4]['link'] = 'admin/broken.php';
$adminmenu[4]['icon'] = 'assets/images/admin/broken.png';
//$adminmenu[4]['menu'] = "assets/images/admin/menu_broken.png";
$adminmenu[5]['title'] = _MI_TDMDOWNLOADS_ADMENU5;
$adminmenu[5]['link'] = 'admin/modified.php';
$adminmenu[5]['icon'] = 'assets/images/admin/modified.png';
//$adminmenu[5]['menu'] = "assets/images/admin/menu_modified.png";
$adminmenu[6]['title'] = _MI_TDMDOWNLOADS_ADMENU6;
$adminmenu[6]['link'] = 'admin/field.php';
$adminmenu[6]['icon'] = 'assets/images/admin/field.png';
//$adminmenu[6]['menu'] = "assets/images/admin/menu_field.png";
$adminmenu[7]['title'] = _MI_TDMDOWNLOADS_ADMENU7;
$adminmenu[7]['link'] = 'admin/import.php';
$adminmenu[7]['icon'] = 'assets/images/admin/import.png';
//$adminmenu[7]['menu'] = "assets/images/admin/menu_import.png";
$adminmenu[8]['title'] = _MI_TDMDOWNLOADS_ADMENU8;
$adminmenu[8]['link'] = 'admin/permissions.php';
$adminmenu[8]['icon'] = 'assets/images/admin/permissions.png';
//$adminmenu[8]['menu'] = "assets/images/admin/menu_permissions.png";
$adminmenu[9]['title'] = _MI_TDMDOWNLOADS_ADMENU9;
$adminmenu[9]['link'] = 'admin/about.php';
$adminmenu[9]['icon'] = 'assets/images/admin/about.png';
//$adminmenu[9]['menu'] = "assets/images/admin/menu_about.png";
*/

