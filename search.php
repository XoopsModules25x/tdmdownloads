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

require_once __DIR__ . '/header.php';
// template d'affichage
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_liste.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/css/styles.css', null);

$categories = TDMDownloads_MygetItemIds('tdmdownloads_view', 'TDMDownloads');

if (isset($_REQUEST['title'])) {
    '' != $_REQUEST['title'] ? $title = $_REQUEST['title'] : $title = '';
} else {
    $title = '';
}

if (isset($_REQUEST['cat'])) {
    0 != $_REQUEST['cat'] ? $cat = $_REQUEST['cat'] : $cat = 0;
} else {
    $cat = 0;
}
// tableau ------
$criteria_2 = new CriteriaCompo();
$criteria_2->add(new Criteria('status', 0, '!='));
$criteria_2->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
// ------
//formulaire de recherche
$form = new XoopsThemeForm(_MD_TDMDOWNLOADS_SEARCH, 'search', 'search.php', 'post');
$form->setExtra('enctype="multipart/form-data"');
//recherche par titre
$form->addElement(new XoopsFormText(_MD_TDMDOWNLOADS_SEARCH_TITLE, 'title', 25, 255, $title));
//recherche par catégorie
$criteria = new CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
/*$cat_select = new XoopsFormSelect(_MD_TDMDOWNLOADS_SEARCH_CATEGORIES . ' ', 'cat', $cat);
$cat_select->addOption(0,_MD_TDMDOWNLOADS_SEARCH_ALL2);
$cat_select->addOptionArray($downloadscatHandler->getList($criteria ));
$form->addElement($cat_select);*/
$downloadscat_arr = $downloadscatHandler->getall($criteria);
$mytree           = new XoopsObjectTree($downloadscat_arr, 'cat_cid', 'cat_pid');
$form->addElement($mytree->makeSelectElement('cat', 'cat_title', '--', $cat, true, 0, '', _AM_TDMDOWNLOADS_FORMINCAT), true);

//recherche champ sup.
$downloadsfieldHandler = xoops_getModuleHandler('tdmdownloads_field', 'TDMDownloads');
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('search', 1));
$criteria->add(new Criteria('status', 1));
$criteria->setSort('weight ASC, title');
$criteria->setOrder('ASC');
$downloads_field = $downloadsfieldHandler->getall($criteria);

