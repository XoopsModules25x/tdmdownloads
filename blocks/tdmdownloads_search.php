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

use XoopsModules\Tdmdownloads\{
    Helper,
    Tree
};

/**
 * @return array
 */
function b_tdmdownloads_search_show()
{
    require dirname(__DIR__) . '/include/common.php';
    $moduleDirName = basename(dirname(__DIR__));
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    require_once XOOPS_ROOT_PATH . '/class/tree.php';
    $db = null;
    $helper = Helper::getInstance();
    //appel des class
    //appel des fichiers de langues
    $helper->loadLanguage('admin');
    $helper->loadLanguage('main');
    $helper->loadLanguage('common');
    $utility    = new \XoopsModules\Tdmdownloads\Utility();
    $categories = $utility->getItemIds('tdmdownloads_view', $moduleDirName);
    /** @var \XoopsModules\Tdmdownloads\CategoryHandler $categoryHandler */
    $categoryHandler = Helper::getInstance()->getHandler('Category');
    $block           = [];
    //formulaire de recherche
    $form = new \XoopsThemeForm(_MD_TDMDOWNLOADS_SEARCH, 'search', XOOPS_URL . '/modules/' . $moduleDirName . '/search.php', 'post');
    $form->setExtra('enctype="multipart/form-data"');
    //recherche par titre
    $form->addElement(new \XoopsFormText(_MD_TDMDOWNLOADS_SEARCH_TITLE, 'title', 25, 255, ''));
    //recherche par catégorie
    $criteria = new \CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $criteria->add(new \Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
    $downloadscatArray = $categoryHandler->getAll($criteria);
    $mytree            = new Tree($downloadscatArray, 'cat_cid', 'cat_pid');
    $form->addElement($mytree->makeSelectElement('cat', 'cat_title', '--', '', true, 0, '', _AM_TDMDOWNLOADS_FORMINCAT), true);
    //recherche champ sup.
    /** @var \XoopsModules\Tdmdownloads\FieldHandler $fieldHandler */
    $fieldHandler = Helper::getInstance()->getHandler('Field');
    $criteria     = new \CriteriaCompo();
    $criteria->add(new \Criteria('search', 1));
    $criteria->add(new \Criteria('status', 1));
    $criteria->setSort('weight ASC, title');
    $criteria->setOrder('ASC');
    $downloads_field = $fieldHandler->getAll($criteria);
    $fieldNameBase   = '';
    foreach (array_keys($downloads_field) as $i) {
        /** @var \XoopsModules\Tdmdownloads\Field[] $downloads_field */
        $title_sup                                         = '';
        $contentArray                                      = [];
        $lid_arr                                           = [];
        $fieldName                                         = 'champ' . $downloads_field[$i]->getVar('fid');
        $criteria                                          = new \CriteriaCompo();
        $fieldContent[$downloads_field[$i]->getVar('fid')] = 999;
        if (1 == $downloads_field[$i]->getVar('status_def')) {
            $criteria->add(new \Criteria('status', 0, '!='));
            if (1 == $downloads_field[$i]->getVar('fid')) {
                //page d'accueil
                $title_sup = _AM_TDMDOWNLOADS_FORMHOMEPAGE;
                $criteria->setSort('homepage');
                $fieldNameBase = 'homepage';
            }
            if (2 == $downloads_field[$i]->getVar('fid')) {
                //version
                $title_sup = _AM_TDMDOWNLOADS_FORMVERSION;
                $criteria->setSort('version');
                $fieldNameBase = 'version';
            }
            if (3 == $downloads_field[$i]->getVar('fid')) {
                //taille du fichier
                $title_sup = _AM_TDMDOWNLOADS_FORMSIZE;
                $criteria->setSort('size');
                $fieldNameBase = 'size';
            }
            if (4 == $downloads_field[$i]->getVar('fid')) {
                //platform
                $title_sup     = _AM_TDMDOWNLOADS_FORMPLATFORM;
                $platformArray = explode('|', xoops_getModuleOption('platform', $moduleDirName));
                foreach ($platformArray as $platform) {
                    $contentArray[$platform] = $platform;
                }
            } else {
                $criteria->setOrder('ASC');
                /** @var \XoopsModules\Tdmdownloads\DownloadsHandler $downloadsHandler */
                $downloadsHandler  = Helper::getInstance()->getHandler('Downloads');
                $tdmdownloadsArray = $downloadsHandler->getAll($criteria);
                foreach (array_keys($tdmdownloadsArray) as $j) {
                    /** @var \XoopsModules\Tdmdownloads\Downloads[] $tdmdownloadsArray */
                    $temp                = $tdmdownloadsArray[$j]->getVar($fieldNameBase);
                    $contentArray[$temp] = $temp;
                }
            }
        } else {
            $title_sup = $downloads_field[$i]->getVar('title');
            $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));
            $criteria->setSort('data');
            $criteria->setOrder('ASC');
            /** @var \XoopsModules\Tdmdownloads\FielddataHandler $fielddataHandler */
            $fielddataHandler  = Helper::getInstance()->getHandler('Fielddata');
            $tdmdownloadsArray = $fielddataHandler->getAll($criteria);
            foreach (array_keys($tdmdownloadsArray) as $j) {
                /** @var \XoopsModules\Tdmdownloads\Downloads[] $tdmdownloadsArray */
                $contentArray[$tdmdownloadsArray[$j]->getVar('data', 'n')] = $tdmdownloadsArray[$j]->getVar('data');
            }
            if ('' != $fieldContent[$downloads_field[$i]->getVar('fid')]) {
                $criteria_1 = new \CriteriaCompo();
                $criteria_1->add(new \Criteria('data', $fieldContent[$downloads_field[$i]->getVar('fid')]));
                /** @var \XoopsModules\Tdmdownloads\Fielddata $dataArray */
                $dataArray = $fielddataHandler->getAll($criteria_1);
                foreach (array_keys($dataArray) as $k) {
                    /** @var \XoopsModules\Tdmdownloads\Fielddata[] $dataArray */
                    $lid_arr[] = $dataArray[$k]->getVar('lid');
                }
            }
            //            $form->addElement($select_sup);
        }
        $select_sup = new \XoopsFormSelect($title_sup, $fieldName, $fieldContent[$downloads_field[$i]->getVar('fid')]);
        $select_sup->addOption(999, _MD_TDMDOWNLOADS_SEARCH_ALL1);
        $select_sup->addOptionArray($contentArray);
        $form->addElement($select_sup);
        unset($select_sup);
    }
    //bouton validation
    $buttonTray = new \XoopsFormElementTray('', '');
    $buttonTray->addElement(new \XoopsFormButton('', 'submit', _MD_TDMDOWNLOADS_SEARCH_BT, 'submit'));
    $form->addElement($buttonTray);
    $block['form'] = $form->render();
    return $block;
}
