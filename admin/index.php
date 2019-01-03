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

require __DIR__ . '/admin_header.php';
xoops_cp_header();

// compte le nombre de catégories
$criteria = new \CriteriaCompo();
$nb_categories = $categoryHandler->getCount($criteria);
// compte le nombre de téléchargements
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 0, '!='));
$nb_downloads = $downloadsHandler->getCount($criteria);
// compte le nombre de téléchargements en attente de validation
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 0));
$nb_downloads_waiting = $downloadsHandler->getCount($criteria);
// compte le nombre de rapport de téléchargements brisés
$nb_broken = $brokenHandler->getCount();
// compte le nombre de demande de modifications
$nb_modified = $modifiedHandler->getCount();

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->addInfoBox(_MI_TDMDOWNLOADS_ADMENU2);
if (0 == $nb_categories) {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_CATEGORIES, '<span class="red" style = "font-weight: bold">' . $nb_categories . '</span>'), '', 'Red');
} else {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_CATEGORIES, '<span class="green" style = "font-weight: bold">' . $nb_categories . '</span>'), '', 'Green');
}


$adminObject->addInfoBox(_MI_TDMDOWNLOADS_ADMENU3);
$adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_DOWNLOADS, $nb_downloads), '');
if (0 == $nb_downloads_waiting) {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_DOWNLOADSWAITING, '<span class="green" style = "font-weight: bold">' . $nb_downloads_waiting . '</span>'), '', 'Green');
} else {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_DOWNLOADSWAITING, '<span class="red" style = "font-weight: bold">' . $nb_downloads_waiting . '</span>'), '', 'Red');
}

$adminObject->addInfoBox(_MI_TDMDOWNLOADS_ADMENU4);
if (0 == $nb_broken) {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_BROKEN, '<span class="green" style = "font-weight: bold">' . $nb_broken . '</span>'), '', 'Green');

} else {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_BROKEN, '<span class="red" style = "font-weight: bold">' . $nb_broken . '</span>'), '', 'Red');
}

$adminObject->addInfoBox(_MI_TDMDOWNLOADS_ADMENU5);
if (0 == $nb_modified) {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_MODIFIED, '<span class="green" style = "font-weight: bold">' . $nb_modified . '</span>'), '', 'Green');
} else {
    $adminObject->addInfoBoxLine(sprintf(_AM_TDMDOWNLOADS_INDEX_MODIFIED, '<span class="red" style = "font-weight: bold">' . $nb_modified . '</span>'), '', 'Red');
}

//---------------------------
$adminObject->addConfigBoxLine('');

$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();
$helper->loadLanguage('common');

/** @var \XoopsModules\Tdmdownloads\Common\Configurator $configurator */
$configurator = new \XoopsModules\Tdmdownloads\Common\Configurator();

/** @var \XoopsModules\Tdmdownloads\Utility $utility */
$utility = new \XoopsModules\Tdmdownloads\Utility();



foreach (array_keys($configurator->uploadFolders) as $i) {
    $utility::createFolder($configurator->uploadFolders[$i]);
    $adminObject->addConfigBoxLine($configurator->uploadFolders[$i], 'folder');
}

$adminObject->displayNavigation(basename(__FILE__));


//------------- Test Data ----------------------------

if ($helper->getConfig('displaySampleButton')) {
    xoops_loadLanguage('admin/modulesadmin', 'system');
    require __DIR__ . '/../testdata/index.php';

    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=load', 'add');

    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=save', 'add');

    //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), '__DIR__ . /../../testdata/index.php?op=exportschema', 'add');

    $adminObject->displayButton('left', '');
}

//------------- End Test Data ----------------------------

$adminObject->displayIndex();

echo $utility::getServerStats();

//codeDump(__FILE__);
require __DIR__ . '/admin_footer.php';
