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

use XoopsModules\Tdmdownloads\Helper;

/**
 * Prepares system prior to attempting to install module
 * @param \XoopsModule $module {@link XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_install_tdmdownloads(\XoopsModule $module)
{
    require_once dirname(__DIR__, 3) . '/mainfile.php';
    //    require_once __DIR__ . '/common.php';
    $utility       = new \XoopsModules\Tdmdownloads\Utility();
    $xoopsSuccess0 = $utility::checkVerXoops($module);
    $xoopsSuccess  = $utility::checkVerXoops($module);
    $phpSuccess0   = $utility::checkVerPhp($module);
    $phpSuccess    = $utility::checkVerPhp($module);
    if ($xoopsSuccess && $phpSuccess) {
        $mod_tables = &$module->getInfo('tables');
        foreach ($mod_tables as $table) {
            $GLOBALS['xoopsDB']->queryF('DROP TABLE IF EXISTS ' . $GLOBALS['xoopsDB']->prefix($table) . ';');
        }
    }
    return $xoopsSuccess && $phpSuccess;
}

/**
 * @return bool
 */
function xoops_module_install_tdmdownloads()
{
    global $xoopsModule, $xoopsConfig, $xoopsDB;
    $moduleDirName = basename(dirname(__DIR__));
    $namemodule    = $moduleDirName;
    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/language/' . $xoopsConfig['language'] . '/admin.php')) {
        require_once XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/language/' . $xoopsConfig['language'] . '/admin.php';
    } else {
        require_once XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/language/english/admin.php';
    }
    $fieldHandler = Helper::getInstance()->getHandler('Field');
    $obj          = $fieldHandler->create();
    $obj->setVar('title', _AM_TDMDOWNLOADS_FORMHOMEPAGE);
    $obj->setVar('img', 'homepage.png');
    $obj->setVar('weight', 1);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $fieldHandler->insert($obj);
    $obj = $fieldHandler->create();
    $obj->setVar('title', _AM_TDMDOWNLOADS_FORMVERSION);
    $obj->setVar('img', 'version.png');
    $obj->setVar('weight', 2);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $fieldHandler->insert($obj);
    $obj = $fieldHandler->create();
    $obj->setVar('title', _AM_TDMDOWNLOADS_FORMSIZE);
    $obj->setVar('img', 'size.png');
    $obj->setVar('weight', 3);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $fieldHandler->insert($obj);
    $obj = $fieldHandler->create();
    $obj->setVar('title', _AM_TDMDOWNLOADS_FORMPLATFORM);
    $obj->setVar('img', 'platform.png');
    $obj->setVar('weight', 4);
    $obj->setVar('search', 0);
    $obj->setVar('status', 1);
    $obj->setVar('status_def', 1);
    $fieldHandler->insert($obj);
    //File creation ".$namemodule."/
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
    chmod($dir, 0777);
    //File creation ".$namemodule."/images/
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
    chmod($dir, 0777);
    //File creation ".$namemodule."/images/cat
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/cats';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
    chmod($dir, 0777);
    //File creation ".$namemodule."/images/shots
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/shots';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
    chmod($dir, 0777);
    //File creation ".$namemodule."/images/field
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
    chmod($dir, 0777);
    //File creation ".$namemodule."/downloads
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/downloads';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
    chmod($dir, 0777);
    //Copy index.html
    $indexFile = XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/include/index.php';
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/index.php');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/index.php');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/cats/index.php');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/shots/index.php');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field/index.php');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/downloads/index.php');
    //Copy blank.gif
    $blankFile = XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/assets/images/blank.gif';
    copy($blankFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/cats/blank.gif');
    copy($blankFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/shots/blank.gif');
    copy($blankFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field/blank.gif');
    //Copy images for fields
    copy(XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/assets/images/icons/16/homepage.png', XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field/homepage.png');
    copy(XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/assets/images/icons/16/version.png', XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field/version.png');
    copy(XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/assets/images/icons/16/size.png', XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field/size.png');
    copy(XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/assets/images/icons/16/platform.png', XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field/platform.png');
    return true;
}
