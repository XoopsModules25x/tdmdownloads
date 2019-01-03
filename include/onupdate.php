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

function xoops_module_update_tdmdownloads()
{
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
