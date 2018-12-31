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
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_ratefile.tpl';
require_once XOOPS_ROOT_PATH.'/header.php';
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/css/styles.css', null);
//On recupere la valeur de l'argument op dans l'URL$
$op = TDMDownloads_CleanVars($_REQUEST, 'op', 'liste', 'string');
$lid = TDMDownloads_CleanVars($_REQUEST, 'lid', 0, 'int');

//redirection si pas de permission de vote
if ($perm_vote === false) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}

$view_downloads = $downloadsHandler->get($lid);
// redirection si le téléchargement n'existe pas ou n'est pas activé
if (count($view_downloads) == 0 || $view_downloads->getVar('status') == 0) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
    exit();
}

//redirection si pas de permission (cat)
$categories = TDMDownloads_MygetItemIds('tdmdownloads_view', 'TDMDownloads');
if (!in_array($view_downloads->getVar('cid'), $categories)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
    exit();
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case "liste":
        //tableau des catégories
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
        $downloadscat_arr = $downloadscatHandler->getall($criteria);
        $mytree = new XoopsObjectTree($downloadscat_arr, 'cat_cid', 'cat_pid');
        //navigation
        $navigation = TDMDownloads_PathTreeUrl($mytree, $view_downloads->getVar('cid'), $downloadscat_arr, 'cat_title', $prefix = ' <img src="images/deco/arrow.gif" alt="arrow"> ', true, 'ASC', true);
        $navigation .= ' <img src="images/deco/arrow.gif" alt="arrow"> <a title="' . $view_downloads->getVar('title') . '" href="singlefile.php?lid=' . $view_downloads->getVar('lid') . '">' . $view_downloads->getVar('title') . '</a>';
        $navigation .= ' <img src="images/deco/arrow.gif" alt="arrow"> ' . _MD_TDMDOWNLOADS_SINGLEFILE_RATHFILE;
        $xoopsTpl->assign('navigation', $navigation);
        // référencement
        // titre de la page
        $pagetitle = _MD_TDMDOWNLOADS_SINGLEFILE_RATHFILE . ' - ' . $view_downloads->getVar('title') . ' - ';
        $pagetitle .= TDMDownloads_PathTreeUrl($mytree, $view_downloads->getVar('cid'), $downloadscat_arr, 'cat_title', $prefix = ' - ', false, 'DESC', true);
        $xoopsTpl->assign('xoops_pagetitle', $pagetitle);
        //description
        $xoTheme->addMeta('meta', 'description', strip_tags(_MD_TDMDOWNLOADS_SINGLEFILE_RATHFILE . ' (' . $view_downloads->getVar('title') . ')'));
        //Affichage du formulaire de notation des téléchargements
        $obj = $downloadsvotedataHandler->create();
        $form = $obj->getForm($lid);
        $xoopsTpl->assign('themeForm', $form->render());
    break;

    // save
    case "save":
        $obj = $downloadsvotedataHandler->create();
        if (empty($xoopsUser)) {
            $ratinguser = 0;
        } else {
            $ratinguser = $xoopsUser->getVar('uid');
        }
        // si c'est un membre on vérifie qu'il ne vote pas pour son fichier
        if ($ratinguser != 0) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $lid));
            $downloads_arr = $downloadsHandler->getall($criteria);
            foreach (array_keys($downloads_arr) as $i) {
                if ($downloads_arr[$i]->getVar('submitter') == $ratinguser) {
                    redirect_header('singlefile.php?lid=' . intval($_REQUEST['lid']), 2, _MD_TDMDOWNLOADS_RATEFILE_CANTVOTEOWN);
                    exit();
                }
            }
            // si c'est un membre on vérifie qu'il ne vote pas 2 fois
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $lid));
            $downloadsvotes_arr = $downloadsvotedataHandler->getall($criteria);
            foreach (array_keys($downloadsvotes_arr) as $i) {
                if ($downloadsvotes_arr[$i]->getVar('ratinguser') == $ratinguser) {
                    redirect_header('singlefile.php?lid=' . intval($_REQUEST['lid']), 2, _MD_TDMDOWNLOADS_RATEFILE_VOTEONCE);
                    exit();
                }
            }
        } else {
            // si c'est un utilisateur anonyme on vérifie qu'il ne vote pas 2 fois par jour
            $yesterday = (time()-86400);
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $lid));
            $criteria->add(new Criteria('ratinguser', 0));
            $criteria->add(new Criteria('ratinghostname', getenv("REMOTE_ADDR")));
            $criteria->add(new Criteria('ratingtimestamp', $yesterday, '>'));
            if ($downloadsvotedataHandler->getCount($criteria) >= 1) {
                redirect_header('singlefile.php?lid=' . intval($_REQUEST['lid']), 2, _MD_TDMDOWNLOADS_RATEFILE_VOTEONCE);
                exit();
            }
        }
        $erreur = false;
        $message_erreur = '';
        // Test avant la validation
        $rating = intval($_POST['rating']);
        if ($rating < 0 || $rating > 10) {
            $message_erreur.= _MD_TDMDOWNLOADS_RATEFILE_NORATING . '<br>';
            $erreur=true;
        }
        xoops_load("captcha");
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            $message_erreur.=$xoopsCaptcha->getMessage() . '<br>';
            $erreur=true;
        }
        $obj->setVar('lid', $lid);
        $obj->setVar('ratinguser', $ratinguser);
        $obj->setVar('rating', $rating);
        $obj->setVar('ratinghostname', getenv("REMOTE_ADDR"));
        $obj->setVar('ratingtimestamp', time());
        if ($erreur==true) {
            $xoopsTpl->assign('message_erreur', $message_erreur);
        } else {
            if ($downloadsvotedataHandler->insert($obj)) {
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $lid));
                $downloadsvotes_arr = $downloadsvotedataHandler->getall($criteria);
                $total_vote = $downloadsvotedataHandler->getCount($criteria);
                $total_rating = 0;
                foreach (array_keys($downloadsvotes_arr) as $i) {
                    $total_rating += $downloadsvotes_arr[$i]->getVar('rating');
                }
                $rating = $total_rating / $total_vote;
                $objdownloads = $downloadsHandler->get($lid);
                $objdownloads->setVar('rating', number_format($rating, 1));
                $objdownloads->setVar('votes', $total_vote);
                if ($downloadsHandler->insert($objdownloads)) {
                    redirect_header('singlefile.php?lid=' . $lid, 2, _MD_TDMDOWNLOADS_RATEFILE_VOTEOK);
                }
                echo $objdownloads->getHtmlErrors();
            }
            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des téléchargements
        $form = $obj->getForm($lid);
        $xoopsTpl->assign('themeForm', $form->render());

    break;
}
include XOOPS_ROOT_PATH.'/footer.php';
