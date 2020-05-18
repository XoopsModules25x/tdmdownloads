<?php declare(strict_types=1);

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
$GLOBALS['xoopsOption']['template_main'] = 'tdmdownloads_modfile.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
$moduleDirName = basename(__DIR__);

/** @var \xos_opal_Theme $xoTheme */
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/styles.css', null);

//On recupere la valeur de l'argument op dans l'URL$
$op = \Xmf\Request::getCmd('op', 'list');

// redirection si pas de droit pour poster
if (false === $perm_modif) {
    redirect_header('index.php', 2, _NOPERM);
}

$lid = \Xmf\Request::getInt('lid', 0, 'REQUEST');

//information du téléchargement
$viewDownloads = $downloadsHandler->get($lid);

// redirection si le téléchargement n'existe pas ou n'est pas activé
if (!is_object($viewDownloads) || 0 == $viewDownloads->getVar('status')) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'list':
        //navigation
        $view_category = $categoryHandler->get($viewDownloads->getVar('cid'));
        $categories    = $utility->getItemIds('tdmdownloads_view', $moduleDirName);
        if (!in_array($viewDownloads->getVar('cid'), $categories)) {
            redirect_header('index.php', 2, _NOPERM);
        }
        //tableau des catégories
        $criteria = new \CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $criteria->add(new \Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
        $downloadscatArray = $categoryHandler->getAll($criteria);
        $mytree            = new \XoopsModules\Tdmdownloads\Tree($downloadscatArray, 'cat_cid', 'cat_pid');
        //navigation
        $navigation = $utility->getPathTreeUrl($mytree, $viewDownloads->getVar('cid'), $downloadscatArray, 'cat_title', $prefix = ' <img src="assets/images/deco/arrow.gif" alt="arrow"> ', true, 'ASC', true);
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow"> <a title="' . $viewDownloads->getVar('title') . '" href="singlefile.php?lid=' . $viewDownloads->getVar('lid') . '">' . $viewDownloads->getVar('title') . '</a>';
        $navigation .= ' <img src="assets/images/deco/arrow.gif" alt="arrow"> ' . _MD_TDMDOWNLOADS_SINGLEFILE_MODIFY;
        $xoopsTpl->assign('navigation', $navigation);
        // référencement
        // titre de la page
        $pagetitle = _MD_TDMDOWNLOADS_SINGLEFILE_MODIFY . ' - ' . $viewDownloads->getVar('title') . ' - ';
        $pagetitle .= $utility->getPathTreeUrl($mytree, $viewDownloads->getVar('cid'), $downloadscatArray, 'cat_title', $prefix = ' - ', false, 'DESC', true);
        $xoopsTpl->assign('xoops_pagetitle', $pagetitle);
        //description
        $xoTheme->addMeta('meta', 'description', strip_tags(_MD_TDMDOWNLOADS_SINGLEFILE_MODIFY . ' (' . $viewDownloads->getVar('title') . ')'));

        //Affichage du formulaire de notation des téléchargements
        if ($perm_autoapprove) {
            /** @var \XoopsModules\Tdmdownloads\Downloads $obj */

            $obj = $downloadsHandler->get($lid);

            /** @var \XoopsThemeForm $form */

            $form = $obj->getForm($donnee = [], false, 'submit.php');
        } else {
            /** @var \XoopsModules\Tdmdownloads\Modified $obj */

            $obj = $modifiedHandler->create();

            $form = $obj->getForm($lid, false, $donnee = []);
        }
        $xoopsTpl->assign('themeForm', $form->render());
        $xoopsTpl->assign('message_erreur', false);
        break;
    // save
    case 'save':
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        /** @var \XoopsModules\Tdmdownloads\Downloads $obj */
        $obj          = $modifiedHandler->create();
        $erreur       = false;
        $errorMessage = '';
        $donnee       = [];
        $obj->setVar('title', \Xmf\Request::getString('title', '', 'POST')); //$_POST['title']);
        $donnee['title'] = \Xmf\Request::getString('title', '', 'POST'); //$_POST['title'];
        $obj->setVar('cid', \Xmf\Request::getInt('cid', 0, 'POST')); //$_POST['cid']);
        $donnee['cid'] = \Xmf\Request::getInt('cid', 0, 'POST'); //$_POST['cid'];
        $obj->setVar('lid', \Xmf\Request::getInt('lid', 0, 'POST')); //$_POST['lid']);
        $obj->setVar('homepage', \Xmf\Request::getString('homepage', '', 'POST')); //formatURL($_POST["homepage"]));
        $donnee['homepage'] = \Xmf\Request::getString('homepage', '', 'POST'); //formatURL($_POST["homepage"]);
        $obj->setVar('version', \Xmf\Request::getString('version', '', 'POST')); //$_POST["version"]);
        $donnee['version'] = \Xmf\Request::getString('version', '', 'POST'); //$_POST["version"];
        if (\Xmf\Request::hasVar('platform', 'POST')) {
            $obj->setVar('platform', implode('|', \Xmf\Request::getString('platform', '', 'POST'))); //$_POST['platform']));
            $donnee['platform'] = implode('|', \Xmf\Request::getString('platform', '', 'POST')); //$_POST["platform"]);
        } else {
            $donnee['platform'] = '';
        }
        $obj->setVar('description', \Xmf\Request::getString('description', '', 'POST')); //$_POST["description"]);
        $donnee['description'] = \Xmf\Request::getString('description', '', 'POST'); //$_POST["description"];
        $obj->setVar('modifysubmitter', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);

        // erreur si la catégorie est vide
        if (\Xmf\Request::hasVar('cid')) {
            if (0 == \Xmf\Request::getInt('cid', 0, 'POST')) {
                $erreur = true;

                $errorMessage .= _MD_TDMDOWNLOADS_ERREUR_NOCAT . '<br>';
            }
        }
        // get captcha (members are skipped in class/download.php getForm
        if (!$xoopsUser) {
            // erreur si le captcha est faux

            xoops_load('xoopscaptcha');

            $xoopsCaptcha = \XoopsCaptcha::getInstance();

            if (!$xoopsCaptcha->verify()) {
                $errorMessage .= $xoopsCaptcha->getMessage() . '<br>';

                $erreur = true;
            }
        }
        // pour enregistrer temporairement les valeur des champs sup
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $fieldHandler->getAll($criteria);
        foreach (array_keys($downloads_field) as $i) {
            /** @var \XoopsModules\Tdmdownloads\Field[] $downloads_field */

            if (0 == $downloads_field[$i]->getVar('status_def')) {
                $fieldName = 'champ' . $downloads_field[$i]->getVar('fid');

                $donnee[$fieldName] = \Xmf\Request::getString($fieldName, '', 'POST');
            }
        }
        if (true === $erreur) {
            $xoopsTpl->assign('message_erreur', $errorMessage);
        } else {
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

                        /** @var \XoopsThemeForm $form */

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

                $error_message = _AM_TDMDOWNLOADS_ERREUR_SIZE;

                $timeToRedirect = 10;
            }

            if ($modifiedHandler->insert($obj)) {
                $lidDownloads = $obj->getNewEnreg($db);

                // Récupération des champs supplémentaires:

                $criteria = new \CriteriaCompo();

                $criteria->setSort('weight ASC, title');

                $criteria->setOrder('ASC');

                $downloads_field = $fieldHandler->getAll($criteria);

                foreach (array_keys($downloads_field) as $i) {
                    /** @var \XoopsModules\Tdmdownloads\Field[] $downloads_field */

                    if (0 == $downloads_field[$i]->getVar('status_def')) {
                        //$objdata = $modifiedfielddataHandler->create();

                        $objdata = $modifieddataHandler->create();

                        $fieldName = 'champ' . $downloads_field[$i]->getVar('fid');

                        $objdata->setVar('moddata', \Xmf\Request::getString($fieldName, '', 'POST'));

                        $objdata->setVar('lid', $lidDownloads);

                        $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));

                        //$modifiedfielddataHandler->insert($objdata) || $objdata->getHtmlErrors();

                        $modifieddataHandler->insert($objdata) || $objdata->getHtmlErrors();
                    }
                }

                $tags = [];

                $tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/' . $moduleDirName . '/admin/modified.php';

                /** @var \XoopsNotificationHandler $notificationHandler */

                $notificationHandler = xoops_getHandler('notification');

                $notificationHandler->triggerEvent('global', 0, 'file_modify', $tags);

                redirect_header('singlefile.php?lid=' . \Xmf\Request::getInt('lid', 0, 'REQUEST'), timeToRedirect, _MD_TDMDOWNLOADS_MODFILE_THANKSFORINFO . '<br><br>' . $error_message);
            }

            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des téléchargements
        $form = $obj->getForm(\Xmf\Request::getInt('lid', 0, 'REQUEST'), true, $donnee);
        $xoopsTpl->assign('themeForm', $form->render());

        break;
}
require XOOPS_ROOT_PATH . '/footer.php';
