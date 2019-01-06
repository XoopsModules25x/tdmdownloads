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
 * @param      $module
 * @param null $prev_version
 * @return bool|null
 */
function xoops_module_update_tdmdownloads(&$module, $prev_version = null)
{
    $ret = null;
    if ($prev_version < 163) {
        $ret = update_tdmdownloads_v163($module);
    }
    if ($prev_version < 167) {
        $ret = update_tdmdownloads_v167($module);
    }
    $errors = $module->getErrors();
    if (!empty($errors)) {
        //        print_r($errors);
    }

    return $ret;
}

/**
 * @param $module
 *
 * @return bool
 */
function update_tdmdownloads_v167(&$module)
{
    // rename module dir from upper case to lower case
    rename(XOOPS_ROOT_PATH . '/modules/TDMDownloads', XOOPS_ROOT_PATH . '/modules/tdmdownloads');
    // rename upload dir from upper case to lower case
    rename(XOOPS_ROOT_PATH . '/uploads/TDMDownloads', XOOPS_ROOT_PATH . '/uploads/tdmdownloads');

    // files have been moved to assets-folder
    $src = XOOPS_ROOT_PATH . '/modules/tdmdownloads/css/';

    rrmdir($src);
    $src = XOOPS_ROOT_PATH . '/modules/tdmdownloads/images/';

    rrmdir($src);

    // delete unneeded/replacfiles
    // unlink( XOOPS_ROOT_PATH.'/modules/tdmdownloads/admin/admin_header.php' );

    // clean template directory
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/tdmdownloads_brokenfile.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/tdmdownloads_download.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/tdmdownloads_index.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/tdmdownloads_modfile.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/tdmdownloads_ratefile.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/tdmdownloads_singlefile.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/tdmdownloads_submit.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/tdmdownloads_viewcat.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/tdmdownloads_liste.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/tdmdownloads_rss.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/blocks/tdmdownloads_block_new.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/blocks/tdmdownloads_block_random.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/blocks/tdmdownloads_block_rating.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/blocks/tdmdownloads_block_search.html');
    unlink(XOOPS_ROOT_PATH . '/modules/tdmdownloads/templates/blocks/tdmdownloads_block_top.html');

    return true;
}

/**
 * @param $src
 */
function rrmdir($src)
{
    if (is_dir($src)) {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (('.' !== $file) && ('..' !== $file)) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    rrmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}

/**
 * @return bool
 */
function update_tdmdownloads_v163()
{
    /** @var \XoopsMySQLDatabase $db */
    $db  = \XoopsDatabaseFactory::getDatabaseConnection();
    $sql = 'ALTER TABLE `' . $db->prefix('tdmdownloads_cat') . '` CHANGE `cid` `cat_cid` INT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT ;';
    $db->query($sql);
    $sql = 'ALTER TABLE `' . $db->prefix('tdmdownloads_cat') . "` CHANGE `pid` `cat_pid` INT( 5 ) UNSIGNED NOT NULL DEFAULT '0' ;";
    $db->query($sql);
    $sql = 'ALTER TABLE `' . $db->prefix('tdmdownloads_cat') . '` CHANGE `title` `cat_title` VARCHAR( 255 ) NOT NULL ;';
    $db->query($sql);
    $sql = 'ALTER TABLE `' . $db->prefix('tdmdownloads_cat') . '` CHANGE `imgurl` `cat_imgurl` VARCHAR( 255 ) NOT NULL ;';
    $db->query($sql);
    $sql = 'ALTER TABLE `' . $db->prefix('tdmdownloads_cat') . '` CHANGE `description_main` `cat_description_main` TEXT NOT NULL ;';
    $db->query($sql);
    $sql = 'ALTER TABLE `' . $db->prefix('tdmdownloads_cat') . "` CHANGE `weight` `cat_weight` INT( 11 ) NOT NULL DEFAULT '0' ;";
    $db->query($sql);
    $sql = 'ALTER TABLE `' . $db->prefix('tdmdownloads_downloads') . '` ADD `paypal` VARCHAR( 255 ) NOT NULL;';
    $db->query($sql);
    $sql = 'ALTER TABLE `' . $db->prefix('tdmdownloads_downloads') . "` CHANGE `size` `size` VARCHAR( 15 ) NOT NULL DEFAULT '';";
    $db->query($sql);
    $sql = 'ALTER TABLE `' . $db->prefix('tdmdownloads_mod') . "` CHANGE `size` `size` VARCHAR( 15 ) NOT NULL DEFAULT '';";
    $db->query($sql);
    $sql = 'CREATE TABLE `' . $db->prefix('tdmdownloads_downlimit') . "` (downlimit_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, downlimit_lid INT(11) UNSIGNED NOT NULL DEFAULT '0',
           downlimit_uid INT(11) NOT NULL DEFAULT '0', downlimit_hostname VARCHAR(60) NOT NULL DEFAULT '', downlimit_date INT(10) NOT NULL DEFAULT '0', PRIMARY KEY  (downlimit_id)
           ) ENGINE=MyISAM";
    $db->query($sql);

    return true;
}
