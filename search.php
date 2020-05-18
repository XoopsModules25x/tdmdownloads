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
 * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage)
 */

use XoopsModules\Tdmdownloads;

require_once __DIR__ . '/header.php';

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper        = Tdmdownloads\Helper::getInstance();
$moduleDirName = basename(__DIR__);

// template d'affichage
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_liste.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
/** @var \xos_opal_Theme $xoTheme */
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);

$categories = $utility->getItemIds('tdmdownloads_view', $moduleDirName);

$title = '';
if (\Xmf\Request::hasVar('title', 'REQUEST')) {
    $title = \Xmf\Request::getString('title', '', 'REQUEST');
}

$cat = 0;
if (\Xmf\Request::hasVar('cat', 'REQUEST')) {
    $cat = \Xmf\Request::getInt('cat', 0, 'REQUEST');
}
// tableau ------
$criteria_2 = new \CriteriaCompo();
$criteria_2->add(new \Criteria('status', 0, '!='));
$criteria_2->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
// ------
//formulaire de recherche
$form = new \XoopsThemeForm(_MD_TDMDOWNLOADS_SEARCH, 'search', 'search.php', 'post');
$form->setExtra('enctype="multipart/form-data"');
//recherche par titre
$form->addElement(new \XoopsFormText(_MD_TDMDOWNLOADS_SEARCH_TITLE, 'title', 25, 255, $title));
//recherche par catégorie
$criteria = new \CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new \Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
/*$cat_select = new \XoopsFormSelect(_MD_TDMDOWNLOADS_SEARCH_CATEGORIES . ' ', 'cat', $cat);
$cat_select->addOption(0,_MD_TDMDOWNLOADS_SEARCH_ALL2);
$cat_select->addOptionArray($categoryHandler->getList($criteria ));
$form->addElement($cat_select);*/
$downloadscatArray = $categoryHandler->getAll($criteria);
$mytree            = new \XoopsModules\Tdmdownloads\Tree($downloadscatArray, 'cat_cid', 'cat_pid');
$form->addElement($mytree->makeSelectElement('cat', 'cat_title', '--', $cat, true, 0, '', _AM_TDMDOWNLOADS_FORMINCAT), true);

//recherche champ sup.
//$fieldHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Field');
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('search', 1));
$criteria->add(new \Criteria('status', 1));
$criteria->setSort('weight ASC, title');
$criteria->setOrder('ASC');
$downloads_field = $fieldHandler->getAll($criteria);

