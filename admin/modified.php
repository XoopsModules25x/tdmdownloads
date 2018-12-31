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
            $modified_admin = \Xmf\Module\Admin::getInstance();
            echo $modified_admin->displayNavigation('modified.php');
        }
        $criteria = new \CriteriaCompo();
        if (isset($_REQUEST['limit'])) {
            $criteria->setLimit($_REQUEST['limit']);
            $limit = $_REQUEST['limit'];
        } else {
            $criteria->setLimit($xoopsModuleConfig['perpageadmin']);
            $limit = $xoopsModuleConfig['perpageadmin'];
        }
        if (isset($_REQUEST['start'])) {
            $criteria->setStart($_REQUEST['start']);
            $start = $_REQUEST['start'];
        } else {
            $criteria->setStart(0);
            $start = 0;
        }
        $criteria->setSort('requestid');
        $criteria->setOrder('ASC');
        $downloadsmod_arr = $modifiedHandler->getAll($criteria);
        $numrows = $modifiedHandler->getCount($criteria);
        if ($numrows > $limit) {
            $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', 'op=liste&limit=' . $limit);
            $pagenav = $pagenav->renderNav(4);
        } else {
            $pagenav = '';
        }
        //Affichage du tableau des téléchargements modifiés
        if ($numrows > 0) {
            echo '<table width="100%" cellspacing="1" class="outer">';
            echo '<tr>';
            echo '<th align="center">' . _AM_TDMDOWNLOADS_FORMTITLE . '</th>';
            echo '<th align="center" width="20%">' . _AM_TDMDOWNLOADS_BROKEN_SENDER . '</th>';
            echo '<th align="center" width="15%">'._AM_TDMDOWNLOADS_FORMACTION.'</th>';
            echo '</tr>';
            $class = 'odd';
            foreach (array_keys($downloadsmod_arr) as $i) {
                $class = ('even' === $class) ? 'odd' : 'even';
                $downloads_lid = $downloadsmod_arr[$i]->getVar('lid');
                $downloads_requestid = $downloadsmod_arr[$i]->getVar('requestid');
                $downloads = $downloadsHandler->get($downloadsmod_arr[$i]->getVar('lid'));
                // pour savoir si le fichier est nouveau
                $downloads_url = $downloads->getVar('url');
                $moddownloads_url = $downloadsmod_arr[$i]->getVar('url');
                $new_file = ($downloads_url == $moddownloads_url ? false : true);
                echo '<tr class="' . $class . '">';
                echo '<td align="center">' . $downloads->getVar('title') . '</td>';
                echo '<td align="center"><b>' . XoopsUser::getUnameFromId($downloadsmod_arr[$i]->getVar('modifysubmitter')) . '</b></td>';
                echo '<td align="center" width="15%">';
                echo '<a href="modified.php?op=view_downloads&downloads_lid=' . $downloads_lid . '&mod_id=' . $downloads_requestid . '"><img src="../assets/images/icon/view_mini.png" alt="' . _AM_TDMDOWNLOADS_FORMDISPLAY . '" title="' . _AM_TDMDOWNLOADS_FORMDISPLAY . '"></a> ';
                echo '<a href="modified.php?op=del_moddownloads&mod_id=' . $downloads_requestid . '&new_file=' . $new_file . '"><img src="../assets/images/icon/ignore_mini.png" alt="' . _AM_TDMDOWNLOADS_FORMIGNORE . '" title="' . _AM_TDMDOWNLOADS_FORMIGNORE . '"></a>';
                echo '</td>';
            }
            echo '</table><br>';
            echo '<br><div align=right>' . $pagenav . '</div><br>';
        } else {
            echo '<div class="errorMsg" style="text-align: center;">' . _AM_TDMDOWNLOADS_ERREUR_NOBMODDOWNLOADS . '</div>';
        }
    break;

    // Affiche la comparaison de fichier
    case 'view_downloads':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        if (TDMDownloads_checkModuleAdmin()) {
            $modified_admin = \Xmf\Module\Admin::getInstance();
            echo $modified_admin->displayNavigation('modified.php');
            $modified_admin->addItemButton(_MI_TDMDOWNLOADS_ADMENU5, 'modified.php', 'list');
            echo $modified_admin->displayButton();
        }
        //information du téléchargement
        $view_downloads = $downloadsHandler->get($_REQUEST['downloads_lid']);
        //information du téléchargement modifié
        $view_moddownloads = $modifiedHandler->get($_REQUEST['mod_id']);

        // original
        $downloads_title = $view_downloads->getVar('title');
        $downloads_url = $view_downloads->getVar('url');
        //catégorie
        $view_categorie = $categoryHandler->get($view_downloads->getVar('cid'));
        $downloads_categorie = $view_categorie->getVar('cat_title');
        $downloads_homepage = $view_downloads->getVar('homepage');
        $downloads_version = $view_downloads->getVar('version');
        $downloads_size = $view_downloads->getVar('size');
        $downloads_platform = $view_downloads->getVar('platform');
        $downloads_description = $view_downloads->getVar('description');
        $downloads_logourl = $view_downloads->getVar('logourl');
        // modifié
        $moddownloads_title = $view_moddownloads->getVar('title');
        $moddownloads_url = $view_moddownloads->getVar('url');
        //catégorie
        $view_categorie = $categoryHandler->get($view_moddownloads->getVar('cid'));
        $moddownloads_categorie = $view_categorie->getVar('cat_title');
        $moddownloads_homepage = $view_moddownloads->getVar('homepage');
        $moddownloads_version = $view_moddownloads->getVar('version');
        $moddownloads_size = $view_moddownloads->getVar('size');
        $moddownloads_platform = $view_moddownloads->getVar('platform');
        $moddownloads_description = $view_moddownloads->getVar('description');
        $moddownloads_logourl = $view_moddownloads->getVar('logourl');
        echo "<style type=\"text/css\">\n";
        echo ".style_dif {color: #FF0000; font-weight: bold;}\n";
        echo ".style_ide {color: #009966; font-weight: bold;}\n";
        echo "</style>\n";
        //originale
        echo '<table width="100%" border="0" cellspacing="1" class="outer"><tr class="odd"><td>';
        echo '<table border="1" cellpadding="5" cellspacing="0" align="center"><tr><td>';
        echo '<h4>' . _AM_TDMDOWNLOADS_MODIFIED_ORIGINAL . '</h4>';
        echo '<table width="100%">';
        echo '<tr>';
        echo '<td valign="top" width="50%"><small><span class="' . ($downloads_title == $moddownloads_title ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMTITLE . '</span>: ' . $downloads_title . '</small></td>';
        echo '<td valign="top" rowspan="14"><small><span class="' . ($downloads_description == $moddownloads_description ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMTEXT . '</span>:<br>' . $downloads_description . '</small></td>';
        echo '</tr>';
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_url == $moddownloads_url ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMURL . '</span>:<br>' . $downloads_url . '</small></td></tr>';
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_categorie == $moddownloads_categorie ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMCAT . '</span>: ' . $downloads_categorie . '</small></td></tr>';
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new \Criteria('status', 1));
        $downloads_field = $fieldHandler->getAll($criteria);
        foreach (array_keys($downloads_field) as $i) {
            if (1 == $downloads_field[$i]->getVar('status_def')) {
                if (1 == $downloads_field[$i]->getVar('fid')) {
                    //page d'accueil
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_homepage == $moddownloads_homepage ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMHOMEPAGE . '</span>: <a href="' . $downloads_homepage . '">' . $downloads_homepage . '</a></small></td></tr>';
                }
                if (2 == $downloads_field[$i]->getVar('fid')) {
                    //version
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_version == $moddownloads_version ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMVERSION . '</span>: ' . $downloads_version . '</small></td></tr>';
                }
                if (3 == $downloads_field[$i]->getVar('fid')) {
                    //taille du fichier
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_size == $moddownloads_size ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMSIZE . '</span>: ' . $downloads_size  . '</small></td></tr>';
                }
                if (4 == $downloads_field[$i]->getVar('fid')) {
                    //plateforme
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_platform == $moddownloads_platform ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMPLATFORM . '</span>: ' . $downloads_platform  . '</small></td></tr>';
                }
            } else {
                //original
                $contenu = '';
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('lid', $_REQUEST['downloads_lid']));
                $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfielddata = $fielddataHandler->getAll($criteria);
                foreach (array_keys($downloadsfielddata) as $j) {
                    $contenu = $downloadsfielddata[$j]->getVar('data');
                }
                //proposé
                $mod_contenu = '';
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('lid', $_REQUEST['mod_id']));
                $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfieldmoddata = $modifieddataHandler->getAll($criteria);
                foreach (array_keys($downloadsfieldmoddata) as $j) {
                    $mod_contenu = $downloadsfieldmoddata[$j]->getVar('moddata');
                }
                echo '<tr><td valign="top" width="40%"><small><span class="' . ($contenu == $mod_contenu ? 'style_ide' : 'style_dif') . '">' . $downloads_field[$i]->getVar('title') . '</span>: ' . $contenu  . '</small></td></tr>';
            }
        }
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_logourl == $moddownloads_logourl ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMIMG . '</span>:<br> <img src="' . $uploadurl_shots . $downloads_logourl . '" alt="" title=""></small></td></tr>';
        echo '</table>';
        //proposé
        echo '</td></tr><tr><td>';
        echo '<h4>' . _AM_TDMDOWNLOADS_MODIFIED_MOD . '</h4>';
        echo '<table width="100%">';
        echo '<tr>';
        echo '<td valign="top" width="40%"><small><span class="' . ($downloads_title == $moddownloads_title ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMTITLE . '</span>: ' . $moddownloads_title . '</small></td>';
        echo '<td valign="top" rowspan="14"><small><span class="' . ($downloads_description == $moddownloads_description ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMTEXT . '</span>:<br>' . $moddownloads_description . '</small></td>';
        echo '</tr>';
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_url == $moddownloads_url ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMURL . '</span>:<br>' . $moddownloads_url . '</small></td></tr>';
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_categorie == $moddownloads_categorie ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMCAT . '</span>: ' . $moddownloads_categorie . '</small></td></tr>';
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new \Criteria('status', 1));
        $downloads_field = $fieldHandler->getAll($criteria);
        foreach (array_keys($downloads_field) as $i) {
            if (1 == $downloads_field[$i]->getVar('status_def')) {
                if (1 == $downloads_field[$i]->getVar('fid')) {
                    //page d'accueil
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_homepage == $moddownloads_homepage ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMHOMEPAGE . '</span>: <a href="' . $moddownloads_homepage . '">' . $moddownloads_homepage . '</a></small></td></tr>';
                }
                if (2 == $downloads_field[$i]->getVar('fid')) {
                    //version
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_version == $moddownloads_version ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMVERSION . '</span>: ' . $moddownloads_version . '</small></td></tr>';
                }
                if (3 == $downloads_field[$i]->getVar('fid')) {
                    //taille du fichier
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_size == $moddownloads_size ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMSIZE . '</span>: ' . $moddownloads_size  . '</small></td></tr>';
                }
                if (4 == $downloads_field[$i]->getVar('fid')) {
                    //plateforme
                    echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_platform == $moddownloads_platform ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMPLATFORM . '</span>: ' . $moddownloads_platform  . '</small></td></tr>';
                }
            } else {
                //original
                $contenu = '';
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('lid', $_REQUEST['downloads_lid']));
                $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfielddata = $fielddataHandler->getAll($criteria);
                foreach (array_keys($downloadsfielddata) as $j) {
                    $contenu = $downloadsfielddata[$j]->getVar('data');
                }
                //proposé
                $mod_contenu = '';
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('lid', $_REQUEST['mod_id']));
                $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfieldmoddata = $modifieddataHandler->getAll($criteria);
                foreach (array_keys($downloadsfieldmoddata) as $j) {
                    $mod_contenu = $downloadsfieldmoddata[$j]->getVar('moddata');
                }
                echo '<tr><td valign="top" width="40%"><small><span class="' . ($contenu == $mod_contenu ? 'style_ide' : 'style_dif') . '">' . $downloads_field[$i]->getVar('title') . '</span>: ' . $mod_contenu  . '</small></td></tr>';
            }
        }
        echo '<tr><td valign="top" width="40%"><small><span class="' . ($downloads_logourl == $moddownloads_logourl ? 'style_ide' : 'style_dif') . '">' . _AM_TDMDOWNLOADS_FORMIMG . '</span>:<br> <img src="' . $uploadurl_shots . $moddownloads_logourl . '" alt="" title=""></small></td></tr>';
        echo '</table>';
        echo '</table>';
        echo '</td></tr></table>';
        //permet de savoir si le fichier est nouveau
        $new_file = ($downloads_url == $moddownloads_url ? false : true);
        echo '<table><tr><td>';
        echo myTextForm('modified.php?op=approve&mod_id=' . $_REQUEST['mod_id'] . '&new_file=' . $new_file, _AM_TDMDOWNLOADS_FORMAPPROVE);
        echo '</td><td>';
        echo myTextForm('downloads.php?op=edit_downloads&downloads_lid=' . $_REQUEST['downloads_lid'], _AM_TDMDOWNLOADS_FORMEDIT);
        echo '</td><td>';
        echo myTextForm('modified.php?op=del_moddownloads&mod_id=' . $_REQUEST['mod_id'] . '&new_file=' . $new_file, _AM_TDMDOWNLOADS_FORMIGNORE);
        echo '</td></tr></table>';
    break;

    // permet de suprimmer le téléchargment modifié
    case 'del_moddownloads':
        $obj = $modifiedHandler->get($_REQUEST['mod_id']);
        if (isset($_REQUEST['ok']) && 1 == $_REQUEST['ok']) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('downloads.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if (true == $_REQUEST['new_file']) {
                $urlfile = substr_replace($obj->getVar('url'), '', 0, strlen($uploadurl_downloads));
                // permet de donner le chemin du fichier
                $urlfile = $uploaddir_downloads . $urlfile;
                // si le fichier est sur le serveur il es détruit
                if (is_file($urlfile)) {
                    chmod($urlfile, 0777);
                    unlink($urlfile);
                }
            }
            // supression des data des champs sup
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('lid', $_REQUEST['mod_id']));
            $downloads_fielddata = $modifieddataHandler->getAll($criteria);
            foreach (array_keys($downloads_fielddata) as $i) {
                $objfielddata = $modifieddataHandler->get($downloads_fielddata[$i]->getVar('modiddata'));
                $modifieddataHandler->delete($objfielddata) or $objvfielddata->getHtmlErrors();
            }
            if ($modifiedHandler->delete($obj)) {
                redirect_header('modified.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
            }
            echo $objvotedata->getHtmlErrors();
        } else {
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            if (TDMDownloads_checkModuleAdmin()) {
                $modified_admin = \Xmf\Module\Admin::getInstance();
                $modified_admin->addItemButton(_MI_TDMDOWNLOADS_ADMENU5, 'modified.php', 'list');
                echo $modified_admin->displayButton();
            }
            xoops_confirm(['ok' => 1, 'mod_id' => $_REQUEST['mod_id'], 'new_file' => $_REQUEST['new_file'], 'op' => 'del_moddownloads'], $_SERVER['REQUEST_URI'], _AM_TDMDOWNLOADS_MODIFIED_SURDEL . '<br>');
        }
    break;

    // permet d'accépter la modification
    case 'approve':
        // choix du téléchargement:
        $view_moddownloads = $modifiedHandler->get($_REQUEST['mod_id']);
        $obj = $downloadsHandler->get($view_moddownloads->getVar('lid'));
        // permet d'effacer le fichier actuel si un nouveau fichier proposé est accepté.
        if (true == $_REQUEST['new_file']) {
            $urlfile = substr_replace($obj->getVar('url'), '', 0, strlen($uploadurl_downloads));
            // permet de donner le chemin du fichier
            $urlfile = $uploaddir_downloads . $urlfile;
            // si le fichier est sur le serveur il es détruit
            if (is_file($urlfile)) {
                chmod($urlfile, 0777);
                unlink($urlfile);
            }
        }
        // mise à jour:
        $obj->setVar('title', $view_moddownloads->getVar('title'));
        $obj->setVar('url', $view_moddownloads->getVar('url'));
        $obj->setVar('cid', $view_moddownloads->getVar('cid'));
        $obj->setVar('homepage', $view_moddownloads->getVar('homepage'));
        $obj->setVar('version', $view_moddownloads->getVar('version'));
        $obj->setVar('size', $view_moddownloads->getVar('size'));
        $obj->setVar('platform', $view_moddownloads->getVar('platform'));
        $obj->setVar('description', $view_moddownloads->getVar('description'));
        $obj->setVar('logourl', $view_moddownloads->getVar('logourl'));
        $obj->setVar('date', time());
        $obj->setVar('status', 2);
        // Récupération des champs supplémentaires:
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $fieldHandler->getAll($criteria);
        foreach (array_keys($downloads_field) as $i) {
            $contenu = '';
            $iddata = 0;
            if (0 == $downloads_field[$i]->getVar('status_def')) {
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('lid', $view_moddownloads->getVar('requestid')));
                $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfieldmoddata = $modifieddataHandler->getAll($criteria);
                foreach (array_keys($downloadsfieldmoddata) as $j) {
                    $contenu = $downloadsfieldmoddata[$j]->getVar('moddata');
                }
                $criteria = new \CriteriaCompo();
                $criteria->add(new \Criteria('lid', $view_moddownloads->getVar('lid')));
                $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfielddata = $fielddataHandler->getAll($criteria);
                foreach (array_keys($downloadsfielddata) as $j) {
                    $iddata = $downloadsfielddata[$j]->getVar('iddata');
                }
                if (0 == $iddata) {
                    $objdata = $fielddataHandler->create();
                    $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));
                    $objdata->setVar('lid', $view_moddownloads->getVar('lid'));
                } else {
                    $objdata = $fielddataHandler->get($iddata);
                }
                $objdata->setVar('data', $contenu);
                $fielddataHandler->insert($objdata) or $objdata->getHtmlErrors();
            }
        }
        // supression du rapport de modification
        $objmod = $modifiedHandler->get($_REQUEST['mod_id']);
        $modifiedHandler->delete($objmod);
        // supression des data des champs sup
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('lid', $_REQUEST['mod_id']));
        $downloads_fielddata = $modifieddataHandler->getAll($criteria);
        foreach (array_keys($downloads_fielddata) as $i) {
            $objfielddata = $modifieddataHandler->get($downloads_fielddata[$i]->getVar('modiddata'));
            $modifieddataHandler->delete($objfielddata) or $objvfielddata->getHtmlErrors();
        }
        // enregistrement
        if ($downloadsHandler->insert($obj)) {
            redirect_header('modified.php', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
        }
        echo $obj->getHtmlErrors();
    break;
}
//Affichage de la partie basse de l'administration de Xoops
xoops_cp_footer();
