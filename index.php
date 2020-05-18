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
require_once __DIR__ . '/header.php';

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

// template d'affichage
$moduleDirName                           = basename(__DIR__);
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
/** @var \xos_opal_Theme $xoTheme */
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);
// pour les permissions
$categories = $utility->getItemIds('tdmdownloads_view', $moduleDirName);

//tableau des téléchargements
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 0, '!='));
$criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
$downloadsArray = $downloadsHandler->getAll($criteria);
$xoopsTpl->assign('lang_thereare', sprintf(_MD_TDMDOWNLOADS_INDEX_THEREARE, count($downloadsArray)));

//tableau des catégories
$criteria = new \CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new \Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
$downloadscatArray = $categoryHandler->getAll($criteria);
$mytree            = new \XoopsModules\Tdmdownloads\Tree($downloadscatArray, 'cat_cid', 'cat_pid');

//affichage des catégories
$xoopsTpl->assign('nb_catcol', $helper->getConfig('nb_catcol'));
$count    = 1;
$keywords = '';
foreach (array_keys($downloadscatArray) as $i) {
    /** @var \XoopsModules\Tdmdownloads\Category[] $downloadscatArray */
    if (0 === $downloadscatArray[$i]->getVar('cat_pid')) {
        $totaldownloads    = $utility->getNumbersOfEntries($mytree, $categories, $downloadsArray, $downloadscatArray[$i]->getVar('cat_cid'));
        $subcategories_arr = $mytree->getFirstChild($downloadscatArray[$i]->getVar('cat_cid'));
        $chcount           = 0;
        $subcategories     = '';
        //pour les mots clef
        $keywords .= $downloadscatArray[$i]->getVar('cat_title') . ',';
        foreach (array_keys($subcategories_arr) as $j) {
            /** @var \XoopsModules\Tdmdownloads\Category[] $subcategories_arr */
            if ($chcount >= $helper->getConfig('nbsouscat')) {
                $subcategories .= '<li>[<a href="' . XOOPS_URL . '/modules/' . $moduleDirName . '/viewcat.php?cid=' . $downloadscatArray[$i]->getVar('cat_cid') . '">+</a>]</li>';
                break;
            }
            $subcategories .= '<li><a href="' . XOOPS_URL . '/modules/' . $moduleDirName . '/viewcat.php?cid=' . $subcategories_arr[$j]->getVar('cat_cid') . '">' . $subcategories_arr[$j]->getVar('cat_title') . '</a></li>';
            $keywords      .= $downloadscatArray[$i]->getVar('cat_title') . ',';
            ++$chcount;
        }
        $xoopsTpl->append(
            'categories',
            [
                'image'            => $uploadurl . $downloadscatArray[$i]->getVar('cat_imgurl'),
                'id'               => $downloadscatArray[$i]->getVar('cat_cid'),
                'title'            => $downloadscatArray[$i]->getVar('cat_title'),
                'description_main' => $downloadscatArray[$i]->getVar('cat_description_main'),
                'subcategories'    => $subcategories,
                'countsubs'        => count($subcategories_arr),
                'totaldownloads'   => $totaldownloads,
                'count'            => $count,
            ]
        );
        ++$count;
    }
}

