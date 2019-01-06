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
include dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName      = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();
$helper->loadLanguage('common');

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU1,
    'link'  => 'admin/index.php',
    'icon'  => "{$pathModIcon32}/home.png",
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU2,
    'link'  => 'admin/category.php',
    'icon'  => "{$pathModIcon32}/category.png",
    //'menu' =>  "{$pathIcon32}/menu_category.png",
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU3,
    'link'  => 'admin/downloads.php',
    'icon'  => "{$pathModIcon32}/downloads.png",
    //'menu' =>  "{$pathIcon32}/menu_downloads.png",
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU4,
    'link'  => 'admin/broken.php',
    'icon'  => "{$pathModIcon32}/broken.png",
    //'menu' =>  "{$pathIcon32}/menu_broken.png",
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU5,
    'link'  => 'admin/modified.php',
    'icon'  => "{$pathModIcon32}/modified.png",
    //'menu' =>  "{$pathIcon32}/menu_modified.png",
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU6,
    'link'  => 'admin/field.php',
    'icon'  => "{$pathModIcon32}/field.png",
    //'menu' =>  "{$pathIcon32}/menu_field.png",
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU7,
    'link'  => 'admin/import.php',
    'icon'  => "{$pathModIcon32}/import.png",
    //'menu' =>  "{$pathIcon32}/menu_import.png",
];

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU8,
    'link'  => 'admin/permissions.php',
    'icon'  => "{$pathIcon32}/permissions.png",
    //'menu' =>  "{$pathIcon32}/menu_permissions.png",
];

// Blocks Admin
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS'),
    'link'  => 'admin/blocksadmin.php',
    'icon'  => $pathIcon32 . '/block.png',
];

if ($helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
        'link'  => 'admin/migrate.php',
        'icon'  => $pathIcon32 . '/database_go.png',
    ];
}

$adminmenu[] = [
    'title' => _MI_TDMDOWNLOADS_ADMENU9,
    'link'  => 'admin/about.php',
    'icon'  => "{$pathIcon32}/about.png",
    //'menu' =>  "{$pathIcon32}/menu_about.png",
];
