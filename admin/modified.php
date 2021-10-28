<?php declare(strict_types=1);

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
 * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage)
 */

use XoopsModules\Tdmdownloads\Helper;

require __DIR__ . '/admin_header.php';

// Template
$templateMain = 'tdmdownloads_admin_modified.tpl';

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = Helper::getInstance();

//On recupere la valeur de l'argument op dans l'URL$
$op = \Xmf\Request::getCmd('op', 'list');

xoops_cp_header();

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // show list
    case 'list':
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));

        $criteria = new \CriteriaCompo();
        if (\Xmf\Request::hasVar('limit', 'REQUEST')) {
            $criteria->setLimit(\Xmf\Request::getInt('limit', 0, 'REQUEST'));

            $limit = \Xmf\Request::getInt('limit', 0, 'REQUEST');
        } else {
            $criteria->setLimit($helper->getConfig('perpageadmin'));

            $limit = $helper->getConfig('perpageadmin');
        }
        if (\Xmf\Request::hasVar('start', 'REQUEST')) {
            $criteria->setStart(\Xmf\Request::getInt('start', 0, 'REQUEST'));

            $start = \Xmf\Request::getInt('start', 0, 'REQUEST');
        } else {
            $criteria->setStart(0);

            $start = 0;
        }
        $criteria->setSort('requestid');
        $criteria->setOrder('ASC');
        $downloadsmod_arr = $modifiedHandler->getAll($criteria);
        //        $numrows          = $modifiedHandler->getCount($criteria);
        $numrows = $modifiedHandler->getCount(); //Ggoffy
        if ($numrows > $limit) {
            $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', 'op=liste&limit=' . $limit);

            $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(4));
        } else {
            $pagenav = '';
        }
        //Affichage du tableau des téléchargements modifiés
        if ($numrows > 0) {
            $GLOBALS['xoopsTpl']->assign('modified_count', $numrows);

            foreach (array_keys($downloadsmod_arr) as $i) {
                /** @var \XoopsModules\Tdmdownloads\Modified[] $downloadsmod_arr */

                $downloads = $downloadsHandler->get($downloadsmod_arr[$i]->getVar('lid'));

                // pour savoir si le fichier est nouveau

                $downloads_url = $downloads->getVar('url');

                $moddownloads_url = $downloadsmod_arr[$i]->getVar('url');

                $new_file = ($downloads_url != $moddownloads_url);

                $modified = [
                    'lid'             => $downloadsmod_arr[$i]->getVar('lid'),
                    'requestid'       => $downloadsmod_arr[$i]->getVar('requestid'),
                    'new_file'        => $new_file,
                    'download_title'  => $downloads->getVar('title'),
                    'modifysubmitter' => XoopsUser::getUnameFromId($downloadsmod_arr[$i]->getVar('modifysubmitter')),
                ];

                $GLOBALS['xoopsTpl']->append('modified_list', $modified);

                unset($modified);
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('message_erreur', _AM_TDMDOWNLOADS_ERREUR_NOBMODDOWNLOADS);
        }
        break;
    // show a comparision of the versions
    case 'view_downloads':
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
        $adminObject->addItemButton(_MI_TDMDOWNLOADS_ADMENU5, 'modified.php', 'list');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

        //information du téléchargement
        $viewDownloads = $downloadsHandler->get(\Xmf\Request::getInt('downloads_lid', 0, 'REQUEST'));
        //information du téléchargement modifié
        $viewModdownloads = $modifiedHandler->get(\Xmf\Request::getInt('mod_id', 0, 'REQUEST'));

        // original
        $downloads_title = $viewDownloads->getVar('title');
        $downloads_url   = $viewDownloads->getVar('url');
        //catégorie
        $view_category         = $categoryHandler->get($viewDownloads->getVar('cid'));
        $downloads_category    = $view_category->getVar('cat_title');
        $downloads_homepage    = $viewDownloads->getVar('homepage');
        $downloads_version     = $viewDownloads->getVar('version');
        $downloads_size        = $viewDownloads->getVar('size');
        $downloads_platform    = $viewDownloads->getVar('platform');
        $downloads_description = $viewDownloads->getVar('description');
        $downloads_logourl     = $viewDownloads->getVar('logourl');
        // modifié
        $moddownloads_title = $viewModdownloads->getVar('title');
        $moddownloads_url   = $viewModdownloads->getVar('url');
        //catégorie
        $view_category            = $categoryHandler->get($viewModdownloads->getVar('cid'));
        $moddownloads_category    = $view_category->getVar('cat_title');
        $moddownloads_homepage    = $viewModdownloads->getVar('homepage');
        $moddownloads_version     = $viewModdownloads->getVar('version');
        $moddownloads_size        = $viewModdownloads->getVar('size');
        $moddownloads_platform    = $viewModdownloads->getVar('platform');
        $moddownloads_description = $viewModdownloads->getVar('description');
        $moddownloads_logourl     = $viewModdownloads->getVar('logourl');

        $compare['title']       = ['info' => _AM_TDMDOWNLOADS_FORMTITLE, 'current' => $downloads_title, 'modified' => $moddownloads_title];
        $compare['description'] = ['info' => _AM_TDMDOWNLOADS_FORMTEXT, 'current' => $downloads_description, 'modified' => $moddownloads_description];
        $compare['url']         = ['info' => _AM_TDMDOWNLOADS_FORMURL, 'current' => $downloads_url, 'modified' => $moddownloads_url];
        $compare['category']    = ['info' => _AM_TDMDOWNLOADS_FORMCAT, 'current' => $downloads_category, 'modified' => $moddownloads_category];

        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $criteria->add(new \Criteria('status', 1));
        $downloads_field = $fieldHandler->getAll($criteria);
        foreach (array_keys($downloads_field) as $i) {
            /** @var \XoopsModules\Tdmdownloads\Field[] $downloads_field */

            if (1 == $downloads_field[$i]->getVar('status_def')) {
                if (1 == $downloads_field[$i]->getVar('fid')) {
                    //page d'accueil

                    $compare['cfields'][] = ['info' => _AM_TDMDOWNLOADS_FORMHOMEPAGE, 'current' => $downloads_homepage, 'modified' => $moddownloads_homepage];
                }

                if (2 == $downloads_field[$i]->getVar('fid')) {
                    //version

                    $compare['cfields'][] = ['info' => _AM_TDMDOWNLOADS_FORMVERSION, 'current' => $downloads_version, 'modified' => $moddownloads_version];
                }

                if (3 == $downloads_field[$i]->getVar('fid')) {
                    //taille du fichier

                    $compare['cfields'][] = ['info' => _AM_TDMDOWNLOADS_FORMSIZE_WHEN_SUBMIT, 'current' => $downloads_size, 'modified' => $moddownloads_size];
                }

                if (4 == $downloads_field[$i]->getVar('fid')) {
                    //plateforme

                    $compare['cfields'][] = ['info' => _AM_TDMDOWNLOADS_FORMPLATFORM, 'current' => $downloads_platform, 'modified' => $moddownloads_platform];
                }
            } else {
                //original

                $contenu = '';

                $criteria = new \CriteriaCompo();

                $criteria->add(new \Criteria('lid', \Xmf\Request::getInt('downloads_lid', 0, 'REQUEST')));

                $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));

                $downloadsfielddata = $fielddataHandler->getAll($criteria);

                foreach (array_keys($downloadsfielddata) as $j) {
                    /** @var \XoopsModules\Tdmdownloads\Fielddata[] $downloadsfielddata */

                    //                    $contenu = $downloadsfielddata[$j]->getVar('data');

                    $contenu = $downloadsfielddata[$j]->getVar('data', 'e');
                }

                //proposé

                $contentModified = '';

                $criteria = new \CriteriaCompo();

                $criteria->add(new \Criteria('lid', \Xmf\Request::getInt('mod_id', 0, 'REQUEST')));

                $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));

                $downloadsfieldmoddata = $modifieddataHandler->getAll($criteria);

                foreach (array_keys($downloadsfieldmoddata) as $j) {
                    /** @var \XoopsModules\Tdmdownloads\Modified[] $downloadsfieldmoddata */

                    $contentModified = $downloadsfieldmoddata[$j]->getVar('moddata', 'e');
                }

                //                echo '<tr><td valign="top" width="40%"><small><span class="' . ($contenu == $contentModified ? 'style_ide' : 'style_dif') . '">' . $downloads_field[$i]->getVar('title') . '</span>: ' . $contentModified . '</small></td></tr>';

                $compare['cfields'][] = ['info' => $downloads_field[$i]->getVar('title'), 'current' => $contenu, 'modified' => $contentModified];
            }
        }
        $compare['img'] = ['info' => _AM_TDMDOWNLOADS_FORMIMG, 'current' => $downloads_logourl, 'modified' => $moddownloads_logourl];
        //permet de savoir si le fichier est nouveau
        $new_file = ($downloads_url != $moddownloads_url);
        $buttons  = [
            myTextForm('modified.php?op=approve&mod_id=' . \Xmf\Request::getInt('mod_id', 0, 'GET') . '&new_file=' . $new_file, _AM_TDMDOWNLOADS_FORMAPPROVE),
            myTextForm('downloads.php?op=edit_downloads&downloads_lid=' . \Xmf\Request::getInt('downloads_lid', 0, 'GET'), _AM_TDMDOWNLOADS_FORMEDIT),
            myTextForm('modified.php?op=del_moddownloads&mod_id=' . \Xmf\Request::getInt('mod_id', 0, 'GET') . '&new_file=' . $new_file, _AM_TDMDOWNLOADS_FORMIGNORE),
        ];
        $GLOBALS['xoopsTpl']->assign('compare_list', $compare);
        $GLOBALS['xoopsTpl']->assign('cbuttons', $buttons);
        $GLOBALS['xoopsTpl']->assign('uploadurl_shots', $uploadurl_shots);
        break;
    // permet de suprimmer le téléchargment modifié
    case 'del_moddownloads':
        $obj = $modifiedHandler->get(\Xmf\Request::getInt('mod_id', 0, 'REQUEST'));
        if (1 === \Xmf\Request::getInt('ok', 0, 'POST')) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('downloads.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }

            if (true === \Xmf\Request::getBool('new_file', false, 'REQUEST')) {
                $urlfile = substr_replace($obj->getVar('url'), '', 0, mb_strlen($uploadurl_downloads));

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

            $criteria->add(new \Criteria('lid', \Xmf\Request::getInt('mod_id', 0, 'REQUEST')));

            $downloads_fielddata = $modifieddataHandler->getAll($criteria);

            foreach (array_keys($downloads_fielddata) as $i) {
                /** @var \XoopsModules\Tdmdownloads\Fielddata[] $downloads_fielddata */

                $objfielddata = $modifieddataHandler->get($downloads_fielddata[$i]->getVar('modiddata'));

                $modifieddataHandler->delete($objfielddata) || $objvfielddata->getHtmlErrors();
            }

            if ($modifiedHandler->delete($obj)) {
                redirect_header('modified.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
            }

            $GLOBALS['xoopsTpl']->assign('message_erreur', $obj->getHtmlErrors());
        } else {
            $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));

            $adminObject->addItemButton(_MI_TDMDOWNLOADS_ADMENU5, 'modified.php', 'list');

            $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));

            xoops_confirm(
                [
                    'ok'       => 1,
                    'mod_id'   => \Xmf\Request::getInt('mod_id', 0, 'REQUEST'),
                    'new_file' => \Xmf\Request::getString('new_file', 0, 'REQUEST'),
                    'op'       => 'del_moddownloads',
                ],
                $_SERVER['REQUEST_URI'],
                _AM_TDMDOWNLOADS_MODIFIED_SURDEL . '<br>'
            );
        }
        break;
    // permet d'accépter la modification
    case 'approve':
        // choix du téléchargement:
        $viewModdownloads = $modifiedHandler->get(\Xmf\Request::getInt('mod_id', 0, 'REQUEST'));
        $obj              = $downloadsHandler->get($viewModdownloads->getVar('lid'));
        // delete the current file if a new proposed file is accepted.
        if (true === \Xmf\Request::getBool('new_file', false, 'REQUEST')) {
            $urlfile = substr_replace($obj->getVar('url'), '', 0, mb_strlen($uploadurl_downloads));

            // permet de donner le chemin du fichier

            $urlfile = $uploaddir_downloads . $urlfile;

            // si le fichier est sur le serveur il es détruit

            if (is_file($urlfile)) {
                chmod($urlfile, 0777);

                unlink($urlfile);
            }
        }
        // mise à jour:
        $obj->setVar('title', $viewModdownloads->getVar('title'));
        $obj->setVar('url', $viewModdownloads->getVar('url'));
        $obj->setVar('cid', $viewModdownloads->getVar('cid'));
        $obj->setVar('homepage', $viewModdownloads->getVar('homepage'));
        $obj->setVar('version', $viewModdownloads->getVar('version'));
        $obj->setVar('size', $viewModdownloads->getVar('size'));
        $obj->setVar('platform', $viewModdownloads->getVar('platform'));
        $obj->setVar('description', $viewModdownloads->getVar('description'));
        $obj->setVar('logourl', $viewModdownloads->getVar('logourl'));
        $obj->setVar('date', time());
        $obj->setVar('status', 2);
        // Récupération des champs supplémentaires:
        $criteria = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $fieldHandler->getAll($criteria);
        foreach (array_keys($downloads_field) as $i) {
            /** @var \XoopsModules\Tdmdownloads\Field[] $downloads_field */

            $contenu = '';

            $iddata = 0;

            if (0 == $downloads_field[$i]->getVar('status_def')) {
                $criteria = new \CriteriaCompo();

                $criteria->add(new \Criteria('lid', $viewModdownloads->getVar('requestid')));

                $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));

                $downloadsfieldmoddata = $modifieddataHandler->getAll($criteria);

                foreach (array_keys($downloadsfieldmoddata) as $j) {
                    /** @var \XoopsModules\Tdmdownloads\Modified[] $downloadsfieldmoddata */

                    $contenu = $downloadsfieldmoddata[$j]->getVar('moddata');
                }

                $criteria = new \CriteriaCompo();

                $criteria->add(new \Criteria('lid', $viewModdownloads->getVar('lid')));

                $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));

                $downloadsfielddata = $fielddataHandler->getAll($criteria);

                foreach (array_keys($downloadsfielddata) as $j) {
                    /** @var \XoopsModules\Tdmdownloads\Fielddata[] $downloadsfielddata */

                    $iddata = $downloadsfielddata[$j]->getVar('iddata');
                }

                if (0 == $iddata) {
                    $objdata = $fielddataHandler->create();

                    $objdata->setVar('fid', $downloads_field[$i]->getVar('fid'));

                    $objdata->setVar('lid', $viewModdownloads->getVar('lid'));
                } else {
                    $objdata = $fielddataHandler->get($iddata);
                }

                $objdata->setVar('data', $contenu);

                $fielddataHandler->insert($objdata) || $objdata->getHtmlErrors();
            }
        }
        // supression du rapport de modification
        $objmod = $modifiedHandler->get(\Xmf\Request::getInt('mod_id', 0, 'REQUEST'));
        $modifiedHandler->delete($objmod);
        // supression des data des champs sup
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('lid', \Xmf\Request::getInt('mod_id', 0, 'REQUEST')));
        $downloads_fielddata = $modifieddataHandler->getAll($criteria);
        foreach (array_keys($downloads_fielddata) as $i) {
            /** @var \XoopsModules\Tdmdownloads\Fielddata[] $downloads_fielddata */

            $objfielddata = $modifieddataHandler->get($downloads_fielddata[$i]->getVar('modiddata'));

            $modifieddataHandler->delete($objfielddata) || $objvfielddata->getHtmlErrors();
        }
        // enregistrement
        if ($downloadsHandler->insert($obj)) {
            redirect_header('modified.php', 1, _AM_TDMDOWNLOADS_REDIRECT_SAVE);
        }
        $GLOBALS['xoopsTpl']->assign('message_erreur', $obj->getHtmlErrors());
        break;
}

// Local icons path
if (is_object($helper->getModule())) {
    $pathModIcon16 = $helper->getModule()->getInfo('modicons16');

    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');

    $GLOBALS['xoopsTpl']->assign('pathModIcon16', XOOPS_URL . '/modules/' . $moduleDirName . '/' . $pathModIcon16);

    $GLOBALS['xoopsTpl']->assign('pathModIcon32', $pathModIcon32);
}

require __DIR__ . '/admin_footer.php';