//pour afficher les résumés
//----------------------------------------------------------------------------------------------------------------------------------------------------
//téléchargements récents
if (1 == $helper->getConfig('bldate')) {
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('status', 0, '!='));
    $criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->setSort('date');
    $criteria->setOrder('DESC');
    $criteria->setLimit($helper->getConfig('nbbl'));
    $downloads_arr_date = $downloadsHandler->getAll($criteria);
    foreach (array_keys($downloads_arr_date) as $i) {
        /** @var \XoopsModules\Tdmdownloads\Downloads[] $downloads_arr_date */
        $title = $downloads_arr_date[$i]->getVar('title');
        if (mb_strlen($title) >= $helper->getConfig('longbl')) {
            $title = mb_substr($title, 0, $helper->getConfig('longbl')) . '...';
        }
        $date = formatTimestamp($downloads_arr_date[$i]->getVar('date'), 's');
        $xoopsTpl->append(
            'bl_date',
            [
                'id'    => $downloads_arr_date[$i]->getVar('lid'),
                'cid'   => $downloads_arr_date[$i]->getVar('cid'),
                'date'  => $date,
                'title' => $title,
            ]
        );
    }
}
//plus téléchargés
if (1 == $helper->getConfig('blpop')) {
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('status', 0, '!='));
    $criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->setSort('hits');
    $criteria->setOrder('DESC');
    $criteria->setLimit($helper->getConfig('nbbl'));
    $downloads_arr_hits = $downloadsHandler->getAll($criteria);
    foreach (array_keys($downloads_arr_hits) as $i) {
        /** @var \XoopsModules\Tdmdownloads\Downloads[] $downloads_arr_hits */
        $title = $downloads_arr_hits[$i]->getVar('title');
        if (mb_strlen($title) >= $helper->getConfig('longbl')) {
            $title = mb_substr($title, 0, $helper->getConfig('longbl')) . '...';
        }
        $xoopsTpl->append(
            'bl_pop',
            [
                'id'    => $downloads_arr_hits[$i]->getVar('lid'),
                'cid'   => $downloads_arr_hits[$i]->getVar('cid'),
                'hits'  => $downloads_arr_hits[$i]->getVar('hits'),
                'title' => $title,
            ]
        );
    }
}
//mieux notés
if (1 == $helper->getConfig('blrating')) {
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('status', 0, '!='));
    $criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->setSort('rating');
    $criteria->setOrder('DESC');
    $criteria->setLimit($helper->getConfig('nbbl'));
    $downloads_arr_rating = $downloadsHandler->getAll($criteria);
    foreach (array_keys($downloads_arr_rating) as $i) {
        /** @var \XoopsModules\Tdmdownloads\Downloads[] $downloads_arr_rating */
        $title = $downloads_arr_rating[$i]->getVar('title');
        if (mb_strlen($title) >= $helper->getConfig('longbl')) {
            $title = mb_substr($title, 0, $helper->getConfig('longbl')) . '...';
        }
        $rating = number_format($downloads_arr_rating[$i]->getVar('rating'), 1);
        $xoopsTpl->append(
            'bl_rating',
            [
                'id'     => $downloads_arr_rating[$i]->getVar('lid'),
                'cid'    => $downloads_arr_rating[$i]->getVar('cid'),
                'rating' => $rating,
                'title'  => $title,
            ]
        );
    }
}
$bl_affichage = 1;
if (0 == $helper->getConfig('bldate') && 0 == $helper->getConfig('blpop') && 0 == $helper->getConfig('blrating')) {
    $bl_affichage = 0;
}
$xoopsTpl->assign('bl_affichage', $bl_affichage);
$xoopsTpl->assign('show_latest_files', $helper->getConfig('show_latest_files'));

//----------------------------------------------------------------------------------------------------------------------------------------------------

