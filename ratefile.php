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
$moduleDirName = basename(__DIR__);

// template d'affichage
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_ratefile.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
/** @var \xos_opal_Theme $xoTheme */
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);

//On recupere la valeur de l'argument op dans l'URL$
$op = \Xmf\Request::getString( 'op', 'list');
$lid = \Xmf\Request::getInt('lid', 0,  'REQUEST');

//redirection si pas de permission de vote
if (false === $perm_vote) {
    redirect_header('index.php', 2, _NOPERM);
}

$viewDownloads = $downloadsHandler->get($lid);
// redirection si le téléchargement n'existe pas ou n'est pas activé
if (0 === count($viewDownloads) || 0 == $viewDownloads->getVar('status')) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
}

//redirection si pas de permission (cat)
$categories = $utility->getItemIds('tdmdownloads_view', $moduleDirName);
if (!in_array($viewDownloads->getVar('cid'), $categories, true)) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue list
    case 'list':
        //tableau des catégories
        $criteria = new \CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $criteria->add(new \Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
        $downloadscatArray = $categoryHandler->getAll($criteria);
        $mytree = new \XoopsModules\Tdmdownloads\Tree($downloadscatArray, 'cat_cid', 'cat_pid');
        //navigation
        $navigation = $utility->getPathTreeUrl($mytree, $viewDownloads->getVar('cid'), $downloadscatArray, 'cat_title', $prefix = ' <img src="assets/images/deco/arrow.gif" alt="arrow"> ', true, 'ASC', true);
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow"> <a title="' . $viewDownloads->getVar('title') . '" href="singlefile.php?lid=' . $viewDownloads->getVar('lid') . '">' . $viewDownloads->getVar('title') . '</a>';
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow"> ' . _MD_TDMDOWNLOADS_SINGLEFILE_RATHFILE;
        $xoopsTpl->assign('navigation', $navigation);
        // référencement
        // titre de la page
        $pagetitle = _MD_TDMDOWNLOADS_SINGLEFILE_RATHFILE . ' - ' . $viewDownloads->getVar('title') . ' - ';
        $pagetitle .= $utility->getPathTreeUrl($mytree, $viewDownloads->getVar('cid'), $downloadscatArray, 'cat_title', $prefix = ' - ', false, 'DESC', true);
        $xoopsTpl->assign('xoops_pagetitle', $pagetitle);
        //description
        $xoTheme->addMeta('meta', 'description', strip_tags(_MD_TDMDOWNLOADS_SINGLEFILE_RATHFILE . ' (' . $viewDownloads->getVar('title') . ')'));
        //Affichage du formulaire de notation des téléchargements
        $obj = $ratingHandler->create();
        $form = $obj->getForm($lid);
        $xoopsTpl->assign('themeForm', $form->render());
        break;
    // save
    case 'save':
        $obj = $ratingHandler->create();
        if (empty($xoopsUser)) {
            $ratinguser = 0;
        } else {
            $ratinguser = $xoopsUser->getVar('uid');
        }
        // si c'est un membre on vérifie qu'il ne vote pas pour son fichier
        if (0 !== $ratinguser) {
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('lid', $lid));
            $downloads_arr = $downloadsHandler->getAll($criteria);
            foreach (array_keys($downloads_arr) as $i) {
                if ($downloads_arr[$i]->getVar('submitter') == $ratinguser) {
                    redirect_header('singlefile.php?lid=' . \Xmf\Request::getInt('lid', 0), 2, _MD_TDMDOWNLOADS_RATEFILE_CANTVOTEOWN);
                }
            }
            // si c'est un membre on vérifie qu'il ne vote pas 2 fois
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('lid', $lid));
            $downloadsvotes_arr = $ratingHandler->getAll($criteria);
            foreach (array_keys($downloadsvotes_arr) as $i) {
                if ($downloadsvotes_arr[$i]->getVar('ratinguser') === $ratinguser) {
                    redirect_header('singlefile.php?lid=' . \Xmf\Request::getInt('lid', 0), 2, _MD_TDMDOWNLOADS_RATEFILE_VOTEONCE);
                }
            }
        } else {
            // si c'est un utilisateur anonyme on vérifie qu'il ne vote pas 2 fois par jour
            $yesterday = (time() - 86400);
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('lid', $lid));
            $criteria->add(new \Criteria('ratinguser', 0));
            $criteria->add(new \Criteria('ratinghostname', getenv('REMOTE_ADDR')));
            $criteria->add(new \Criteria('ratingtimestamp', $yesterday, '>'));
            if ($ratingHandler->getCount($criteria) >= 1) {
                redirect_header('singlefile.php?lid=' . \Xmf\Request::getInt('lid', 0), 2, _MD_TDMDOWNLOADS_RATEFILE_VOTEONCE);
            }
        }
        $erreur = false;
        $message_erreur = '';
        // Test avant la validation
        $rating = \Xmf\Request::getInt('rating', 0, 'POST');
        if ($rating < 0 || $rating > 10) {
            $message_erreur .= _MD_TDMDOWNLOADS_RATEFILE_NORATING . '<br>';
            $erreur = true;
        }
        xoops_load('captcha');
        $xoopsCaptcha = \XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            $message_erreur .= $xoopsCaptcha->getMessage() . '<br>';
            $erreur = true;
        }
        $obj->setVar('lid', $lid);
        $obj->setVar('ratinguser', $ratinguser);
        $obj->setVar('rating', $rating);
        $obj->setVar('ratinghostname', getenv('REMOTE_ADDR'));
        $obj->setVar('ratingtimestamp', time());
        if (true === $erreur) {
            $xoopsTpl->assign('message_erreur', $message_erreur);
        } else {
            if ($ratingHandler->insert($obj)) {
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('lid', $lid));
                $downloadsvotes_arr = $ratingHandler->getAll($criteria);
                $total_vote = $ratingHandler->getCount($criteria);
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
require XOOPS_ROOT_PATH . '/footer.php';
