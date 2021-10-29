<?php

declare(strict_types=1);

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

use Xmf\Database\TableLoad;
use Xmf\Database\Tables;
use Xmf\Module\Admin;

require __DIR__ . '/admin_header.php';
xoops_cp_header();
// Template
$templateMain = 'tdmdownloads_admin_import.tpl';
$adminObject  = Admin::getInstance();
//Action dans switch
$op = 'index';
if (\Xmf\Request::hasVar('op', 'REQUEST')) {
    $op = \Xmf\Request::getCmd('op', '', 'REQUEST');
}
$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));
// import depuis mydownloads
/**
 * @param string $path
 * @param string $imgurl
 */
function importMydownloads($path = '', $imgurl = '')
{
    $moduleDirName = basename(dirname(__DIR__));
    $ok            = \Xmf\Request::getInt('ok', 0, 'POST');
    global $xoopsDB;
    if (1 === $ok) {
        //Vider les tables
        $myTables = ['tdmdownloads_broken', 'tdmdownloads_cat', 'tdmdownloads_downloads', 'tdmdownloads_fielddata', 'tdmdownloads_modfielddata', 'tdmdownloads_votedata'];
        $table    = new TableLoad();
        $tables   = new Tables();
        foreach ($myTables as $myTable) {
            if ($tables->useTable($myTable)) { // if this returns false, there is no table
                $table::truncateTable($myTable);
            }
        }
        //Inserer les données des catégories
        $result = $xoopsDB->query('SELECT cid, pid, title, imgurl FROM ' . $xoopsDB->prefix('mydownloads_cat'));
        if ($result instanceof \mysqli_result) {
            while (false !== ($donnees = $xoopsDB->fetchArray($result))) {
                if ('' === $donnees['imgurl']) {
                    $img = 'blank.gif';
                } else {
                    $img = substr_replace($donnees['imgurl'], '', 0, mb_strlen($imgurl));
                    @copy($path . $img, XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/cats/' . $img);
                }
                $title  = $donnees['title'];
                $insert = $xoopsDB->queryF('INSERT INTO ' . $xoopsDB->prefix('tdmdownloads_cat') . " (cat_cid, cat_pid, cat_title, cat_imgurl, cat_description_main, cat_weight ) VALUES ('" . $donnees['cid'] . "', '" . $donnees['pid'] . "', '" . $title . "', '" . $img . "', '', '0')");
                if (!$insert) {
                    $errors[] = ['title' => _AM_TDMDOWNLOADS_IMPORT_ERROR_DATA, 'info' => $donnees['title']];
                }
                $successes[] = sprintf(_AM_TDMDOWNLOADS_IMPORT_CAT_IMP, $donnees['title']);
            }
        }
        echo '<br>';
        //Inserer les données des téléchargemnts
        $result = $xoopsDB->query('SELECT lid, cid, title, url, homepage, version, size, platform, logourl, submitter, status, date, hits, rating, votes, comments FROM ' . $xoopsDB->prefix('mydownloads_downloads'));
        if ($result instanceof \mysqli_result) {
            while (false !== ($donnees = $xoopsDB->fetchArray($result))) {
                //On recupere la description
                $requete = $xoopsDB->queryF('SELECT description FROM ' . $xoopsDB->prefix('mydownloads_text') . " WHERE lid = '" . $donnees['lid'] . "'");
                [$description] = $xoopsDB->fetchRow($requete);
                $insert = $xoopsDB->queryF(
                    'INSERT INTO '
                    . $xoopsDB->prefix('tdmdownloads_downloads')
                    . " (
            lid, cid, title, url, homepage, version, size, platform, description, logourl, submitter, status, date, hits, rating, votes, comments, top) VALUES
            ('"
                    . $donnees['lid']
                    . "', '"
                    . $donnees['cid']
                    . "', '"
                    . $donnees['title']
                    . "', '"
                    . $donnees['url']
                    . "', '"
                    . $donnees['homepage']
                    . "', '"
                    . $donnees['version']
                    . "', '"
                    . $donnees['size']
                    . "', '"
                    . $donnees['platform']
                    . "', '"
                    . $description
                    . "',  '"
                    . $donnees['logourl']
                    . "', '"
                    . $donnees['submitter']
                    . "', '"
                    . $donnees['status']
                    . "', '"
                    . $donnees['date']
                    . "', '"
                    . $donnees['hits']
                    . "', '"
                    . $donnees['rating']
                    . "', '"
                    . $donnees['votes']
                    . "', '0', '0' )"
                );
                if (!$insert) {
                    $errors[] = ['title' => _AM_TDMDOWNLOADS_IMPORT_ERROR_DATA, 'info' => $donnees['title']];
                }
                $successes[] = sprintf(_AM_TDMDOWNLOADS_IMPORT_DOWNLOADS_IMP, $donnees['title']);
                @copy($path . $donnees['logourl'], XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/shots/' . $donnees['logourl']);
            }
        }
        echo '<br>';
        //Inserer les données des votes
        $result = $xoopsDB->query('SELECT ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp FROM ' . $xoopsDB->prefix('mydownloads_votedata'));
        if ($result instanceof \mysqli_result) {
            while (false !== ($donnees = $xoopsDB->fetchArray($result))) {
                $insert = $xoopsDB->queryF(
                    'INSERT INTO '
                    . $xoopsDB->prefix('tdmdownloads_votedata')
                    . " (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp ) VALUES ('"
                    . $donnees['ratingid']
                    . "', '"
                    . $donnees['lid']
                    . "', '"
                    . $donnees['ratinguser']
                    . "', '"
                    . $donnees['rating']
                    . "', '"
                    . $donnees['ratinghostname']
                    . "', '"
                    . $donnees['ratingtimestamp']
                    . "')"
                );
                if (!$insert) {
                    $errors[] = ['title' => _AM_TDMDOWNLOADS_IMPORT_ERROR_DATA, 'info' => $donnees['ratingid']];
                }
                $successes[] = sprintf(_AM_TDMDOWNLOADS_IMPORT_VOTE_IMP, $donnees['ratingid']);
            }
        }
        echo '<br><br>';
        echo "<div class='errorMsg'>";
        echo _AM_TDMDOWNLOADS_IMPORT_OK;
        echo '</div>';
    } else {
        xoops_confirm(['op' => 'importMydownloads', 'ok' => 1, 'path' => $path, 'imgurl' => $imgurl], 'import.php', _AM_TDMDOWNLOADS_IMPORT_CONF_MYDOWNLOADS . '<br>');
    }
}

// import depuis WF-Downloads
/**
 * @param string $shots
 * @param string $catimg
 */
function importWfdownloads($shots = '', $catimg = '')
{
    $moduleDirName = basename(dirname(__DIR__));
    $ok            = \Xmf\Request::getInt('ok', 0, 'POST');
    global $xoopsDB;
    if (1 === $ok) {
        //Vider les tables
        $myTables = ['tdmdownloads_broken', 'tdmdownloads_cat', 'tdmdownloads_downloads', 'tdmdownloads_fielddata', 'tdmdownloads_modfielddata', 'tdmdownloads_votedata'];
        $table    = new TableLoad();
        $tables   = new Tables();
        foreach ($myTables as $myTable) {
            if ($tables->useTable($myTable)) { // if this returns false, there is no table
                $table::truncateTable($myTable);
            }
        }
        //Inserer les données des catégories
        $result = $xoopsDB->query('SELECT cid, pid, title, imgurl, description, total, summary, spotlighttop, spotlighthis, dohtml, dosmiley, doxcode, doimage, dobr, weight, formulize_fid FROM ' . $xoopsDB->prefix('wfdownloads_cat'));
        if ($result instanceof \mysqli_result) {
            while (false !== ($donnees = $xoopsDB->fetchArray($result))) {
                if ('' === $donnees['imgurl']) {
                    $img = 'blank.gif';
                } else {
                    $img = $donnees['imgurl'];
                    @copy($catimg . $img, XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/cats/' . $img);
                }
                $insert = $xoopsDB->queryF(
                    'INSERT INTO ' . $xoopsDB->prefix('tdmdownloads_cat') . " (cat_cid, cat_pid, cat_title, cat_imgurl, cat_description_main, cat_weight ) VALUES ('" . $donnees['cid'] . "', '" . $donnees['pid'] . "', '" . addcslashes($donnees['title'], "'") . "', '" . $img . "', '" . addcslashes(
                        $donnees['description'],
                        "'"
                    ) . "', '" . $donnees['weight'] . "')"
                );
                if (!$insert) {
                    $errors[] = ['title' => _AM_TDMDOWNLOADS_IMPORT_ERROR_DATA, 'info' => $donnees['title']];
                }
                $successes[] = sprintf(_AM_TDMDOWNLOADS_IMPORT_CAT_IMP, $donnees['title']);
            }
        }
        echo '<br>';
        //Inserer les données des téléchargemnts
        $query_links = $xoopsDB->query(
            'SELECT lid, cid, title, url, filename, filetype, homepage, version, size, platform, screenshot, screenshot2, screenshot3, screenshot4, submitter, publisher, status, date, hits, rating, votes, comments, license, mirror, price, paypalemail, features, requirements, homepagetitle, forumid, limitations, versiontypes, dhistory, published, expired, updated, offline, summary, description, ipaddress, notifypub, formulize_idreq  FROM '
            . $xoopsDB->prefix('wfdownloads_downloads')
        );
        if ($query_links instanceof \mysqli_result) {
            while (false !== ($donnees = $xoopsDB->fetchArray($query_links))) {
                if ('' === $donnees['url']) {
                    $newurl = XOOPS_URL . '/uploads/' . $donnees['filename'];
                } else {
                    $newurl = $donnees['url'];
                }
                $insert = $xoopsDB->queryF(
                    'INSERT INTO ' . $xoopsDB->prefix('tdmdownloads_downloads') . " (
            lid, cid, title, url, homepage, version, size, platform, description, logourl, submitter, status, date, hits, rating, votes, comments, top) VALUES
            ('" . $donnees['lid'] . "', '" . $donnees['cid'] . "', '" . addcslashes($donnees['title'], "'") . "', '" . $newurl . "', '" . $donnees['homepage'] . "', '" . $donnees['version'] . "', '" . $donnees['size'] . "', '" . $donnees['platform'] . "', '" . addcslashes(
                        $donnees['description'],
                        "'"
                    ) . "',  '" . $donnees['screenshot'] . "', '" . $donnees['submitter'] . "', '" . $donnees['status'] . "', '" . $donnees['date'] . "', '" . $donnees['hits'] . "', '" . $donnees['rating'] . "', '" . $donnees['votes'] . "', '0', '0' )"
                );
                if (!$insert) {
                    $errors[] = ['title' => _AM_TDMDOWNLOADS_IMPORT_ERROR_DATA, 'info' => $donnees['title']];
                }
                $successes[] = sprintf(_AM_TDMDOWNLOADS_IMPORT_DOWNLOADS_IMP, $donnees['title']);
                @copy($shots . $donnees['screenshot'], XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/shots/' . $donnees['screenshot']);
            }
        }
        echo '<br>';
        //Inserer les données des votes
        $query_vote = $xoopsDB->query('SELECT ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp FROM ' . $xoopsDB->prefix('wfdownloads_votedata'));
        if ($query_vote instanceof \mysqli_result) {
            while (false !== ($donnees = $xoopsDB->fetchArray($query_vote))) {
                $insert = $xoopsDB->queryF(
                    'INSERT INTO '
                    . $xoopsDB->prefix('tdmdownloads_votedata')
                    . " (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp ) VALUES ('"
                    . $donnees['ratingid']
                    . "', '"
                    . $donnees['lid']
                    . "', '"
                    . $donnees['ratinguser']
                    . "', '"
                    . $donnees['rating']
                    . "', '"
                    . $donnees['ratinghostname']
                    . "', '"
                    . $donnees['ratingtimestamp']
                    . "')"
                );
                if (!$insert) {
                    echo '<span style="color: #ff0000; ">' . _AM_TDMDOWNLOADS_IMPORT_ERROR_DATA . ': </span> ' . $donnees['ratingid'] . '<br>';
                }
                echo sprintf(_AM_TDMDOWNLOADS_IMPORT_VOTE_IMP . '<br>', $donnees['ratingid']);
            }
        }
        $successes[] = _AM_TDMDOWNLOADS_IMPORT_OK;
        $GLOBALS['xoopsTpl']->assign('successes', $successes);
        $GLOBALS['xoopsTpl']->assign('errors', $errors);
    } else {
        xoops_confirm(['op' => 'importWfdownloads', 'ok' => 1, 'shots' => $shots, 'catimg' => $catimg], 'import.php', _AM_TDMDOWNLOADS_IMPORT_CONF_WFDOWNLOADS . '<br>');
    }
}

switch ($op) {
    case 'index':
    default:
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS, 'import.php?op=form_mydownloads', 'add');
        $adminObject->addItemButton(_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS, 'import.php?op=form_wfdownloads', 'add');
        $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('center'));
        $GLOBALS['xoopsTpl']->assign('intro', true);
        break;
    // import Mydownloads
    case 'importMydownloads':
        if ('' == \Xmf\Request::getString('path', '', 'REQUEST') || '' == \Xmf\Request::getString('imgurl', '', 'REQUEST')) {
            redirect_header('import.php?op=form_mydownloads', 3, _AM_TDMDOWNLOADS_IMPORT_ERREUR);
        } else {
            importMydownloads(\Xmf\Request::getString('path', '', 'REQUEST'), \Xmf\Request::getString('imgurl', '', 'REQUEST'));
        }
        break;
    case 'form_mydownloads':
        // Get Theme Form
        xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm(_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS, 'form_mydownloads', 'import.php', 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form number
        $counter = 0;
        $check   = '<ul>';
        global $xoopsDB;
        $sql = $xoopsDB->query('SELECT COUNT(lid) AS count FROM ' . $xoopsDB->prefix('mydownloads_downloads'));
        [$count_downloads] = $xoopsDB->fetchRow($sql);
        if ($count_downloads < 1) {
            $check .= '<li>' . _AM_TDMDOWNLOADS_IMPORT_DONT_DOWNLOADS . '</li>';
        } else {
            $check .= '<li>' . sprintf(_AM_TDMDOWNLOADS_IMPORT_NB_DOWNLOADS, $count_downloads) . '</li>';
            $counter++;
        }
        $sql = $xoopsDB->query('SELECT COUNT(cid) AS count FROM ' . $xoopsDB->prefix('mydownloads_cat'));
        [$count_topic] = $xoopsDB->fetchRow($sql);
        if ($count_topic < 1) {
            $check .= '<li>' . _AM_TDMDOWNLOADS_IMPORT_DONT_TOPIC . '</li>';
        } else {
            $check .= '<li>' . sprintf('<br>' . _AM_TDMDOWNLOADS_IMPORT_NB_CAT, $count_topic) . '</li>';
            $counter++;
        }
        $check .= '</ul>';
        $form->addElement(new \XoopsFormLabel(_AM_TDMDOWNLOADS_IMPORT_NUMBER, $check));
        // Form path
        $form->addElement(new \XoopsFormText(_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS_PATH, 'path', 100, 255, XOOPS_ROOT_PATH . '/modules/mydownloads/images/shots/'));
        // Form url
        $form->addElement(new \XoopsFormText(_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS_URL, 'path', 100, 255, XOOPS_URL . '/modules/mydownloads/images/shots/'));
        // To execute
        if ($counter > 0) {
            $form->addElement(new \XoopsFormHidden('op', 'import_mydownloads'));
            $form->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
        } else {
            $form->addElement(new \XoopsFormHidden('op', 'cancel'));
            $form->addElement(new \XoopsFormButton('', 'submit', _CANCEL, 'submit'));
        }
        $GLOBALS['xoopsTpl']->assign('themeForm', $form->render());
        break;
    // import WF-Downloads
    case 'importWfdownloads':
        if ('' === \Xmf\Request::getString('shots') || '' === \Xmf\Request::getString('catimg')) {
            redirect_header('import.php?op=form_wfdownloads', 3, _AM_TDMDOWNLOADS_IMPORT_ERREUR);
        } else {
            importWfdownloads(\Xmf\Request::getString('shots'), \Xmf\Request::getString('catimg'));
        }
        break;
    case 'form_wfdownloads':
        global $xoopsDB;
        // Get Theme Form
        xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm(_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS, 'form_mydownloads', 'import.php', 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form number
        $counter = 0;
        $check   = '<ul>';
        $sql     = $xoopsDB->query('SELECT COUNT(lid) AS count FROM ' . $xoopsDB->prefix('wfdownloads_downloads'));
        [$count_downloads] = $xoopsDB->fetchRow($sql);
        if ($count_downloads < 1) {
            $check .= '<li>' . _AM_TDMDOWNLOADS_IMPORT_DONT_DOWNLOADS . '</li>';
        } else {
            $check .= '<li>' . sprintf(_AM_TDMDOWNLOADS_IMPORT_NB_DOWNLOADS, $count_downloads) . '</li>';
            $counter++;
        }
        $sql = $xoopsDB->query('SELECT COUNT(cid) AS count FROM ' . $xoopsDB->prefix('wfdownloads_cat'));
        [$count_topic] = $xoopsDB->fetchRow($sql);
        if ($count_topic < 1) {
            $check .= '<li>' . _AM_TDMDOWNLOADS_IMPORT_DONT_TOPIC . '</li>';
        } else {
            $check .= '<li>' . sprintf('<br>' . _AM_TDMDOWNLOADS_IMPORT_NB_CAT, $count_topic) . '</li>';
            $counter++;
        }
        $check .= '</ul>';
        $form->addElement(new \XoopsFormLabel(_AM_TDMDOWNLOADS_IMPORT_NUMBER, $check));
        // Form path
        $form->addElement(new \XoopsFormText(_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS_SHOTS, 'path', 100, 255, XOOPS_ROOT_PATH . '/modules/wfdownloads/assets/images/screenshots/'));
        // Form url
        $form->addElement(new \XoopsFormText(_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS_CATIMG, 'catimg', 100, 255, XOOPS_ROOT_PATH . '/modules/wfdownloads/assets/images/category/'));
        // To execute
        if ($counter > 0) {
            $form->addElement(new \XoopsFormHidden('op', 'import_mydownloads'));
            $form->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
        } else {
            $form->addElement(new \XoopsFormHidden('op', 'cancel'));
            $form->addElement(new \XoopsFormButton('', 'submit', _CANCEL, 'submit'));
        }
        $GLOBALS['xoopsTpl']->assign('themeForm', $form->render());
        break;
}
require __DIR__ . '/admin_footer.php';
