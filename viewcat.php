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

require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = Helper::getInstance();
// template d'affichage
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_viewcat.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
/** @var \xos_opal_Theme $xoTheme */
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);
$cid = \Xmf\Request::getInt('cid', 0, 'REQUEST');
// pour les permissions
$categories = $utility->getItemIds('tdmdownloads_view', $moduleDirName);
// redirection si la catégorie n'existe pas
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('cat_cid', $cid));
if (0 === $cid || 0 === $categoryHandler->getCount($criteria)) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_CAT_NONEXISTENT);
}
// pour les permissions (si pas de droit, redirection)
if (!in_array($cid, $categories)) {
    redirect_header('index.php', 2, _NOPERM);
}
//tableau des catégories
$criteria = new \CriteriaCompo();
$criteria->setSort('cat_weight ASC, cat_title');
$criteria->setOrder('ASC');
$criteria->add(new \Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
$downloadscatArray = $categoryHandler->getAll($criteria);
$mytree            = new Tree($downloadscatArray, 'cat_cid', 'cat_pid');
//tableau des téléchargements
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 0, '!='));
$criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
$downloadsArray = $downloadsHandler->getAll($criteria);
$xoopsTpl->assign('lang_thereare', sprintf(_MD_TDMDOWNLOADS_INDEX_THEREARE, count($downloadsArray)));
//navigation
$navCategory = $utility::getPathTreeUrl($mytree, $cid, $downloadscatArray, 'cat_title', $prefix = ' <img src="assets/images/deco/arrow.gif" alt="arrow"> ', true, 'ASC');
$xoopsTpl->assign('category_path', $navCategory);
// info catégorie
$xoopsTpl->assign('category_id', $cid);
$cat_info = $categoryHandler->get($cid);
$xoopsTpl->assign('cat_description', $cat_info->getVar('cat_description_main'));
$uploadurl      = XOOPS_URL . '/uploads/' . $moduleDirName . '/images/cats/';
$categoryObject = $categoryHandler->get($cid);
$tempCategory   = [
    'image'            => $cat_info->getVar('cat_imgurl'),
    'id'               => $cat_info->getVar('cat_cid'),
    'title'            => $cat_info->getVar('cat_title'),
    'description_main' => $cat_info->getVar('cat_description_main'),
];
if (!empty($tempCategory['image'])) {
    $tempCategory['image'] = $uploadurl . $tempCategory['image'];
}
$xoopsTpl->assign('category', $tempCategory);
//affichage des catégories
$xoopsTpl->assign('nb_catcol', $helper->getConfig('nb_catcol'));
$count    = 1;
$keywords = '';
foreach (array_keys($downloadscatArray) as $i) {
    /** @var \XoopsModules\Tdmdownloads\Category[] $downloadscatArray */
    if ($downloadscatArray[$i]->getVar('cat_pid') == $cid) {
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
        $xoopsTpl->append('subcategories', [
            'image'            => $uploadurl . $downloadscatArray[$i]->getVar('cat_imgurl'),
            'id'               => $downloadscatArray[$i]->getVar('cat_cid'),
            'title'            => $downloadscatArray[$i]->getVar('cat_title'),
            'description_main' => $downloadscatArray[$i]->getVar('cat_description_main'),
            'infercategories'  => $subcategories,
            'totaldownloads'   => $totaldownloads,
            'count'            => $count,
        ]);
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
    $criteria->add(new \Criteria('cid', \Xmf\Request::getInt('cid', 0, 'REQUEST')));
    $criteria->setSort('date');
    $criteria->setOrder('DESC');
    $criteria->setLimit($helper->getConfig('nbbl'));
    $downloadsArray = $downloadsHandler->getAll($criteria);
    foreach (array_keys($downloadsArray) as $i) {
        /** @var \XoopsModules\Tdmdownloads\Downloads[] $downloadsArray */
        $title = $downloadsArray[$i]->getVar('title');
        if (mb_strlen($title) >= $helper->getConfig('longbl')) {
            $title = mb_substr($title, 0, $helper->getConfig('longbl')) . '...';
        }
        $date = formatTimestamp($downloadsArray[$i]->getVar('date'), 's');
        $xoopsTpl->append('bl_date', [
            'id'    => $downloadsArray[$i]->getVar('lid'),
            'cid'   => $downloadsArray[$i]->getVar('cid'),
            'date'  => $date,
            'title' => $title,
        ]);
    }
}
//plus téléchargés
if (1 == $helper->getConfig('blpop')) {
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('status', 0, '!='));
    $criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->add(new \Criteria('cid', \Xmf\Request::getInt('cid', 0, 'REQUEST')));
    $criteria->setSort('hits');
    $criteria->setOrder('DESC');
    $criteria->setLimit($helper->getConfig('nbbl'));
    $downloadsArray = $downloadsHandler->getAll($criteria);
    foreach (array_keys($downloadsArray) as $i) {
        $title = $downloadsArray[$i]->getVar('title');
        if (mb_strlen($title) >= $helper->getConfig('longbl')) {
            $title = mb_substr($title, 0, $helper->getConfig('longbl')) . '...';
        }
        $xoopsTpl->append('bl_pop', [
            'id'    => $downloadsArray[$i]->getVar('lid'),
            'cid'   => $downloadsArray[$i]->getVar('cid'),
            'hits'  => $downloadsArray[$i]->getVar('hits'),
            'title' => $title,
        ]);
    }
}
//mieux notés
if (1 == $helper->getConfig('blrating')) {
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('status', 0, '!='));
    $criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    $criteria->add(new \Criteria('cid', \Xmf\Request::getInt('cid', 0, 'REQUEST')));
    $criteria->setSort('rating');
    $criteria->setOrder('DESC');
    $criteria->setLimit($helper->getConfig('nbbl'));
    $downloadsArray = $downloadsHandler->getAll($criteria);
    foreach (array_keys($downloadsArray) as $i) {
        $title = $downloadsArray[$i]->getVar('title');
        if (mb_strlen($title) >= $helper->getConfig('longbl')) {
            $title = mb_substr($title, 0, $helper->getConfig('longbl')) . '...';
        }
        $rating = number_format((float)$downloadsArray[$i]->getVar('rating'), 1);
        $xoopsTpl->append('bl_rating', [
            'id'     => $downloadsArray[$i]->getVar('lid'),
            'cid'    => $downloadsArray[$i]->getVar('cid'),
            'rating' => $rating,
            'title'  => $title,
        ]);
    }
}
// affichage du résumé
$bl_affichage = 1;
if (0 === $helper->getConfig('bldate') && 0 === $helper->getConfig('blpop') && 0 === $helper->getConfig('blrating')) {
    $bl_affichage = 0;
}
//----------------------------------------------------------------------------------------------------------------------------------------------------
// affichage des téléchargements
if ($helper->getConfig('perpage') > 0) {
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
    $criteria->add(new \Criteria('cid', \Xmf\Request::getInt('cid', 0, 'REQUEST')));
    $numrows = $downloadsHandler->getCount($criteria);
    $xoopsTpl->assign('lang_thereare', sprintf(_MD_TDMDOWNLOADS_CAT_THEREARE, $numrows));
    // Pour un affichage sur plusieurs pages
    if (\Xmf\Request::hasVar('limit', 'REQUEST')) {
        $criteria->setLimit(\Xmf\Request::getInt('limit', 0, 'REQUEST'));
        $limit = \Xmf\Request::getInt('limit', 0, 'REQUEST');
    } else {
        $criteria->setLimit($helper->getConfig('perpage'));
        $limit = $helper->getConfig('perpage');
    }
    if (\Xmf\Request::hasVar('start', 'REQUEST')) {
        $criteria->setStart(\Xmf\Request::getInt('start', 0, 'REQUEST'));
        $start = \Xmf\Request::getInt('start', 0, 'REQUEST');
    } else {
        $criteria->setStart(0);
        $start = 0;
    }
    if (\Xmf\Request::hasVar('sort', 'REQUEST')) {
        $criteria->setSort(\Xmf\Request::getString('sort', '', 'REQUEST'));
        $sort = \Xmf\Request::getString('sort', '', 'REQUEST');
    } else {
        $criteria->setSort('date');
        $sort = 'date';
    }
    if (\Xmf\Request::hasVar('order', 'REQUEST')) {
        $criteria->setOrder(\Xmf\Request::getString('order', '', 'REQUEST'));
        $order = \Xmf\Request::getString('order', '', 'REQUEST');
    } else {
        $criteria->setOrder('DESC');
        $order = 'DESC';
    }
    $downloadsArray = $downloadsHandler->getAll($criteria);
    if ($numrows > $limit) {
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', 'limit=' . $limit . '&cid=' . \Xmf\Request::getInt('cid', 0, 'REQUEST') . '&sort=' . $sort . '&order=' . $order);
        $pagenav = $pagenav->renderNav(4);
    } else {
        $pagenav = '';
    }
    $xoopsTpl->assign('pagenav', $pagenav);
    $summary    = '';
    $cpt        = 0;
    $categories = $utility->getItemIds('tdmdownloads_download', $moduleDirName);
    $item       = $utility->getItemIds('tdmdownloads_download_item', $moduleDirName);
    foreach (array_keys($downloadsArray) as $i) {
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
        // utilisation du sommaire
        ++$cpt;
        $summary = $cpt . '- <a href="#l' . $cpt . '">' . $downloadsArray[$i]->getVar('title') . '</a><br>';
        $xoopsTpl->append('summary', ['title' => $summary, 'count' => $cpt]);
        $xoopsTpl->append('file', [
            'id'                => $downloadsArray[$i]->getVar('lid'),
            'cid'               => $downloadsArray[$i]->getVar('cid'),
            'title'             => $downloadsArray[$i]->getVar('title'),
            'rating'            => number_format((float)$downloadsArray[$i]->getVar('rating'), 1),
            'hits'              => $downloadsArray[$i]->getVar('hits'),
            'new'               => $new,
            'pop'               => $pop,
            'logourl'           => $logourl,
            'updated'           => $datetime,
            'description_short' => $descriptionShort,
            'adminlink'         => $adminlink,
            'submitter'         => $submitter,
            'perm_download'     => $downloadPermission,
            'count'             => $cpt,
        ]);
        //pour les mots clef
        $keywords .= $downloadsArray[$i]->getVar('title') . ',';
    }
    if (0 == $numrows) {
        $bl_affichage = 0;
    }
    $xoopsTpl->assign('bl_affichage', $bl_affichage);
    // affichage du sommaire
    if ($helper->getConfig('autosummary')) {
        if (0 == $numrows) {
            $xoopsTpl->assign('aff_summary', false);
        } else {
            $xoopsTpl->assign('aff_summary', true);
        }
    } else {
        $xoopsTpl->assign('aff_summary', false);
    }
    // sort menu display
    if ($numrows > 1) {
        $xoopsTpl->assign('navigation', true);
        $sortorder = $sort . $order;
        if ('hitsASC' === $sortorder) {
            $displaySort = _MD_TDMDOWNLOADS_CAT_POPULARITYLTOM;
        }
        if ('hitsDESC' === $sortorder) {
            $displaySort = _MD_TDMDOWNLOADS_CAT_POPULARITYMTOL;
        }
        if ('titleASC' === $sortorder) {
            $displaySort = _MD_TDMDOWNLOADS_CAT_TITLEATOZ;
        }
        if ('titleDESC' === $sortorder) {
            $displaySort = _MD_TDMDOWNLOADS_CAT_TITLEZTOA;
        }
        if ('dateASC' === $sortorder) {
            $displaySort = _MD_TDMDOWNLOADS_CAT_DATEOLD;
        }
        if ('dateDESC' === $sortorder) {
            $displaySort = _MD_TDMDOWNLOADS_CAT_DATENEW;
        }
        if ('ratingASC' === $sortorder) {
            $displaySort = _MD_TDMDOWNLOADS_CAT_RATINGLTOH;
        }
        if ('ratingDESC' === $sortorder) {
            $displaySort = _MD_TDMDOWNLOADS_CAT_RATINGHTOL;
        }
        $xoopsTpl->assign('affichage_tri', sprintf(_MD_TDMDOWNLOADS_CAT_CURSORTBY, $displaySort));
    }
}
// référencement
// titre de la page
$pagetitle = $utility::getPathTreeUrl($mytree, $cid, $downloadscatArray, 'cat_title', $prefix = ' - ', false, 'DESC');
$xoopsTpl->assign('xoops_pagetitle', $pagetitle);
//description
$xoTheme->addMeta('meta', 'description', strip_tags($downloadscatArray[$cid]->getVar('cat_description_main')));
//keywords
$keywords = mb_substr($keywords, 0, -1);
$xoTheme->addMeta('meta', 'keywords', $keywords);
$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);
require XOOPS_ROOT_PATH . '/footer.php';
