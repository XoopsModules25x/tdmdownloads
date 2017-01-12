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

include 'admin_header.php';

//On recupere la valeur de l'argument op dans l'URL$
$op = TDMDownloads_CleanVars($_REQUEST, 'op', 'list', 'string');

// compte le nombre de téléchargement non validé
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', 0));
$downloads_waiting = $downloads_Handler->getCount($criteria);

$statut_menu = TDMDownloads_CleanVars($_REQUEST, 'statut_display', 1, 'int');

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case "list":
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMDownloads_checkModuleAdmin()) {
            $downloads_admin = new ModuleAdmin();
            echo $downloads_admin->addNavigation('downloads.php');
            if ($statut_menu == 1) {
                $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
                if ($downloads_waiting == 0) {
                    $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
                } else {
                    $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
                }
            } else {
                $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
                $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
            }
            echo $downloads_admin->renderButton();
        }
        //nombre de téléchargement par page
        $limit = $xoopsModuleConfig['perpageadmin'];
        $category_arr = $downloadscat_Handler->getall();
        $numrowscat = count($category_arr);
        // redirection si il n'y a pas de catégories
        if ($numrowscat==0) {
            redirect_header('category.php?op=new_cat', 2, _AM_TDMDOWNLOADS_REDIRECT_NOCAT);
        }
        $criteria = new CriteriaCompo();
        // affiche uniquement les téléchargements activés
        if (isset($_REQUEST['statut_display'])) {
            if ($_REQUEST['statut_display']==0) {
                $criteria->add(new Criteria('status', 0));
                $statut_display = 0;
            } else {
                $criteria->add(new Criteria('status', 0, '!='));
                $statut_display = 1;
            }
        } else {
            $criteria->add(new Criteria('status', 0, '!='));
            $statut_display = 1;
        }
        $document_tri = 1;
        $document_order = 1;
        if (isset($_REQUEST['document_tri'])) {
            if ($_REQUEST['document_tri']==1) {
                $criteria->setSort('date');
                $document_tri=1;
            }
            if ($_REQUEST['document_tri']==2) {
                $criteria->setSort('title');
                $document_tri=2;
            }
            if ($_REQUEST['document_tri']==3) {
                $criteria->setSort('hits');
                $document_tri=3;
            }
            if ($_REQUEST['document_tri']==4) {
                $criteria->setSort('rating');
                $document_tri=4;
            }
            if ($_REQUEST['document_tri']==5) {
                $criteria->setSort('cid');
                $document_tri=5;
            }
        } else {
            $criteria->setSort('date');
         }
        if (isset($_REQUEST['document_order'])) {
            if ($_REQUEST['document_order']==1) {
                $criteria->setOrder('DESC');
                $document_order=1;
            }
            if ($_REQUEST['document_order']==2) {
                $criteria->setOrder('ASC');
                $document_order=2;
            }
        } else {
            $criteria->setOrder('DESC');
         }
        $start = TDMDownloads_CleanVars ( $_REQUEST, 'start', 0, 'int' );
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        //pour faire une jointure de table
        $downloads_Handler->table_link = $downloads_Handler->db->prefix("tdmdownloads_cat"); // Nom de la table en jointure
        $downloads_Handler->field_link = "cat_cid"; // champ de la table en jointure
        $downloads_Handler->field_object = "cid"; // champ de la table courante
        $downloads_arr = $downloads_Handler->getByLink($criteria);
        $numrows = $downloads_Handler->getCount($criteria);
        if ($numrows > $limit) {
            $pagenav = new XoopsPageNav($numrows, $limit, $start, 'start', 'op=list&document_tri=' . $document_tri. '&document_order=' . $document_order . '&statut_display=' . $statut_display);
             $pagenav = $pagenav->renderNav(4);
         } else {
             $pagenav = '';
         }
        //Affichage du tableau des téléchargements
        if ($numrows>0) {
            echo '<div align="right"><form id="form_document_tri" name="form_document_tri" method="get" action="document.php">';
            echo _AM_TDMDOWNLOADS_TRIPAR . "<select name=\"document_tri\" id=\"document_tri\" onchange=\"location='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/admin/downloads.php?statut_display=$statut_display&document_order=$document_order&document_tri='+this.options[this.selectedIndex].value\">";
            echo '<option value="1"' . ($document_tri == 1 ? ' selected="selected"' : '') . '>' . _AM_TDMDOWNLOADS_FORMDATE . '</option>';
            echo '<option value="2"' . ($document_tri == 2 ? ' selected="selected"' : '') . '>' . _AM_TDMDOWNLOADS_FORMTITLE . '</option>';
            echo '<option value="3"' . ($document_tri == 3 ? ' selected="selected"' : '') . '>' . _AM_TDMDOWNLOADS_FORMHITS . '</option>';
            echo '<option value="4"' . ($document_tri == 4 ? ' selected="selected"' : '') . '>' . _AM_TDMDOWNLOADS_FORMRATING . '</option>';
            echo '<option value="5"' . ($document_tri == 5 ? ' selected="selected"' : '') . '>' . _AM_TDMDOWNLOADS_FORMCAT . '</option>';
            echo '</select> ';
            echo _AM_TDMDOWNLOADS_ORDER . "<select name=\"order_tri\" id=\"order_tri\" onchange=\"location='".XOOPS_URL."/modules/".$xoopsModule->dirname()."/admin/downloads.php?statut_display=$statut_display&document_tri=$document_tri&document_order='+this.options[this.selectedIndex].value\">";
            echo '<option value="1"' . ($document_order == 1 ? ' selected="selected"' : '') . '>DESC</option>';
            echo '<option value="2"' . ($document_order == 2 ? ' selected="selected"' : '') . '>ASC</option>';
            echo '</select> ';
            echo '</form></div>';
            echo '<table width="100%" cellspacing="1" class="outer">';
            echo '<tr>';
            echo '<th align="center" width="5%">'._AM_TDMDOWNLOADS_FORMFILE.'</th>';
            echo '<th align="left" width="20%">' . _AM_TDMDOWNLOADS_FORMTITLE . '</th>';
            echo '<th align="left">' . _AM_TDMDOWNLOADS_FORMCAT . '</th>';
            echo '<th align="center" width="5%">' . _AM_TDMDOWNLOADS_FORMHITS . '</th>';
            echo '<th align="center" width="5%">' . _AM_TDMDOWNLOADS_FORMRATING . '</th>';
            echo '<th align="center" width="15%">'._AM_TDMDOWNLOADS_FORMACTION.'</th>';
            echo '</tr>';
            $mytree = new XoopsObjectTree($category_arr, 'cat_cid', 'cat_pid');
            $class = 'odd';
            foreach (array_keys($downloads_arr) as $i) {
                $class = ($class == 'even') ? 'odd' : 'even';
                $category = TDMDownloads_PathTree($mytree, $downloads_arr[$i]->getVar('cid'), $category_arr, 'cat_title', $prefix = ' <img src="../images/deco/arrow.gif"> ' );
                echo '<tr class="'.$class.'">';
                echo '<td align="center">';
                echo '<a href="../visit.php?cid=' . $downloads_arr[$i]->getVar('cid') . '&lid=' . $i . '" target="_blank"><img src="../images/icon/download.png" alt="Download ' . $downloads_arr[$i]->getVar('title') . '" title="Download ' . $downloads_arr[$i]->getVar('title') . '"></a>';
                echo '</td>';
                echo '<td align="left">' . $downloads_arr[$i]->getVar('title') . '</td>';
                echo '<td align="left">' . $category . '</td>';
                echo '<td align="center">' . $downloads_arr[$i]->getVar('hits') . '</td>';
                echo '<td align="center">' . number_format($downloads_arr[$i]->getVar('rating'), 1) . '</td>';

                echo '<td align="center">';
                echo ($statut_display == 1 ? '<a href="downloads.php?op=lock_status&downloads_lid=' . $i . '"><img src="./../images/icon/on.png" border="0" alt="' . _AM_TDMDOWNLOADS_FORMLOCK . '" title="' . _AM_TDMDOWNLOADS_FORMLOCK . '"></a> ' : '<a href="downloads.php?op=update_status&downloads_lid=' . $i . '"><img src="./../images/icon/off.png" border="0" alt="' . _AM_TDMDOWNLOADS_FORMVALID . '" title="' . _AM_TDMDOWNLOADS_FORMVALID . '"></a> ');
                echo '<a href="downloads.php?op=view_downloads&downloads_lid=' . $i . '"><img src="../images/icon/view_mini.png" alt="' . _AM_TDMDOWNLOADS_FORMDISPLAY.'" title="' . _AM_TDMDOWNLOADS_FORMDISPLAY . '"></a> ';
                echo '<a href="downloads.php?op=edit_downloads&downloads_lid=' . $i . '"><img src="../images/icon/edit.png" alt="' . _AM_TDMDOWNLOADS_FORMEDIT.'" title="' . _AM_TDMDOWNLOADS_FORMEDIT . '"></a> ';
                echo '<a href="downloads.php?op=del_downloads&downloads_lid=' . $i . '"><img src="../images/icon/delete.png" alt="' . _AM_TDMDOWNLOADS_FORMDEL.'" title="' . _AM_TDMDOWNLOADS_FORMDEL . '"></a>';
                echo '</td>';
             }
             echo '</table><br />';
             echo '<br /><div align=right>' . $pagenav . '</div><br />';
        } else {
            echo '<div class="errorMsg" style="text-align: center;">' . _AM_TDMDOWNLOADS_ERREUR_NODOWNLOADS . '</div>';
        }

    break;

    // vue création
    case "new_downloads":
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMDownloads_checkModuleAdmin()) {
            $downloads_admin = new ModuleAdmin();
            echo $downloads_admin->addNavigation('downloads.php');
            $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
            if ($downloads_waiting == 0) {
                $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
            } else {
                $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
            }
            echo $downloads_admin->renderButton();
        }
        //Affichage du formulaire de création des téléchargements
        $obj = $downloads_Handler->create();
        $form = $obj->getForm($donnee = array(), false);
        $form->display();
    break;

    // Pour éditer un téléchargement
    case "edit_downloads":
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMDownloads_checkModuleAdmin()) {
            $downloads_admin = new ModuleAdmin();
            echo $downloads_admin->addNavigation('downloads.php');
            $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
            $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
            if ($downloads_waiting == 0) {
                $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
            } else {
                $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
            }
            echo $downloads_admin->renderButton();
        }
        //Affichage du formulaire de création des téléchargements
        $downloads_lid = TDMDownloads_CleanVars($_REQUEST, 'downloads_lid', 0, 'int');
        $obj = $downloads_Handler->get($downloads_lid);
        $form = $obj->getForm($donnee = array(), false);
        $form->display();
    break;

    // Pour supprimer un téléchargement
    case "del_downloads":
        global $xoopsModule;
        $downloads_lid = TDMDownloads_CleanVars($_REQUEST, 'downloads_lid', 0, 'int');
        $obj = $downloads_Handler->get($downloads_lid);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('downloads.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            // permet d'extraire le nom du fichier
            $urlfile = substr_replace($obj->getVar('url'),'',0,strlen($uploadurl_downloads));
            if ($downloads_Handler->delete($obj)) {
                // permet de donner le chemin du fichier
                $urlfile = $uploaddir_downloads . $urlfile;
                // si le fichier est sur le serveur il es détruit
                if (is_file($urlfile)) {
                    chmod($urlfile, 0777);
                    unlink($urlfile);
                }
                // supression des votes
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $downloads_lid));
                $downloads_votedata = $downloadsvotedata_Handler->getall( $criteria );
                foreach (array_keys($downloads_votedata) as $i) {
                    $objvotedata = $downloadsvotedata_Handler->get($downloads_votedata[$i]->getVar('ratingid'));
                    $downloadsvotedata_Handler->delete($objvotedata) or $objvotedata->getHtmlErrors();
                }
                // supression des rapports de fichier brisé
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $downloads_lid));
                $downloads_broken = $downloadsbroken_Handler->getall( $criteria );
                foreach (array_keys($downloads_broken) as $i) {
                    $objbroken = $downloadsbroken_Handler->get($downloads_broken[$i]->getVar('reportid'));
                    $downloadsbroken_Handler->delete($objbroken ) or $objbroken ->getHtmlErrors();
                }
                // supression des data des champs sup.
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $downloads_lid));
                $downloads_fielddata = $downloadsfielddata_Handler->getall( $criteria );
                foreach (array_keys($downloads_fielddata) as $i) {
                    $objfielddata = $downloadsfielddata_Handler->get($downloads_fielddata[$i]->getVar('iddata'));
                    $downloadsfielddata_Handler->delete($objfielddata) or $objvfielddata->getHtmlErrors();
                }
                // supression des commentaires
                xoops_comment_delete($xoopsModule->getVar('mid'), $downloads_lid);
                //supression des tags
                if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../../tag'))) {
                    $tag_handler = xoops_getmodulehandler('link', 'tag');
                    $criteria = new CriteriaCompo();
                    $criteria->add(new Criteria('tag_itemid', $downloads_lid));
                    $downloads_tags = $tag_handler->getall( $criteria );
                    foreach (array_keys($downloads_tags) as $i) {
                        $objtags = $tag_handler->get($downloads_tags[$i]->getVar('tl_id'));
                        $tag_handler->delete($objtags) or $objtags->getHtmlErrors();
                    }
                }
                redirect_header('downloads.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            if (TDMDownloads_checkModuleAdmin()) {
                $downloads_admin = new ModuleAdmin();
                $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
                $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
                if ($downloads_waiting == 0) {
                    $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
                } else {
                    $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
                }
                echo $downloads_admin->renderButton();
            }
            xoops_confirm(array('ok' => 1, 'downloads_lid' => $downloads_lid, 'op' => 'del_downloads'), $_SERVER['REQUEST_URI'], sprintf(_AM_TDMDOWNLOADS_FORMSUREDEL, $obj->getVar('title')) . '<br><br>' . _AM_TDMDOWNLOADS_FORMWITHFILE . ' <b><a href="' . $obj->getVar('url') . '">' . $obj->getVar('url') . '</a></b><br>');
        }
    break;

    // Pour voir les détails du téléchargement
    case "view_downloads":
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMDownloads_checkModuleAdmin()) {
            $downloads_admin = new ModuleAdmin();
            echo $downloads_admin->addNavigation('downloads.php');
            $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_LISTE, 'downloads.php?op=list', 'list');
            $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_NEW, 'downloads.php?op=new_downloads', 'add');
            if ($downloads_waiting == 0) {
                $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add');
            } else {
                $downloads_admin->addItemButton(_AM_TDMDOWNLOADS_DOWNLOADS_WAIT, 'downloads.php?op=list&statut_display=0', 'add', 'style="color : Red"');
            }
            echo $downloads_admin->renderButton();
        }
        $downloads_lid = TDMDownloads_CleanVars($_REQUEST, 'downloads_lid', 0, 'int');
        //information du téléchargement
        $view_downloads = $downloads_Handler->get($downloads_lid);
        //catégorie
        //$view_categorie = $downloadscat_Handler->get($view_downloads->getVar('cid'));
        $category_arr = $downloadscat_Handler->getall();
        $mytree = new XoopsObjectTree($category_arr, 'cat_cid', 'cat_pid');
        // sortie des informations
        $downloads_title = $view_downloads->getVar('title');
        $downloads_description = $view_downloads->getVar('description');
        //permet d'enlever [pagebreak] du texte
        $downloads_description = str_replace('[pagebreak]','',$downloads_description);

        $category = TDMDownloads_PathTree($mytree, $view_downloads->getVar('cid'), $category_arr, 'cat_title', $prefix = ' <img src="../images/deco/arrow.gif"> ' );
        // affichages des informations du téléchargement
        echo '<table width="100%" cellspacing="1" class="outer">';
        echo '<tr>';
        echo '<th align="center" colspan="2">' . $downloads_title . '</th>';
        echo '</tr>';
        echo '<tr class="even">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMFILE . ' </td>';
        echo '<td><a href="../visit.php?cid=' . $view_downloads->getVar('cid') . '&lid=' . $downloads_lid . '"><img src="../images/icon/download.png" alt="Download ' . $downloads_title . '" title="Download ' . $downloads_title . '"></a></td>';
        echo '</tr>';
        echo '<tr class="odd">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMCAT . ' </td>';
        echo '<td>' . $category . '</td>';
        echo '</tr>';
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new Criteria('status', 1));
        $downloads_field = $downloadsfield_Handler->getall($criteria);
        $class = 'odd';
        foreach (array_keys($downloads_field) as $i) {

            if ($downloads_field[$i]->getVar('status_def') == 1) {
                if ($downloads_field[$i]->getVar('fid') == 1) {
                    //page d'accueil
                    if ($view_downloads->getVar('homepage') !='') {
                        $class = ($class == 'even') ? 'odd' : 'even';
                        echo '<tr class="' . $class . '">';
                        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMHOMEPAGE . ' </td>';
                        echo '<td><a href="' . $view_downloads->getVar('homepage') . '">' . $view_downloads->getVar('homepage') . '</a></td>';
                    }
                }
                if ($downloads_field[$i]->getVar('fid') == 2) {
                    //version
                    if ($view_downloads->getVar('version') !='') {
                        $class = ($class == 'even') ? 'odd' : 'even';
                        echo '<tr class="' . $class . '">';
                        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMVERSION . ' </td>';
                        echo '<td>' . $view_downloads->getVar('version') . '</td></tr>';
                    }
                }
                if ($downloads_field[$i]->getVar('fid') == 3) {
                    //taille du fichier
                    if ($view_downloads->getVar('size') !='') {
                        $class = ($class == 'even') ? 'odd' : 'even';
                        echo '<tr class="' . $class . '">';
                        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMSIZE . '</td>';
                        echo '<td>' . $view_downloads->getVar('size') . '</td></tr>';
                    }
                }
                if ($downloads_field[$i]->getVar('fid') == 4) {
                    //plateforme
                    if ($view_downloads->getVar('platform') !='') {
                        $class = ($class == 'even') ? 'odd' : 'even';
                        echo '<tr class="' . $class . '">';
                        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMPLATFORM . ' </td>';
                        echo '<td>' . $view_downloads->getVar('platform') . '</td></tr>';
                    }
                }
            } else {
                $contenu = '';
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $downloads_lid));
                $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfielddata = $downloadsfielddata_Handler->getall($criteria);
                foreach (array_keys($downloadsfielddata) as $j) {
                    $contenu = $downloadsfielddata[$j]->getVar('data');
                }
                if ($contenu !='') {
                    $class = ($class == 'even') ? 'odd' : 'even';
                    echo '<tr class="' . $class . '">';
                    echo '<td width="30%">' . $downloads_field[$i]->getVar('title') . ' </td>';
                    echo '<td>' . $contenu . '</td></tr>';
                }
            }
        }
        $class = ($class == 'even') ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMTEXT . ' </td>';
        echo '<td>' . $downloads_description . '</td>';
        echo '</tr>';
        // tags
        if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../../tag'))) {
            require_once XOOPS_ROOT_PATH.'/modules/tag/include/tagbar.php';
            $tags_array = tagBar($downloads_lid, 0);
            if (!empty($tags_array)) {
                $tags = '';
                foreach (array_keys($tags_array['tags']) as $i) {
                    $tags .= $tags_array['delimiter'] . ' ' . $tags_array['tags'][$i] . ' ';
                }

                $class = ($class == 'even') ? 'odd' : 'even';
                echo '<tr class="' . $class . '">';
                echo '<td width="30%">' . $tags_array['title'] . ' </td>';
                echo '<td>' . $tags . '</td>';
                echo '</tr>';
            }
        }
        if ($xoopsModuleConfig['useshots']) {
            if ($view_downloads->getVar('logourl') != 'blank.gif') {
                $class = ($class == 'even') ? 'odd' : 'even';
                echo '<tr class="' . $class . '">';
                echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMIMG . ' </td>';
                echo '<td><img src="' . $uploadurl_shots . $view_downloads->getVar('logourl') . '" alt="" title=""></td>';
                echo '</tr>';
            }
        }
        $class = ($class == 'even') ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMDATE . ' </td>';
        echo '<td>' . formatTimestamp($view_downloads->getVar('date')) . '</td>';
        echo '</tr>';
        $class = ($class == 'even') ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMPOSTER . ' </td>';
        echo '<td>' . XoopsUser::getUnameFromId($view_downloads->getVar('submitter')) . '</td>';
        echo '</tr>';
        $class = ($class == 'even') ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMHITS . ' </td>';
        echo '<td>' . $view_downloads->getVar('hits') . '</td>';
        echo '</tr>';
        $class = ($class == 'even') ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMRATING . ' </td>';
        echo '<td>' . number_format($view_downloads->getVar('rating'),1) . ' (' . $view_downloads->getVar('votes') . ' ' . _AM_TDMDOWNLOADS_FORMVOTE . ')</td>';
        echo '</tr>';
        if ( $view_downloads->getVar('paypal') != '' && $xoopsModuleConfig['use_paypal'] == true) {
            $class = ($class == 'even') ? 'odd' : 'even';
            echo '<tr class="' . $class . '">';
            echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMPAYPAL . ' </td>';
            echo '<td>' . $view_downloads->getVar('paypal') . '</td>';
            echo '</tr>';
        }
        $class = ($class == 'even') ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMCOMMENTS . ' </td>';
        echo '<td>' . $view_downloads->getVar('comments') . ' <a href="../singlefile.php?cid=' . $view_downloads->getVar('cid') . '&lid=' . $downloads_lid . '"><img src="../images/icon/view_mini.png" alt="' . _AM_TDMDOWNLOADS_FORMDISPLAY . '" title="' . _AM_TDMDOWNLOADS_FORMDISPLAY . '"></a></td>';
        echo '</tr>';
        $class = ($class == 'even') ? 'odd' : 'even';
        echo '<tr class="' . $class . '">';
        echo '<td width="30%">' . _AM_TDMDOWNLOADS_FORMACTION . ' </td>';
        echo '<td>';
        echo ($view_downloads->getVar('status') != 0 ? '' : '<a href="downloads.php?op=update_status&downloads_lid=' . $downloads_lid . '"><img src="./../images/icon/off.png" border="0" alt="' . _AM_TDMDOWNLOADS_FORMVALID . '" title="' . _AM_TDMDOWNLOADS_FORMVALID . '"></a> ');
        echo '<a href="downloads.php?op=edit_downloads&downloads_lid=' . $downloads_lid . '"><img src="../images/icon/edit.png" alt="' . _AM_TDMDOWNLOADS_FORMEDIT .
        '" title="' . _AM_TDMDOWNLOADS_FORMEDIT . '"></a> <a href="downloads.php?op=del_downloads&downloads_lid=' . $downloads_lid . '">
        <img src="../images/icon/delete.png" alt="' . _AM_TDMDOWNLOADS_FORMDEL . '" title="' . _AM_TDMDOWNLOADS_FORMDEL . '"></a></td>';
        echo '</tr>';
        echo '</table>';
        echo '<br>';
        // Affichage des votes:

        // Utilisateur enregistré
        echo '<hr>';
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('lid', $downloads_lid));
        $criteria->add(new Criteria('ratinguser', 0, '!='));
        $downloadsvotedata_arr = $downloadsvotedata_Handler->getall($criteria);
        $total_vote = count($downloadsvotedata_arr);
        echo '<table width="100%">';
        echo '<tr><td colspan="5"><b>';
        printf(_AM_TDMDOWNLOADS_DOWNLOADS_VOTESUSER, $total_vote);
        echo '</b><br /><br /></td></tr>';
        echo '<tr><td><b>' . _AM_TDMDOWNLOADS_DOWNLOADS_VOTE_USER . '</b></td>' . '<td><b>' . _AM_TDMDOWNLOADS_DOWNLOADS_VOTE_IP . '</b></td>' . '<td align="center"><b>' . _AM_TDMDOWNLOADS_FORMRATING . '</b></td>'
        . '<td><b>' . _AM_TDMDOWNLOADS_FORMDATE . '</b></td>' . '<td align="center"><b>' . _AM_TDMDOWNLOADS_FORMDEL . '</b></td></tr>';
        foreach (array_keys($downloadsvotedata_arr) as $i) {
            echo '<tr>';
            echo '<td>' . XoopsUser::getUnameFromId($downloadsvotedata_arr[$i]->getVar('ratinguser')) . '</td>';
            echo '<td>' . $downloadsvotedata_arr[$i]->getVar('ratinghostname') . '</td>';
            echo '<td align="center">' . $downloadsvotedata_arr[$i]->getVar('rating') . '</td>';
            echo '<td>' . formatTimestamp($downloadsvotedata_arr[$i]->getVar('ratingtimestamp')) . '</td>';
            echo '<td align="center">';
            echo myTextForm('downloads.php?op=del_vote&lid=' . $downloadsvotedata_arr[$i]->getVar('lid') . '&rid=' . $downloadsvotedata_arr[$i]->getVar('ratingid') , 'X');
            echo '</td>';
            echo '</tr>';
        }
        // Utilisateur anonyme
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('lid', $downloads_lid));
        $criteria->add(new Criteria('ratinguser', 0));
        $downloadsvotedata_arr = $downloadsvotedata_Handler->getall($criteria);
        $total_vote = count($downloadsvotedata_arr);
        echo '<tr><td colspan="5"><br /><b>';
        printf(_AM_TDMDOWNLOADS_DOWNLOADS_VOTESANONYME, $total_vote);
        echo '</b><br /><br /></td></tr>';
        echo '<tr><td colspan="2"><b>' . _AM_TDMDOWNLOADS_DOWNLOADS_VOTE_IP . '</b></td>' . '<td align="center"><b>' . _AM_TDMDOWNLOADS_FORMRATING . '</b></td>'
        . '<td><b>' . _AM_TDMDOWNLOADS_FORMDATE . '</b></td>' . '<td align="center"><b>' . _AM_TDMDOWNLOADS_FORMDEL . '</b></td></tr>';
        foreach (array_keys($downloadsvotedata_arr) as $i) {
            echo '<tr>';
            echo '<td colspan="2">' . $downloadsvotedata_arr[$i]->getVar('ratinghostname') . '</td>';
            echo '<td align="center">' . $downloadsvotedata_arr[$i]->getVar('rating') . '</td>';
            echo '<td>' . formatTimestamp($downloadsvotedata_arr[$i]->getVar('ratingtimestamp')) . '</td>';
            echo '<td align="center">';
            echo myTextForm('downloads.php?op=del_vote&lid=' . $downloadsvotedata_arr[$i]->getVar('lid') . '&rid=' . $downloadsvotedata_arr[$i]->getVar('ratingid') , 'X');
            echo '</tr>';
        }
        echo'</table>';
    break;

    // permet de suprimmer un vote et de recalculer la note
    case "del_vote":
        $objvotedata = $downloadsvotedata_Handler->get($_REQUEST['rid']);
        if ($downloadsvotedata_Handler->delete($objvotedata)) {
            $criteria = new CriteriaCompo();
            $criteria->add(new Criteria('lid', $_REQUEST['lid']));
            $downloadsvotedata_arr = $downloadsvotedata_Handler->getall($criteria);
            $total_vote = $downloadsvotedata_Handler->getCount($criteria);
            $obj = $downloads_Handler->get($_REQUEST['lid']);
            if ($total_vote == 0) {
                $obj->setVar('rating', number_format(0, 1));
                $obj->setVar('votes', 0);
                if ($downloads_Handler->insert($obj)) {
                    redirect_header('downloads.php?op=view_downloads&downloads_lid=' . $_REQUEST['lid'], 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
                }
            } else {
                $total_rating = 0;
                foreach (array_keys($downloadsvotedata_arr) as $i) {
                    $total_rating += $downloadsvotedata_arr[$i]->getVar('rating');
                }
                $rating = $total_rating / $total_vote;
                $obj->setVar('rating', number_format($rating, 1));
                $obj->setVar('votes', $total_vote);
                if ($downloads_Handler->insert($obj)) {
                    redirect_header('downloads.php?op=view_downloads&downloads_lid=' . $_REQUEST['lid'], 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
                }
            }
            echo $obj->getHtmlErrors();
        }
        echo $objvotedata->getHtmlErrors();
    break;

    // Pour sauver un téléchargement
    case "save_downloads":
        global $xoopsDB;
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        if (!$GLOBALS['xoopsSecurity']->check()) {
           redirect_header('downloads.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        if (isset($_REQUEST['lid'])) {
           $obj = $downloads_Handler->get($_REQUEST['lid']);
        } else {
           $obj = $downloads_Handler->create();
        }
        $erreur = false;
        $message_erreur = '';
        $donnee = array();
        $obj->setVar('title', $_POST['title']);
        $obj->setVar('cid', $_POST['cid']);
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
        if (!isset($_REQUEST['downloads_modified'])) {
            $obj->setVar('date', time());
            if (isset($_POST['status'])) {
                $obj->setVar('status', 1);
                $donnee['status'] = 1;
            } else {
                $obj->setVar('status', 0);
                $donnee['status'] = 0;
            }
        } else {
            if ($_POST['date_update'] == 'Y') {
                $obj->setVar('date', strtotime($_POST['date']));
                if (isset($_POST['status'])) {
                    $obj->setVar('status', 2);
                    $donnee['status'] = 1;
                } else {
                    $obj->setVar('status', 0);
                    $donnee['status'] = 0;
                }
            } else {
                if (isset($_POST['status'])) {
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
        if (intval($_REQUEST['size']) == 0) {
            if ($_REQUEST['size'] == '0' || $_REQUEST['size'] == '') {
                $erreur = false;
            } else {
                $erreur = true;
                $message_erreur .= _AM_TDMDOWNLOADS_ERREUR_SIZE . '<br>';
            }
        }
        // erreur si la description est vide
        if (isset($_REQUEST['description'])) {
            if ($_REQUEST['description'] == '') {
                $erreur=true;
                $message_erreur .= _AM_TDMDOWNLOADS_ERREUR_NODESCRIPTION . '<br>';
            }
        }
        // erreur si la catégorie est vide
        if (isset($_REQUEST['cid'])) {
            if ($_REQUEST['cid'] == 0) {
                $erreur = true;
                $message_erreur .= _AM_TDMDOWNLOADS_ERREUR_NOCAT . '<br>';
            }
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
        if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../../tag'))) {
            $donnee['TAG'] = $_POST['tag'];
        }

        if ($erreur == 1) {
            xoops_cp_header();
            echo '<div class="errorMsg" style="text-align: left;">' . $message_erreur . '</div>';
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
            // enregistrement
            if ($downloads_Handler->insert($obj)) {
                if (!isset($_REQUEST['downloads_modified'])) {
                    $lid_dowwnloads = $obj->get_new_enreg();
                } else {
                    $lid_dowwnloads = $_REQUEST['lid'];
                }
                //tags
                if (($xoopsModuleConfig['usetag'] == 1) and (is_dir('../../tag'))) {
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
                        $iddata = 'iddata' . $downloads_field[$i]->getVar('fid');
                        if (isset($_REQUEST[$iddata])) {
                            if ($_REQUEST[$iddata] == '') {
                                $objdata = $downloadsfielddata_Handler->create();
                            } else {
                                $objdata = $downloadsfielddata_Handler->get($_REQUEST[$iddata]);
                            }
                        } else {
                            $objdata = $downloadsfielddata_Handler->create();
                        }
                        $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                        $objdata->setVar('data', $_POST[$nom_champ]);
                        $objdata->setVar('lid', $lid_dowwnloads);
                        $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));
                        $downloadsfielddata_Handler->insert($objdata) or $objdata->getHtmlErrors();
                    }
                }
                //permission pour télécharger
                if ($xoopsModuleConfig['permission_download'] == 2) {
                    $gperm_handler = xoops_gethandler('groupperm');
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
                // pour les notifications uniquement lors d'un nouveau téléchargement
                if (!isset($_REQUEST['downloads_modified'])) {
                    $tags = array();
                    $tags['FILE_NAME'] = $_POST['title'];
                    $tags['FILE_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlefile.php?cid=' . $_POST['cid'] . '&amp;lid=' . $lid_dowwnloads;
                    $downloadscat_cat = $downloadscat_Handler->get($_POST['cid']);
                    $tags['CATEGORY_NAME'] = $downloadscat_cat->getVar('cat_title');
                    $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $_POST['cid'];
                    $notification_handler = xoops_gethandler('notification');
                    $notification_handler->triggerEvent('global', 0, 'new_file', $tags);
                    $notification_handler->triggerEvent('category', $_POST['cid'], 'new_file', $tags);
                }
                redirect_header('downloads.php', 2, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
            }
            echo $obj->getHtmlErrors();
        }
        $form = $obj->getForm($donnee, true);
        $form->display();
    break;

    // permet de valider un téléchargement proposé
    case "update_status":
        $obj = $downloads_Handler->get($_REQUEST['downloads_lid']);
        $obj->setVar('status', 1);
        if ($downloads_Handler->insert($obj)) {
            redirect_header('downloads.php', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
        }
        echo $obj->getHtmlErrors();
    break;

    // permet de valider un téléchargement proposé
    case "lock_status":
        $obj = $downloads_Handler->get($_REQUEST['downloads_lid']);
        $obj->setVar('status', 0);
        if ($downloads_Handler->insert($obj)) {
            redirect_header('downloads.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DEACTIVATED);
        }
        echo $obj->getHtmlErrors();
    break;
}
//Affichage de la partie basse de l'administration de Xoops
xoops_cp_footer();
