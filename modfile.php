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

use Xmf\Request;
use XoopsModules\Tdmdownloads;

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
$op = $utility->cleanVars($_REQUEST, 'op', 'list', 'string');

// redirection si pas de droit pour poster
if (false === $perm_modif) {
    redirect_header('index.php', 2, _NOPERM);
}

$lid = $utility->cleanVars($_REQUEST, 'lid', 0, 'int');

//information du téléchargement
$viewDownloads = $downloadsHandler->get($lid);

// redirection si le téléchargement n'existe pas ou n'est pas activé
if (0 === count($viewDownloads) || 0 == $viewDownloads->getVar('status')) {
    redirect_header('index.php', 3, _MD_TDMDOWNLOADS_SINGLEFILE_NONEXISTENT);
}

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'list':
        //navigation
        $view_category = $categoryHandler->get($viewDownloads->getVar('cid'));
        $categories    = $utility->getItemIds('tdmdownloads_view', $moduleDirName);
        if (!in_array($viewDownloads->getVar('cid'), $categories, true)) {
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
        $obj  = $modifiedHandler->create();
        $form = $obj->getForm($lid, false, $donnee = []);
        $xoopsTpl->assign('themeForm', $form->render());
        break;
    // save
    case 'save':
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $obj            = $modifiedHandler->create();
        $erreur         = false;
        $message_erreur = '';
        $donnee         = [];
        $obj->setVar('title', \Xmf\Request::getString('title', '', 'POST')); //$_POST['title']);
        $donnee['title'] = \Xmf\Request::getString('title', '', 'POST'); //$_POST['title'];
        $obj->setVar('cid', \Xmf\Request::getInt('cid', 0, 'POST')); //$_POST['cid']);
        $donnee['cid'] = \Xmf\Request::getInt('cid', 0, 'POST'); //$_POST['cid'];
        $obj->setVar('lid', \Xmf\Request::getInt('lid', 0, 'POST')); //$_POST['lid']);
        $obj->setVar('homepage', \Xmf\Request::getString('homepage', '', 'POST')); //formatURL($_POST["homepage"]));
        $donnee['homepage'] = \Xmf\Request::getString('homepage', '', 'POST'); //formatURL($_POST["homepage"]);
        $obj->setVar('version', \Xmf\Request::getString('version', '', 'POST')); //$_POST["version"]);
        $donnee['version'] = \Xmf\Request::getString('version', '', 'POST'); //$_POST["version"];
        $obj->setVar('size', \Xmf\Request::getString('size', '', 'POST')); //$_POST["size"]);
        $donnee['size']      = \Xmf\Request::getString('size', '', 'POST'); //$_POST["size"];
        $donnee['type_size'] = \Xmf\Request::getString('type_size', '', 'POST'); //$_POST['type_size'];
        if (\Xmf\Request::hasVar('platform', 'POST')) {
            $obj->setVar('platform', implode('|', \Xmf\Request::getString('platform', '', 'POST'))); //$_POST['platform']));
            $donnee['platform'] = implode('|', \Xmf\Request::getString('platform', '', 'POST')); //$_POST["platform"]);
        } else {
            $donnee['platform'] = '';
        }
        $obj->setVar('description', \Xmf\Request::getString('description', '', 'POST')); //$_POST["description"]);
        $donnee['description'] = \Xmf\Request::getString('description', '', 'POST'); //$_POST["description"];
        $obj->setVar('modifysubmitter', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);

        // erreur si la taille du fichier n'est pas un nombre
        //        if (0 == (int)$_REQUEST['size']) {
        //            if ('0' == $_REQUEST['size'] || '' == $_REQUEST['size']) {
        if (\Xmf\Request::hasVar('size') && 0 == \Xmf\Request::getInt('size')) {
            if ('0' == \Xmf\Request::getString('size', '', 'POST')
                || '' === \Xmf\Request::getString('size', '', 'POST')) {
                $erreur = false;
            } else {
                $erreur         = true;
                $message_erreur .= _MD_TDMDOWNLOADS_ERREUR_SIZE . '<br>';
            }
        }
        // erreur si la catégorie est vide
        if (\Xmf\Request::hasVar('cid')) {
            if (0 == \Xmf\Request::getInt('cid', 0, 'POST')) {
                $erreur         = true;
                $message_erreur .= _MD_TDMDOWNLOADS_ERREUR_NOCAT . '<br>';
            }
        }
        // erreur si le captcha est faux
        xoops_load('captcha');
        $xoopsCaptcha = \XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            $message_erreur .= $xoopsCaptcha->getMessage() . '<br>';
            $erreur         = true;
        }
        // pour enregistrer temporairement les valeur des champs sup
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $fieldHandler->getAll($criteria);
        foreach (array_keys($downloads_field) as $i) {
            if (0 == $downloads_field[$i]->getVar('status_def')) {
                $nom_champ          = 'champ' . $downloads_field[$i]->getVar('fid');
                $donnee[$nom_champ] = \Xmf\Request::getString($nom_champ, '', 'POST');
            }
        }
        if (true === $erreur) {
            $xoopsTpl->assign('message_erreur', $message_erreur);
        } else {
            $obj->setVar('size', \Xmf\Request::getInt('size', 0, 'POST') . ' ' . \Xmf\Request::getString('type_size', '', 'POST'));
            // Pour le fichier
            if (isset($_POST['xoops_upload_file'][0])) {
                $uploader = new \XoopsMediaUploader($uploaddir_downloads, explode('|', $helper->getConfig('mimetype')), $helper->getConfig('maxuploadsize'), null, null);
                if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                    if ($helper->getConfig('newnamedownload')) {
                        $uploader->setPrefix($helper->getConfig('prefixdownloads'));
                    }
                    $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
                    if (!$uploader->upload()) {
                        $errors = $uploader->getErrors();
                        redirect_header('javascript:history.go(-1)', 3, $errors);
                    } else {
                        $obj->setVar('url', $uploadurl_downloads . $uploader->getSavedFileName());
                    }
                } else {
                    $obj->setVar('url', $_REQUEST['url']);
                }
            }
            // Pour l'image
            if (isset($_POST['xoops_upload_file'][1])) {
                $uploader_2 = new \XoopsMediaUploader($uploaddir_shots, [
                    'image/gif',
                    'image/jpeg',
                    'image/pjpeg',
                    'image/x-png',
                    'image/png',
                ], $helper->getConfig('maxuploadsize'), null, null);
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
                    $obj->setVar('logourl', $_REQUEST['logo_img']);
                }
            }

            if ($modifiedHandler->insert($obj)) {
                $lidDownloads = $obj->getNewEnreg($db);
                // Récupération des champs supplémentaires:
                $criteria = new \CriteriaCompo();
                $criteria->setSort('weight ASC, title');
                $criteria->setOrder('ASC');
                $downloads_field = $fieldHandler->getAll($criteria);
                foreach (array_keys($downloads_field) as $i) {
                    if (0 == $downloads_field[$i]->getVar('status_def')) {
                        //$objdata = $modifiedfielddataHandler->create();
                        $objdata   = $modifieddataHandler->create();
                        $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                        $objdata->setVar('moddata', $_POST[$nom_champ]);
                        $objdata->setVar('lid', $lidDownloads);
                        $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));
                        //$modifiedfielddataHandler->insert($objdata) || $objdata->getHtmlErrors();
                        $modifieddataHandler->insert($objdata) || $objdata->getHtmlErrors();
                    }
                }
                $tags                      = [];
                $tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/' . $moduleDirName . '/admin/modified.php';
                /** @var \XoopsNotificationHandler $notificationHandler */
                $notificationHandler = xoops_getHandler('notification');
                $notificationHandler->triggerEvent('global', 0, 'file_modify', $tags);
                redirect_header('singlefile.php?lid=' . \Xmf\Request::getInt('lid', 0, 'REQUEST'), 1, _MD_TDMDOWNLOADS_MODFILE_THANKSFORINFO);
            }
            echo $obj->getHtmlErrors();
        }
        //Affichage du formulaire de notation des téléchargements
        $form = $obj->getForm(\Xmf\Request::getInt('lid', 0, 'REQUEST'), true, $donnee);
        $xoopsTpl->assign('themeForm', $form->render());

        break;
}
require XOOPS_ROOT_PATH . '/footer.php';
