<?php

declare(strict_types=1);

use Xmf\Database\Tables;
use XoopsModules\Tdmdownloads\{
    Common\Configurator,
    Common\Migrate,
    Common\MigrateHelper,
    Helper,
    Utility
};

/** @var Helper $helper */
/** @var Utility $utility */
/** @var Configurator $configurator */
/** @var Migrate $migrator */
if ((!defined('XOOPS_ROOT_PATH')) || !($GLOBALS['xoopsUser'] instanceof XoopsUser)
    || !$GLOBALS['xoopsUser']->isAdmin()) {
    exit('Restricted access' . PHP_EOL);
}
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
 * @param      $module
 * @param null $prev_version
 * @return bool|null
 * @copyright   Gregory Mage (Aka Mage)
 * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage)
 */
/**
 * Prepares system prior to attempting to install module
 * @param \XoopsModule $module {@link XoopsModule}
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_update_tdmdownloads(\XoopsModule $module)
{
    $moduleDirName = \basename(\dirname(__DIR__));
    $helper        = Helper::getInstance();
    $utility       = new Utility();
    $xoopsSuccess  = $utility::checkVerXoops($module);
    $phpSuccess    = $utility::checkVerPhp($module);
    $configurator = new Configurator();
    //create upload folders
    $uploadFolders = $configurator->uploadFolders;
    foreach ($uploadFolders as $value) {
        $utility::prepareFolder($value);
    }

    //$migrator = new Migrate();
    //$migrator->synchronizeSchema();
    return $xoopsSuccess && $phpSuccess;
}

function xoops_module_update_tdmdownloads(&$module, $prev_version = null)
{
    $ret                = null;
    $moduleDirName      = \basename(\dirname(__DIR__));
    $moduleDirNameUpper = \mb_strtoupper($moduleDirName);
    $helper             = Helper::getInstance();
    $utility            = new Utility();
    $configurator       = new Configurator();
    $helper->loadLanguage('common');

    $migrate = new Migrate();

    // convert prev_version into integer
    $prev_version = (int)(str_replace('.', '', (string)$prev_version));

    if ($prev_version < 163) {
        $ret = update_tdmdownloads_v163($module);
    }
    if ($prev_version < 167) {
        $ret = update_tdmdownloads_v167($module);
    }
    if ($prev_version < 200) {
        $ret = update_tdmdownloads_v200($module);
    }
    if ($prev_version < 201) {
        $ret = update_tdmdownloads_v201($module);
    }

    $fileSql = \XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/sql/mysql.sql';
    // ToDo: add function setDefinitionFile to .\class\libraries\vendor\xoops\xmf\src\Database\Migrate.php
    // Todo: once we are using setDefinitionFile this part has to be adapted
    //$fileYaml = \XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/sql/update_' . $moduleDirName . '_migrate.yml';
    //try {
    //$migrate->setDefinitionFile('update_' . $moduleDirName);
    //} catch (\Exception $e) {
    // as long as this is not done default file has to be created
    $moduleVersionOld = $module->getInfo('version');
    $moduleVersionNew = \str_replace(['.', '-'], '_', $moduleVersionOld);
    $fileYaml = \XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . "/sql/{$moduleDirName}_{$moduleVersionNew}_migrate.yml";
    //}

    // create a schema file based on sql/mysql.sql
    $migratehelper = new MigrateHelper($fileSql, $fileYaml);
    if (!$migratehelper->createSchemaFromSqlfile()) {
        \xoops_error('Error: creation schema file failed!');
        return false;
    }

    //create copy for XOOPS 2.5.11 Beta 1 and older versions
    $fileYaml2 = \XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . "/sql/{$moduleDirName}_{$moduleVersionOld}_migrate.yml";
    if (!\copy($fileYaml, $fileYaml2)) {
        \xoops_error('Error: could not create schema file copy: ' . $fileYaml2);
        return false;
    }

    // run standard procedure for db migration
    $migrate->getTargetDefinitions();
    $migrate->synchronizeSchema();

    $errors = $module->getErrors();
    if (!empty($errors)) {
        //        print_r($errors);
    }
    return $ret;
}

/**
 * @param $module
 * @return bool
 */
