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
use Xmf\Request;
use XoopsModules\Tdmdownloads\{
    Common\Configurator,
    Common\TestdataButtons,
    Helper,
    Utility
};
/** @var Admin $adminObject */
/** @var Configurator $configurator */
/** @var Utility $utility */
/** @var Helper $helper */

require __DIR__ . '/admin_header.php';
xoops_cp_header();

$moduleDirName      = basename(dirname(__DIR__));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

// Template Index
$templateMain = 'tdmdownloads_admin_index.tpl';

// compte le nombre de catégories
$criteria      = new \CriteriaCompo();
$nb_categories = $categoryHandler->getCount($criteria);
// compte le nombre de téléchargements
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 0, '!='));
$nb_downloads = $downloadsHandler->getCount($criteria);
unset($criteria);
// compte le nombre de téléchargements en attente de validation
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 0));
$nb_downloads_waiting = $downloadsHandler->getCount($criteria);
unset($criteria);
// compte le nombre de rapport de téléchargements brisés
$nb_broken = $brokenHandler->getCount();
// compte le nombre de demande de modifications
$nb_modified = $modifiedHandler->getCount();

$adminObject = Admin::getInstance();
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

$helper = Helper::getInstance();
$helper->loadLanguage('common');

$configurator = new Configurator();

$utility = new Utility();

foreach (array_keys($configurator->uploadFolders) as $i) {
    $utility::createFolder($configurator->uploadFolders[$i]);

    $adminObject->addConfigBoxLine($configurator->uploadFolders[$i], 'folder');
}

$adminObject->displayNavigation(basename(__FILE__));

//check for latest release
//$newRelease = $utility->checkVerModule($helper);
//if (null !== $newRelease) {
//    $adminObject->addItemButton($newRelease[0], $newRelease[1], 'download', 'style="color : Red"');
//}

//------------- Test Data Buttons ----------------------------
if ($helper->getConfig('displaySampleButton')) {
    TestdataButtons::loadButtonConfig($adminObject);
    $adminObject->displayButton('left', '');
}
$op = Request::getString('op', 0, 'GET');
switch ($op) {
    case 'hide_buttons':
        TestdataButtons::hideButtons();
        break;
    case 'show_buttons':
        TestdataButtons::showButtons();
        break;
}
//------------- End Test Data Buttons ----------------------------

$adminObject->displayIndex();
echo $utility::getServerStats();

//codeDump(__FILE__);
require __DIR__ . '/admin_footer.php';
