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
$xoopsOption['template_main'] = 'tdmdownloads_modfile.html';
include_once XOOPS_ROOT_PATH.'/header.php';
$xoTheme->addStylesheet( XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/css/styles.css', null );
//On recupere la valeur de l'argument op dans l'URL$
$op = TDMDownloads_CleanVars($_REQUEST, 'op', 'list', 'string');

// redirection si pas de droit pour poster
if ($perm_modif == false) {
    redirect_header('index.php', 2, _NOPERM);
    exit();
}

$lid = TDMDownloads_CleanVars($_REQUEST, 'lid', 0, 'int');

//information du téléchargement
$view_downloads = $downloads_Handler->get($lid);

// redirection si le téléchargement n'existe pas ou n'est pas activé
if (count($view_downloads) == 0 || $view_downloads->getVar('status') == 0) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
    exit();
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case "list":
        //navigation
        $view_categorie = $downloadscat_Handler->get($view_downloads->getVar('cid'));
        $categories = TDMDownloads_MygetItemIds('tdmdownloads_view', 'TDMDownloads');
        if (!in_array($view_downloads->getVar('cid'), $categories)) {
            redirect_header('index.php', 2, _NOPERM);
            exit();
        }
        //tableau des catégories
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')','IN'));
        $downloadscat_arr = $downloadscat_Handler->getall($criteria);
        $mytree = new XoopsObjectTree($downloadscat_arr, 'cat_cid', 'cat_pid');
        //navigation
        $navigation = TDMDownloads_PathTreeUrl($mytree, $view_downloads->getVar('cid'), $downloadscat_arr, 'cat_title', $prefix = ' <img src="images/deco/arrow.gif" alt="arrow" /> ', true, 'ASC', true);
        $navigation .= ' <img src="images/deco/arrow.gif" alt="arrow" /> <a title="' . $view_downloads->getVar('title') . '" href="singlefile.php?lid=' . $view_downloads->getVar('lid') . '">' . $view_downloads->getVar('title') . '</a>';
        $navigation .= ' <img src="images/deco/arrow.gif" alt="arrow" /> ' . _MD_TDMDOWNLOADS_SINGLEFILE_MODIFY;
        $xoopsTpl->assign('navigation', $navigation);
         // référencement
        // titre de la page
        $pagetitle = _MD_TDMDOWNLOADS_SINGLEFILE_MODIFY . ' - ' . $view_downloads->getVar('title') . ' - ';
        $pagetitle .= TDMDownloads_PathTreeUrl($mytree, $view_downloads->getVar('cid'), $downloadscat_arr, 'cat_title', $prefix = ' - ', false, 'DESC', true);
        $xoopsTpl->assign('xoops_pagetitle', $pagetitle);
        //description
        $xoTheme->addMeta( 'meta', 'description', strip_tags(_MD_TDMDOWNLOADS_SINGLEFILE_MODIFY . ' (' . $view_downloads->getVar('title') . ')'));

        //Affichage du formulaire de notation des téléchargements
        $obj =& $downloadsmod_Handler->create();
        $form = $obj->getForm($lid, false, $donnee = array());
        $xoopsTpl->assign('themeForm', $form->render());
    break;
    // save
    case "save":
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $obj =& $downloadsmod_Handler->create();
        $erreur = false;
        $message_erreur = '';
        $donnee = array();
        $obj->setVar('title', $_POST['title']);
        $donnee['title'] = $_POST['title'];
        $obj->setVar('cid', $_POST['cid']);
        $donnee['cid'] = $_POST['cid'];
        $obj->setVar('lid', $_POST['lid']);
        $obj->setVar('homepage', formatURL($_POST["homepage"]));
        $donnee['homepage'] = formatURL($_POST["homepage"]);
        $obj->setVar('version', $_POST["version"]);
        $donnee['version'] = $_POST["version"];
        $obj->setVar('size', $_POST["size"]);
        $donnee['size'] = $_POST["size"];
        $donnee['type_size'] = $_POST['type_size'];
        if (isset($_POST['platform'])) {
            $obj->setVar('platform', implode('|',$_POST['platform']));
            $donnee['platform'] = implode('|',$_POST["platform"]);
        } else {
            $donnee['platform'] = '';
        }
        $obj->setVar('description', $_POST["description"]);
        $donnee['description'] = $_POST["description"];
        $obj->setVar('modifysubmitter', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);

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

            if ($downloadsmod_Handler->insert($obj)) {
                $lid_dowwnloads = $obj->get_new_enreg();
                // Récupération des champs supplémentaires:
                $criteria = new CriteriaCompo();
                $criteria->setSort('weight ASC, title');
                $criteria->setOrder('ASC');
                $downloads_field = $downloadsfield_Handler->getall($criteria);
                foreach (array_keys($downloads_field) as $i) {
                    if ($downloads_field[$i]->getVar('status_def') == 0) {
                        $objdata =& $downloadsfieldmoddata_Handler->create();
                        $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                        $objdata->setVar('moddata', $_POST[$nom_champ]);
                        $objdata->setVar('lid', $lid_dowwnloads);
                        $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));
                        $downloadsfieldmoddata_Handler->insert($objdata) or $objdata->getHtmlErrors();
                    }
                }
                $tags = array();
                $tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/modified.php';
                $notification_handler =& xoops_gethandler('notification');
                $notification_handler->triggerEvent('global', 0, 'file_modify', $tags);
                redirect_header('singlefile.php?lid=' . intval($_REQUEST['lid']), 1, _MD_TDMDOWNLOADS_MODFILE_THANKSFORINFO);
            }
            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des téléchargements
        $form =& $obj->getForm(intval($_REQUEST['lid']), true, $donnee);
        $xoopsTpl->assign('themeForm', $form->render());

    break;
}
include XOOPS_ROOT_PATH.'/footer.php';