$arguments = '';
foreach (array_keys($downloads_field) as $i) {
    /** @var \XoopsModules\Tdmdownloads\Field[] $downloads_field */
    $title_sup    = '';
    $contentArray = [];
    $lid_arr      = [];
    $fieldName    = 'champ' . $downloads_field[$i]->getVar('fid');
    $criteria     = new \CriteriaCompo();
    if (\Xmf\Request::hasVar($fieldName, 'REQUEST')) {
        999 !== \Xmf\Request::getInt($fieldName, 0, 'REQUEST') ? $fieldContent[$downloads_field[$i]->getVar('fid')] = \Xmf\Request::getInt($fieldName, 0, 'REQUEST') : $fieldContent[$downloads_field[$i]->getVar('fid')] = 999;
        $arguments .= $fieldName . '=' . \Xmf\Request::getInt($fieldName, 0, 'REQUEST') . '&amp;';
    } else {
        $fieldContent[$downloads_field[$i]->getVar('fid')] = 999;
        $arguments                                         .= $fieldName . '=&amp;';
    }
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
            $platformArray = explode('|', $helper->getConfig('plateform'));
            foreach ($platformArray as $platform) {
                $contentArray[$platform] = $platform;
            }
            if (999 !== $fieldContent[$downloads_field[$i]->getVar('fid')]) {
                $criteria_2->add(new \Criteria('platform', '%' . $fieldContent[$downloads_field[$i]->getVar('fid')] . '%', 'LIKE'));
            }
        } else {
            $criteria->setOrder('ASC');
            $tdmdownloadsArray = $downloadsHandler->getAll($criteria);
            foreach (array_keys($tdmdownloadsArray) as $j) {
                /** @var \XoopsModules\Tdmdownloads\Downloads[] $tdmdownloadsArray */
                $contentArray[$tdmdownloadsArray[$j]->getVar($fieldNameBase)] = $tdmdownloadsArray[$j]->getVar($fieldNameBase);
            }
            if (999 !== $fieldContent[$downloads_field[$i]->getVar('fid')]) {
                $criteria_2->add(new \Criteria($fieldNameBase, $fieldContent[$downloads_field[$i]->getVar('fid')]));
            }
        }
    } else {
        $title_sup = $downloads_field[$i]->getVar('title');
        $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));
        $criteria->setSort('data');
        $criteria->setOrder('ASC');
        $tdmdownloadsArray = $fielddataHandler->getAll($criteria);
        foreach (array_keys($tdmdownloadsArray) as $j) {
            /** @var \XoopsModules\Tdmdownloads\Downloads[] $tdmdownloadsArray */
            $contentArray[$tdmdownloadsArray[$j]->getVar('data', 'n')] = $tdmdownloadsArray[$j]->getVar('data');
        }
        if ('' !== $fieldContent[$downloads_field[$i]->getVar('fid')]) {
            $criteria_1 = new \CriteriaCompo();
            $criteria_1->add(new \Criteria('data', $fieldContent[$downloads_field[$i]->getVar('fid')]));
            $dataArray = $fielddataHandler->getAll($criteria_1);
            foreach (array_keys($dataArray) as $k) {
                /** @var \XoopsModules\Tdmdownloads\Fielddata[] $dataArray */
                $lid_arr[] = $dataArray[$k]->getVar('lid');
            }
        }
    }
    if (count($lid_arr) > 0) {
        $criteria_2->add(new \Criteria('lid', '(' . implode(',', $lid_arr) . ')', 'IN'));
    }
    $select_sup = new \XoopsFormSelect($title_sup, $fieldName, $fieldContent[$downloads_field[$i]->getVar('fid')]);
    $select_sup->addOption(999, _MD_TDMDOWNLOADS_SEARCH_ALL1);
    $select_sup->addOptionArray($contentArray);
    $form->addElement($select_sup);
    unset($select_sup);
    $xoopsTpl->append('field', $downloads_field[$i]->getVar('title'));
}

//bouton validation
$buttonTray = new \XoopsFormElementTray('', '');
$buttonTray->addElement(new \XoopsFormButton('', 'submit', _MD_TDMDOWNLOADS_SEARCH_BT, 'submit'));
$form->addElement($buttonTray);

