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

use XoopsModules\Tdmdownloads;

require __DIR__ . '/admin_header.php';
//On recupere la valeur de l'argument op dans l'URL$
$op = TDMDownloads_CleanVars($_REQUEST, 'op', 'list', 'string');

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'list':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMDownloads_checkModuleAdmin()) {
            $category_admin = \Xmf\Module\Admin::getInstance();
            echo $category_admin->displayNavigation('category.php');
            $category_admin->addItemButton(_AM_TDMDOWNLOADS_CAT_NEW, 'category.php?op=new_cat', 'add');
            echo $category_admin->displayButton();
        }
        $criteria = new \CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $downloads_cat = $categoryHandler->getAll($criteria);
        //Affichage du tableau
        if (count($downloads_cat)>0) {
            echo '<table width="100%" cellspacing="1" class="outer">';
            echo '<tr>';
            echo '<th align="left" width="25%">' . _AM_TDMDOWNLOADS_FORMTITLE . '</th>';
            echo '<th align="center" width="10%">' . _AM_TDMDOWNLOADS_FORMIMG . '</th>';
            echo '<th align="center">' . _AM_TDMDOWNLOADS_FORMTEXT . '</th>';
            echo '<th align="center" width="3%">' . _AM_TDMDOWNLOADS_FORMWEIGHT . '</th>';
            echo '<th align="center" width="8%">' . _AM_TDMDOWNLOADS_FORMACTION . '</th>';
            echo '</tr>';
            $class = 'odd';
//            require_once XOOPS_ROOT_PATH . '/modules/tdmdownloads/class/tree.php';
            $mytree = new Tdmdownloads\Tree($downloads_cat, 'cat_cid', 'cat_pid');
            $category_ArrayTree = $mytree->makeArrayTree('cat_title', '<img src="../assets/images/deco/arrow.gif">');
            foreach (array_keys($category_ArrayTree) as $i) {
                echo '<tr class="'.$class.'">';
                echo '<td align="left" ><a href="' . XOOPS_URL . '/modules/tdmdownloads/viewcat.php?cid=' . $i . '">' . $category_ArrayTree[$i] . '</a></td>';
                echo '<td align="center">';
                echo '<img src="' . $uploadurl . $downloads_cat[$i]->getVar('cat_imgurl') . '" alt="" title="" height="60">';
                echo '</td>';
                echo '<td align="left">' . $downloads_cat[$i]->getVar('cat_description_main') . '</td>';
                echo '<td align="center">' . $downloads_cat[$i]->getVar('cat_weight') . '</td>';
                echo '<td align="center">';
                echo '<a href="category.php?op=edit_cat&downloadscat_cid=' . $i . '"><img src="../assets/images/icon/edit.png" alt="'._AM_TDMDOWNLOADS_FORMEDIT.'" title="'._AM_TDMDOWNLOADS_FORMEDIT.'"></a> ';
                echo '<a href="category.php?op=del_cat&downloadscat_cid=' . $i . '"><img src="../assets/images/icon/delete.png" alt="'._AM_TDMDOWNLOADS_FORMDEL.'" title="'._AM_TDMDOWNLOADS_FORMDEL.'"></a>';
                echo '</td>';
                echo '</tr>';
                $class = ('even' === $class) ? 'odd' : 'even';
            }
            echo '</table>';
        }
    break;

    // vue création
    case 'new_cat':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMDownloads_checkModuleAdmin()) {
            $category_admin = \Xmf\Module\Admin::getInstance();
            echo $category_admin->displayNavigation('category.php');
            $category_admin->addItemButton(_AM_TDMDOWNLOADS_CAT_LIST, 'category.php?op=list', 'list');
            echo $category_admin->displayButton();
        }
        //Affichage du formulaire de création des catégories
        $obj = $categoryHandler->create();
        $form = $obj->getForm();
        $form->display();
    break;

    // Pour éditer une catégorie
    case 'edit_cat':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMDownloads_checkModuleAdmin()) {
            $category_admin = \Xmf\Module\Admin::getInstance();
            echo $category_admin->displayNavigation('category.php');
            $category_admin->addItemButton(_AM_TDMDOWNLOADS_CAT_NEW, 'category.php?op=new_cat', 'add');
            $category_admin->addItemButton(_AM_TDMDOWNLOADS_CAT_LIST, 'category.php?op=list', 'list');
            echo $category_admin->displayButton();
        }
        //Affichage du formulaire de création des catégories
        $downloadscat_cid = TDMDownloads_CleanVars($_REQUEST, 'downloadscat_cid', 0, 'int');
        $obj = $categoryHandler->get($downloadscat_cid);
        $form = $obj->getForm();
        $form->display();
    break;

    // Pour supprimer une catégorie
    case 'del_cat':
        global $xoopsModule;
        $downloadscat_cid = TDMDownloads_CleanVars($_REQUEST, 'downloadscat_cid', 0, 'int');
        $obj = $categoryHandler->get($downloadscat_cid);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('category.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // supression des téléchargements de la catégorie
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('cid', $downloadscat_cid));
            $downloads_arr = $downloadsHandler->getAll($criteria);
            foreach (array_keys($downloads_arr) as $i) {
                // supression des votes
                $criteria_1 = new \CriteriaCompo();
                $criteria_1->add(new \Criteria('lid', $downloads_arr[$i]->getVar('lid')));
                $downloads_votedata = $ratingHandler->getAll($criteria_1);
                foreach (array_keys($downloads_votedata) as $j) {
                    $objvotedata = $ratingHandler->get($downloads_votedata[$j]->getVar('ratingid'));
                    $ratingHandler->delete($objvotedata) or $objvotedata->getHtmlErrors();
                }
                // supression des rapports de fichier brisé
                $criteria_2 = new \CriteriaCompo();
                $criteria_2->add(new \Criteria('lid', $downloads_arr[$i]->getVar('lid')));
                $downloads_broken = $brokenHandler->getAll($criteria_2);
                foreach (array_keys($downloads_broken) as $j) {
                    $objbroken = $brokenHandler->get($downloads_broken[$j]->getVar('reportid'));
                    $brokenHandler->delete($objbroken) or $objbroken ->getHtmlErrors();
                }
                // supression des data des champs sup.
                $criteria_3 = new \CriteriaCompo();
                $criteria_3->add(new \Criteria('lid', $downloads_arr[$i]->getVar('lid')));
                $downloads_fielddata = $fielddataHandler->getAll($criteria_3);
                if ($fielddataHandler->getCount($criteria_3) > 0) {
                    foreach (array_keys($downloads_fielddata) as $j) {
                        $objfielddata = $fielddataHandler->get($downloads_fielddata[$j]->getVar('iddata'));
                        $fielddataHandler->delete($objfielddata) or $objvfielddata->getHtmlErrors();
                    }
                }
                // supression des commentaires
                if ($downloads_arr[$i]->getVar('comments') > 0) {
                    xoops_comment_delete($xoopsModule->getVar('mid'), $downloads_arr[$i]->getVar('lid'));
                }
                //supression des tags
                if ((1 == $xoopsModuleConfig['usetag']) && is_dir('../../tag')) {
                    $tagHandler = xoops_getModuleHandler('link', 'tag');
                    $criteria = new \CriteriaCompo();
                    $criteria->add(new \Criteria('tag_itemid', $downloads_arr[$i]->getVar('lid')));
                    $downloads_tags = $tagHandler->getall($criteria);
                    if (count($downloads_tags) > 0) {
                        foreach (array_keys($downloads_tags) as $j) {
                            $objtags = $tagHandler->get($downloads_tags[$j]->getVar('tl_id'));
                            $tagHandler->delete($objtags) or $objtags->getHtmlErrors();
                        }
                    }
                }
                // supression du fichier
                // pour extraire le nom du fichier
                $urlfile = substr_replace($downloads_arr[$i]->getVar('url'), '', 0, strlen($uploadurl_downloads));
                // chemin du fichier
                $urlfile = $uploaddir_downloads . $urlfile;
                if (is_file($urlfile)) {
                    chmod($urlfile, 0777);
                    unlink($urlfile);
                }
                // supression du téléchargment
                $objdownloads = $downloadsHandler->get($downloads_arr[$i]->getVar('lid'));
                $downloadsHandler->delete($objdownloads) or $objdownloads->getHtmlErrors();
            }
            // supression des sous catégories avec leurs téléchargements
            $downloadscat_arr = $categoryHandler->getAll();
            $mytree = new \XoopsModules\Tdmdownloads\Tree($downloadscat_arr, 'cat_cid', 'cat_pid');
            $downloads_childcat=$mytree->getAllChild($downloadscat_cid);
            foreach (array_keys($downloads_childcat) as $i) {
                // supression de la catégorie
                $objchild = $categoryHandler->get($downloads_childcat[$i]->getVar('cat_cid'));
                $categoryHandler->delete($objchild) or $objchild->getHtmlErrors();
                // supression des téléchargements associés
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('cid', $downloads_childcat[$i]->getVar('cat_cid')));
                $downloads_arr = $downloadsHandler->getAll($criteria);
                foreach (array_keys($downloads_arr) as $i) {
                    // supression des votes
                    $criteria = new \CriteriaCompo();
                    $criteria->add(new \Criteria('lid', $downloads_arr[$i]->getVar('lid')));
                    $downloads_votedata = $ratingHandler->getAll($criteria);
                    foreach (array_keys($downloads_votedata) as $j) {
                        $objvotedata = $ratingHandler->get($downloads_votedata[$j]->getVar('ratingid'));
                        $ratingHandler->delete($objvotedata) or $objvotedata->getHtmlErrors();
                    }
                    // supression des rapports de fichier brisé
                    $criteria = new \CriteriaCompo();
                    $criteria->add(new \Criteria('lid', $downloads_arr[$i]->getVar('lid')));
                    $downloads_broken = $brokenHandler->getAll($criteria);
                    foreach (array_keys($downloads_broken) as $j) {
                        $objbroken = $brokenHandler->get($downloads_broken[$j]->getVar('reportid'));
                        $brokenHandler->delete($objbroken) or $objbroken ->getHtmlErrors();
                    }
                    // supression des data des champs sup.
                    $criteria = new \CriteriaCompo();
                    $criteria->add(new \Criteria('lid', $downloads_arr[$i]->getVar('lid')));
                    $downloads_fielddata = $fielddataHandler->getAll($criteria);
                    foreach (array_keys($downloads_fielddata) as $j) {
                        $objfielddata = $fielddataHandler->get($downloads_fielddata[$j]->getVar('iddata'));
                        $fielddataHandler->delete($objfielddata) or $objvfielddata->getHtmlErrors();
                    }
                    // supression des commentaires
                    if ($downloads_arr[$i]->getVar('comments') > 0) {
                        xoops_comment_delete($xoopsModule->getVar('mid'), $downloads_arr[$i]->getVar('lid'));
                    }
                    //supression des tags
                    if ((1 == $xoopsModuleConfig['usetag']) && (is_dir('../../tag'))) {
                        $tagHandler = xoops_getModuleHandler('link', 'tag');
                        $criteria = new \CriteriaCompo();
                        $criteria->add(new \Criteria('tag_itemid', $downloads_arr[$i]->getVar('lid')));
                        $downloads_tags = $tagHandler->getall($criteria);
                        if (count($downloads_tags) > 0) {
                            foreach (array_keys($downloads_tags) as $j) {
                                $objtags = $tagHandler->get($downloads_tags[$j]->getVar('tl_id'));
                                $tagHandler->delete($objtags) or $objtags->getHtmlErrors();
                            }
                        }
                    }
                    // supression du fichier
                    $urlfile = substr_replace($downloads_arr[$i]->getVar('url'), '', 0, strlen($uploadurl_downloads)); // pour extraire le nom du fichier
                    $urlfile = $uploaddir_downloads . $urlfile; // chemin du fichier
                    if (is_file($urlfile)) {
                        chmod($urlfile, 0777);
                        unlink($urlfile);
                    }
                    // supression du téléchargment
                    $objdownloads = $downloadsHandler->get($downloads_arr[$i]->getVar('lid'));
                    $downloadsHandler->delete($objdownloads) or $objdownloads->getHtmlErrors();
                }
            }
            if ($categoryHandler->delete($obj)) {
                redirect_header('category.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            $message = '';
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('cid', $downloadscat_cid));
            $downloads_arr = $downloadsHandler->getAll($criteria);
            if (count($downloads_arr) > 0) {
                $message .= _AM_TDMDOWNLOADS_DELDOWNLOADS .'<br>';
                foreach (array_keys($downloads_arr) as $i) {
                    $message .= '<span style="color : #ff0000">' . $downloads_arr[$i]->getVar('title') . '</span><br>';
                }
            }
            $downloadscat_arr = $categoryHandler->getAll();
            $mytree = new \XoopsModules\Tdmdownloads\Tree($downloadscat_arr, 'cat_cid', 'cat_pid');
            $downloads_childcat=$mytree->getAllChild($downloadscat_cid);
            if (count($downloads_childcat) > 0) {
                $message .=_AM_TDMDOWNLOADS_DELSOUSCAT . ' <br><br>';
                foreach (array_keys($downloads_childcat) as $i) {
                    $message .= '<b><span style="color : #ff0000">' . $downloads_childcat[$i]->getVar('cat_title') . '</span></b><br>';
                    $criteria = new \CriteriaCompo();
                    $criteria->add(new \Criteria('cid', $downloads_childcat[$i]->getVar('cat_cid')));
                    $downloads_arr = $downloadsHandler->getAll($criteria);
                    if (count($downloads_arr) > 0) {
                        $message .= _AM_TDMDOWNLOADS_DELDOWNLOADS .'<br>';
                        foreach (array_keys($downloads_arr) as $i) {
                            $message .= '<span style="color : #ff0000">' . $downloads_arr[$i]->getVar('title') . '</span><br>';
                        }
                    }
                }
            } else {
                $message.='';
            }
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            if (TDMDownloads_checkModuleAdmin()) {
                $category_admin = \Xmf\Module\Admin::getInstance();
                $category_admin->addItemButton(_AM_TDMDOWNLOADS_CAT_NEW, 'category.php?op=new_cat', 'add');
                $category_admin->addItemButton(_AM_TDMDOWNLOADS_CAT_LIST, 'category.php?op=list', 'list');
                echo $category_admin->displayButton();
            }
            xoops_confirm(['ok' => 1, 'downloadscat_cid' => $downloadscat_cid, 'op' => 'del_cat'], $_SERVER['REQUEST_URI'], sprintf(_AM_TDMDOWNLOADS_FORMSUREDEL, $obj->getVar('cat_title')) . '<br><br>' . $message);
        }

    break;

    // Pour sauver une catégorie
    case 'save_cat':
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('category.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $cat_cid = TDMDownloads_CleanVars($_REQUEST, 'cat_cid', 0, 'int');
        if (isset($_REQUEST['cat_cid'])) {
            $obj = $categoryHandler->get($cat_cid);
        } else {
            $obj = $categoryHandler->create();
        }
        $erreur = false;
        $message_erreur = '';
        // Récupération des variables:
        // Pour l'image
        require_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $uploader = new \XoopsMediaUploader($uploaddir, ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'], $xoopsModuleConfig['maxuploadsize'], null, null);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->setPrefix('downloads_') ;
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $errors = $uploader->getErrors();
                redirect_header('javascript:history.go(-1)', 3, $errors);
            } else {
                $obj->setVar('cat_imgurl', $uploader->getSavedFileName());
            }
        } else {
            $obj->setVar('cat_imgurl', $_REQUEST['downloadscat_img']);
        }
        // Pour les autres variables
        $obj->setVar('cat_pid', $_POST['cat_pid']);
        $obj->setVar('cat_title', $_POST['cat_title']);
        $obj->setVar('cat_description_main', $_POST['cat_description_main']);
        $obj->setVar('cat_weight', $_POST['cat_weight']);
        if (0 == (int)$_REQUEST['cat_weight'] && '0' != $_REQUEST['cat_weight']) {
            $erreur=true;
            $message_erreur = _AM_TDMDOWNLOADS_ERREUR_WEIGHT . '<br>';
        }
        if (isset($_REQUEST['cat_cid'])) {
            if ($cat_cid == $_REQUEST['cat_pid']) {
                $erreur=true;
                $message_erreur .= _AM_TDMDOWNLOADS_ERREUR_CAT;
            }
        }
        if (true == $erreur) {
            echo '<div class="errorMsg" style="text-align: left;">' . $message_erreur . '</div>';
        } else {
            if ($categoryHandler->insert($obj)) {
                $newcat_cid = $obj->get_new_enreg();
                //permission pour voir
                $perm_id = isset($_REQUEST['cat_cid']) ? $cat_cid : $newcat_cid;
                $gpermHandler = xoops_getHandler('groupperm');
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('gperm_itemid', $perm_id, '='));
                $criteria->add(new \Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
                $criteria->add(new \Criteria('gperm_name', 'tdmdownloads_view', '='));
                $gpermHandler->deleteAll($criteria);
                if (isset($_POST['groups_view'])) {
                    foreach ($_POST['groups_view'] as $onegroup_id) {
                        $gpermHandler->addRight('tdmdownloads_view', $perm_id, $onegroup_id, $xoopsModule->getVar('mid'));
                    }
                }
                //permission pour editer
                $perm_id = isset($_REQUEST['cat_cid']) ? $cat_cid : $newcat_cid;
                $gpermHandler = xoops_getHandler('groupperm');
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('gperm_itemid', $perm_id, '='));
                $criteria->add(new \Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
                $criteria->add(new \Criteria('gperm_name', 'tdmdownloads_submit', '='));
                $gpermHandler->deleteAll($criteria);
                if (isset($_POST['groups_submit'])) {
                    foreach ($_POST['groups_submit'] as $onegroup_id) {
                        $gpermHandler->addRight('tdmdownloads_submit', $perm_id, $onegroup_id, $xoopsModule->getVar('mid'));
                    }
                }
                //permission pour télécharger
                if (1 == $xoopsModuleConfig['permission_download']) {
                    $perm_id = isset($_REQUEST['cat_cid']) ? $cat_cid : $newcat_cid;
                    $gpermHandler = xoops_getHandler('groupperm');
                    $criteria = new \CriteriaCompo();
                    $criteria->add(new \Criteria('gperm_itemid', $perm_id, '='));
                    $criteria->add(new \Criteria('gperm_modid', $xoopsModule->getVar('mid'), '='));
                    $criteria->add(new \Criteria('gperm_name', 'tdmdownloads_download', '='));
                    $gpermHandler->deleteAll($criteria);
                    if (isset($_POST['groups_download'])) {
                        foreach ($_POST['groups_download'] as $onegroup_id) {
                            $gpermHandler->addRight('tdmdownloads_download', $perm_id, $onegroup_id, $xoopsModule->getVar('mid'));
                        }
                    }
                }
                //notification
                if (!isset($_REQUEST['categorie_modified'])) {
                    $tags = [];
                    $tags['CATEGORY_NAME'] = $_POST['cat_title'];
                    $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $newcat_cid;
                    $notificationHandler = xoops_getHandler('notification');
                    $notificationHandler->triggerEvent('global', 0, 'new_category', $tags);
                }
                redirect_header('category.php?op=list', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
            }
            echo $obj->getHtmlErrors();
        }
        $form = $obj->getForm();
        $form->display();
    break;
}
//Affichage de la partie basse de l'administration de Xoops
xoops_cp_footer();