function update_tdmdownloads_v201($module)
{
    $moduleDirName      = \basename(\dirname(__DIR__));
    $moduleDirNameUpper = \mb_strtoupper($moduleDirName);
    $helper             = Helper::getInstance();
    $utility            = new Utility();
    $configurator       = new Configurator();
    $helper->loadLanguage('common');
    //delete old HTML templates
    if (count($configurator->templateFolders) > 0) {
        foreach ($configurator->templateFolders as $folder) {
            $templateFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $folder);
            if (is_dir($templateFolder)) {
                $templateList = array_diff(scandir($templateFolder, SCANDIR_SORT_NONE), ['..', '.']);
                foreach ($templateList as $k => $v) {
                    $fileInfo = new SplFileInfo($templateFolder . $v);
                    if ('html' === $fileInfo->getExtension() && 'index.html' !== $fileInfo->getFilename()) {
                        if (is_file($templateFolder . $v)) {
                            unlink($templateFolder . $v);
                        }
                    }
                }
            }
        }
    }
    //  ---  DELETE OLD FILES ---------------
    if (count($configurator->oldFiles) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->oldFiles) as $i) {
            $tempFile = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator->oldFiles[$i]);
            if (is_file($tempFile)) {
                unlink($tempFile);
            }
        }
    }
    //  ---  DELETE OLD FOLDERS ---------------
    xoops_load('XoopsFile');
    if (count($configurator->oldFolders) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->oldFolders) as $i) {
            $tempFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator->oldFolders[$i]);
            /** @var XoopsObjectHandler $folderHandler */
            $folderHandler = \XoopsFile::getHandler('folder', $tempFolder);
            $folderHandler->delete($tempFolder);
        }
    }
    //  ---  CREATE UPLOAD FOLDERS ---------------
    if (count($configurator->uploadFolders) > 0) {
        //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
        foreach (array_keys($configurator->uploadFolders) as $i) {
            $utility::createFolder($configurator->uploadFolders[$i]);
        }
    }
    //  ---  COPY blank.png FILES ---------------
    if (count($configurator->copyBlankFiles) > 0) {
        $file = dirname(__DIR__) . '/assets/images/blank.png';
        foreach (array_keys($configurator->copyBlankFiles) as $i) {
            $dest = $configurator->copyBlankFiles[$i] . '/blank.png';
            $utility::copyFile($file, $dest);
        }
    }
    //delete .html entries from the tpl table
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $module->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
    $GLOBALS['xoopsDB']->queryF($sql);
    //delete .tpl entries from the tpl table
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $module->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.tpl%'";
    $GLOBALS['xoopsDB']->queryF($sql);
    //delete tdmdownloads entries from the tpl_source table
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplsource') . " WHERE `tpl_source` LIKE '%tdmdownloads%'";
    $GLOBALS['xoopsDB']->queryF($sql);
    $sql = 'CREATE TABLE `' . $GLOBALS['xoopsDB']->prefix('tdmdownloads_downlimit') . "` (downlimit_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, downlimit_lid INT(11) UNSIGNED NOT NULL DEFAULT '0',
           downlimit_uid INT(11) NOT NULL DEFAULT '0', downlimit_hostname VARCHAR(60) NOT NULL DEFAULT '', downlimit_date INT(10) NOT NULL DEFAULT '0', PRIMARY KEY  (downlimit_id)
           ) ENGINE=MyISAM";
    $GLOBALS['xoopsDB']->query($sql);
    /** @var XoopsGroupPermHandler $gpermHandler */
    $gpermHandler = xoops_getHandler('groupperm');
    return $gpermHandler->deleteByModule($module->getVar('mid'), 'item_read');
}

/**
 * @param $module
 * @return bool
 */
