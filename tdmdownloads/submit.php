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

include_once 'header.php';
// template d'affichage
$xoopsOption['template_main'] = 'tdmdownloads_submit.html';
include_once XOOPS_ROOT_PATH.'/header.php';
$xoTheme->addStylesheet( XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/css/styles.css', null );
//On recupere la valeur de l'argument op dans l'URL$
$op = TDMDownloads_CleanVars($_REQUEST, 'op', 'list', 'string');

// redirection si pas de droit pour poster
if ($perm_submit == false) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case "list":
        //navigation
        $navigation = _MD_TDMDOWNLOADS_SUBMIT_PROPOSER;
        $xoopsTpl->assign('navigation', $navigation);
        // référencement
        // titre de la page
        $titre = _MD_TDMDOWNLOADS_SUBMIT_PROPOSER . '&nbsp;-&nbsp;';
        $titre .= $xoopsModule->name();
        $xoopsTpl->assign('xoops_pagetitle', $titre);
        //description
        $xoTheme->addMeta( 'meta', 'description', strip_tags(_MD_TDMDOWNLOADS_SUBMIT_PROPOSER));

        //Affichage du formulaire de notation des téléchargements
        $obj =& $downloads_Handler->create();
        $form = $obj->getForm($donnee = array(), false);
        $xoopsTpl->assign('themeForm', $form->render());
    break;
    // save
    case "save_downloads":
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $obj =& $downloads_Handler->create();
        $erreur = false;
        $message_erreur = '';
        $donnee = array();
        $obj->setVar('title', $_POST['title']);
        $donnee['title'] = $_POST['title'];
        $obj->setVar('cid', $_POST['cid']);
        $donnee['cid'] = $_POST['cid'];
        $obj->setVar('homepage', formatURL($_POST['homepage']));
        $obj->setVar('version', $_POST['version']);
        $obj->setVar('size', $_POST['size']);
        $donnee['type_size'] = $_POST['type_size'];
        $obj->setVar('paypal', $_POST['paypal']);
        if (isset($_POST['platform'])) {
            $obj->setVar('platform', implode('|',$_POST['platform']));
        }
        $obj->setVar('description', $_POST['description']);
        if (isset($_POST['submitter'])) {
            $obj->setVar('submitter', $_POST['submitter']);
            $donnee['submitter'] = $_POST['submitter'];
        } else {
            $obj->setVar('submitter', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);
            $donnee['submitter'] = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
        }
        $obj->setVar('date', time());
        if ($perm_autoapprove == true) {
            $obj->setVar('status', 1);
        } else {
            $obj->setVar('status', 0);
        }
        if ($xoopsUser) {
            if ( $xoopsUser->isAdmin($xoopsModule->mid()) ) {
                if (isset($_POST['status'])) {
                    $obj->setVar('status', $_POST['status']);
                    $donnee['status'] = $_POST['status'];
                } else {
                    $obj->setVar('status', 0);
                    $donnee['status'] = 0;
                }
            }
        }
        $donnee['date_update'] = 0;
        // erreur si la taille du fichier n'est pas un nombre
        if (intval($_REQUEST['size']) == 0) {
            if ($_REQUEST['size'] == '0' || $_REQUEST['size'] == '') {
                $erreur = false;
            } else {
                $erreur = true;
                $message_erreur .= _MD_TDMDOWNLOADS_ERREUR_SIZE . '<br>';
            }
        }
        // erreur si la catégorie est vide
        if (isset($_REQUEST['cid'])) {
            if ($_REQUEST['cid'] == 0) {
                $erreur=true;
                $message_erreur .= _MD_TDMDOWNLOADS_ERREUR_NOCAT . '<br>';
            }
        }
        // erreur si le captcha est faux
        xoops_load("captcha");
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if ( !$xoopsCaptcha->verify() ) {
            $message_erreur .=$xoopsCaptcha->getMessage().'<br>';
            $erreur=true;
        }
        // pour enregistrer temporairement les valeur des champs sup
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $downloadsfield_Handler->getall($criteria);
        foreach (array_keys($downloads_field) as $i) {
            if ($downloads_field[$i]->getVar('status_def') == 0) {
                $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                $donnee[$nom_champ] = $_POST[$nom_champ];
            }
        }
        // enregistrement temporaire des tags
        if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../tag'))) {
            $donnee['TAG'] = $_POST['tag'];
        }
        if ($erreur==true) {
            $xoopsTpl->assign('message_erreur', $message_erreur);
        } else {
            $obj->setVar('size', $_POST['size'] . ' ' . $_POST['type_size']);
            // Pour le fichier
            if (isset($_POST['xoops_upload_file'][0])) {
                $uploader = new XoopsMediaUploader($uploaddir_downloads, explode('|',$xoopsModuleConfig['mimetype']), $xoopsModuleConfig['maxuploadsize'], null, null);
                if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                    if ($xoopsModuleConfig['newnamedownload']) {
                        $uploader->setPrefix($xoopsModuleConfig['prefixdownloads']) ;
                    }
                    $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
                    if (!$uploader->upload()) {
                        $errors = $uploader->getErrors();
                        redirect_header("javascript:history.go(-1)",3, $errors);
                    } else {
                        $obj->setVar('url', $uploadurl_downloads . $uploader->getSavedFileName());
                    }
                } else {
                    $obj->setVar('url', $_REQUEST['url']);
                }
            }
            // Pour l'image
            if (isset($_POST['xoops_upload_file'][1])) {
                $uploader_2 = new XoopsMediaUploader($uploaddir_shots, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'), $xoopsModuleConfig['maxuploadsize'], null, null);
                if ($uploader_2->fetchMedia($_POST['xoops_upload_file'][1])) {
                    $uploader_2->setPrefix('downloads_') ;
                    $uploader_2->fetchMedia($_POST['xoops_upload_file'][1]);
                    if (!$uploader_2->upload()) {
                        $errors = $uploader_2->getErrors();
                        redirect_header("javascript:history.go(-1)",3, $errors);
                    } else {
                        $obj->setVar('logourl', $uploader_2->getSavedFileName());
                    }
                } else {
                    $obj->setVar('logourl', $_REQUEST['logo_img']);
                }
            }

            if ($downloads_Handler->insert($obj)) {
                $lid_dowwnloads = $obj->get_new_enreg();
                //tags
                if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../tag'))) {
                    $tag_handler = xoops_getmodulehandler('tag', 'tag');
                    $tag_handler->updateByItem($_POST['tag'], $lid_dowwnloads, $xoopsModule->getVar('dirname'), 0);
                }
                // Récupération des champs supplémentaires:
                $criteria = new CriteriaCompo();
                $criteria->setSort('weight ASC, title');
                $criteria->setOrder('ASC');
                $downloads_field = $downloadsfield_Handler->getall($criteria);
                foreach (array_keys($downloads_field) as $i) {
                    if ($downloads_field[$i]->getVar('status_def') == 0) {
                        $objdata =& $downloadsfielddata_Handler->create();
                        $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                        $objdata->setVar('data', $_POST[$nom_champ]);
                        $objdata->setVar('lid', $lid_dowwnloads);
                        $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));
                        $downloadsfielddata_Handler->insert($objdata) or $objdata->getHtmlErrors();
                    }
                }
                if ($xoopsUser) {
                    if ( $xoopsUser->isAdmin($xoopsModule->mid()) ) {
                        //permission pour télécharger
                        if ($xoopsModuleConfig['permission_download'] == 1) {
                            $gperm_handler = &xoops_gethandler('groupperm');
                            $criteria = new CriteriaCompo();
                            $criteria->add(new Criteria('gperm_itemid', $lid_dowwnloads, '='));
                            $criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'),'='));
                            $criteria->add(new Criteria('gperm_name', 'tdmdownloads_download_item', '='));
                            $gperm_handler->deleteAll($criteria);
                            if (isset($_POST['item_download'])) {
                                foreach ($_POST['item_download'] as $onegroup_id) {
                                    $gperm_handler->addRight('tdmdownloads_download_item', $lid_dowwnloads, $onegroup_id, $xoopsModule->getVar('mid'));
                                }
                            }
                        }
                    }
                }
                $notification_handler =& xoops_gethandler('notification');
                $tags = array();
                $tags['FILE_NAME'] = $donnee['title'];
                $tags['FILE_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlefile.php?cid=' . $donnee['cid'] . '&lid=' . $lid_dowwnloads;
                $downloadscat_cat = $downloadscat_Handler->get($donnee['cid']);
                $tags['CATEGORY_NAME'] = $downloadscat_cat->getVar('cat_title');
                $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $donnee['cid'];

                if ($perm_autoapprove == true) {
                    $notification_handler->triggerEvent('global', 0, 'new_file', $tags);
                    $notification_handler->triggerEvent('category', $donnee['cid'], 'new_file', $tags);
                    redirect_header('index.php',2,_MD_TDMDOWNLOADS_SUBMIT_RECEIVED . '<br />' . _MD_TDMDOWNLOADS_SUBMIT_ISAPPROVED . '');
                    exit;
                } else {
                    $tags['WAITINGFILES_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listNewDownloads';
                    $notification_handler->triggerEvent('global', 0, 'file_submit', $tags);
                    $notification_handler->triggerEvent('category', $donnee['cid'], 'file_submit', $tags);
                    redirect_header('index.php',2,_MD_TDMDOWNLOADS_SUBMIT_RECEIVED);
                    exit;
                }
            }
            echo $obj->getHtmlErrors();
        }
        $form =& $obj->getForm($donnee, true);
        $xoopsTpl->assign('themeForm', $form->render());

    break;
}
include XOOPS_ROOT_PATH.'/footer.php';