if ('' !== $title) {
    $criteria_2->add(new \Criteria('title', '%' . $title . '%', 'LIKE'));
    $arguments .= 'title=' . $title . '&amp;';
}
if (0 !== $cat) {
    $criteria_2->add(new \Criteria('cid', $cat));
    $arguments .= 'cat=' . $cat . '&amp;';
}
$tblsort     = [];
$tblsort[1]  = 'date';
$tblsort[2]  = 'date';
$tblsort[3]  = 'hits';
$tblsort[4]  = 'hits';
$tblsort[5]  = 'rating';
$tblsort[6]  = 'rating';
$tblsort[7]  = 'title';
$tblsort[8]  = 'title';
$tblorder    = [];
$tblorder[1] = 'DESC';
$tblorder[2] = 'ASC';
$tblorder[3] = 'DESC';
$tblorder[4] = 'ASC';
$tblorder[5] = 'DESC';
$tblorder[6] = 'ASC';
$tblorder[7] = 'DESC';
$tblorder[8] = 'ASC';
$sort        = $helper->getConfig('searchorder') ?? 1;
$order       = $helper->getConfig('searchorder') ?? 1;
$criteria_2->setSort($tblsort[$sort]);
$criteria_2->setOrder($tblorder[$order]);
$numrows = $downloadsHandler->getCount($criteria_2);
if (\Xmf\Request::hasVar('limit', 'REQUEST')) {
    $criteria_2->setLimit(\Xmf\Request::getInt('limit', 0, 'REQUEST'));
    $limit = \Xmf\Request::getInt('limit', 0, 'REQUEST');
} else {
    $criteria_2->setLimit($helper->getConfig('perpageliste'));
    $limit = $helper->getConfig('perpageliste');
}
if (\Xmf\Request::hasVar('start', 'REQUEST')) {
    $criteria_2->setStart(\Xmf\Request::getInt('start', 0, 'REQUEST'));
    $start = \Xmf\Request::getInt('start', 0, 'REQUEST');
} else {
    $criteria_2->setStart(0);
    $start = 0;
}
//pour faire une jointure de table
$downloadsHandler->table_link   = $downloadsHandler->db->prefix('tdmdownloads_cat'); // Nom de la table en jointure
$downloadsHandler->field_link   = 'cat_cid'; // champ de la table en jointure
$downloadsHandler->field_object = 'cid'; // champ de la table courante
$tdmdownloadsArray              = $downloadsHandler->getByLink($criteria_2);
if ($numrows > $limit) {
    require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', $arguments);
    $pagenav = $pagenav->renderNav(4);
} else {
    $pagenav = '';
}
$xoopsTpl->assign('lang_thereare', sprintf(_MD_TDMDOWNLOADS_SEARCH_THEREARE, $downloadsHandler->getCount($criteria_2)));
$xoopsTpl->assign('pagenav', $pagenav);
$keywords = '';
foreach (array_keys($tdmdownloadsArray) as $i) {
    $tdmdownloadsTab['lid']    = $tdmdownloadsArray[$i]->getVar('lid');
    $tdmdownloadsTab['cid']    = $tdmdownloadsArray[$i]->getVar('cid');
    $tdmdownloadsTab['title']  = $tdmdownloadsArray[$i]->getVar('title');
    $tdmdownloadsTab['cat']    = $tdmdownloadsArray[$i]->getVar('cat_title');
    $tdmdownloadsTab['imgurl'] = $uploadurl . $tdmdownloadsArray[$i]->getVar('cat_imgurl');
    $tdmdownloadsTab['date']   = formatTimestamp($tdmdownloadsArray[$i]->getVar('date'), 'd/m/Y');
    $tdmdownloadsTab['rating'] = number_format($tdmdownloadsArray[$i]->getVar('rating'), 0);
    $tdmdownloadsTab['hits']   = $tdmdownloadsArray[$i]->getVar('hits');
    $contenu                   = '';
    foreach (array_keys($downloads_field) as $j) {
        if (1 == $downloads_field[$j]->getVar('status_def')) {
            if (1 == $downloads_field[$j]->getVar('fid')) {
                //page d'accueil
                $contenu = $tdmdownloadsArray[$i]->getVar('homepage');
            }
            if (2 == $downloads_field[$j]->getVar('fid')) {
                //version
                $contenu = $tdmdownloadsArray[$i]->getVar('version');
            }
            if (3 == $downloads_field[$j]->getVar('fid')) {
                //taille du fichier
                //mb $contenu = $utilities->convertFileSize($tdmdownloads_arr[$i]->getVar('size'));
                $contenu = $tdmdownloadsArray[$i]->getVar('size');
            }
            if (4 == $downloads_field[$j]->getVar('fid')) {
                //plateforme
                $contenu = $tdmdownloadsArray[$i]->getVar('platform');
            }
        } else {
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('lid', $tdmdownloadsArray[$i]->getVar('lid')));
            $criteria->add(new \Criteria('fid', $downloads_field[$j]->getVar('fid')));
            $downloadsfielddata = $fielddataHandler->getAll($criteria);
            if (count($downloadsfielddata) > 0) {
                foreach (array_keys($downloadsfielddata) as $k) {
                    /** @var \XoopsModules\Tdmdownloads\Fielddata[] $downloadsfielddata */
                    $contenu = $downloadsfielddata[$k]->getVar('data', 'n');
                }
            } else {
                $contenu = '';
            }
        }

        $tdmdownloadsTab['fielddata'][$j] = $contenu;
        unset($contenu);
    }
    $xoopsTpl->append('search_list', $tdmdownloadsTab);

    $keywords .= $tdmdownloadsArray[$i]->getVar('title') . ',';
}

$xoopsTpl->assign('searchForm', $form->render());
$xoopsTpl->assign('perm_submit', $perm_submit);
$xoopsTpl->assign('pathModIcon16', XOOPS_URL . '/modules/' . $moduleDirName . '/' . $pathModIcon16);

// référencement
// titre de la page
$titre = _MD_TDMDOWNLOADS_SEARCH_PAGETITLE . ' - ' . $xoopsModule->name();
$xoopsTpl->assign('xoops_pagetitle', $titre);
//description
$xoTheme->addMeta('meta', 'description', strip_tags($xoopsModule->name()));
//keywords
$keywords = mb_substr($keywords, 0, -1);
$xoTheme->addMeta('meta', 'keywords', strip_tags($keywords));

require XOOPS_ROOT_PATH . '/footer.php';
