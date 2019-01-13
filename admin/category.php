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
// Template
$templateMain = 'tdmdownloads_admin_category.tpl';

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

        $moduleDirName = basename(dirname(__DIR__));
        $adminObject   = \Xmf\Module\Admin::getInstance();
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_CAT_NEW, 'category.php?op=new_cat', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoopsTpl']->assign('tdmdownloads_url', TDMDOWNLOADS_URL);

        $criteria = new \CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $downloads_cat = $categoryHandler->getAll($criteria);
        //Affichage du tableau

        //        if (count($downloads_cat) > 0) {
        if (count($downloads_cat) > 0) {
            $GLOBALS['xoopsTpl']->assign('categories_count', count($downloads_cat));
            $mytree             = new \XoopsModules\Tdmdownloads\Tree($downloads_cat, 'cat_cid', 'cat_pid');
            $category_ArrayTree = $mytree->makeArrayTree('cat_title', '<img src="../assets/images/deco/arrow.gif">');
            $category           = [];
            foreach (array_keys($category_ArrayTree) as $i) {
                $category = [
                    'cid'                  => $i,
                    'title'                => $downloads_cat[$i]->getVar('cat_title'),
                    'category'             => $category_ArrayTree[$i],
                    'cat_imgurl'           => $uploadurl . $downloads_cat[$i]->getVar('cat_imgurl'),
                    'cat_description_main' => $downloads_cat[$i]->getVar('cat_description_main'),
                    'cat_weight'           => $downloads_cat[$i]->getVar('cat_weight'),
                ];
                $GLOBALS['xoopsTpl']->append('categories_list', $category);
                unset($category);
            }
        }

        break;
    // vue création
    case 'new_cat':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_CAT_LIST, 'category.php?op=list', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        //Affichage du formulaire de création des catégories
        $obj  = $categoryHandler->create();
        $form = $obj->getForm();
        $GLOBALS['xoopsTpl']->assign('themeForm', $form->render());
        break;
    // Pour éditer une catégorie
    case 'edit_cat':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_CAT_LIST, 'category.php?op=list', 'list');
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_CAT_NEW, 'category.php?op=new_cat', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        //Affichage du formulaire de création des catégories
        $categoryId = \Xmf\Request::getInt('downloadscat_cid', 0, 'GET');
        /** @var \XoopsModules\Tdmdownloads\Category $obj */
        $obj  = $categoryHandler->get($categoryId);
        $form = $obj->getForm();
        $GLOBALS['xoopsTpl']->assign('themeForm', $form->render());
        break;
    // Pour supprimer une catégorie
    case 'del_cat':
        global $xoopsModule;
        $categoryId = \Xmf\Request::getInt('downloadscat_cid', 0, 'GET');
        /** @var \XoopsModules\Tdmdownloads\Category $obj */
        $obj = $categoryHandler->get($categoryId);
        if (\Xmf\Request::hasVar('ok', 'REQUEST') && 1 == \Xmf\Request::getInt('ok', 0, 'REQUEST')) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('category.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // supression des téléchargements de la catégorie
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('cid', $categoryId));
            $downloadsArray = $downloadsHandler->getAll($criteria);
            foreach (array_keys($downloadsArray) as $i) {
                // supression des votes
                $criteria_1 = new \CriteriaCompo();
                $criteria_1->add(new \Criteria('lid', $downloadsArray[$i]->getVar('lid')));
                $votedata = $ratingHandler->getAll($criteria_1);
                foreach (array_keys($votedata) as $j) {
                    $objvotedata = $ratingHandler->get($votedata[$j]->getVar('ratingid'));
                    $ratingHandler->delete($objvotedata) || $objvotedata->getHtmlErrors();
                }
                // supression des rapports de fichier brisé
                $criteria_2 = new \CriteriaCompo();
                $criteria_2->add(new \Criteria('lid', $downloadsArray[$i]->getVar('lid')));
                $downloads_broken = $brokenHandler->getAll($criteria_2);
                foreach (array_keys($downloads_broken) as $j) {
                    $objbroken = $brokenHandler->get($downloads_broken[$j]->getVar('reportid'));
                    $brokenHandler->delete($objbroken) || $objbroken->getHtmlErrors();
                }
                // supression des data des champs sup.
                $criteria_3 = new \CriteriaCompo();
                $criteria_3->add(new \Criteria('lid', $downloadsArray[$i]->getVar('lid')));
                $downloads_fielddata = $fielddataHandler->getAll($criteria_3);
                if ($fielddataHandler->getCount($criteria_3) > 0) {
                    foreach (array_keys($downloads_fielddata) as $j) {
                        $objfielddata = $fielddataHandler->get($downloads_fielddata[$j]->getVar('iddata'));
                        $fielddataHandler->delete($objfielddata) || $objvfielddata->getHtmlErrors();
                    }
                }
                // supression des commentaires
                if ($downloadsArray[$i]->getVar('comments') > 0) {
                    xoops_comment_delete($xoopsModule->getVar('mid'), $downloadsArray[$i]->getVar('lid'));
                }
                //supression des tags
                if ((1 == $helper->getConfig('usetag')) && class_exists('\XoopsModules\Tag\LinkHandler')) {
                    /** @var \XoopsModules\Tag\LinkHandler $linkHandler */
                    $linkHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Link');
                    $criteria    = new \CriteriaCompo();
                    $criteria->add(new \Criteria('tag_itemid', $downloadsArray[$i]->getVar('lid')));
                    $downloadsTags = $linkHandler->getAll($criteria);
                    if (count($downloadsTags) > 0) {
                        foreach (array_keys($downloadsTags) as $j) {
                            $objtags = $linkHandler->get($downloadsTags[$j]->getVar('tl_id'));
                            $linkHandler->delete($objtags) || $objtags->getHtmlErrors();
                        }
                    }
                }
                // supression du fichier
                // pour extraire le nom du fichier
                $urlfile = substr_replace($downloadsArray[$i]->getVar('url'), '', 0, mb_strlen($uploadurl_downloads));
                // chemin du fichier
                $urlfile = $uploaddir_downloads . $urlfile;
                if (is_file($urlfile)) {
                    chmod($urlfile, 0777);
                    unlink($urlfile);
                }
                // supression du téléchargment
                $objdownloads = $downloadsHandler->get($downloadsArray[$i]->getVar('lid'));
                $downloadsHandler->delete($objdownloads) || $objdownloads->getHtmlErrors();
            }
            // supression des sous catégories avec leurs téléchargements
            $downloadscatArray  = $categoryHandler->getAll();
            $mytree             = new \XoopsModules\Tdmdownloads\Tree($downloadscatArray, 'cat_cid', 'cat_pid');
            $downloads_childcat = $mytree->getAllChild($categoryId);
            foreach (array_keys($downloads_childcat) as $i) {
                // supression de la catégorie
                $objchild = $categoryHandler->get($downloads_childcat[$i]->getVar('cat_cid'));
                $categoryHandler->delete($objchild) || $objchild->getHtmlErrors();
                // supression des téléchargements associés
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('cid', $downloads_childcat[$i]->getVar('cat_cid')));
                $downloadsArray = $downloadsHandler->getAll($criteria);
                foreach (array_keys($downloadsArray) as $j) {
                    // supression des votes
                    $criteria = new \CriteriaCompo();
                    $criteria->add(new \Criteria('lid', $downloadsArray[$j]->getVar('lid')));
                    $votedata = $ratingHandler->getAll($criteria);
                    foreach (array_keys($votedata) as $k) {
                        $objvotedata = $ratingHandler->get($votedata[$k]->getVar('ratingid'));
                        $ratingHandler->delete($objvotedata) || $objvotedata->getHtmlErrors();
                    }
                    // supression des rapports de fichier brisé
                    $criteria = new \CriteriaCompo();
                    $criteria->add(new \Criteria('lid', $downloadsArray[$j]->getVar('lid')));
                    $downloads_broken = $brokenHandler->getAll($criteria);
                    foreach (array_keys($downloads_broken) as $k) {
                        $objbroken = $brokenHandler->get($downloads_broken[$k]->getVar('reportid'));
                        $brokenHandler->delete($objbroken) || $objbroken->getHtmlErrors();
                    }
                    // supression des data des champs sup.
                    $criteria = new \CriteriaCompo();
                    $criteria->add(new \Criteria('lid', $downloadsArray[$j]->getVar('lid')));
                    $downloads_fielddata = $fielddataHandler->getAll($criteria);
                    foreach (array_keys($downloads_fielddata) as $k) {
                        $objfielddata = $fielddataHandler->get($downloads_fielddata[$k]->getVar('iddata'));
                        $fielddataHandler->delete($objfielddata) || $objvfielddata->getHtmlErrors();
                    }
                    // supression des commentaires
                    if ($downloadsArray[$j]->getVar('comments') > 0) {
                        xoops_comment_delete($xoopsModule->getVar('mid'), $downloadsArray[$j]->getVar('lid'));
                    }
                    //supression des tags
                    if ((1 == $helper->getConfig('usetag')) && class_exists('\XoopsModules\Tag\LinkHandler')) {
                        /** @var \XoopsModules\Tag\LinkHandler $linkHandler */
                        $linkHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Link');
                        $criteria    = new \CriteriaCompo();
                        $criteria->add(new \Criteria('tag_itemid', $downloadsArray[$j]->getVar('lid')));
                        $downloadsTags = $linkHandler->getAll($criteria);
                        if (count($downloadsTags) > 0) {
                            foreach (array_keys($downloadsTags) as $k) {
                                $objtags = $linkHandler->get($downloadsTags[$k]->getVar('tl_id'));
                                $linkHandler->delete($objtags) || $objtags->getHtmlErrors();
                            }
                        }
                    }
                    // supression du fichier
                    $urlfile = substr_replace($downloadsArray[$j]->getVar('url'), '', 0, mb_strlen($uploadurl_downloads)); // pour extraire le nom du fichier
                    $urlfile = $uploaddir_downloads . $urlfile; // chemin du fichier
                    if (is_file($urlfile)) {
                        chmod($urlfile, 0777);
                        unlink($urlfile);
                    }
                    // supression du téléchargment
                    $objdownloads = $downloadsHandler->get($downloadsArray[$j]->getVar('lid'));
                    $downloadsHandler->delete($objdownloads) || $objdownloads->getHtmlErrors();
                }
            }
            if ($categoryHandler->delete($obj)) {
                redirect_header('category.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
            } else {
                $GLOBALS['xoopsTpl']->assign('message_erreur', $obj->getHtmlErrors());
            }
        } else {
            $message  = '';
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('cid', $categoryId));
            $downloadsArray = $downloadsHandler->getAll($criteria);
            if (count($downloadsArray) > 0) {
                $message .= _AM_TDMDOWNLOADS_DELDOWNLOADS . '<br>';
                foreach (array_keys($downloadsArray) as $i) {
                    $message .= '<span style="color : #ff0000">' . $downloadsArray[$i]->getVar('title') . '</span><br>';
                }
            }
            $downloadscatArray  = $categoryHandler->getAll();
            $mytree             = new \XoopsModules\Tdmdownloads\Tree($downloadscatArray, 'cat_cid', 'cat_pid');
            $downloads_childcat = $mytree->getAllChild($categoryId);
            if (count($downloads_childcat) > 0) {
                $message .= _AM_TDMDOWNLOADS_DELSOUSCAT . ' <br><br>';
                foreach (array_keys($downloads_childcat) as $i) {
                    $message  .= '<b><span style="color : #ff0000">' . $downloads_childcat[$i]->getVar('cat_title') . '</span></b><br>';
                    $criteria = new \CriteriaCompo();
                    $criteria->add(new \Criteria('cid', $downloads_childcat[$i]->getVar('cat_cid')));
                    $downloadsArray = $downloadsHandler->getAll($criteria);
                    if (count($downloadsArray) > 0) {
                        $message .= _AM_TDMDOWNLOADS_DELDOWNLOADS . '<br>';
                        foreach (array_keys($downloadsArray) as $k) {
                            $message .= '<span style="color: #ff0000;">' . $downloadsArray[$k]->getVar('title') . '</span><br>';
                        }
                    }
                }
            } else {
                $message .= '';
            }
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            $adminObject = \Xmf\Module\Admin::getInstance();
            $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_CAT_LIST, 'category.php?op=list', 'list');
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_CAT_NEW, 'category.php?op=new_cat', 'add');
            $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
            xoops_confirm([
                              'ok'               => 1,
                              'downloadscat_cid' => $categoryId,
                              'op'               => 'del_cat',
                          ], $_SERVER['REQUEST_URI'], sprintf(_AM_TDMDOWNLOADS_FORMSUREDEL, $obj->getVar('cat_title')) . '<br><br>' . $message);
        }

        break;
    // Pour sauver une catégorie
    case 'save_cat':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('category.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        xoops_cp_header();
		$cat_cid = \Xmf\Request::getInt('cat_cid', 0, 'POST');
        if (0 !== $cat_cid) {
            $obj = $categoryHandler->get($cat_cid);
        } else {
            $obj = $categoryHandler->create();
        }
        $erreur         = false;
        $errorMessage = '';
        // Récupération des variables:
        // Pour l'image
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        $uploader = new \XoopsMediaUploader($uploaddir, [
            'image/gif',
            'image/jpeg',
            'image/pjpeg',
            'image/x-png',
            'image/png',
        ], $helper->getConfig('maxuploadsize'), null, null);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->setPrefix('downloads_');
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $obj->setVar('cat_imgurl', $uploader->getSavedFileName());
            }
        } else {
            $obj->setVar('cat_imgurl', \Xmf\Request::getString('downloadscat_img', '', 'REQUEST'));
        }
        // Pour les autres variables
        $obj->setVar('cat_pid', \Xmf\Request::getInt('cat_pid', 0, 'POST')); //$_POST['cat_pid']);
        $obj->setVar('cat_title', \Xmf\Request::getString('cat_title', '', 'POST')); //$_POST['cat_title']);
        $obj->setVar('cat_description_main', \Xmf\Request::getString('cat_description_main', '', 'POST')); //$_POST['cat_description_main']);
		$obj->setVar('cat_weight', \Xmf\Request::getInt('cat_weight', 0, 'POST'));
        if (\Xmf\Request::hasVar('cat_cid', 'REQUEST')) {
            if ($cat_cid === \Xmf\Request::getInt('cat_pid', 0, 'POST')) {
                $erreur         = true;
                $errorMessage .= _AM_TDMDOWNLOADS_ERREUR_CAT;
            }
        }
        if (true === $erreur) {
            $GLOBALS['xoopsTpl']->assign('message_erreur', $errorMessage);
        } else {
            if ($categoryHandler->insert($obj)) {
                $newcat_cid = $obj->getNewEnreg($db);
                //permission pour voir
                $perm_id = \Xmf\Request::hasVar('cat_cid', 'POST') ? $cat_cid : $newcat_cid;
                /** @var \XoopsGroupPermHandler $grouppermHandler */
                $grouppermHandler = xoops_getHandler('groupperm');
                $criteria         = new \CriteriaCompo();
                $criteria->add(new \Criteria('gperm_itemid', $perm_id, '='));
                $criteria->add(new \Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
                $criteria->add(new \Criteria('gperm_name', 'tdmdownloads_view', '='));
                $grouppermHandler->deleteAll($criteria);
                if (\Xmf\Request::hasVar('groups_view', 'POST')) {
                    foreach ($_POST['groups_view'] as $onegroup_id) {
                        $grouppermHandler->addRight('tdmdownloads_view', $perm_id, $onegroup_id, $xoopsModule->getVar('mid'));
                    }
                }
                //permission pour editer
                $perm_id          = \Xmf\Request::getInt('cat_cid', $newcat_cid, 'POST');
                $grouppermHandler = xoops_getHandler('groupperm');
                $criteria         = new \CriteriaCompo();
                $criteria->add(new \Criteria('gperm_itemid', $perm_id, '='));
                $criteria->add(new \Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
                $criteria->add(new \Criteria('gperm_name', 'tdmdownloads_submit', '='));
                $grouppermHandler->deleteAll($criteria);
                if (\Xmf\Request::hasVar('groups_submit', 'POST')) {
                    foreach ($_POST['groups_submit'] as $onegroup_id) {
                        $grouppermHandler->addRight('tdmdownloads_submit', $perm_id, $onegroup_id, $xoopsModule->getVar('mid'));
                    }
                }
                //permission pour télécharger
                if (1 == $helper->getConfig('permission_download')) {
                    $perm_id          = \Xmf\Request::getInt('cat_cid', $newcat_cid, 'POST');
                    $grouppermHandler = xoops_getHandler('groupperm');
                    $criteria         = new \CriteriaCompo();
                    $criteria->add(new \Criteria('gperm_itemid', $perm_id, '='));
                    $criteria->add(new \Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
                    $criteria->add(new \Criteria('gperm_name', 'tdmdownloads_download', '='));
                    $grouppermHandler->deleteAll($criteria);
                    if (\Xmf\Request::hasVar('groups_download', 'POST')) {
                        foreach ($_POST['groups_download'] as $onegroup_id) {
                            $grouppermHandler->addRight('tdmdownloads_download', $perm_id, $onegroup_id, $xoopsModule->getVar('mid'));
                        }
                    }
                }
                //notification
                if (!\Xmf\Request::hasVar('categorie_modified', 'POST')) {
                    $tags                  = [];
                    $tags['CATEGORY_NAME'] = \Xmf\Request::getString('cat_title', '', 'POST');
                    $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $moduleDirName . '/viewcat.php?cid=' . $newcat_cid;
                    $notificationHandler   = xoops_getHandler('notification');
                    $notificationHandler->triggerEvent('global', 0, 'new_category', $tags);
                }
                redirect_header('category.php?op=list', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
            }
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
