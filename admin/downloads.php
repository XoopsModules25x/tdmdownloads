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
$templateMain = 'tdmdownloads_admin_downloads.tpl';

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

$myts = \MyTextSanitizer::getInstance();

//On recupere la valeur de l'argument op dans l'URL$
$op = \Xmf\Request::getString('op', 'list');

// compte le nombre de téléchargement non validé
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 0));
$downloads_waiting = $downloadsHandler->getCount($criteria);

$statut_menu = \Xmf\Request::getInt('statut_display', 1);

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'list':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
        if (1 == $statut_menu) {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
            if (0 == $downloads_waiting) {
                $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
            } else {
                $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
            }
        } else {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
        }
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        $limit        = $helper->getConfig('perpageadmin');
        $category_arr = $categoryHandler->getAll();
        $numrowscat   = count($category_arr);

        // redirection si il n'y a pas de catégories
        if (0 === $numrowscat) {
            redirect_header('category.php?op=new_cat', 2, _AM_TDMDOWNLOADS_REDIRECT_NOCAT);
        }
        $criteria = new \CriteriaCompo();
        // affiche uniquement les téléchargements activés
        if (\Xmf\Request::hasVar('statut_display', 'GET')) {
            if (0 === \Xmf\Request::getInt('statut_display', 0, 'GET')) {
                $criteria->add(new \Criteria('status', 0));
                $statut_display = 0;
            } else {
                $criteria->add(new \Criteria('status', 0, '!='));
                $statut_display = 1;
            }
        } else {
            $criteria->add(new \Criteria('status', 0, '!='));
            $statut_display = 1;
        }
        $document_tri   = 1;
        $document_order = 1;
        if (\Xmf\Request::hasVar('document_tri')) {
            if (1 == \Xmf\Request::getInt('document_tri')) {
                $criteria->setSort('date');
                $document_tri = 1;
            }
            if (2 == \Xmf\Request::getInt('document_tri')) {
                $criteria->setSort('title');
                $document_tri = 2;
            }
            if (3 == \Xmf\Request::getInt('document_tri')) {
                $criteria->setSort('hits');
                $document_tri = 3;
            }
            if (4 == \Xmf\Request::getInt('document_tri')) {
                $criteria->setSort('rating');
                $document_tri = 4;
            }
            if (5 == \Xmf\Request::getInt('document_tri')) {
                $criteria->setSort('cid');
                $document_tri = 5;
            }
        } else {
            $criteria->setSort('date');
        }
        if (\Xmf\Request::hasVar('document_order')) {
            if (1 == \Xmf\Request::getInt('document_order')) {
                $criteria->setOrder('DESC');
                $document_order = 1;
            }
            if (2 == \Xmf\Request::getInt('document_order')) {
                $criteria->setOrder('ASC');
                $document_order = 2;
            }
        } else {
            $criteria->setOrder('DESC');
        }
        $start = \Xmf\Request::getInt('start', 0);
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        //pour faire une jointure de table
        $downloadsHandler->table_link   = $downloadsHandler->db->prefix('tdmdownloads_cat'); // Nom de la table en jointure
        $downloadsHandler->field_link   = 'cat_cid'; // champ de la table en jointure
        $downloadsHandler->field_object = 'cid'; // champ de la table courante
        $downloads_arr                  = $downloadsHandler->getByLink($criteria);
        $numrows                        = $downloadsHandler->getCount($criteria);

        $pagenav = '';
        if ($numrows > $limit) {
            $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', 'op=list&document_tri=' . $document_tri . '&document_order=' . $document_order . '&statut_display=' . $statut_display);
            $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
        }
        //Affichage du tableau des téléchargements
        $GLOBALS['xoopsTpl']->append('downloads_count', $numrows);
        if ($numrows > 0) {
            $selectDocument = _AM_TDMDOWNLOADS_TRIPAR
                              . "<select name=\"document_tri\" id=\"document_tri\" onchange=\"location='"
                              . XOOPS_URL
                              . '/modules/'
                              . $xoopsModule->dirname()
                              . "/admin/downloads.php?statut_display=$statut_display&document_order=$document_order&document_tri='+this.options[this.selectedIndex].value\">";
            $selectDocument .= '<option value="1"' . (1 == $document_tri ? ' selected="selected"' : '') . '>' . _AM_TDMDOWNLOADS_FORMDATE . '</option>';
            $selectDocument .= '<option value="2"' . (2 == $document_tri ? ' selected="selected"' : '') . '>' . _AM_TDMDOWNLOADS_FORMTITLE . '</option>';
            $selectDocument .= '<option value="3"' . (3 == $document_tri ? ' selected="selected"' : '') . '>' . _AM_TDMDOWNLOADS_FORMHITS . '</option>';
            $selectDocument .= '<option value="4"' . (4 == $document_tri ? ' selected="selected"' : '') . '>' . _AM_TDMDOWNLOADS_FORMRATING . '</option>';
            $selectDocument .= '<option value="5"' . (5 == $document_tri ? ' selected="selected"' : '') . '>' . _AM_TDMDOWNLOADS_FORMCAT . '</option>';
            $selectDocument .= '</select> ';
            $GLOBALS['xoopsTpl']->assign('selectDocument', $selectDocument);
            $selectOrder = _AM_TDMDOWNLOADS_ORDER
                           . "<select name=\"order_tri\" id=\"order_tri\" onchange=\"location='"
                           . XOOPS_URL
                           . '/modules/'
                           . $xoopsModule->dirname()
                           . "/admin/downloads.php?statut_display=$statut_display&document_tri=$document_tri&document_order='+this.options[this.selectedIndex].value\">";
            $selectOrder .= '<option value="1"' . (1 == $document_order ? ' selected="selected"' : '') . '>DESC</option>';
            $selectOrder .= '<option value="2"' . (2 == $document_order ? ' selected="selected"' : '') . '>ASC</option>';
            $selectOrder .= '</select> ';
            $GLOBALS['xoopsTpl']->assign('selectOrder', $selectOrder);

            $mytree    = new \XoopsModules\Tdmdownloads\Tree($category_arr, 'cat_cid', 'cat_pid');
            $class     = 'odd';
            $downloads = [];
            foreach (array_keys($downloads_arr) as $i) {
                $download = [
                    'category'       => $utility->getPathTree($mytree, $downloads_arr[$i]->getVar('cid'), $category_arr, 'cat_title', $prefix = ' <img src="../assets/images/deco/arrow.gif"> '),
                    'cid'            => $downloads_arr[$i]->getVar('cid'),
                    'lid'            => $i,
                    'title'          => $downloads_arr[$i]->getVar('title'),
                    'hits'           => $downloads_arr[$i]->getVar('hits'),
                    'rating'         => number_format($downloads_arr[$i]->getVar('rating'), 1),
                    'statut_display' => $statut_display,
                ];
                $GLOBALS['xoopsTpl']->append('downloads_list', $download);
                unset($download);
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_TDMDOWNLOADS_ERREUR_NODOWNLOADSWAITING);
        }
        break;
    // vue création
    case 'new_downloads':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
        if (0 == $downloads_waiting) {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
        } else {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
        }
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        //Affichage du formulaire de création des téléchargements
        /** @var \XoopsModules\Tdmdownloads\Downloads $obj */
        $obj  = $downloadsHandler->create();
        $form = $obj->getForm($donnee = [], false);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    // Pour éditer un téléchargement
    case 'edit_downloads':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');

        if (0 == $downloads_waiting) {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
        } else {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
        }
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        //Affichage du formulaire de création des téléchargements
        $downloads_lid = \Xmf\Request::getInt('downloads_lid', 0, 'GET');
        $obj           = $downloadsHandler->get($downloads_lid);
        $form          = $obj->getForm($donnee = [], false);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    // Pour supprimer un téléchargement
    case 'del_downloads':
        global $xoopsModule;
        $downloads_lid = \Xmf\Request::getInt('downloads_lid', 0, 'GET');
        $obj           = $downloadsHandler->get($downloads_lid);
        if (\Xmf\Request::hasVar('ok') && 1 == \Xmf\Request::getInt('ok')) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('downloads.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // permet d'extraire le nom du fichier
            $urlfile = substr_replace($obj->getVar('url'), '', 0, mb_strlen($uploadurl_downloads));
            if ($downloadsHandler->delete($obj)) {
                // permet de donner le chemin du fichier
                $urlfile = $uploaddir_downloads . $urlfile;
                // si le fichier est sur le serveur il es détruit
                if (is_file($urlfile)) {
                    chmod($urlfile, 0777);
                    unlink($urlfile);
                }
                // supression des votes
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('lid', $downloads_lid));
                $downloads_votedata = $ratingHandler->getAll($criteria);
                foreach (array_keys($downloads_votedata) as $i) {
                    $objvotedata = $ratingHandler->get($downloads_votedata[$i]->getVar('ratingid'));
                    $ratingHandler->delete($objvotedata) || $objvotedata->getHtmlErrors();
                }
                // supression des rapports de fichier brisé
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('lid', $downloads_lid));
                $downloads_broken = $brokenHandler->getAll($criteria);
                foreach (array_keys($downloads_broken) as $i) {
                    $objbroken = $brokenHandler->get($downloads_broken[$i]->getVar('reportid'));
                    $brokenHandler->delete($objbroken) || $objbroken->getHtmlErrors();
                }
                // supression des data des champs sup.
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('lid', $downloads_lid));
                $downloads_fielddata = $fielddataHandler->getAll($criteria);
                foreach (array_keys($downloads_fielddata) as $i) {
                    $objfielddata = $fielddataHandler->get($downloads_fielddata[$i]->getVar('iddata'));
                    $fielddataHandler->delete($objfielddata) || $objvfielddata->getHtmlErrors();
                }
                // supression des commentaires
                xoops_comment_delete($xoopsModule->getVar('mid'), $downloads_lid);
                //supression des tags
                if ((1 === $helper->getConfig('usetag')) && is_dir('../../tag')) {
                    /** @var \XoopsModules\Tag\LinkHandler $linkHandler */
                    $linkHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Link');
                    $criteria    = new \CriteriaCompo();
                    $criteria->add(new \Criteria('tag_itemid', $downloads_lid));
                    $downloads_tags = $linkHandler->getAll($criteria);
                    foreach (array_keys($downloads_tags) as $i) {
                        $objtags = $linkHandler->get($downloads_tags[$i]->getVar('tl_id'));
                        $linkHandler->delete($objtags) || $objtags->getHtmlErrors();
                    }
                }
                redirect_header('downloads.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
            } else {
                $GLOBALS['xoopsTpl']->assign('error', $obj->getHtmlErrors());
            }
        } else {
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            $adminObject = \Xmf\Module\Admin::getInstance();
            $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');

            if (0 == $downloads_waiting) {
                $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
            } else {
                $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
            }
            $adminObject->displayButton('left');
            xoops_confirm(['ok' => 1, 'downloads_lid' => $downloads_lid, 'op' => 'del_downloads'], $_SERVER['REQUEST_URI'],
                          sprintf(_AM_TDMDOWNLOADS_FORMSUREDEL, $obj->getVar('title')) . '<br><br>' . _AM_TDMDOWNLOADS_FORMWITHFILE . ' <b><a href="' . $obj->getVar('url') . '">' . $obj->getVar('url') . '</a></b><br>');
        }
        break;
    // Pour voir les détails du téléchargement
    case 'view_downloads':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
        if (0 == $downloads_waiting) {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
        } else {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
        }
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
        $GLOBALS['xoopsTpl']->assign('uploadurl_shots', $uploadurl_shots);

        $downloads_lid = \Xmf\Request::getInt('downloads_lid', 0, 'GET');
        //information du téléchargement
        $viewDownloads = $downloadsHandler->get($downloads_lid);
        //catégorie
        //$view_category = $categoryHandler->get($viewDownloads->getVar('cid'));
        $category_arr = $categoryHandler->getAll();
        $mytree       = new \XoopsModules\Tdmdownloads\Tree($category_arr, 'cat_cid', 'cat_pid');
        // sortie des informations
        $downloads_title       = $viewDownloads->getVar('title');
        $downloads_description = $viewDownloads->getVar('description');
        //permet d'enlever [pagebreak] du texte
        $downloads_description = str_replace('[pagebreak]', '', $downloads_description);

        $category = $utility->getPathTree($mytree, $viewDownloads->getVar('cid'), $category_arr, 'cat_title', $prefix = ' <img src="../assets/images/deco/arrow.gif"> ');
        // affichages des informations du téléchargement
        $download = [
            'title'       => $downloads_title,
            'description' => $downloads_description,
            'cid'         => $viewDownloads->getVar('cid'),
            'lid'         => $downloads_lid,
            'category'    => $category,
        ];

        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new \Criteria('status', 1));
        $downloads_field = $fieldHandler->getAll($criteria);
        $fields_list     = [];
        foreach (array_keys($downloads_field) as $i) {
            if (1 == $downloads_field[$i]->getVar('status_def')) {
                if (1 == $downloads_field[$i]->getVar('fid')) {
                    //page d'accueil
                    if ('' !== $viewDownloads->getVar('homepage')) {
                        $fields_list[] = ['name' => _AM_TDMDOWNLOADS_FORMHOMEPAGE, 'value' => '<a href="' . $viewDownloads->getVar('homepage') . '">' . $viewDownloads->getVar('homepage') . '</a>'];
                    }
                }
                if (2 == $downloads_field[$i]->getVar('fid')) {
                    //version
                    if ('' !== $viewDownloads->getVar('version')) {
                        $fields_list[] = ['name' => _AM_TDMDOWNLOADS_FORMVERSION, 'value' => $viewDownloads->getVar('version')];
                    }
                }
                if (3 == $downloads_field[$i]->getVar('fid')) {
                    //taille du fichier
                    if ('' !== $viewDownloads->getVar('size')) {
                        $fields_list[] = ['name' => _AM_TDMDOWNLOADS_FORMSIZE, 'value' => $viewDownloads->getVar('size')];
                    }
                }
                if (4 == $downloads_field[$i]->getVar('fid')) {
                    //plateforme
                    if ('' !== $viewDownloads->getVar('platform')) {
                        $fields_list[] = ['name' => _AM_TDMDOWNLOADS_FORMPLATFORM, 'value' => $viewDownloads->getVar('platform')];
                    }
                }
            } else {
                $contenu  = '';
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('lid', $downloads_lid));
                $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfielddata = $fielddataHandler->getAll($criteria);
                foreach (array_keys($downloadsfielddata) as $j) {
                    $contenu = $downloadsfielddata[$j]->getVar('data');
                }
                if ('' !== $contenu) {
                    $fields_list[] = ['name' => $downloads_field[$i]->getVar('title'), 'value' => $contenu];
                }
            }
        }
        $download['fields_list'] = $fields_list;
        // tags
        if ((1 == $helper->getConfig('usetag')) && is_dir('../../tag')) {
            require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
            $tags_array = tagBar($downloads_lid, 0);
            if (!empty($tags_array)) {
                $tags = '';
                foreach (array_keys($tags_array['tags']) as $i) {
                    $tags .= $tags_array['delimiter'] . ' ' . $tags_array['tags'][$i] . ' ';
                }
                $download['tags'] = ['title' => $tags_array['title'], 'value' => $tags];
            }
        }
        if ($helper->getConfig('useshots')) {
            if ('blank.gif' !== $viewDownloads->getVar('logourl')) {
                $download['logourl'] = $viewDownloads->getVar('logourl');
            }
        }
        $download['date']      = formatTimestamp($viewDownloads->getVar('date'));
        $download['submitter'] = XoopsUser::getUnameFromId($viewDownloads->getVar('submitter'));
        $download['hits']      = $viewDownloads->getVar('hits');
        $download['rating']    = number_format($viewDownloads->getVar('rating'), 1);
        $download['votes']     = $viewDownloads->getVar('votes');

        if (true === $helper->getConfig('use_paypal') && '' !== $viewDownloads->getVar('paypal')) {
            $download['paypal'] = $viewDownloads->getVar('paypal');
        }
        $download['comments'] = $viewDownloads->getVar('comments');

        $GLOBALS['xoopsTpl']->assign('download', $download);

        // Utilisateur enregistré
        $ratings  = [];
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('lid', $downloads_lid));
        $criteria->add(new \Criteria('ratinguser', 0, '!='));
        $downloadsvotedata_arr = $ratingHandler->getAll($criteria);
        $total_vote            = count($downloadsvotedata_arr);
        $ratings['user_total'] = $total_vote;
        $user_list             = [];
        foreach (array_keys($downloadsvotedata_arr) as $i) {
            $user_list[] = [
                'ratinguser'      => \XoopsUser::getUnameFromId($downloadsvotedata_arr[$i]->getVar('ratinguser')),
                'ratinghostname'  => $downloadsvotedata_arr[$i]->getVar('ratinghostname'),
                'rating'          => $downloadsvotedata_arr[$i]->getVar('rating'),
                'ratingtimestamp' => formatTimestamp($downloadsvotedata_arr[$i]->getVar('ratingtimestamp')),
                'myTextForm'      => myTextForm('downloads.php?op=del_vote&lid=' . $downloadsvotedata_arr[$i]->getVar('lid') . '&rid=' . $downloadsvotedata_arr[$i]->getVar('ratingid'), 'X'),
            ];
        }
        $ratings['user_list'] = $user_list;
        // Utilisateur anonyme
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('lid', $downloads_lid));
        $criteria->add(new \Criteria('ratinguser', 0));
        $downloadsvotedata_arr = $ratingHandler->getAll($criteria);
        $total_vote            = count($downloadsvotedata_arr);
        $ratings['anon_total'] = $total_vote;
        $anon_list             = [];
        foreach (array_keys($downloadsvotedata_arr) as $i) {
            $anon_list[] = [
                'ratinghostname'  => $downloadsvotedata_arr[$i]->getVar('ratinghostname'),
                'rating'          => $downloadsvotedata_arr[$i]->getVar('rating'),
                'ratingtimestamp' => formatTimestamp($downloadsvotedata_arr[$i]->getVar('ratingtimestamp')),
                'myTextForm'      => myTextForm('downloads.php?op=del_vote&lid=' . $downloadsvotedata_arr[$i]->getVar('lid') . '&rid=' . $downloadsvotedata_arr[$i]->getVar('ratingid'), 'X'),
            ];
        }
        $ratings['anon_list']   = $anon_list;
        $ratings['votes_total'] = $ratings['user_total'] + $ratings['anon_total'];
        $GLOBALS['xoopsTpl']->assign('ratings', $ratings);
        $GLOBALS['xoopsTpl']->assign('download_detail', true);
        break;
    // permet de suprimmer un vote et de recalculer la note
    case 'del_vote':
        $objvotedata = $ratingHandler->get(\Xmf\Request::getInt('rid'));
        if ($ratingHandler->delete($objvotedata)) {
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('lid', \Xmf\Request::getInt('lid')));
            $downloadsvotedata_arr = $ratingHandler->getAll($criteria);
            $total_vote            = $ratingHandler->getCount($criteria);
            $obj                   = $downloadsHandler->get(\Xmf\Request::getInt('lid'));
            if (0 === $total_vote) {
                $obj->setVar('rating', number_format(0, 1));
                $obj->setVar('votes', 0);
                if ($downloadsHandler->insert($obj)) {
                    redirect_header('downloads.php?op=view_downloads&downloads_lid=' . \Xmf\Request::getInt('lid'), 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
                }
            } else {
                $total_rating = 0;
                foreach (array_keys($downloadsvotedata_arr) as $i) {
                    $total_rating += $downloadsvotedata_arr[$i]->getVar('rating');
                }
                $rating = $total_rating / $total_vote;
                $obj->setVar('rating', number_format($rating, 1));
                $obj->setVar('votes', $total_vote);
                if ($downloadsHandler->insert($obj)) {
                    redirect_header('downloads.php?op=view_downloads&downloads_lid=' . \Xmf\Request::getInt('lid'), 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
                }
            }
            $GLOBALS['xoopsTpl']->assign('error', $obj->getHtmlErrors());
        }
        $GLOBALS['xoopsTpl']->assign('error', $objvotedata->getHtmlErrors());
        break;
    // Pour sauver un téléchargement
    case 'save_downloads':
        global $xoopsDB;
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
        xoops_cp_header();
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('downloads.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (\Xmf\Request::hasVar('lid')) {
            $obj = $downloadsHandler->get(\Xmf\Request::getInt('lid'));
        } else {
            $obj = $downloadsHandler->create();
        }
        $erreur         = false;
        $message_erreur = '';
        $donnee         = [];
        $obj->setVar('title', \Xmf\Request::getString('title', '', 'POST'));
        $obj->setVar('cid', \Xmf\Request::getInt('cid', 0, 'POST'));
        $obj->setVar('homepage', formatURL(\Xmf\Request::getUrl('homepage', '', 'POST')));
        $obj->setVar('version', \Xmf\Request::getString('version', '', 'POST'));
        $obj->setVar('size', \Xmf\Request::getString('size', '', 'POST'));
        $donnee['type_size'] = \Xmf\Request::getString('type_size', '', 'POST');
        $obj->setVar('paypal', \Xmf\Request::getString('paypal', '', 'POST'));
        if (\Xmf\Request::hasVar('platform', 'POST')) {
            $obj->setVar('platform', implode('|', \Xmf\Request::getString('platform', '', 'POST')));
        }
        $obj->setVar('description', \Xmf\Request::getString('description', '', 'POST'));

        if (\Xmf\Request::hasVar('submitter', 'POST')) {
            $obj->setVar('submitter', \Xmf\Request::getInt('submitter', 0, 'POST'));
            $donnee['submitter'] = \Xmf\Request::getInt('submitter', 0, 'POST');
        } else {
            $obj->setVar('submitter', !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0);
            $donnee['submitter'] = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
        }
        if (\Xmf\Request::hasVar('downloads_modified')) {
            $obj->setVar('date', time());
            if (\Xmf\Request::hasVar('status', 'POST')) {
                $obj->setVar('status', 1);
                $donnee['status'] = 1;
            } else {
                $obj->setVar('status', 0);
                $donnee['status'] = 0;
            }
        } else {
            if ('Y' === $_POST['date_update']) {
                $obj->setVar('date', strtotime($_POST['date']));
                if (\Xmf\Request::hasVar('status', 'POST')) {
                    $obj->setVar('status', 2);
                    $donnee['status'] = 1;
                } else {
                    $obj->setVar('status', 0);
                    $donnee['status'] = 0;
                }
            } else {
                if (\Xmf\Request::hasVar('status', 'POST')) {
                    $obj->setVar('status', 1);
                    $donnee['status'] = 1;
                } else {
                    $obj->setVar('status', 0);
                    $donnee['status'] = 0;
                }
            }
            $donnee['date_update'] = $_POST['date_update'];
        }
        // erreur si la taille du fichier n'est pas un nombre
        if (0 === \Xmf\Request::getInt('size')) {
            if (0 == \Xmf\Request::getInt('size') || '' === \Xmf\Request::getString('size')) {
                $erreur = false;
            } else {
                $erreur         = true;
                $message_erreur .= _AM_TDMDOWNLOADS_ERREUR_SIZE . '<br>';
            }
        }
        // erreur si la description est vide
        if (\Xmf\Request::hasVar('description', 'POST')) {
            if ('' === \Xmf\Request::getString('description', '')) {
                $erreur         = true;
                $message_erreur .= _AM_TDMDOWNLOADS_ERREUR_NODESCRIPTION . '<br>';
            }
        }
        // erreur si la catégorie est vide
        if (\Xmf\Request::hasVar('cid', 'POST')) {
            if (0 == \Xmf\Request::getInt('cid', 0, 'POST')) {
                $erreur         = true;
                $message_erreur .= _AM_TDMDOWNLOADS_ERREUR_NOCAT . '<br>';
            }
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
        // enregistrement temporaire des tags
        if ((1 == $helper->getConfig('usetag')) && is_dir('../../tag')) {
            $donnee['TAG'] = $_POST['tag'];
        }

        if (1 == $erreur) {
            xoops_cp_header();
            $GLOBALS['xoopsTpl']->assign('error', $message_erreur);
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
                    $obj->setVar('url', \Xmf\Request::getUrl('url', '', 'POST'));
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
                    $obj->setVar('logourl', \Xmf\Request::getString('logo_img', '', 'POST'));
                }
            }
            // enregistrement
            if ($downloadsHandler->insert($obj)) {
                if (!\Xmf\Request::hasVar('downloads_modified')) {
                    $lidDownloads = $obj->getNewEnreg($db);
                } else {
                    $lidDownloads = \Xmf\Request::getInt('lid');
                }
                //tags
                if ((1 == $helper->getConfig('usetag')) && is_dir('../../tag')) {
                    /** @var \XoopsModules\Tag\TagHandler $tagHandler */
                    $tagHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Tag');
                    $tagHandler->updateByItem($_POST['tag'], $lidDownloads, $moduleDirName, 0);
                }
                // Récupération des champs supplémentaires:
                $criteria = new \CriteriaCompo();
                $criteria->setSort('weight ASC, title');
                $criteria->setOrder('ASC');
                $downloads_field = $fieldHandler->getAll($criteria);
                foreach (array_keys($downloads_field) as $i) {
                    if (0 == $downloads_field[$i]->getVar('status_def')) {
                        $iddata = 'iddata' . $downloads_field[$i]->getVar('fid');
                        if (\Xmf\Request::hasVar($iddata, 'POST')) {
                            if ('' === \Xmf\Request::getString($iddata, '')) {
                                $objdata = $fielddataHandler->create();
                            } else {
                                $objdata = $fielddataHandler->get(\Xmf\Request::getString($iddata, '', 'POST'));
                            }
                        } else {
                            $objdata = $fielddataHandler->create();
                        }
                        $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                        $objdata->setVar('data', \Xmf\Request::getString($nom_champ, '', 'POST'));
                        $objdata->setVar('lid', $lidDownloads);
                        $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));
                        $fielddataHandler->insert($objdata) || $objdata->getHtmlErrors();
                    }
                }
                //permission pour télécharger
                if (2 == $helper->getConfig('permission_download')) {
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
                // pour les notifications uniquement lors d'un nouveau téléchargement
                if (\Xmf\Request::hasVar('downloads_modified')) {
                    $tags                  = [];
                    $tags['FILE_NAME']     = \Xmf\Request::getString('title', '', 'POST');
                    $tags['FILE_URL']      = XOOPS_URL . '/modules/' . $moduleDirName . '/singlefile.php?cid=' . \Xmf\Request::getInt('cid', 0, 'POST') . '&amp;lid=' . $lidDownloads;
                    $downloadscat_cat      = $categoryHandler->get(\Xmf\Request::getInt('cid', 0, 'POST'));
                    $tags['CATEGORY_NAME'] = $downloadscat_cat->getVar('cat_title');
                    $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $moduleDirName . '/viewcat.php?cid=' . \Xmf\Request::getInt('cid', 0, 'POST');
                    /** @var \XoopsNotificationHandler $notificationHandler */
                    $notificationHandler = xoops_getHandler('notification');
                    $notificationHandler->triggerEvent('global', 0, 'new_file', $tags);
                    $notificationHandler->triggerEvent('category', \Xmf\Request::getInt('cid', 0, 'POST'), 'new_file', $tags);
                }
                redirect_header('downloads.php', 2, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
            }
            $GLOBALS['xoopsTpl']->assign('error', $obj->getHtmlErrors());
        }
        $form = $obj->getForm($donnee, true);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;
    // permet de valider un téléchargement proposé
    case 'update_status':
        $obj = $downloadsHandler->get(\Xmf\Request::getInt('downloads_lid'));
        $obj->setVar('status', 1);
        if ($downloadsHandler->insert($obj)) {
            redirect_header('downloads.php', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
        }
        $GLOBALS['xoopsTpl']->assign('error', $obj->getHtmlErrors());
        break;
    // permet de valider un téléchargement proposé
    case 'lock_status':
        $obj = $downloadsHandler->get(\Xmf\Request::getInt('downloads_lid'));
        $obj->setVar('status', 0);
        if ($downloadsHandler->insert($obj)) {
            redirect_header('downloads.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DEACTIVATED);
        }
        $GLOBALS['xoopsTpl']->assign('error', $obj->getHtmlErrors());
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
