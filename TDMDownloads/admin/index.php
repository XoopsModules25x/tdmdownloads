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
// compte le nombre de catégories
$criteria = new CriteriaCompo();
$nb_categories = $downloadscat_Handler->getCount($criteria);
// compte le nombre de téléchargements
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 0, '!='));
$nb_downloads = $downloads_Handler->getCount($criteria);
// compte le nombre de téléchargements en attente de validation
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 0));
$nb_downloads_waiting = $downloads_Handler->getCount($criteria);
// compte le nombre de rapport de téléchargements brisés
$nb_broken = $downloadsbroken_Handler->getCount();
// compte le nombre de demande de modifications
$nb_modified = $downloadsmod_Handler->getCount();
// dossier dans uploads
$folder = array(XOOPS_ROOT_PATH . '/uploads/TDMDownloads/', XOOPS_ROOT_PATH . '/uploads/TDMDownloads/downloads', XOOPS_ROOT_PATH . '/uploads/TDMDownloads/images',
               XOOPS_ROOT_PATH . '/uploads/TDMDownloads/images/cats', XOOPS_ROOT_PATH . '/uploads/TDMDownloads/images/field', XOOPS_ROOT_PATH . '/uploads/TDMDownloads/images/shots');

if (TDMDownloads_checkModuleAdmin()){
    $index_admin = new ModuleAdmin();
    $index_admin->addInfoBox(_MI_TDMDOWNLOADS_ADMENU2);
    $index_admin->addInfoBox(_MI_TDMDOWNLOADS_ADMENU3);
    $index_admin->addInfoBox(_MI_TDMDOWNLOADS_ADMENU4);
    $index_admin->addInfoBox(_MI_TDMDOWNLOADS_ADMENU5);
    $index_admin->addInfoBoxLine(_MI_TDMDOWNLOADS_ADMENU2, _AM_TDMDOWNLOADS_INDEX_CATEGORIES, $nb_categories);
    $index_admin->addInfoBoxLine(_MI_TDMDOWNLOADS_ADMENU3, _AM_TDMDOWNLOADS_INDEX_DOWNLOADS, $nb_downloads);
    if ($nb_downloads_waiting == 0){
        $index_admin->addInfoBoxLine(_MI_TDMDOWNLOADS_ADMENU3, _AM_TDMDOWNLOADS_INDEX_DOWNLOADSWAITING, $nb_downloads_waiting, 'Green');
    }else{
        $index_admin->addInfoBoxLine(_MI_TDMDOWNLOADS_ADMENU3, _AM_TDMDOWNLOADS_INDEX_DOWNLOADSWAITING, $nb_downloads_waiting, 'Red');
    }
    if ($nb_broken == 0){
        $index_admin->addInfoBoxLine(_MI_TDMDOWNLOADS_ADMENU4, _AM_TDMDOWNLOADS_INDEX_BROKEN, $nb_broken, 'Green');
    }else{
        $index_admin->addInfoBoxLine(_MI_TDMDOWNLOADS_ADMENU4, _AM_TDMDOWNLOADS_INDEX_BROKEN, $nb_broken, 'Red');
    }
    if ($nb_modified == 0){
        $index_admin->addInfoBoxLine(_MI_TDMDOWNLOADS_ADMENU5, _AM_TDMDOWNLOADS_INDEX_MODIFIED, $nb_modified, 'Green');
    }else{
        $index_admin->addInfoBoxLine(_MI_TDMDOWNLOADS_ADMENU5, _AM_TDMDOWNLOADS_INDEX_MODIFIED, $nb_modified, 'Red');
    }
    foreach (array_keys( $folder) as $i) {
        $index_admin->addConfigBoxLine($folder[$i], 'folder');
        $index_admin->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
    }
    echo $index_admin->addNavigation('index.php');
    echo $index_admin->renderIndex();
}
xoops_cp_footer();
?>