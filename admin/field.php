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
require __DIR__ . '/admin_header.php';
require dirname(__DIR__) . '/include/common.php';
// Template
$templateMain = 'tdmdownloads_admin_field.tpl';

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

//On recupere la valeur de l'argument op dans l'URL$
$op = \Xmf\Request::getString('op', 'list');

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'list':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_FIELD_NEW, 'field.php?op=new_field', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $fieldHandler->getAll($criteria);
        $numrows         = count($downloads_field);
        //Affichage du tableau
        if ($numrows > 0) {
            $GLOBALS['xoopsTpl']->assign('fields_count', $numrows);

            foreach (array_keys($downloads_field) as $i) {
                $field = [
                    'title'      => $downloads_field[$i]->getVar('title'),
                    'img'        => $uploadurl_field . $downloads_field[$i]->getVar('img'),
                    'weight'     => $downloads_field[$i]->getVar('weight'),
                    'fid'        => $downloads_field[$i]->getVar('fid'),
                    'status'     => $downloads_field[$i]->getVar('status'),
                    'search'     => $downloads_field[$i]->getVar('search'),
                    'status_def' => $downloads_field[$i]->getVar('status_def'),
                    'search'     => $downloads_field[$i]->getVar('search'),
                ];
                $GLOBALS['xoopsTpl']->append('fields_list', $field);
                unset($field);
            }
        }
        break;
    case 'update_status':
        $obj = $fieldHandler->get(\Xmf\Request::getInt('fid', 0, 'REQUEST'));

        $obj->setVar('status', \Xmf\Request::getInt('aff', 0, 'REQUEST'));
        if ($fieldHandler->insert($obj)) {
            redirect_header('field.php?op=list', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
        }
        $GLOBALS['xoopsTpl']->assign('message_erreur', $obj->getHtmlErrors());
        break;
    case 'update_search':
        $obj = $fieldHandler->get(\Xmf\Request::getInt('fid', 0, 'REQUEST'));

        $obj->setVar('search', \Xmf\Request::getInt('aff', 0, 'REQUEST'));
        if ($fieldHandler->insert($obj)) {
            redirect_header('field.php?op=list', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
        }
        $GLOBALS['xoopsTpl']->assign('message_erreur', $obj->getHtmlErrors());
        break;
    // vue création
    case 'new_field':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_FIELD_LIST, 'field.php?op=list', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        //Affichage du formulaire de création des champs
        $obj  = $fieldHandler->create();
        $form = $obj->getForm();
        $GLOBALS['xoopsTpl']->assign('themeForm', $form->render());
        break;
    // Pour éditer un champ
    case 'edit_field':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_FIELD_NEW, 'field.php?op=new_field', 'add');
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_FIELD_LIST, 'field.php?op=list', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        //Affichage du formulaire de création des champs
        $obj  = $fieldHandler->get(\Xmf\Request::getInt('fid', 0, 'GET'));
        $form = $obj->getForm();
        $GLOBALS['xoopsTpl']->assign('themeForm', $form->render());
        break;
    // Pour supprimer un champ
    case 'del_field':
        global $xoopsModule;
        $obj = $fieldHandler->get(\Xmf\Request::getInt('fid', 0, 'GET'));
        if (\Xmf\Request::hasVar('ok', 'POST') && 1 == \Xmf\Request::getInt('ok', 0, 'POST')) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('field.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // supression des entrée du champ
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('fid', \Xmf\Request::getInt('fid', 0)));
            $downloadsArray = $fielddataHandler->getAll($criteria);
            foreach (array_keys($downloadsArray) as $i) {
                // supression de l'entrée
                $objdownloadsfielddata = $fielddataHandler->get($downloadsArray[$i]->getVar('iddata'));
                $fielddataHandler->delete($objdownloadsfielddata) || $objdownloads->getHtmlErrors();
            }
            if ($fieldHandler->delete($obj)) {
                redirect_header('field.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
            } else {
                $GLOBALS['xoopsTpl']->assign('message_erreur', $obj->getHtmlErrors());
            }
        } else {
            $downloadsfield = $fieldHandler->get(\Xmf\Request::getInt('fid', 0, 'GET'));
            if (1 == $downloadsfield->getVar('status_def')) {
                redirect_header('field.php', 2, _AM_TDMDOWNLOADS_REDIRECT_NODELFIELD);
            }
            $message  = '';
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('fid', \Xmf\Request::getInt('fid', 0, 'GET')));
            $downloadsArray = $fielddataHandler->getAll($criteria);
            if (count($downloadsArray) > 0) {
                $message .= _AM_TDMDOWNLOADS_DELDATA . '<br>';
                foreach (array_keys($downloadsArray) as $i) {
                    $message .= '<span style="color: #ff0000;">' . $downloadsArray[$i]->getVar('data') . '</span><br>';
                }
            }
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            $adminObject = \Xmf\Module\Admin::getInstance();
            $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_FIELD_NEW, 'field.php?op=new_field', 'add');
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_FIELD_LIST, 'field.php?op=list', 'list');
            $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

            xoops_confirm(['ok' => 1, 'fid' => \Xmf\Request::getInt('fid', 0, 'GET'), 'op' => 'del_field'], $_SERVER['REQUEST_URI'], sprintf(_AM_TDMDOWNLOADS_FORMSUREDEL, $obj->getVar('title')) . '<br><br>' . $message);
        }

        break;
    // Pour sauver un champ
    case 'save_field':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('field.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (\Xmf\Request::hasVar('fid', 'POST')) {
            $obj = $fieldHandler->get(\Xmf\Request::getInt('fid', 0, 'POST'));
        } else {
            $obj = $fieldHandler->create();
        }
        $erreur         = false;
        $errorMessage = '';
        // Récupération des variables:
        // Pour l'image
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploader = new \XoopsMediaUploader($uploaddir_field, [
            'image/gif',
            'image/jpeg',
            'image/pjpeg',
            'image/x-png',
            'image/png',
        ], $helper->getConfig('maxuploadsize'), 16, null);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->setPrefix('downloads_');
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $obj->setVar('img', $uploader->getSavedFileName());
            }
        } else {
            $obj->setVar('img', \Xmf\Request::getString('downloadsfield_img', '', 'POST'));
        }
        // Pour les autres variables
        $obj->setVar('title', \Xmf\Request::getString('title', '', 'POST'));
        $obj->setVar('weight', \Xmf\Request::getInt('weight', 0, 'POST'));
        $obj->setVar('status', \Xmf\Request::getInt('status', 0, 'POST'));
        $obj->setVar('search', \Xmf\Request::getInt('search', 0, 'POST'));
        $obj->setVar('status_def', \Xmf\Request::getInt('status_def', 0, 'POST'));

        if (true === $erreur) {
			xoops_cp_header();
            $GLOBALS['xoopsTpl']->assign('message_erreur', $errorMessage);
        } else {
            if ($fieldHandler->insert($obj)) {
                redirect_header('field.php', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
            }
			xoops_cp_header();
            $GLOBALS['xoopsTpl']->assign('message_erreur', $obj->getHtmlErrors());
        }
        $form = $obj->getForm();
        $GLOBALS['xoopsTpl']->assign('themeForm', $form->render());
        break;
}

// Local icons path
if (is_object($helper->getModule())) {
    $pathModIcon16 = $helper->getModule()->getInfo('modicons16');
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');

    $GLOBALS['xoopsTpl']->assign('pathModIcon16', XOOPS_URL . '/modules/' . $moduleDirName . '/' . $pathModIcon16);
    $GLOBALS['xoopsTpl']->assign('pathModIcon32', $pathModIcon32);
}

//Affichage de la partie basse de l'administration de Xoops
require_once __DIR__ . '/admin_footer.php';
