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

/**
 * @return bool
 */
function xoops_module_install_tdmdownloads()
{
    global $xoopsModule, $xoopsConfig, $xoopsDB;
    $moduleDirName = basename(dirname(__DIR__));

    $namemodule = $moduleDirName;
    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/language/' . $xoopsConfig['language'] . '/admin.php')) {
        require_once XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/language/' . $xoopsConfig['language'] . '/admin.php';
    } else {
        require_once XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/language/english/admin.php';
    }
    $fieldHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Field');
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

    //Creation du fichier ".$namemodule."/
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
    chmod($dir, 0777);

    //Creation du fichier ".$namemodule."/images/
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
    chmod($dir, 0777);

    //Creation du fichier ".$namemodule."/images/cat
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/cats';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
    chmod($dir, 0777);

    //Creation du fichier ".$namemodule."/images/shots
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/shots';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
    chmod($dir, 0777);

    //Creation du fichier ".$namemodule."/images/field
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
    chmod($dir, 0777);

    //Creation du fichier ".$namemodule."/downloads
    $dir = XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/downloads';
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }
    chmod($dir, 0777);

    //Copie des index.html
    $indexFile = XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/include/index.html';
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/index.html');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/index.html');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/cats/index.html');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/shots/index.html');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field/index.html');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/downloads/index.html');

    //Copie des blank.gif
    $blankFile = XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/assets/images/blank.gif';
    copy($blankFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/cats/blank.gif');
    copy($blankFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/shots/blank.gif');
    copy($blankFile, XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field/blank.gif');

    //Copie des images pour les champs
    copy(XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/assets/images/icons/16/homepage.png', XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field/homepage.png');
    copy(XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/assets/images/icons/16/version.png', XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field/version.png');
    copy(XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/assets/images/icons/16/size.png', XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field/size.png');
    copy(XOOPS_ROOT_PATH . '/modules/' . $namemodule . '/assets/images/icons/16/platform.png', XOOPS_ROOT_PATH . '/uploads/' . $namemodule . '/images/field/platform.png');

    return true;
}
