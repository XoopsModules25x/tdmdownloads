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
xoops_cp_header();
if (TDMDownloads_checkModuleAdmin()) {
    $import_admin = \Xmf\Module\Admin::getInstance();
    echo $import_admin->displayNavigation('import.php');
}
//Action dans switch
if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
} else {
    $op = 'index';
}

// import depuis mydownloads
function Import_mydownloads($path='', $imgurl='')
{
    $ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;
    global $xoopsDB;
    if ($ok == 1) {
        //Vider les tables
        $query = $xoopsDB->queryF('truncate table ' . $xoopsDB->prefix('tdmdownloads_broken'));
        $query = $xoopsDB->queryF('truncate table ' . $xoopsDB->prefix('tdmdownloads_cat'));
        $query = $xoopsDB->queryF('truncate table ' . $xoopsDB->prefix('tdmdownloads_downloads'));
        $query = $xoopsDB->queryF('truncate table ' . $xoopsDB->prefix('tdmdownloads_fielddata'));
        $query = $xoopsDB->queryF('truncate table ' . $xoopsDB->prefix('tdmdownloads_modfielddata'));
        $query = $xoopsDB->queryF('truncate table ' . $xoopsDB->prefix('tdmdownloads_votedata'));
        //Inserer les données des catégories
        $query_topic = $xoopsDB->query('SELECT cid, pid, title, imgurl FROM ' . $xoopsDB->prefix('mydownloads_cat'));
        while (false !== ($donnees = $xoopsDB->fetchArray($query_topic))) {
            if ($donnees['imgurl'] == '') {
                $img = 'blank.gif';
            } else {
                $img = substr_replace($donnees['imgurl'], '', 0, strlen($imgurl));
                @copy($path . $img, XOOPS_ROOT_PATH . '/uploads/tdmdownloads/images/cats/' . $img);
            }

            $title = $donnees['title'];
            $insert = $xoopsDB->queryF('INSERT INTO ' . $xoopsDB->prefix('tdmdownloads_cat') . " (cat_cid, cat_pid, cat_title, cat_imgurl, cat_description_main, cat_weight ) VALUES ('" . $donnees['cid'] . "', '" . $donnees['pid'] . "', '" . $title . "', '" . $img . "', '', '0')");
            if (!$insert) {
                echo '<span style="color: #ff0000; ">' . _AM_TDMDOWNLOADS_IMPORT_ERROR_DATA . ': </span> ' . $donnees['title'] . '<br>';
            }
            echo sprintf(_AM_TDMDOWNLOADS_IMPORT_CAT_IMP . '<br>', $donnees['title']);
        }
        echo '<br>';

        //Inserer les donnees des téléchargemnts
        $query_links = $xoopsDB->query('SELECT lid, cid, title, url, homepage, version, size, platform, logourl, submitter, status, date, hits, rating, votes, comments FROM ' . $xoopsDB->prefix('mydownloads_downloads'));
        while (false !== ($donnees = $xoopsDB->fetchArray($query_links))) {
            //On recupere la description
            $requete = $xoopsDB->queryF('SELECT description FROM ' . $xoopsDB->prefix('mydownloads_text') . " WHERE lid = '" . $donnees['lid'] . "'");
            list($description) = $xoopsDB -> fetchRow($requete);
            $insert = $xoopsDB->queryF('INSERT INTO '
                                       . $xoopsDB->prefix('tdmdownloads_downloads') . " (
            lid, cid, title, url, homepage, version, size, platform, description, logourl, submitter, status, date, hits, rating, votes, comments, top) VALUES
            ('" .$donnees['lid'] . "', '" .$donnees['cid'] . "', '" .$donnees['title'] . "', '" .$donnees['url'] . "', '" .$donnees['homepage'] . "', '" .$donnees['version'] . "', '" .$donnees['size'] . "', '" .$donnees['platform'] . "', '" . $description . "',  '" .$donnees['logourl'] . "', '" .$donnees['submitter'] . "', '" .$donnees['status'] . "', '" .$donnees['date'] . "', '" .$donnees['hits'] . "', '" .$donnees['rating'] . "', '" .$donnees['votes'] . "', '0', '0' )");
            if (!$insert) {
                echo '<span style="color: #ff0000; ">' . _AM_TDMDOWNLOADS_IMPORT_ERROR_DATA . ': </span> ' . $donnees['title'] . '<br>';
            }
            echo sprintf(_AM_TDMDOWNLOADS_IMPORT_DOWNLOADS_IMP . '<br>', $donnees['title']);
            @copy($path . $donnees['logourl'], XOOPS_ROOT_PATH . '/uploads/tdmdownloads/images/shots/' . $donnees['logourl']);
        }
        echo '<br>';
        //Inserer les donnees des votes
        $query_vote = $xoopsDB->query('SELECT ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp FROM ' . $xoopsDB->prefix('mydownloads_votedata'));
        while (false !== ($donnees = $xoopsDB->fetchArray($query_vote))) {
            $insert = $xoopsDB->queryF('INSERT INTO '
                                       . $xoopsDB->prefix('tdmdownloads_votedata') . " (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp ) VALUES ('" . $donnees['ratingid'] . "', '" . $donnees['lid'] . "', '" . $donnees['ratinguser'] . "', '" . $donnees['rating'] . "', '" . $donnees['ratinghostname'] . "', '" . $donnees['ratingtimestamp'] . "')");
            if (!$insert) {
                echo '<span style="color: #ff0000; ">' . _AM_TDMDOWNLOADS_IMPORT_ERROR_DATA . ': </span> ' . $donnees['ratingid'] . '<br>';
            }
            echo sprintf(_AM_TDMDOWNLOADS_IMPORT_VOTE_IMP . '<br>', $donnees['ratingid']);
        }
        echo '<br><br>';
        echo "<div class='errorMsg'>";
        echo _AM_TDMDOWNLOADS_IMPORT_OK;
        echo '</div>';
    } else {
        xoops_confirm(['op' => 'import_mydownloads', 'ok' => 1, 'path' => $path, 'imgurl' => $imgurl], 'import.php', _AM_TDMDOWNLOADS_IMPORT_CONF_MYDOWNLOADS . '<br>');
    }
}

