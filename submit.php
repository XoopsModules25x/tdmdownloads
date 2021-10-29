<?php

use XoopsModules\Tag\{
    Helper as TagHelper,
    Tag,
    TagHandler
};
use XoopsModules\Tdmdownloads\{
    Helper
};

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
$moduleDirName = basename(__DIR__);
$helper = Helper::getInstance();
// template d'affichage
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_submit.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
/** @var \xos_opal_Theme $xoTheme */
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);
//On recupere la valeur de l'argument op dans l'URL$
$op  = \Xmf\Request::getString('op', 'list');
$lid = \Xmf\Request::getInt('lid', 0, 'REQUEST');
// redirection si pas de droit pour poster
if (false === $perm_submit) {
    redirect_header('index.php', 2, _NOPERM);
}
// user must have perm to autoapprove if he want to modify, otherwise modfile.php must be used
if (false === $perm_autoapprove && $lid > 0) {
    redirect_header('index.php', 2, _NOPERM);
}
//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'list':
        //navigation
        $navigation = _MD_TDMDOWNLOADS_SUBMIT_PROPOSER;
        $xoopsTpl->assign('navigation', $navigation);
        // référencement
        // titre de la page
        $titre = _MD_TDMDOWNLOADS_SUBMIT_PROPOSER . '&nbsp;-&nbsp;';
        $titre .= $xoopsModule->name();
        $xoopsTpl->assign('xoops_pagetitle', $titre);
        //description
        $xoTheme->addMeta('meta', 'description', strip_tags(_MD_TDMDOWNLOADS_SUBMIT_PROPOSER));
        //Affichage du formulaire de notation des téléchargements
        /** @var \XoopsModules\Tdmdownloads\Downloads $obj */
        $obj = $downloadsHandler->create();
        $form = $obj->getForm($donnee = [], false);
        $xoopsTpl->assign('themeForm', $form->render());
        break;
    // save
    case 'save_downloads':
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $newUpload = true;
        /** @var \XoopsModules\Tdmdownloads\Downloads $obj */
        if (true === $perm_autoapprove && $lid > 0) {
            $obj       = $downloadsHandler->get($lid);
            $newUpload = false;
        } else {
            $obj = $downloadsHandler->create();
        }
        $erreur       = false;
        $errorMessage = '';
        $donnee       = [];
        $obj->setVar('title', \Xmf\Request::getString('title', '', 'POST'));
        $donnee['title'] = \Xmf\Request::getString('title', '', 'POST');
        $obj->setVar('cid', \Xmf\Request::getString('cid', '', 'POST'));
        $donnee['cid'] = \Xmf\Request::getString('cid', '', 'POST');
        $obj->setVar('homepage', formatURL(\Xmf\Request::getString('homepage', '', 'POST')));
        $obj->setVar('version', \Xmf\Request::getString('version', '', 'POST'));
        $obj->setVar('paypal', \Xmf\Request::getString('paypal', '', 'POST'));
        if (\Xmf\Request::hasVar('platform', 'POST')) {
            $obj->setVar('platform', implode('|', \Xmf\Request::getString('platform', '', 'POST')));
        }
        $obj->setVar('description', \Xmf\Request::getString('description', '', 'POST'));
        if (\Xmf\Request::hasVar('submitter', 'POST')) {
            $obj->setVar('submitter', \Xmf\Request::getString('submitter', '', 'POST'));
            $donnee['submitter'] = \Xmf\Request::getString('submitter', '', 'POST');
        } else {
            $obj->setVar('submitter', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);
            $donnee['submitter'] = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
        }
        $obj->setVar('date', time());
        if (true === $perm_autoapprove) {
            $obj->setVar('status', 1);
        } else {
            $obj->setVar('status', 0);
        }
        if ($xoopsUser) {
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                if (\Xmf\Request::hasVar('status', 'POST')) {
                    $obj->setVar('status', \Xmf\Request::getInt('status', 0, 'POST'));
                    $donnee['status'] = \Xmf\Request::getInt('status', 0, 'POST');
                } else {
                    $obj->setVar('status', 0);
                    $donnee['status'] = 0;
                }
            }
        }
        $donnee['date_update'] = 0;
        // erreur si la catégorie est vide
        if (\Xmf\Request::hasVar('cid', 'REQUEST')) {
            if (0 === \Xmf\Request::getInt('cid', 0, 'REQUEST')) {
                $erreur       = true;
                $errorMessage .= _MD_TDMDOWNLOADS_ERREUR_NOCAT . '<br>';
            }
        }
        // erreur si le captcha est faux
        xoops_load('xoopscaptcha');
        $xoopsCaptcha = \XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            $errorMessage .= $xoopsCaptcha->getMessage() . '<br>';
            $erreur       = true;
        }
        // pour enregistrer temporairement les valeur des champs sup
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $fieldHandler->getAll($criteria);
        foreach (array_keys($downloads_field) as $i) {
            /** @var \XoopsModules\Tdmdownloads\Field[] $downloads_field */
            if (0 === $downloads_field[$i]->getVar('status_def')) {
                $fieldName          = 'champ' . $downloads_field[$i]->getVar('fid');
                $donnee[$fieldName] = \Xmf\Request::getString($fieldName, '', 'POST');
            }
        }
        // enregistrement temporaire des tags
        if (1 == $helper->getConfig('usetag') && class_exists(Tag::class)) {
            $donnee['TAG'] = $_POST['tag'];
        }
        if (true === $erreur) {
            $xoopsTpl->assign('message_erreur', $errorMessage);
            /** @var \XoopsThemeForm $form */
            $form = $obj->getForm($donnee, true);
            $GLOBALS['xoopsTpl']->assign('themeForm', $form->render());
            break;
        }
        $obj->setVar('size', \Xmf\Request::getString('size', '', 'POST') . ' ' . \Xmf\Request::getString('type_size', '', 'POST'));
        // Pour le fichier
        $mediaSize = 0;
        if (isset($_POST['xoops_upload_file'][0])) {
            $uploader = new \XoopsMediaUploader($uploaddir_downloads, $helper->getConfig('mimetypes'), $helper->getConfig('maxuploadsize'), null, null);
            if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                if ($helper->getConfig('newnamedownload')) {
                    $uploader->setPrefix($helper->getConfig('prefixdownloads'));
                }
                $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
                if (!$uploader->upload()) {
                    $errors = $uploader->getErrors();
                    redirect_header('javascript:history.go(-1)', 3, $errors);
                } else {
                    $mediaSize = $uploader->getMediaSize();
                    $obj->setVar('url', $uploadurl_downloads . $uploader->getSavedFileName());
                }
            } else {
                if ($_FILES['attachedfile']['name'] > '') {
                    // file name was given, but fetchMedia failed - show error when e.g. file size exceed maxuploadsize
                    $errorMessage .= $uploader->getErrors() . '<br>';
                    $GLOBALS['xoopsTpl']->assign('message_erreur', $errorMessage);
                    $form = $obj->getForm($donnee, true);
                    $GLOBALS['xoopsTpl']->assign('themeForm', $form->render());
                    break;
                }
                $obj->setVar('url', \Xmf\Request::getString('url', '', 'REQUEST'));
            }
        } else {
            $obj->setVar('url', \Xmf\Request::getString('url', '', 'REQUEST'));
        }
        // Pour l'image
        if (isset($_POST['xoops_upload_file'][1])) {
            $uploader_2 = new \XoopsMediaUploader(
                $uploaddir_shots, [
                                    'image/gif',
                                    'image/jpeg',
                                    'image/pjpeg',
                                    'image/x-png',
                                    'image/png',
                                ], $helper->getConfig('maxuploadsize'), null, null
            );
            if ($uploader_2->fetchMedia($_POST['xoops_upload_file'][1])) {
                $uploader_2->setPrefix('downloads_');
                $uploader_2->fetchMedia($_POST['xoops_upload_file'][1]);
                if (!$uploader_2->upload()) {
                    $errors = $uploader_2->getErrors();
                    redirect_header('javascript:history.go(-1)', 3, $errors);
                } else {
                    $obj->setVar('logourl', $uploader_2->getSavedFileName());
                }
            } else {
                if ($_FILES['attachedimage']['name'] > '') {
                    // file name was given, but fetchMedia failed - show error when e.g. file size exceed maxuploadsize
                    $errorMessage .= $uploader_2->getErrors() . '<br>';
                    $GLOBALS['xoopsTpl']->assign('message_erreur', $errorMessage);
                    $form = $obj->getForm($donnee, true);
                    $GLOBALS['xoopsTpl']->assign('themeForm', $form->render());
                    break;
                }
                $obj->setVar('logourl', \Xmf\Request::getString('logo_img', '', 'REQUEST'));
            }
        } else {
            $obj->setVar('logourl', \Xmf\Request::getString('logo_img', '', 'REQUEST'));
        }
        //Automatic file size
        if ('' == Xmf\Request::getString('sizeValue', '')) {
            if (0 == $mediaSize) {
                $obj->setVar('size', $utility::getFileSize(Xmf\Request::getUrl('url', '')));
            } else {
                $obj->setVar('size', $utility::convertFileSize($mediaSize));
            }
        } else {
            $obj->setVar('size', Xmf\Request::getFloat('sizeValue', 0) . ' ' . Xmf\Request::getString('sizeType', ''));
        }
        $timeToRedirect = 2;
        if (0 == $obj->getVar('size')) {
            $obj->setVar('size', '');
            $error_message  = _AM_TDMDOWNLOADS_ERREUR_SIZE;
            $timeToRedirect = 10;
        }
        if ($downloadsHandler->insert($obj)) {
            if ($newUpload) {
                $lidDownloads = $obj->getNewEnreg($db);
            } else {
                $lidDownloads = $lid;
            }
            //tags
            if (1 == $helper->getConfig('usetag') && class_exists(TagHandler::class)) {
                /** @var \XoopsModules\Tag\TagHandler $tagHandler */
                $tagHandler = TagHelper::getInstance()->getHandler('Tag');
                $tagHandler->updateByItem($_POST['tag'], $lidDownloads, $moduleDirName, 0);
            }
            // Récupération des champs supplémentaires:
            $criteria = new \CriteriaCompo();
            $criteria->setSort('weight ASC, title');
            $criteria->setOrder('ASC');
            $downloads_field = $fieldHandler->getAll($criteria);
            foreach (array_keys($downloads_field) as $i) {
                if (0 === $downloads_field[$i]->getVar('status_def')) {
                    $objdata   = $fielddataHandler->create();
                    $fieldName = 'champ' . $downloads_field[$i]->getVar('fid');
                    $objdata->setVar('data', \Xmf\Request::getString($fieldName, '', 'POST'));
                    $objdata->setVar('lid', $lidDownloads);
                    $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));
                    $fielddataHandler->insert($objdata) || $objdata->getHtmlErrors();
                }
            }
            if ($xoopsUser) {
                if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                    //permission pour télécharger
                    if (1 == $helper->getConfig('permission_download')) {
                        /** @var \XoopsGroupPermHandler $grouppermHandler */
                        $grouppermHandler = xoops_getHandler('groupperm');
                        $criteria         = new \CriteriaCompo();
                        $criteria->add(new \Criteria('gperm_itemid', $lidDownloads, '='));
                        $criteria->add(new \Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
                        $criteria->add(new \Criteria('gperm_name', 'tdmdownloads_download_item', '='));
                        $grouppermHandler->deleteAll($criteria);
                        if (\Xmf\Request::hasVar('item_download', 'POST')) {
                            foreach ($_POST['item_download'] as $onegroup_id) {
                                $grouppermHandler->addRight('tdmdownloads_download_item', $lidDownloads, $onegroup_id, $xoopsModule->getVar('mid'));
                            }
                        }
                    }
                }
            }
            /** @var \XoopsNotificationHandler $notificationHandler */
            $notificationHandler   = xoops_getHandler('notification');
            $tags                  = [];
            $tags['FILE_NAME']     = $donnee['title'];
            $tags['FILE_URL']      = XOOPS_URL . '/modules/' . $moduleDirName . '/singlefile.php?cid=' . $donnee['cid'] . '&lid=' . $lidDownloads;
            $downloadscat_cat      = $categoryHandler->get($donnee['cid']);
            $tags['CATEGORY_NAME'] = $downloadscat_cat->getVar('cat_title');
            $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $moduleDirName . '/viewcat.php?cid=' . $donnee['cid'];
            if (true === $perm_autoapprove) {
                $notificationHandler->triggerEvent('global', 0, 'new_file', $tags);
                $notificationHandler->triggerEvent('category', $donnee['cid'], 'new_file', $tags);
                redirect_header('index.php', $timeToRedirect, _MD_TDMDOWNLOADS_SUBMIT_RECEIVED . '<br>' . _MD_TDMDOWNLOADS_SUBMIT_ISAPPROVED . '<br><br>' . $error_message);
                exit;
            }
            $tags['WAITINGFILES_URL'] = XOOPS_URL . '/modules/' . $moduleDirName . '/admin/index.php?op=listNewDownloads';
            $notificationHandler->triggerEvent('global', 0, 'file_submit', $tags);
            $notificationHandler->triggerEvent('category', $donnee['cid'], 'file_submit', $tags);
            redirect_header('index.php', $timeToRedirect, _MD_TDMDOWNLOADS_SUBMIT_RECEIVED . '<br><br>' . $error_message);
            exit;
        }
        $errors = $obj->getHtmlErrors();
        $form   = $obj->getForm($donnee, true);
        $xoopsTpl->assign('themeForm', $form->render());
        break;
}
$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);
require XOOPS_ROOT_PATH . '/footer.php';
