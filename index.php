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
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_index.tpl';
require_once XOOPS_ROOT_PATH.'/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/css/styles.css', null);
// pour les permissions
$categories = TDMDownloads_MygetItemIds('tdmdownloads_view', 'TDMDownloads');

//tableau des téléchargements
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 0, '!='));
$criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
$downloads_arr = $downloadsHandler->getall($criteria);
$xoopsTpl->assign('lang_thereare', sprintf(_MD_TDMDOWNLOADS_INDEX_THEREARE, count($downloads_arr)));

//tableau des catégories
$criteria = new \CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new \Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
$downloadscat_arr = $categoryHandler->getall($criteria);
$mytree = new \XoopsModules\Tdmdownloads\Tree($downloadscat_arr, 'cat_cid', 'cat_pid');

//affichage des catégories
$xoopsTpl->assign('nb_catcol', $xoopsModuleConfig['nb_catcol']);
$count = 1;
$keywords = '';
foreach (array_keys($downloadscat_arr) as $i) {
    if (0 == $downloadscat_arr[$i]->getVar('cat_pid')) {
        $totaldownloads = TDMDownloads_NumbersOfEntries($mytree, $categories, $downloads_arr, $downloadscat_arr[$i]->getVar('cat_cid'));
        $subcategories_arr = $mytree->getFirstChild($downloadscat_arr[$i]->getVar('cat_cid'));
        $chcount = 0;
        $subcategories = '';
        //pour les mots clef
        $keywords .= $downloadscat_arr[$i]->getVar('cat_title') . ',';
        foreach (array_keys($subcategories_arr) as $j) {
            if ($chcount>=$xoopsModuleConfig['nbsouscat']) {
                $subcategories .= '<li>[<a href="' . XOOPS_URL . '/modules/tdmdownloads/viewcat.php?cid=' . $downloadscat_arr[$i]->getVar('cat_cid') . '">+</a>]</li>';
                break;
            }
            $subcategories .= '<li><a href="' . XOOPS_URL . '/modules/tdmdownloads/viewcat.php?cid=' . $subcategories_arr[$j]->getVar('cat_cid') . '">' . $subcategories_arr[$j]->getVar('cat_title') . '</a></li>';
            $keywords .= $downloadscat_arr[$i]->getVar('cat_title') . ',';
            $chcount++;
        }
        $xoopsTpl->append('categories', ['image' => $uploadurl . $downloadscat_arr[$i]->getVar('cat_imgurl'), 'id' => $downloadscat_arr[$i]->getVar('cat_cid'), 'title' => $downloadscat_arr[$i]->getVar('cat_title'), 'description_main' => $downloadscat_arr[$i]->getVar('cat_description_main'), 'subcategories' => $subcategories, 'totaldownloads' => $totaldownloads, 'count' => $count]);
        $count++;
    }
}

//pour afficher les résumés
//----------------------------------------------------------------------------------------------------------------------------------------------------
//téléchargements récents
if (1 == $xoopsModuleConfig['bldate']) {
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('status', 0, '!='));
    $criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->setSort('date');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $downloads_arr_date = $downloadsHandler->getall($criteria);
    foreach (array_keys($downloads_arr_date) as $i) {
        $title = $downloads_arr_date[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title, 0, ($xoopsModuleConfig['longbl'])) . '...';
        }
        $date = formatTimestamp($downloads_arr_date[$i]->getVar('date'), 's');
        $xoopsTpl->append('bl_date', ['id' => $downloads_arr_date[$i]->getVar('lid'), 'cid' => $downloads_arr_date[$i]->getVar('cid'), 'date' => $date, 'title' => $title]);
    }
}
//plus téléchargés
if (1 == $xoopsModuleConfig['blpop']) {
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('status', 0, '!='));
    $criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->setSort('hits');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $downloads_arr_hits = $downloadsHandler->getall($criteria);
    foreach (array_keys($downloads_arr_hits) as $i) {
        $title = $downloads_arr_hits[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title, 0, ($xoopsModuleConfig['longbl'])) . '...';
        }
        $xoopsTpl->append('bl_pop', ['id' => $downloads_arr_hits[$i]->getVar('lid'), 'cid' => $downloads_arr_hits[$i]->getVar('cid'), 'hits' => $downloads_arr_hits[$i]->getVar('hits'), 'title' => $title]);
    }
}
//mieux notés
if (1 == $xoopsModuleConfig['blrating']) {
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('status', 0, '!='));
    $criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->setSort('rating');
    $criteria->setOrder('DESC');
    $criteria->setLimit($xoopsModuleConfig['nbbl']);
    $downloads_arr_rating = $downloadsHandler->getall($criteria);
    foreach (array_keys($downloads_arr_rating) as $i) {
        $title = $downloads_arr_rating[$i]->getVar('title');
        if (strlen($title) >= $xoopsModuleConfig['longbl']) {
            $title = substr($title, 0, ($xoopsModuleConfig['longbl'])) . '...';
        }
        $rating = number_format($downloads_arr_rating[$i]->getVar('rating'), 1);
        $xoopsTpl->append('bl_rating', ['id' => $downloads_arr_rating[$i]->getVar('lid'), 'cid' => $downloads_arr_rating[$i]->getVar('cid'), 'rating' => $rating, 'title' => $title]);
    }
}
if (0 == $xoopsModuleConfig['bldate'] and 0 == $xoopsModuleConfig['blpop'] and 0 == $xoopsModuleConfig['blrating']) {
    $bl_affichage = 0;
} else {
    $bl_affichage = 1;
}
$xoopsTpl->assign('bl_affichage', $bl_affichage);
$xoopsTpl->assign('show_latest_files', $xoopsModuleConfig['show_latest_files']);