$arguments = '';
foreach (array_keys($downloads_field) as $i) {
    $title_sup   = '';
    $contenu_arr = [];
    $lid_arr     = [];
    $nom_champ   = 'champ' . $downloads_field[$i]->getVar('fid');
    $criteria    = new CriteriaCompo();
    if (isset($_REQUEST[$nom_champ])) {
        999 != $_REQUEST[$nom_champ] ? $champ_contenu[$downloads_field[$i]->getVar('fid')] = $_REQUEST[$nom_champ] : $champ_contenu[$downloads_field[$i]->getVar('fid')] = 999;
        $arguments .= $nom_champ . '=' . $_REQUEST[$nom_champ] . '&amp;';
    } else {
        $champ_contenu[$downloads_field[$i]->getVar('fid')] = 999;
        $arguments .= $nom_champ . '=&amp;';
    }
    if (1 == $downloads_field[$i]->getVar('status_def')) {
        $criteria->add(new Criteria('status', 0, '!='));
        if (1 == $downloads_field[$i]->getVar('fid')) {
            //page d'accueil
            $title_sup = _AM_TDMDOWNLOADS_FORMHOMEPAGE;
            $criteria->setSort('homepage');
            $nom_champ_base = 'homepage';
        }
        if (2 == $downloads_field[$i]->getVar('fid')) {
            //version
            $title_sup = _AM_TDMDOWNLOADS_FORMVERSION;
            $criteria->setSort('version');
            $nom_champ_base = 'version';
        }
        if (3 == $downloads_field[$i]->getVar('fid')) {
            //taille du fichier
            $title_sup = _AM_TDMDOWNLOADS_FORMSIZE;
            $criteria->setSort('size');
            $nom_champ_base = 'size';
        }
        if (4 == $downloads_field[$i]->getVar('fid')) {
            //platform
            $title_sup      = _AM_TDMDOWNLOADS_FORMPLATFORM;
            $platform_array = explode('|', $xoopsModuleConfig['plateform']);
            foreach ($platform_array as $platform) {
                $contenu_arr[$platform] = $platform;
            }
            if (999 != $champ_contenu[$downloads_field[$i]->getVar('fid')]) {
                $criteria_2->add(new Criteria('platform', '%' . $champ_contenu[$downloads_field[$i]->getVar('fid')] . '%', 'LIKE'));
            }
        } else {
            $criteria->setOrder('ASC');
            $tdmdownloads_arr = $downloadsHandler->getall($criteria);
            foreach (array_keys($tdmdownloads_arr) as $j) {
                $contenu_arr[$tdmdownloads_arr[$j]->getVar($nom_champ_base)] = $tdmdownloads_arr[$j]->getVar($nom_champ_base);
            }
            if (999 != $champ_contenu[$downloads_field[$i]->getVar('fid')]) {
                $criteria_2->add(new Criteria($nom_champ_base, $champ_contenu[$downloads_field[$i]->getVar('fid')]));
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
        if ('' != $champ_contenu[$downloads_field[$i]->getVar('fid')]) {
            $criteria_1 = new CriteriaCompo();
            $criteria_1->add(new Criteria('data', $champ_contenu[$downloads_field[$i]->getVar('fid')]));
            $data_arr = $downloadsfielddataHandler->getall($criteria_1);
            foreach (array_keys($data_arr) as $k) {
                $lid_arr[] = $data_arr[$k]->getVar('lid');
            }
        }
        $form->addElement($select_sup);
    }
    if (count($lid_arr) > 0) {
        $criteria_2->add(new Criteria('lid', '(' . implode(',', $lid_arr) . ')', 'IN'));
    }
    $select_sup = new XoopsFormSelect($title_sup, $nom_champ, $champ_contenu[$downloads_field[$i]->getVar('fid')]);
    $select_sup->addOption(999, _MD_TDMDOWNLOADS_SEARCH_ALL1);
    $select_sup->addOptionArray($contenu_arr);
    $form->addElement($select_sup);
    unset($select_sup);
    $xoopsTpl->append('field', $downloads_field[$i]->getVar('title'));
}

//bouton validation
$button_tray = new XoopsFormElementTray('', '');
$button_tray->addElement(new XoopsFormButton('', 'submit', _MD_TDMDOWNLOADS_SEARCH_BT, 'submit'));
$form->addElement($button_tray);

if ('' != $title) {
    $criteria_2->add(new Criteria('title', '%' . $title . '%', 'LIKE'));
    $arguments .= 'title=' . $title . '&amp;';
}
if (0 != $cat) {
    $criteria_2->add(new Criteria('cid', $cat));
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
$sort        = isset($xoopsModuleConfig['searchorder']) ? $xoopsModuleConfig['searchorder'] : 1;
$order       = isset($xoopsModuleConfig['searchorder']) ? $xoopsModuleConfig['searchorder'] : 1;
$criteria_2->setSort($tblsort[$sort]);
$criteria_2->setOrder($tblorder[$order]);
$numrows = $downloadsHandler->getCount($criteria_2);
if (isset($_REQUEST['limit'])) {
    $criteria_2->setLimit($_REQUEST['limit']);
    $limit = $_REQUEST['limit'];
} else {
    $criteria_2->setLimit($xoopsModuleConfig['perpageliste']);
    $limit = $xoopsModuleConfig['perpageliste'];
}
if (isset($_REQUEST['start'])) {
    $criteria_2->setStart($_REQUEST['start']);
    $start = $_REQUEST['start'];
} else {
    $criteria_2->setStart(0);
    $start = 0;
}
//pour faire une jointure de table
$downloadsHandler->table_link   = $downloadsHandler->db->prefix('tdmdownloads_cat'); // Nom de la table en jointure
$downloadsHandler->field_link   = 'cat_cid'; // champ de la table en jointure
$downloadsHandler->field_object = 'cid'; // champ de la table courante
$tdmdownloads_arr                = $downloadsHandler->getByLink($criteria_2);
if ($numrows > $limit) {
    $pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', $arguments);
    $pagenav = $pagenav->renderNav(4);
} else {
    $pagenav = '';
}
$xoopsTpl->assign('lang_thereare', sprintf(_MD_TDMDOWNLOADS_SEARCH_THEREARE, $downloadsHandler->getCount($criteria_2)));
$xoopsTpl->assign('pagenav', $pagenav);
$keywords = '';
foreach (array_keys($tdmdownloads_arr) as $i) {
    $tdmdownloads_tab['lid']    = $tdmdownloads_arr[$i]->getVar('lid');
    $tdmdownloads_tab['cid']    = $tdmdownloads_arr[$i]->getVar('cid');
    $tdmdownloads_tab['title']  = $tdmdownloads_arr[$i]->getVar('title');
    $tdmdownloads_tab['cat']    = $tdmdownloads_arr[$i]->getVar('cat_title');
    $tdmdownloads_tab['imgurl'] = $uploadurl . $tdmdownloads_arr[$i]->getVar('cat_imgurl');
    $tdmdownloads_tab['date']   = formatTimestamp($tdmdownloads_arr[$i]->getVar('date'), 'd/m/Y');
    $tdmdownloads_tab['rating'] = number_format($tdmdownloads_arr[$i]->getVar('rating'), 0);
    $tdmdownloads_tab['hits']   = $tdmdownloads_arr[$i]->getVar('hits');
    $contenu                    = '';
    foreach (array_keys($downloads_field) as $j) {
        if (1 == $downloads_field[$j]->getVar('status_def')) {
            if (1 == $downloads_field[$j]->getVar('fid')) {
                //page d'accueil
                $contenu = $tdmdownloads_arr[$i]->getVar('homepage');
            }
            if (2 == $downloads_field[$j]->getVar('fid')) {
                //version
                $contenu = $tdmdownloads_arr[$i]->getVar('version');
            }
            if (3 == $downloads_field[$j]->getVar('fid')) {
                //taille du fichier
                $contenu = $tdmdownloads_arr[$i]->getVar('size');
            }
            if (4 == $downloads_field[$j]->getVar('fid')) {
                //plateforme
                $contenu = $tdmdownloads_arr[$i]->getVar('platform');
            }
        } else {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $tdmdownloads_arr[$i]->getVar('lid')));
            $criteria->add(new Criteria('fid', $downloads_field[$j]->getVar('fid')));
            $downloadsfielddata = $downloadsfielddataHandler->getall($criteria);
            if (count($downloadsfielddata) > 0) {
                foreach (array_keys($downloadsfielddata) as $k) {
                    $contenu = $downloadsfielddata[$k]->getVar('data', 'n');
                }
            } else {
                $contenu = '';
            }
        }
        $tdmdownloads_tab['fielddata'][$j] = $contenu;
        unset($contenu);
    }
    $xoopsTpl->append('downloads', $tdmdownloads_tab);
    
    $keywords .= $tdmdownloads_arr[$i]->getVar('title') . ',';
}
$xoopsTpl->assign('searchForm', $form->render());
// référencement
// titre de la page
$titre = _MD_TDMDOWNLOADS_SEARCH_PAGETITLE . ' - ' . $xoopsModule->name();
$xoopsTpl->assign('xoops_pagetitle', $titre);
//description
$xoTheme->addMeta('meta', 'description', strip_tags($xoopsModule->name()));
//keywords
$keywords = substr($keywords, 0, -1);
$xoTheme->addMeta('meta', 'keywords', strip_tags($keywords));

include XOOPS_ROOT_PATH . '/footer.php';