// affichage des téléchargements
if ($helper->getConfig('newdownloads') > 0) {
    $xoopsTpl->assign('nb_dowcol', $helper->getConfig('nb_dowcol'));
    //Utilisation d'une copie d'écran avec la largeur selon les préférences
    if (1 == $helper->getConfig('useshots')) {
        $xoopsTpl->assign('shotwidth', $helper->getConfig('shotwidth'));
        $xoopsTpl->assign('show_screenshot', true);
        $xoopsTpl->assign('img_float', $helper->getConfig('img_float'));
    }
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('status', 0, '!='));
    $criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->setLimit($helper->getConfig('newdownloads'));
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
    $sort        = null !== $helper->getConfig('toporder') ? $helper->getConfig('toporder') : 1;
    $order       = null !== $helper->getConfig('toporder') ? $helper->getConfig('toporder') : 1;
    $criteria->setSort($tblsort[$sort]);
    $criteria->setOrder($tblorder[$order]);
    $downloadsArray = $downloadsHandler->getAll($criteria);
    $categories     = $utility->getItemIds('tdmdownloads_download', $moduleDirName);
    $item           = $utility->getItemIds('tdmdownloads_download_item', $moduleDirName);
    $count          = 1;
    foreach (array_keys($downloadsArray) as $i) {
        /** @var \XoopsModules\Tdmdownloads\Downloads[] $downloadsArray */
        if ('blank.gif' === $downloadsArray[$i]->getVar('logourl')) {
            $logourl = '';
        } else {
            $logourl = $downloadsArray[$i]->getVar('logourl');
            $logourl = $uploadurl_shots . $logourl;
        }
        $datetime    = formatTimestamp($downloadsArray[$i]->getVar('date'), 's');
        $submitter   = \XoopsUser::getUnameFromId($downloadsArray[$i]->getVar('submitter'));
        $description = $downloadsArray[$i]->getVar('description');
        //permet d'afficher uniquement la description courte
        if (false === mb_strpos($description, '[pagebreak]')) {
            $descriptionShort = $description;
        } else {
            $descriptionShort = mb_substr($description, 0, mb_strpos($description, '[pagebreak]'));
        }
        // pour les vignettes "new" et "mis à jour"
        $new = $utility->getStatusImage($downloadsArray[$i]->getVar('date'), $downloadsArray[$i]->getVar('status'));
        $pop = $utility->getPopularImage($downloadsArray[$i]->getVar('hits'));

        // Défini si la personne est un admin
        $adminlink = '';
        if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
            $adminlink = '<a href="'
                         . XOOPS_URL
                         . '/modules/'
                         . $moduleDirName
                         . '/admin/downloads.php?op=view_downloads&amp;downloads_lid='
                         . $downloadsArray[$i]->getVar('lid')
                         . '" title="'
                         . _MD_TDMDOWNLOADS_EDITTHISDL
                         . '"><img src="'
                         . XOOPS_URL
                         . '/modules/'
                         . $moduleDirName
                         . '/assets/images/icons/16/edit.png" width="16px" height="16px" border="0" alt="'
                         . _MD_TDMDOWNLOADS_EDITTHISDL
                         . '"></a>';
        }
        //permission de télécharger

        $downloadPermission = true;
        if (1 === $helper->getConfig('permission_download')) {
            if (!in_array($downloadsArray[$i]->getVar('cid'), $categories)) {
                $downloadPermission = false;
            }
        } else {
            if (!in_array($downloadsArray[$i]->getVar('lid'), $item)) {
                $downloadPermission = false;
            }
        }
        $xoopsTpl->append(
            'file',
            [
                'id'                => $downloadsArray[$i]->getVar('lid'),
                'cid'               => $downloadsArray[$i]->getVar('cid'),
                'title'             => $downloadsArray[$i]->getVar('title'),
                'rating'            => $downloadsArray[$i]->getVar('rating'),
                'hits'              => $downloadsArray[$i]->getVar('hits'),
                'new'               => $new,
                'pop'               => $pop,
                'logourl'           => $logourl,
                'updated'           => $datetime,
                'description_short' => $descriptionShort,
                'adminlink'         => $adminlink,
                'submitter'         => $submitter,
                'perm_download'     => $downloadPermission,
                'count'             => $count,
            ]
        );
        //pour les mots clef
        $keywords .= $downloadsArray[$i]->getVar('title') . ',';
        ++$count;
    }
}
// référencement
//description
$xoTheme->addMeta('meta', 'description', strip_tags($xoopsModule->name()));
//keywords
$keywords = mb_substr($keywords, 0, -1);
$xoTheme->addMeta('meta', 'keywords', $keywords);

require XOOPS_ROOT_PATH . '/footer.php';
