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
use XoopsModules\Tdmdownloads\Tree;
use XoopsModules\Tdmdownloads;

require __DIR__ . '/admin_header.php';

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

$myts = \MyTextSanitizer::getInstance();

//On recupere la valeur de l'argument op dans l'URL$
$op = $utility->cleanVars($_REQUEST, 'op', 'list', 'string');

// compte le nombre de téléchargement non validé
$criteria = new \CriteriaCompo();
$criteria->add(new \Criteria('status', 0));
$downloads_waiting = $downloadsHandler->getCount($criteria);

$statut_menu = $utility->cleanVars($_REQUEST, 'statut_display', 1, 'int');

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'list':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        if (1 == $statut_menu) {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
            if ($downloads_waiting > 0) {
                $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', ' (<span style="color: #ff0000;">' . $downloads_waiting . '</span>)');
            }
        } else {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
        }
        $adminObject->displayButton('left');
        //nombre de téléchargement par page
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
        $start = $utility->cleanVars($_REQUEST, 'start', 0, 'int');
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        //pour faire une jointure de table
        $downloadsHandler->table_link   = $downloadsHandler->db->prefix('tdmdownloads_cat'); // Nom de la table en jointure
        $downloadsHandler->field_link   = 'cat_cid'; // champ de la table en jointure
        $downloadsHandler->field_object = 'cid'; // champ de la table courante
        $downloads_arr                  = $downloadsHandler->getByLink($criteria);
        $numrows                        = $downloadsHandler->getCount($criteria);
        if ($numrows > $limit) {
            $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', 'op=list&document_tri=' . $document_tri . '&document_order=' . $document_order . '&statut_display=' . $statut_display);
            $pagenav = $pagenav->renderNav(4);
        } else {
            $pagenav = '';
        }
        //Affichage du tableau des téléchargements
        if ($numrows > 0) {
            echo '<div align="right"><form id="form_document_tri" name="form_document_tri" method="get" action="document.php">';
            echo _AM_TDMDOWNLOADS_TRIPAR
                 . "<select name=\"document_tri\" id=\"document_tri\" onchange=\"location='"
                 . XOOPS_URL
                 . '/modules/'
                 . $xoopsModule->dirname()
                 . "/admin/downloads.php?statut_display=$statut_display&document_order=$document_order&document_tri='+this.options[this.selectedIndex].value\">";
            echo '<option value="1"' . (1 == $document_tri ? ' selected' : '') . '>' . _AM_TDMDOWNLOADS_FORMDATE . '</option>';
            echo '<option value="2"' . (2 == $document_tri ? ' selected' : '') . '>' . _AM_TDMDOWNLOADS_FORMTITLE . '</option>';
            echo '<option value="3"' . (3 == $document_tri ? ' selected' : '') . '>' . _AM_TDMDOWNLOADS_FORMHITS . '</option>';
            echo '<option value="4"' . (4 == $document_tri ? ' selected' : '') . '>' . _AM_TDMDOWNLOADS_FORMRATING . '</option>';
            echo '<option value="5"' . (5 == $document_tri ? ' selected' : '') . '>' . _AM_TDMDOWNLOADS_FORMCAT . '</option>';
            echo '</select> ';
            echo _AM_TDMDOWNLOADS_ORDER
                 . "<select name=\"order_tri\" id=\"order_tri\" onchange=\"location='"
                 . XOOPS_URL
                 . '/modules/'
                 . $xoopsModule->dirname()
                 . "/admin/downloads.php?statut_display=$statut_display&document_tri=$document_tri&document_order='+this.options[this.selectedIndex].value\">";
            echo '<option value="1"' . (1 == $document_order ? ' selected' : '') . '>DESC</option>';
            echo '<option value="2"' . (2 == $document_order ? ' selected' : '') . '>ASC</option>';
            echo '</select> ';
            echo '</form></div>';
            echo '<table width="100%" cellspacing="1" class="outer">';
            echo '<tr>';
            echo '<th align="center" width="5%">' . _AM_TDMDOWNLOADS_FORMFILE . '</th>';
            echo '<th align="left" width="20%">' . _AM_TDMDOWNLOADS_FORMTITLE . '</th>';
            echo '<th align="left">' . _AM_TDMDOWNLOADS_FORMCAT . '</th>';
            echo '<th align="center" width="5%">' . _AM_TDMDOWNLOADS_FORMHITS . '</th>';
            echo '<th align="center" width="5%">' . _AM_TDMDOWNLOADS_FORMRATING . '</th>';
            echo '<th align="center" width="15%">' . _AM_TDMDOWNLOADS_FORMACTION . '</th>';
            echo '</tr>';
            $mytree = new \XoopsModules\Tdmdownloads\Tree($category_arr, 'cat_cid', 'cat_pid');
            $class  = 'odd';
            foreach (array_keys($downloads_arr) as $i) {
                $class    = ('even' === $class) ? 'odd' : 'even';
                $category = $utility->getPathTree($mytree, $downloads_arr[$i]->getVar('cid'), $category_arr, 'cat_title', $prefix = ' <img src="../assets/images/deco/arrow.gif"> ');
                echo '<tr class="' . $class . '">';
                echo '<td align="center">';
                echo '<a href="../visit.php?cid=' . $downloads_arr[$i]->getVar('cid') . '&lid=' . $i . '" target="_blank"><img src="../assets/images/icon/download.png" alt="Download ' . $downloads_arr[$i]->getVar('title') . '" title="Download ' . $downloads_arr[$i]->getVar('title') . '"></a>';
                echo '</td>';
                echo '<td align="left"><a href="../singlefile.php?.php?cid=' . $downloads_arr[$i]->getVar('cid') . '&lid=' . $i . '" target="_blank">' . $downloads_arr[$i]->getVar('title') . '</a></td>';
                echo '<td align="left">' . $category . '</td>';
                echo '<td align="center">' . $downloads_arr[$i]->getVar('hits') . '</td>';
                echo '<td align="center">' . number_format($downloads_arr[$i]->getVar('rating'), 1) . '</td>';

                echo '<td align="center">';
                echo(1 == $statut_display ? '<a href="downloads.php?op=lock_status&downloads_lid='
                                            . $i
                                            . '"><img src="./../assets/images/icon/on.png" border="0" alt="'
                                            . _AM_TDMDOWNLOADS_FORMLOCK
                                            . '" title="'
                                            . _AM_TDMDOWNLOADS_FORMLOCK
                                            . '"></a> ' : '<a href="downloads.php?op=update_status&downloads_lid=' . $i . '"><img src="./../assets/images/icon/off.png" border="0" alt="' . _AM_TDMDOWNLOADS_FORMVALID . '" title="' . _AM_TDMDOWNLOADS_FORMVALID . '"></a> ');
                echo '<a href="downloads.php?op=view_downloads&downloads_lid=' . $i . '"><img src="../assets/images/icon/view_mini.png" alt="' . _AM_TDMDOWNLOADS_FORMDISPLAY . '" title="' . _AM_TDMDOWNLOADS_FORMDISPLAY . '"></a> ';
                echo '<a href="downloads.php?op=edit_downloads&downloads_lid=' . $i . '"><img src="../assets/images/icon/edit.png" alt="' . _AM_TDMDOWNLOADS_FORMEDIT . '" title="' . _AM_TDMDOWNLOADS_FORMEDIT . '"></a> ';
                echo '<a href="downloads.php?op=del_downloads&downloads_lid=' . $i . '"><img src="../assets/images/icon/delete.png" alt="' . _AM_TDMDOWNLOADS_FORMDEL . '" title="' . _AM_TDMDOWNLOADS_FORMDEL . '"></a>';
                echo '</td>';
            }
            echo '</table><br>';
            echo '<br><div align=right>' . $pagenav . '</div><br>';
        } else {
            echo '<div class="errorMsg" style="text-align: center;">' . _AM_TDMDOWNLOADS_ERREUR_NODOWNLOADS . '</div>';
        }

        break;
    // vue création
    case 'new_downloads':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
        if (0 == $downloads_waiting) {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
        } else {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
        }
        $adminObject->displayButton('left');
        //Affichage du formulaire de création des téléchargements
        $obj  = $downloadsHandler->create();
        $form = $obj->getForm($donnee = [], false);
        $form->display();
        break;
    // Pour éditer un téléchargement
    case 'edit_downloads':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
        if (0 == $downloads_waiting) {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
        } else {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
        }
        $adminObject->displayButton('left');
        //Affichage du formulaire de création des téléchargements
        $downloads_lid = $utility->cleanVars($_REQUEST, 'downloads_lid', 0, 'int');
        $obj           = $downloadsHandler->get($downloads_lid);
        $form          = $obj->getForm($donnee = [], false);
        $form->display();
        break;
    // Pour supprimer un téléchargement
    case 'del_downloads':
        global $xoopsModule;
        $downloads_lid = $utility->cleanVars($_REQUEST, 'downloads_lid', 0, 'int');
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
                    /** @var \XoopsModules\Tag\LinkHandler $tagHandler */
                    $tagHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Link');
                    $criteria   = new \CriteriaCompo();
                    $criteria->add(new \Criteria('tag_itemid', $downloads_lid));
                    $downloads_tags = $tagHandler->getAll($criteria);
                    foreach (array_keys($downloads_tags) as $i) {
                        $objtags = $tagHandler->get($downloads_tags[$i]->getVar('tl_id'));
                        $tagHandler->delete($objtags) || $objtags->getHtmlErrors();
                    }
                }
                redirect_header('downloads.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            $adminObject = \Xmf\Module\Admin::getInstance();
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
        $adminObject->displayNavigation(basename(__FILE__));
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
        if (0 == $downloads_waiting) {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
        } else {
            $adminObject->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
        }
        $adminObject->displayButton('left');
        $downloads_lid = $utility->cleanVars($_REQUEST, 'downloads_lid', 0, 'int');
        //information du téléchargement
        $viewDownloads = $downloadsHandler->get($downloads_lid);
        //catégorie
        //$view_categorie = $categoryHandler->get($viewDownloads->getVar('cid'));
        $category_arr = $categoryHandler->getAll();
        $mytree       = new \XoopsModules\Tdmdownloads\Tree($category_arr, 'cat_cid', 'cat_pid');
        // sortie des informations
        $downloads_title       = $viewDownloads->getVar('title');
        $downloads_description = $viewDownloads->getVar('description');
        //permet d'enlever [pagebreak] du texte
        $downloads_description = str_replace('[pagebreak]', '', $downloads_description);

        $category = $utility->getPathTree($mytree, $viewDownloads->getVar('cid'), $category_arr, 'cat_title', $prefix = ' <img src="../assets/images/deco/arrow.gif"> ');
        // affichages des informations du téléchargement
        echo '<table width="100%" cellspacing="1" class="outer">';
        echo '<tr>';
        echo '<th align="center" colspan="2">' . $downloads_title . '</th>';
        echo '</tr>';
        echo '<tr class="even">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMFILE . ' </td>';
        echo '<td><a href="../visit.php?cid=' . $viewDownloads->getVar('cid') . '&lid=' . $downloads_lid . '"><img src="../assets/images/icon/download.png" alt="Download ' . $downloads_title . '" title="Download ' . $downloads_title . '"></a></td>';
        echo '</tr>';
        echo '<tr class="odd">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMCAT . ' </td>';
        echo '<td>' . $category . '</td>';
        echo '</tr>';
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new \Criteria('status', 1));
        $downloads_field = $fieldHandler->getAll($criteria);
        $class           = 'odd';
        foreach (array_keys($downloads_field) as $i) {
            if (1 == $downloads_field[$i]->getVar('status_def')) {
                if (1 == $downloads_field[$i]->getVar('fid')) {
                    //page d'accueil
                    if ('' !== $viewDownloads->getVar('homepage')) {
                        $class = ('even' === $class) ? 'odd' : 'even';
                        echo '<tr class="' . $class . '">';
                        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMHOMEPAGE . ' </td>';
                        echo '<td><a href="' . $viewDownloads->getVar('homepage') . '">' . $viewDownloads->getVar('homepage') . '</a></td>';
                    }
                }
                if (2 == $downloads_field[$i]->getVar('fid')) {
                    //version
                    if ('' !== $viewDownloads->getVar('version')) {
                        $class = ('even' === $class) ? 'odd' : 'even';
                        echo '<tr class="' . $class . '">';
                        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMVERSION . ' </td>';
                        echo '<td>' . $viewDownloads->getVar('version') . '</td></tr>';
                    }
                }
                if (3 == $downloads_field[$i]->getVar('fid')) {
                    //taille du fichier
                    if ('' !== $viewDownloads->getVar('size')) {
                        $class = ('even' === $class) ? 'odd' : 'even';
                        echo '<tr class="' . $class . '">';
                        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMSIZE . '</td>';
                        echo '<td>' . $viewDownloads->getVar('size') . '</td></tr>';
                    }
                }
                if (4 == $downloads_field[$i]->getVar('fid')) {
                    //plateforme
                    if ('' !== $viewDownloads->getVar('platform')) {
                        $class = ('even' === $class) ? 'odd' : 'even';
                        echo '<tr class="' . $class . '">';
                        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMPLATFORM . ' </td>';
                        echo '<td>' . $viewDownloads->getVar('platform') . '</td></tr>';
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
                    $class = ('even' === $class) ? 'odd' : 'even';
                    echo '<tr class="' . $class . '">';
                    echo '<td width="30%">' . $downloads_field[$i]->getVar('title') . ' </td>';
                    echo '<td>' . $contenu . '</td></tr>';
                }
            }
        }
        $class = ('even' === $class) ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMTEXT . ' </td>';
        echo '<td>' . $downloads_description . '</td>';
        echo '</tr>';
        // tags
        if ((1 == $helper->getConfig('usetag')) && is_dir('../../tag')) {
            require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
            $tags_array = tagBar($downloads_lid, 0);
            if (!empty($tags_array)) {
                $tags = '';
                foreach (array_keys($tags_array['tags']) as $i) {
                    $tags .= $tags_array['delimiter'] . ' ' . $tags_array['tags'][$i] . ' ';
                }

                $class = ('even' === $class) ? 'odd' : 'even';
                echo '<tr class="' . $class . '">';
                echo '<td width="30%">' . $tags_array['title'] . ' </td>';
                echo '<td>' . $tags . '</td>';
                echo '</tr>';
            }
        }
        if ($helper->getConfig('useshots')) {
            if ('blank.gif' !== $viewDownloads->getVar('logourl')) {
                $class = ('even' === $class) ? 'odd' : 'even';
                echo '<tr class="' . $class . '">';
                echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMIMG . ' </td>';
                echo '<td><img src="' . $uploadurl_shots . $viewDownloads->getVar('logourl') . '" alt="" title=""></td>';
                echo '</tr>';
            }
        }
        $class = ('even' === $class) ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMDATE . ' </td>';
        echo '<td>' . formatTimestamp($viewDownloads->getVar('date')) . '</td>';
        echo '</tr>';
        $class = ('even' === $class) ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMPOSTER . ' </td>';
        echo '<td>' . \XoopsUser::getUnameFromId($viewDownloads->getVar('submitter')) . '</td>';
        echo '</tr>';
        $class = ('even' === $class) ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMHITS . ' </td>';
        echo '<td>' . $viewDownloads->getVar('hits') . '</td>';
        echo '</tr>';
        $class = ('even' === $class) ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMRATING . ' </td>';
        echo '<td>' . number_format($viewDownloads->getVar('rating'), 1) . ' (' . $viewDownloads->getVar('votes') . ' ' . _AM_TDMDOWNLOADS_FORMVOTE . ')</td>';
        echo '</tr>';
        if (true === $helper->getConfig('use_paypal') && '' !== $viewDownloads->getVar('paypal')) {
            $class = ('even' === $class) ? 'odd' : 'even';
            echo '<tr class="' . $class . '">';
            echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMPAYPAL . ' </td>';
            echo '<td>' . $viewDownloads->getVar('paypal') . '</td>';
            echo '</tr>';
        }
        $class = ('even' === $class) ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMCOMMENTS . ' </td>';
        echo '<td>'
             . $viewDownloads->getVar('comments')
             . ' <a href="../singlefile.php?cid='
             . $viewDownloads->getVar('cid')
             . '&lid='
             . $downloads_lid
             . '"><img src="../assets/images/icon/view_mini.png" alt="'
             . _AM_TDMDOWNLOADS_FORMDISPLAY
             . '" title="'
             . _AM_TDMDOWNLOADS_FORMDISPLAY
             . '"></a></td>';
        echo '</tr>';
        $class = ('even' === $class) ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMACTION . ' </td>';
        echo '<td>';
        echo(0 !== $viewDownloads->getVar('status') ? '' : '<a href="downloads.php?op=update_status&downloads_lid=' . $downloads_lid . '"><img src="./../assets/images/icon/off.png" border="0" alt="' . _AM_TDMDOWNLOADS_FORMVALID . '" title="' . _AM_TDMDOWNLOADS_FORMVALID . '"></a> ');
        echo '<a href="downloads.php?op=edit_downloads&downloads_lid=' . $downloads_lid . '"><img src="../assets/images/icon/edit.png" alt="' . _AM_TDMDOWNLOADS_FORMEDIT . '" title="' . _AM_TDMDOWNLOADS_FORMEDIT . '"></a> <a href="downloads.php?op=del_downloads&downloads_lid=' . $downloads_lid . '">
        <img src="../assets/images/icon/delete.png" alt="' . _AM_TDMDOWNLOADS_FORMDEL . '" title="' . _AM_TDMDOWNLOADS_FORMDEL . '"></a></td>';
        echo '</tr>';
        echo '</table>';
        echo '<br>';
        // Affichage des votes:

        // Utilisateur enregistré
        echo '<hr>';
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('lid', $downloads_lid));
        $criteria->add(new \Criteria('ratinguser', 0, '!='));
        $downloadsvotedata_arr = $ratingHandler->getAll($criteria);
        $total_vote            = count($downloadsvotedata_arr);
        echo '<table width="100%">';
        echo '<tr><td colspan="5"><b>';
        printf(_AM_TDMDOWNLOADS_DOWNLOADS_VOTESUSER, $total_vote);
        echo '</b><br><br></td></tr>';
        echo '<tr><td><b>'
             . _AM_TDMDOWNLOADS_DOWNLOADS_VOTE_USER
             . '</b></td>'
             . '<td><b>'
             . _AM_TDMDOWNLOADS_DOWNLOADS_VOTE_IP
             . '</b></td>'
             . '<td align="center"><b>'
             . _AM_TDMDOWNLOADS_FORMRATING
             . '</b></td>'
             . '<td><b>'
             . _AM_TDMDOWNLOADS_FORMDATE
             . '</b></td>'
             . '<td align="center"><b>'
             . _AM_TDMDOWNLOADS_FORMDEL
             . '</b></td></tr>';
        foreach (array_keys($downloadsvotedata_arr) as $i) {
            echo '<tr>';
            echo '<td>' . \XoopsUser::getUnameFromId($downloadsvotedata_arr[$i]->getVar('ratinguser')) . '</td>';
            echo '<td>' . $downloadsvotedata_arr[$i]->getVar('ratinghostname') . '</td>';
            echo '<td align="center">' . $downloadsvotedata_arr[$i]->getVar('rating') . '</td>';
            echo '<td>' . formatTimestamp($downloadsvotedata_arr[$i]->getVar('ratingtimestamp')) . '</td>';
            echo '<td align="center">';
            echo myTextForm('downloads.php?op=del_vote&lid=' . $downloadsvotedata_arr[$i]->getVar('lid') . '&rid=' . $downloadsvotedata_arr[$i]->getVar('ratingid'), 'X');
            echo '</td>';
            echo '</tr>';
        }
        // Utilisateur anonyme
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('lid', $downloads_lid));
        $criteria->add(new \Criteria('ratinguser', 0));
        $downloadsvotedata_arr = $ratingHandler->getAll($criteria);
        $total_vote            = count($downloadsvotedata_arr);
        echo '<tr><td colspan="5"><br><b>';
        printf(_AM_TDMDOWNLOADS_DOWNLOADS_VOTESANONYME, $total_vote);
        echo '</b><br><br></td></tr>';
        echo '<tr><td colspan="2"><b>' . _AM_TDMDOWNLOADS_DOWNLOADS_VOTE_IP . '</b></td>' . '<td align="center"><b>' . _AM_TDMDOWNLOADS_FORMRATING . '</b></td>' . '<td><b>' . _AM_TDMDOWNLOADS_FORMDATE . '</b></td>' . '<td align="center"><b>' . _AM_TDMDOWNLOADS_FORMDEL . '</b></td></tr>';
        foreach (array_keys($downloadsvotedata_arr) as $i) {
            echo '<tr>';
            echo '<td colspan="2">' . $downloadsvotedata_arr[$i]->getVar('ratinghostname') . '</td>';
            echo '<td align="center">' . $downloadsvotedata_arr[$i]->getVar('rating') . '</td>';
            echo '<td>' . formatTimestamp($downloadsvotedata_arr[$i]->getVar('ratingtimestamp')) . '</td>';
            echo '<td align="center">';
            echo myTextForm('downloads.php?op=del_vote&lid=' . $downloadsvotedata_arr[$i]->getVar('lid') . '&rid=' . $downloadsvotedata_arr[$i]->getVar('ratingid'), 'X');
            echo '</tr>';
        }
        echo '</table>';
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
            echo $obj->getHtmlErrors();
        }
        echo $objvotedata->getHtmlErrors();
        break;
    // Pour sauver un téléchargement
    case 'save_downloads':
        global $xoopsDB;
        require_once XOOPS_ROOT_PATH . '/class/uploader.php';
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
        $obj->setVar('title', $_POST['title']);
        $obj->setVar('cid', $_POST['cid']);
        $obj->setVar('homepage', formatURL($_POST['homepage']));
        $obj->setVar('version', $_POST['version']);
        $obj->setVar('size', $_POST['size']);
        $donnee['type_size'] = $_POST['type_size'];
        $obj->setVar('paypal', $_POST['paypal']);
        if (\Xmf\Request::hasVar('platform', 'POST')) {
            $obj->setVar('platform', implode('|', $_POST['platform']));
        }
        $obj->setVar('description', $_POST['description']);

        if (\Xmf\Request::hasVar('submitter', 'POST')) {
            $obj->setVar('submitter', $_POST['submitter']);
            $donnee['submitter'] = $_POST['submitter'];
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
        if (\Xmf\Request::hasVar('description', 'REQUEST')) {
            if ('' === $_REQUEST['description']) {
                $erreur         = true;
                $message_erreur .= _AM_TDMDOWNLOADS_ERREUR_NODESCRIPTION . '<br>';
            }
        }
        // erreur si la catégorie est vide
        if (\Xmf\Request::hasVar('cid')) {
            if (0 == \Xmf\Request::getInt('cid')) {
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
                $donnee[$nom_champ] = $_POST[$nom_champ];
            }
        }
        // enregistrement temporaire des tags
        if ((1 == $helper->getConfig('usetag')) && is_dir('../../tag')) {
            $donnee['TAG'] = $_POST['tag'];
        }

        if (1 == $erreur) {
            xoops_cp_header();
            echo '<div class="errorMsg" style="text-align: left;">' . $message_erreur . '</div>';
        } else {
            $obj->setVar('size', $_POST['size'] . ' ' . $_POST['type_size']);
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
                        if (isset($_REQUEST[$iddata])) {
                            if ('' === $_REQUEST[$iddata]) {
                                $objdata = $fielddataHandler->create();
                            } else {
                                $objdata = $fielddataHandler->get($_REQUEST[$iddata]);
                            }
                        } else {
                            $objdata = $fielddataHandler->create();
                        }
                        $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                        $objdata->setVar('data', $_POST[$nom_champ]);
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
                    $tags['FILE_NAME']     = $_POST['title'];
                    $tags['FILE_URL']      = XOOPS_URL . '/modules/' . $moduleDirName . '/singlefile.php?cid=' . $_POST['cid'] . '&amp;lid=' . $lidDownloads;
                    $downloadscat_cat      = $categoryHandler->get($_POST['cid']);
                    $tags['CATEGORY_NAME'] = $downloadscat_cat->getVar('cat_title');
                    $tags['CATEGORY_URL']  = XOOPS_URL . '/modules/' . $moduleDirName . '/viewcat.php?cid=' . $_POST['cid'];
                    /** @var \XoopsNotificationHandler $notificationHandler */
                    $notificationHandler = xoops_getHandler('notification');
                    $notificationHandler->triggerEvent('global', 0, 'new_file', $tags);
                    $notificationHandler->triggerEvent('category', $_POST['cid'], 'new_file', $tags);
                }
                redirect_header('downloads.php', 2, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
            }
            echo $obj->getHtmlErrors();
        }
        $form = $obj->getForm($donnee, true);
        $form->display();
        break;
    // permet de valider un téléchargement proposé
    case 'update_status':
        $obj = $downloadsHandler->get(\Xmf\Request::getInt('downloads_lid'));
        $obj->setVar('status', 1);
        if ($downloadsHandler->insert($obj)) {
            redirect_header('downloads.php', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
        }
        echo $obj->getHtmlErrors();
        break;
    // permet de valider un téléchargement proposé
    case 'lock_status':
        $obj = $downloadsHandler->get(\Xmf\Request::getInt('downloads_lid'));
        $obj->setVar('status', 0);
        if ($downloadsHandler->insert($obj)) {
            redirect_header('downloads.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DEACTIVATED);
        }
        echo $obj->getHtmlErrors();
        break;
}
//Affichage de la partie basse de l'administration de Xoops
require_once __DIR__ . '/admin_footer.php';