//----------------------------------------------------------------------------------------------------------------------------------------------------

// affichage des téléchargements
if ($xoopsModuleConfig['newdownloads'] > 0) {
    $xoopsTpl->assign('nb_dowcol', $xoopsModuleConfig['nb_dowcol']);
    //Utilisation d'une copie d'écran avec la largeur selon les préférences
    if (1 == $xoopsModuleConfig['useshots']) {
        $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
        $xoopsTpl->assign('show_screenshot', true);
        $xoopsTpl->assign('img_float', $xoopsModuleConfig['img_float']);
    }
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('status', 0, '!='));
    $criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->setLimit($xoopsModuleConfig['newdownloads']);
    $tblsort = [];
    $tblsort[1]='date';
    $tblsort[2]='date';
    $tblsort[3]='hits';
    $tblsort[4]='hits';
    $tblsort[5]='rating';
    $tblsort[6]='rating';
    $tblsort[7]='title';
    $tblsort[8]='title';
    $tblorder = [];
    $tblorder[1]='DESC';
    $tblorder[2]='ASC';
    $tblorder[3]='DESC';
    $tblorder[4]='ASC';
    $tblorder[5]='DESC';
    $tblorder[6]='ASC';
    $tblorder[7]='DESC';
    $tblorder[8]='ASC';
    $sort = isset($xoopsModuleConfig['toporder']) ? $xoopsModuleConfig['toporder'] : 1;
    $order = isset($xoopsModuleConfig['toporder']) ? $xoopsModuleConfig['toporder'] : 1;
    $criteria->setSort($tblsort[$sort]);
    $criteria->setOrder($tblorder[$order]);
    $downloads_arr = $downloadsHandler->getall($criteria);
    $categories = TDMDownloads_MygetItemIds('tdmdownloads_download', 'TDMDownloads');
    $item = TDMDownloads_MygetItemIds('tdmdownloads_download_item', 'TDMDownloads');
    $count = 1;
    foreach (array_keys($downloads_arr) as $i) {
        if ('blank.gif' === $downloads_arr[$i]->getVar('logourl')) {
            $logourl = '';
        } else {
            $logourl = $downloads_arr[$i]->getVar('logourl');
            $logourl = $uploadurl_shots . $logourl;
        }
        $datetime = formatTimestamp($downloads_arr[$i]->getVar('date'), 's');
        $submitter = \XoopsUser::getUnameFromId($downloads_arr[$i]->getVar('submitter'));
        $description = $downloads_arr[$i]->getVar('description');
        //permet d'afficher uniquement la description courte
        if (false == strpos($description, '[pagebreak]')) {
            $description_short = $description;
        } else {
            $description_short = substr($description, 0, strpos($description, '[pagebreak]'));
        }
        // pour les vignettes "new" et "mis à jour"
        $new = TDMDownloads_Thumbnail($downloads_arr[$i]->getVar('date'), $downloads_arr[$i]->getVar('status'));
        $pop = TDMDownloads_Popular($downloads_arr[$i]->getVar('hits'));

        // Défini si la personne est un admin
        if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
            $adminlink = '<a href="' . XOOPS_URL . '/modules/tdmdownloads/admin/downloads.php?op=view_downloads&amp;downloads_lid=' . $downloads_arr[$i]->getVar('lid') . '" title="' . _MD_TDMDOWNLOADS_EDITTHISDL . '"><img src="' . XOOPS_URL . '/modules/tdmdownloads/assets/images/icon/edit.png" width="16px" height="16px" border="0" alt="' . _MD_TDMDOWNLOADS_EDITTHISDL . '"></a>';
        } else {
            $adminlink = '';
        }
        //permission de télécharger
        if (1 == $xoopsModuleConfig['permission_download']) {
            if (!in_array($downloads_arr[$i]->getVar('cid'), $categories)) {
                $perm_download = false;
            } else {
                $perm_download = true;
            }
        } else {
            if (!in_array($downloads_arr[$i]->getVar('lid'), $item)) {
                $perm_download = false;
            } else {
                $perm_download = true;
            }
        }
        $xoopsTpl->append('file', [
            'id'        => $downloads_arr[$i]->getVar('lid'), 'cid' => $downloads_arr[$i]->getVar('cid'), 'title' => $downloads_arr[$i]->getVar('title'), 'new' => $new, 'pop' => $pop, 'logourl' => $logourl, 'updated' => $datetime, 'description_short' => $description_short,
            'adminlink' => $adminlink, 'submitter' => $submitter, 'perm_download' => $perm_download, 'count' => $count
        ]);
        //pour les mots clef
        $keywords .= $downloads_arr[$i]->getVar('title') . ',';
        $count++;
    }
}
// référencement
//description
$xoTheme->addMeta('meta', 'description', strip_tags($xoopsModule->name()));
//keywords
$keywords = substr($keywords, 0, -1);
$xoTheme->addMeta('meta', 'keywords', $keywords);

include XOOPS_ROOT_PATH.'/footer.php';
