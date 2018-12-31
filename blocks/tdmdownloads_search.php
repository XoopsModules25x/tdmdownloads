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

function b_tdmdownloads_search_show()
{
    require_once XOOPS_ROOT_PATH . '/modules/tdmdownloads/include/functions.php';
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once XOOPS_ROOT_PATH . '/class/tree.php';
    //appel des class
    $downloadscatHandler = xoops_getModuleHandler('tdmdownloads_cat', 'TDMDownloads');
    $downloadsHandler = xoops_getModuleHandler('tdmdownloads_downloads', 'TDMDownloads');
    $downloadsfieldHandler = xoops_getModuleHandler('tdmdownloads_field', 'TDMDownloads');
    $downloadsfielddataHandler = xoops_getModuleHandler('tdmdownloads_fielddata', 'TDMDownloads');
    //appel des fichiers de langues
    xoops_loadLanguage('main', 'TDMDownloads');
    xoops_loadLanguage('admin', 'TDMDownloads');

    $categories = TDMDownloads_MygetItemIds('tdmdownloads_view', 'TDMDownloads');

    $block = [];

    //formulaire de recherche
    $form = new XoopsThemeForm(_MD_TDMDOWNLOADS_SEARCH, 'search', XOOPS_URL . '/modules/tdmdownloads/search.php', 'post');
    $form->setExtra('enctype="multipart/form-data"');
    //recherche par titre
    $form->addElement(new XoopsFormText(_MD_TDMDOWNLOADS_SEARCH_TITLE, 'title', 25, 255, ''));
    //recherche par catégorie
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
    $downloadscat_arr = $downloadscatHandler->getall($criteria);
    $mytree = new XoopsObjectTree($downloadscat_arr, 'cat_cid', 'cat_pid');
    $form->addElement($mytree->makeSelectElement('cat', 'cat_title', '--', '', true, 0, '', _AM_TDMDOWNLOADS_FORMINCAT), true);
    //recherche champ sup.
    $downloadsfieldHandler = xoops_getModuleHandler('tdmdownloads_field', 'TDMDownloads');
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('search', 1));
    $criteria->add(new Criteria('status', 1));
    $criteria->setSort('weight ASC, title');
    $criteria->setOrder('ASC');
    $downloads_field = $downloadsfieldHandler->getall($criteria);
    foreach (array_keys($downloads_field) as $i) {
        $title_sup = '';
        $contenu_arr = [];
        $lid_arr = [];
        $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
        $criteria = new CriteriaCompo();
        $champ_contenu[$downloads_field[$i]->getVar('fid')] = 999;
        if ($downloads_field[$i]->getVar('status_def') == 1) {
            $criteria->add(new Criteria('status', 0, '!='));
            if ($downloads_field[$i]->getVar('fid') == 1) {
                //page d'accueil
                $title_sup = _AM_TDMDOWNLOADS_FORMHOMEPAGE;
                $criteria->setSort('homepage');
                $nom_champ_base = 'homepage';
            }
            if ($downloads_field[$i]->getVar('fid') == 2) {
                //version
                $title_sup = _AM_TDMDOWNLOADS_FORMVERSION;
                $criteria->setSort('version');
                $nom_champ_base = 'version';
            }
            if ($downloads_field[$i]->getVar('fid') == 3) {
                //taille du fichier
                $title_sup = _AM_TDMDOWNLOADS_FORMSIZE;
                $criteria->setSort('size');
                $nom_champ_base = 'size';
            }
            if ($downloads_field[$i]->getVar('fid') == 4) {
                //platform
                $title_sup = _AM_TDMDOWNLOADS_FORMPLATFORM;
                $platform_array = explode('|', xoops_getModuleOption('platform', 'TDMDownloads'));
                foreach ($platform_array as $platform) {
                    $contenu_arr[$platform] = $platform;
                }
            } else {
                $criteria->setOrder('ASC');
                $tdmdownloads_arr = $downloadsHandler->getall($criteria);
                foreach (array_keys($tdmdownloads_arr) as $j) {
                    $contenu_arr[$tdmdownloads_arr[$j]->getVar($nom_champ_base)] = $tdmdownloads_arr[$j]->getVar($nom_champ_base);
                }
            }
        } else {
            $title_sup = $downloads_field[$i]->getVar('title');
            $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
            $criteria->setSort('data');
            $criteria->setOrder('ASC');
            $tdmdownloads_arr = $downloadsfielddataHandler->getall($criteria);
            foreach (array_keys($tdmdownloads_arr) as $j) {
                $contenu_arr[$tdmdownloads_arr[$j]->getVar('data', 'n')] = $tdmdownloads_arr[$j]->getVar('data');
            }
            if ($champ_contenu[$downloads_field[$i]->getVar('fid')] != '') {
                $criteria_1 = new CriteriaCompo();
                $criteria_1->add(new Criteria('data', $champ_contenu[$downloads_field[$i]->getVar('fid')]));
                $data_arr = $downloadsfielddataHandler->getall($criteria_1);
                foreach (array_keys($data_arr) as $k) {
                    $lid_arr[] = $data_arr[$k]->getVar('lid');
                }
            }
            $form->addElement($select_sup);
        }
        $select_sup = new XoopsFormSelect($title_sup, $nom_champ, $champ_contenu[$downloads_field[$i]->getVar('fid')]);
        $select_sup->addOption(999, _MD_TDMDOWNLOADS_SEARCH_ALL1);
        $select_sup->addOptionArray($contenu_arr);
        $form->addElement($select_sup);
        unset($select_sup);
    }
    //bouton validation
    $button_tray = new XoopsFormElementTray('', '');
    $button_tray->addElement(new XoopsFormButton('', 'submit', _MD_TDMDOWNLOADS_SEARCH_BT, 'submit'));
    $form->addElement($button_tray);
    $block['form'] = $form->render();

    return $block;
}
