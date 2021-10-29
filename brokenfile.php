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

use XoopsModules\Tdmdownloads\Tree;

require_once __DIR__ . '/header.php';
// template d'affichage
$moduleDirName                           = basename(__DIR__);
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_brokenfile.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
/** @var \xos_opal_Theme $xoTheme */
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);
//On recupere la valeur de l'argument op dans l'URL$
$op  = \Xmf\Request::getString('op', 'list');
$lid = \Xmf\Request::getInt('lid', 0, 'REQUEST');
//redirection si pas de permission de vote
if (false === $perm_vote) {
    redirect_header('index.php', 2, _NOPERM);
}
$viewDownloads = $downloadsHandler->get($lid);
// redirection si le téléchargement n'existe pas ou n'est pas activ�
if (empty($viewDownloads) || 0 == $viewDownloads->getVar('status')) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
}
//redirection si pas de permission (cat)
$categories = $utility->getItemIds('tdmdownloads_view', $moduleDirName);
if (!in_array($viewDownloads->getVar('cid'), $categories)) {
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
        $mytree            = new Tree($downloadscatArray, 'cat_cid', 'cat_pid');
        //navigation
        $navigation = $utility::getPathTreeUrl($mytree, $viewDownloads->getVar('cid'), $downloadscatArray, 'cat_title', $prefix = ' <img src="assets/images/deco/arrow.gif" alt="arrow"> ', true, 'ASC', true);
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow"> <a title="' . $viewDownloads->getVar('title') . '" href="singlefile.php?lid=' . $viewDownloads->getVar('lid') . '">' . $viewDownloads->getVar('title') . '</a>';
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow"> ' . _MD_TDMDOWNLOADS_SINGLEFILE_REPORTBROKEN;
        $xoopsTpl->assign('navigation', $navigation);
        // référencement
        // titre de la page
        $pagetitle = _MD_TDMDOWNLOADS_SINGLEFILE_REPORTBROKEN . ' - ' . $viewDownloads->getVar('title') . ' - ';
        $pagetitle .= $utility::getPathTreeUrl($mytree, $viewDownloads->getVar('cid'), $downloadscatArray, 'cat_title', $prefix = ' - ', false, 'DESC', true);
        $xoopsTpl->assign('xoops_pagetitle', $pagetitle);
        //description
        $xoTheme->addMeta('meta', 'description', strip_tags(_MD_TDMDOWNLOADS_SINGLEFILE_REPORTBROKEN . ' (' . $viewDownloads->getVar('title') . ')'));
        //Affichage du formulaire de fichier brisé*/
        /** @var \XoopsModules\Tdmdownloads\Broken $obj */
        $obj = $brokenHandler->create();
        /** @var \XoopsThemeForm $form */
        $form = $obj->getForm($lid);
        $xoopsTpl->assign('themeForm', $form->render());
        break;
    // save
    case 'save':
        /** @var \XoopsModules\Tdmdownloads\Broken $obj */
        $obj = $brokenHandler->create();
        if (empty($xoopsUser)) {
            $ratinguser = 0;
        } else {
            $ratinguser = $xoopsUser->getVar('uid');
        }
        if (0 !== $ratinguser) {
            // si c'est un membre on vérifie qu'il n'envoie pas 2 fois un rapport
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('lid', $lid));
            $brokenArray = $brokenHandler->getAll($criteria);
            foreach (array_keys($brokenArray) as $i) {
                /** @var \XoopsModules\Tdmdownloads\Broken[] $brokenArray */
                if ($brokenArray[$i]->getVar('sender') == $ratinguser) {
                    redirect_header('singlefile.php?lid=' . $lid, 2, _MD_TDMDOWNLOADS_BROKENFILE_ALREADYREPORTED);
                }
            }
        } else {
            // si c'est un utilisateur anonyme on vérifie qu'il n'envoie pas 2 fois un rapport
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('lid', $lid));
            $criteria->add(new \Criteria('sender', 0));
            $criteria->add(new \Criteria('ip', getenv('REMOTE_ADDR')));
            if ($brokenHandler->getCount($criteria) >= 1) {
                redirect_header('singlefile.php?lid=' . $lid, 2, _MD_TDMDOWNLOADS_BROKENFILE_ALREADYREPORTED);
            }
        }
        $erreur       = false;
        $errorMessage = '';
        // Test avant la validation
        xoops_load('captcha');
        $xoopsCaptcha = \XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            $errorMessage .= $xoopsCaptcha->getMessage() . '<br>';
            $erreur       = true;
        }
        $obj->setVar('lid', $lid);
        $obj->setVar('sender', $ratinguser);
        $obj->setVar('ip', getenv('REMOTE_ADDR'));
        if (true === $erreur) {
            $xoopsTpl->assign('message_erreur', $errorMessage);
        } else {
            if ($brokenHandler->insert($obj)) {
                $tags                      = [];
                $tags['BROKENREPORTS_URL'] = XOOPS_URL . '/modules/' . $moduleDirName . '/admin/broken.php';
                /** @var \XoopsNotificationHandler $notificationHandler */
                $notificationHandler = xoops_getHandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'file_broken', $tags);
                redirect_header('singlefile.php?lid=' . $lid, 2, _MD_TDMDOWNLOADS_BROKENFILE_THANKSFORINFO);
            }
            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des téléchargements
        $form = $obj->getForm($lid);
        $xoopsTpl->assign('themeForm', $form->render());
        break;
}
require XOOPS_ROOT_PATH . '/footer.php';