// import depuis WF-Downloads
function Import_wfdownloads($shots='', $catimg='')
{
    $ok =  isset($_POST['ok']) ? intval($_POST['ok']) : 0;
    global $xoopsDB;
    if ($ok == 1) {
        //Vider les tables
        $query = $xoopsDB->queryF('truncate table ' . $xoopsDB->prefix('tdmdownloads_broken'));
        $query = $xoopsDB->queryF('truncate table ' . $xoopsDB->prefix('tdmdownloads_cat'));
        $query = $xoopsDB->queryF('truncate table ' . $xoopsDB->prefix('tdmdownloads_downloads'));
        $query = $xoopsDB->queryF('truncate table ' . $xoopsDB->prefix('tdmdownloads_fielddata'));
        $query = $xoopsDB->queryF('truncate table ' . $xoopsDB->prefix('tdmdownloads_modfielddata'));
        $query = $xoopsDB->queryF('truncate table ' . $xoopsDB->prefix('tdmdownloads_votedata'));
        //Inserer les données des catégories
        $query_topic = $xoopsDB->query('SELECT cid, pid, title, imgurl, description, total, summary, spotlighttop, spotlighthis, dohtml, dosmiley, doxcode, doimage, dobr, weight, formulize_fid FROM ' . $xoopsDB->prefix('wfdownloads_cat'));
        while (false !== ($donnees = $xoopsDB->fetchArray($query_topic))) {
            if ($donnees['imgurl'] == '') {
                $img = 'blank.gif';
            } else {
                $img = $donnees['imgurl'];
                @copy($catimg . $img, XOOPS_ROOT_PATH . '/uploads/tdmdownloads/images/cats/' . $img);
            }
            $insert = $xoopsDB->queryF('INSERT INTO '
                                       . $xoopsDB->prefix('tdmdownloads_cat') . " (cat_cid, cat_pid, cat_title, cat_imgurl, cat_description_main, cat_weight ) VALUES ('" . $donnees['cid'] . "', '" . $donnees['pid'] . "', '" . addcslashes($donnees['title'], "'") . "', '" . $img . "', '" . addcslashes($donnees['description'], "'") . "', '" . $donnees['weight'] . "')");
            if (!$insert) {
                echo '<span style="color: #ff0000; ">' . _AM_TDMDOWNLOADS_IMPORT_ERROR_DATA . ': </span> ' . $donnees['title'] . '<br>';
            }
            echo sprintf(_AM_TDMDOWNLOADS_IMPORT_CAT_IMP . '<br>', $donnees['title']);
        }
        echo '<br>';

        //Inserer les donnees des téléchargemnts
        $query_links = $xoopsDB->query('SELECT lid, cid, title, url, filename, filetype, homepage, version, size, platform, screenshot, screenshot2, screenshot3, screenshot4, submitter, publisher, status, date, hits, rating, votes, comments, license, mirror, price, paypalemail, features, requirements, homepagetitle, forumid, limitations, versiontypes, dhistory, published, expired, updated, offline, summary, description, ipaddress, notifypub, formulize_idreq  FROM '
                                       . $xoopsDB->prefix('wfdownloads_downloads'));
        while (false !== ($donnees = $xoopsDB->fetchArray($query_links))) {
            if ($donnees['url']=='') {
                $newurl = XOOPS_URL . '/uploads/' . $donnees['filename'];
            } else {
                $newurl = $donnees['url'];
            }
            $insert = $xoopsDB->queryF('INSERT INTO '
                                       . $xoopsDB->prefix('tdmdownloads_downloads') . " (
            lid, cid, title, url, homepage, version, size, platform, description, logourl, submitter, status, date, hits, rating, votes, comments, top) VALUES
            ('" .$donnees['lid'] . "', '" .$donnees['cid'] . "', '" . addcslashes($donnees['title'], "'") . "', '" . $newurl . "', '" .$donnees['homepage'] . "', '" .$donnees['version'] . "', '" .$donnees['size'] . "', '" .$donnees['platform'] . "', '" . addcslashes($donnees['description'], "'") . "',  '" .$donnees['screenshot'] . "', '" .$donnees['submitter'] . "', '" .$donnees['status'] . "', '" .$donnees['date'] . "', '" .$donnees['hits'] . "', '" .$donnees['rating'] . "', '" .$donnees['votes'] . "', '0', '0' )");

            if (!$insert) {
                echo '<span style="color: #ff0000; ">' . _AM_TDMDOWNLOADS_IMPORT_ERROR_DATA . ': </span> ' . $donnees['title'] . '<br>';
            }
            echo sprintf(_AM_TDMDOWNLOADS_IMPORT_DOWNLOADS_IMP . '<br>', $donnees['title']);
            @copy($shots . $donnees['screenshot'], XOOPS_ROOT_PATH . '/uploads/tdmdownloads/images/shots/' . $donnees['screenshot']);
        }
        echo '<br>';

        //Inserer les donnees des votes
        $query_vote = $xoopsDB->query('SELECT ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp FROM ' . $xoopsDB->prefix('wfdownloads_votedata'));
        while (false !== ($donnees = $xoopsDB->fetchArray($query_vote))) {
            $insert = $xoopsDB->queryF('INSERT INTO '
                                       . $xoopsDB->prefix('tdmdownloads_votedata') . " (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp ) VALUES ('" . $donnees['ratingid'] . "', '" . $donnees['lid'] . "', '" . $donnees['ratinguser'] . "', '" . $donnees['rating'] . "', '" . $donnees['ratinghostname'] . "', '" . $donnees['ratingtimestamp'] . "')");
            if (!$insert) {
                echo '<span style="color: #ff0000; ">' . _AM_TDMDOWNLOADS_IMPORT_ERROR_DATA . ': </span> ' . $donnees['ratingid'] . '<br>';
            }
            echo sprintf(_AM_TDMDOWNLOADS_IMPORT_VOTE_IMP . '<br>', $donnees['ratingid']);
        }
        echo '<br><br>';
        echo "<div class='errorMsg'>";
        echo _AM_TDMDOWNLOADS_IMPORT_OK;
        echo '</div>';
    } else {
        xoops_confirm(['op' => 'import_wfdownloads', 'ok' => 1, 'shots' => $shots, 'catimg' => $catimg], 'import.php', _AM_TDMDOWNLOADS_IMPORT_CONF_WFDOWNLOADS . '<br>');
    }
}