function update_tdmdownloads_v200(&$module)
{
    // Update size
    $moduleDirName = basename(dirname(__DIR__));
    $db            = \XoopsDatabaseFactory::getDatabaseConnection();
    $sql           = 'SELECT lid, size FROM ' . $db->prefix('tdmdownloads_downloads');
    $result        = $db->query($sql);
    $helper        = Helper::getInstance();
    $helper->loadLanguage('admin');
    if ($result instanceof \mysqli_result) {
        while (false !== ($myrow = $db->fetchArray($result))) {
            $size_value_arr = explode(' ', $myrow['size']);
            switch ($size_value_arr[1]) {
                case _AM_TDMDOWNLOADS_BYTES:
                case 'Bytes':
                    $sql = 'UPDATE `' . $db->prefix('tdmdownloads_downloads') . '` SET `size` = \'' . $size_value_arr[0] . ' B\'' . ' WHERE `lid` = ' . $myrow['lid'] . ';';
                    $db->query($sql);
                    break;
                case _AM_TDMDOWNLOADS_KBYTES:
                case 'kB':
                    $sql = 'UPDATE `' . $db->prefix('tdmdownloads_downloads') . '` SET `size` = \'' . $size_value_arr[0] . ' K\'' . ' WHERE `lid` = ' . $myrow['lid'] . ';';
                    $db->query($sql);
                    break;
                case _AM_TDMDOWNLOADS_MBYTES:
                case 'MB':
                    $sql = 'UPDATE `' . $db->prefix('tdmdownloads_downloads') . '` SET `size` = \'' . $size_value_arr[0] . ' M\'' . ' WHERE `lid` = ' . $myrow['lid'] . ';';
                    $db->query($sql);
                    break;
                case _AM_TDMDOWNLOADS_GBYTES:
                case 'GB':
                    $sql = 'UPDATE `' . $db->prefix('tdmdownloads_downloads') . '` SET `size` = \'' . $size_value_arr[0] . ' G\'' . ' WHERE `lid` = ' . $myrow['lid'] . ';';
                    $db->query($sql);
                    break;
                case _AM_TDMDOWNLOADS_TBYTES:
                case 'TB':
                    $sql = 'UPDATE `' . $db->prefix('tdmdownloads_downloads') . '` SET `size` = \'' . $size_value_arr[0] . ' T\'' . ' WHERE `lid` = ' . $myrow['lid'] . ';';
                    $db->query($sql);
                    break;
            }
        }
    }
    // Update folder
    rename(XOOPS_ROOT_PATH . '/uploads/TDMDownloads', XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName);
    // Change TDMDownloads with tdmdownloads
    $sql    = 'UPDATE `' . $db->prefix('tdmdownloads_downloads') . '` SET `url` = REPLACE(`url`, \'TDMDownloads\', \'' . $moduleDirName . '\') WHERE `url` LIKE \'%TDMDownloads%\'';
    $result = $db->query($sql);
    return true;
}

/**
 * @param $module
 * @return bool
 */
function update_tdmdownloads_v167(&$module)
{
    $moduleDirName = basename(dirname(__DIR__));
    $modulePath    = XOOPS_ROOT_PATH . '/modules/' . $moduleDirName;

    // rename module dir from upper case to lower case
    rename(XOOPS_ROOT_PATH . '/modules/TDMDownloads', $modulePath);
    // rename upload dir from upper case to lower case
    rename(XOOPS_ROOT_PATH . '/uploads/TDMDownloads', XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName);
    // files have been moved to assets-folder
    $src = $modulePath . '/css/';
    rrmdir($src);
    $src = $modulePath . '/images/';
    rrmdir($src);
    // delete unneeded/replacfiles
    // unlink( XOOPS_ROOT_PATH.'/modules/' . $moduleDirName . '/admin/admin_header.php' );
    // clean template directory
    @unlink($modulePath . '/templates/tdmdownloads_brokenfile.html');
    @unlink($modulePath . '/templates/tdmdownloads_download.html');
    @unlink($modulePath . '/templates/tdmdownloads_index.html');
    @unlink($modulePath . '/templates/tdmdownloads_modfile.html');
    @unlink($modulePath . '/templates/tdmdownloads_ratefile.html');
    @unlink($modulePath . '/templates/tdmdownloads_singlefile.html');
    @unlink($modulePath . '/templates/tdmdownloads_submit.html');
    @unlink($modulePath . '/templates/tdmdownloads_viewcat.html');
    @unlink($modulePath . '/templates/tdmdownloads_liste.html');
    @unlink($modulePath . '/templates/tdmdownloads_rss.html');
    @unlink($modulePath . '/templates/blocks/tdmdownloads_block_new.html');
    @unlink($modulePath . '/templates/blocks/tdmdownloads_block_random.html');
    @unlink($modulePath . '/templates/blocks/tdmdownloads_block_rating.html');
    @unlink($modulePath . '/templates/blocks/tdmdownloads_block_search.html');
    @unlink($modulePath . '/templates/blocks/tdmdownloads_block_top.html');
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