switch ($op) {
    case 'index':
        default:
        echo '<br><br>';
        echo "<div class='errorMsg'>";
        echo _AM_TDMDOWNLOADS_IMPORT_WARNING;
        echo '</div>';
        echo '<br><br>';
        if (TDMDownloads_checkModuleAdmin()) {
            $a_admin = \Xmf\Module\Admin::getInstance();
            $a_admin->addItemButton(_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS, 'import.php?op=form_mydownloads', 'add');
            $a_admin->addItemButton(_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS, 'import.php?op=form_wfdownloads', 'add');
            echo $a_admin->displayButton('center');
        }
    break;

    // import Mydownloads
    case 'import_mydownloads':
        if ($_REQUEST['path'] == '' || $_REQUEST['imgurl'] == '') {
            redirect_header('import.php?op=form_mydownloads', 3, _AM_TDMDOWNLOADS_IMPORT_ERREUR);
        } else {
            Import_mydownloads($_REQUEST['path'], $_REQUEST['imgurl']);
        }
    break;

    case 'form_mydownloads':
        echo '<br><br>';
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_TDMDOWNLOADS_IMPORT_NUMBER . '</legend>';
        global $xoopsDB;
        $query = $xoopsDB->query('SELECT COUNT(lid) as count FROM ' . $xoopsDB->prefix('mydownloads_downloads'));
        list($count_downloads) = $xoopsDB->fetchRow($query) ;
        if ($count_downloads < 1) {
            echo _AM_TDMDOWNLOADS_IMPORT_DONT_DOWNLOADS . '<br>';
        } else {
            echo sprintf(_AM_TDMDOWNLOADS_IMPORT_NB_DOWNLOADS, $count_downloads);
        }
        $query = $xoopsDB->query('SELECT COUNT(cid) as count FROM ' . $xoopsDB->prefix('mydownloads_cat'));
        list($count_topic) = $xoopsDB->fetchRow($query) ;
        if ($count_topic < 1) {
            echo '' . _AM_TDMDOWNLOADS_IMPORT_DONT_TOPIC . '<br>';
        } else {
            echo sprintf('<br>' . _AM_TDMDOWNLOADS_IMPORT_NB_CAT, $count_topic);
        }
        echo '</fieldset>';
        echo '<br><br>';
        echo "<table width='100%' border='0'>
                <form action='import.php?op=import_mydownloads' method=POST>
                <tr>
                    <td  class='even'>" . _AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS_PATH . "</td>
                    <td  class='odd'><input type='text' name='path' id='import_data' size='100' value='" . XOOPS_ROOT_PATH . "/modules/mydownloads/images/shots/'></td>
                </tr>
                <tr>
                    <td  class='even'>" . _AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS_URL . "</td>
                    <td  class='odd'><input type='text' name='imgurl' id='import_data' size='100' value='" . XOOPS_URL . "/modules/mydownloads/images/shots/'></td>
                </tr>
                <tr>
                    <td  class='even'>" . _AM_TDMDOWNLOADS_IMPORT_DOWNLOADS . "</td>
                    <td  class='odd'><input type='submit' name='button' id='import_data' value='" . _AM_TDMDOWNLOADS_IMPORT1 . "'></td>
                </tr>
                </form>
            </table>";
    break;

    // import WF-Downloads
    case 'import_wfdownloads':
        if ($_REQUEST['shots'] == '' || $_REQUEST['catimg'] == '') {
            redirect_header('import.php?op=form_wfdownloads', 3, _AM_TDMDOWNLOADS_IMPORT_ERREUR);
        } else {
            Import_wfdownloads($_REQUEST['shots'], $_REQUEST['catimg']);
        }
    break;

    case 'form_wfdownloads':
        echo '<br><br>';
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_TDMDOWNLOADS_IMPORT_NUMBER . '</legend>';
        global $xoopsDB;
        $query = $xoopsDB->query('SELECT COUNT(lid) as count FROM ' . $xoopsDB->prefix('wfdownloads_downloads'));
        list($count_downloads) = $xoopsDB->fetchRow($query) ;
        if ($count_downloads < 1) {
            echo _AM_TDMDOWNLOADS_IMPORT_DONT_DOWNLOADS . '<br>';
        } else {
            echo sprintf(_AM_TDMDOWNLOADS_IMPORT_NB_DOWNLOADS, $count_downloads);
        }
        $query = $xoopsDB->query('SELECT COUNT(cid) as count FROM ' . $xoopsDB->prefix('wfdownloads_cat'));
        list($count_topic) = $xoopsDB->fetchRow($query) ;
        if ($count_topic < 1) {
            echo '' . _AM_TDMDOWNLOADS_IMPORT_DONT_TOPIC . '<br>';
        } else {
            echo sprintf('<br>' . _AM_TDMDOWNLOADS_IMPORT_NB_CAT, $count_topic);
        }
        echo '</fieldset>';
        echo '<br><br>';
        echo "<table width='100%' border='0'>
                <form action='import.php?op=import_wfdownloads' method=POST>
                <tr>
                    <td  class='even'>" . _AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS_SHOTS . "</td>
                    <td  class='odd'><input type='text' name='shots' id='import_data' size='100' value='" . XOOPS_ROOT_PATH . "/modules/wfdownloads/images/screenshots/'></td>
                </tr>
                <tr>
                    <td  class='even'>" . _AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS_CATIMG . "</td>
                    <td  class='odd'><input type='text' name='catimg' id='import_data' size='100' value='" . XOOPS_ROOT_PATH . "/modules/wfdownloads/images/category/'></td>
                </tr>
                <tr>
                    <td  class='even'>" . _AM_TDMDOWNLOADS_IMPORT_DOWNLOADS . "</td>
                    <td  class='odd'><input type='submit' name='button' id='import_data' value='" . _AM_TDMDOWNLOADS_IMPORT1 . "'></td>
                </tr>
                </form>
            </table>";
    break;
}

xoops_cp_footer();
