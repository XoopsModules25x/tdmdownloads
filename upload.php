<?php

declare(strict_types=1);
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * @copyright       XOOPS Project https://xoops.org/
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link            https://xoops.org/
 * @min_xoops       2.5.9
 * @author          Wedega - Email:<webmaster@wedega.com> - Website:<https://wedega.com>
 */

use Xmf\Jwt\TokenFactory;
use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\Tdmdownloads\{
    CategoryHandler,
    Form\UploadForm
};

/** @var \Xmf\Module\Helper\Permission $permHelper */

require_once __DIR__ . '/header.php';
$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);
// It recovered the value of argument op in URL$
$op    = Request::getString('op', 'form');
$catId = Request::getInt('cat_cid', 0);
// Template
$GLOBALS['xoopsOption']['template_main'] = $moduleDirName . '_upload.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
$pathIcon16 = Admin::iconUrl('', '16');
$GLOBALS['xoopsTpl']->assign('pathIcon16', $pathIcon16);
$categoryHandler = new CategoryHandler();
// Form Create
if (isset($catId)) {
    $categoryObj = $categoryHandler->get($catId);
} else {
    $categoryObj = $categoryHandler->create();
}
$catId = 1; //for testing, comment out later
$xoopsTpl->assign('multiupload', true);
$form = new UploadForm($categoryObj);
$form->setExtra('enctype="multipart/form-data"');
$GLOBALS['xoopsTpl']->assign('form', $form->render());
$permHelper->checkPermissionRedirect('tdmdownloads_submit', $catId, 'index.php', 3, 'You are not allowed to submit a file', false);
$permissionUpload = $permHelper->checkPermission('tdmdownloads_submit', $catId, false);
if ($permissionUpload) {
    if ($catId > 0) {
        $GLOBALS['xoopsTpl']->assign('catId', $catId);
        $categoryObj = $categoryHandler->get($catId);
        // get config for file type/extenstion
        $fileextions = $helper->getConfig('mimetypes');
        $mimetypes   = [];
        foreach ($fileextions as $fe) {
            switch ($fe) {
                case 'jpg':
                case 'jpeg':
                case 'jpe':
                    $mimetypes['image/jpeg'] = 'image/jpeg';
                    break;
                case 'gif':
                    $mimetypes['image/gif'] = 'image/gif';
                    break;
                case 'png':
                    $mimetypes['image/png'] = 'image/png';
                    break;
                case 'bmp':
                    $mimetypes['image/bmp'] = 'image/bmp';
                    break;
                case 'tiff':
                case 'tif':
                    $mimetypes['image/tiff'] = 'image/tiff';
                    break;
                case 'zip':
                    $mimetypes['application/zip'] = 'application/zip';
                    break;
                case 'else':
                default:
                    break;
            }
        }
        $allowedfileext = implode("', '", $fileextions);
        if ('' !== $allowedfileext) {
            $allowedfileext = "'" . $allowedfileext . "'";
        }
        $allowedmimetypes = implode("', '", $mimetypes);
        if ('' !== $allowedmimetypes) {
            $allowedmimetypes = "'" . $allowedmimetypes . "'";
        }
        // Define Stylesheet
        /** @var xos_opal_Theme $xoTheme */
        $xoTheme->addStylesheet(XOOPS_URL . '/media/fine-uploader/fine-uploader-new.css');
        $xoTheme->addStylesheet(XOOPS_URL . '/media/fine-uploader/ManuallyTriggerUploads.css');
        $xoTheme->addStylesheet(XOOPS_URL . '/media/font-awesome/css/font-awesome.min.css');
        $xoTheme->addStylesheet(XOOPS_URL . '/modules/system/css/admin.css');
        // Define scripts
        $xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
        $xoTheme->addScript('modules/system/js/admin.js');
        $xoTheme->addScript('media/fine-uploader/fine-uploader.js');
        // Define Breadcrumb and tips
        $xoopsTpl->assign('multiupload', true);
        // echo $helper->getConfig('mimetypes');
        $xoopsTpl->assign('file_maxsize', $helper->getConfig('maxuploadsize'));
        $xoopsTpl->assign('img_maxwidth', $helper->getConfig('imageWidth'));
        $xoopsTpl->assign('img_maxheight', $helper->getConfig('imageHeight'));
        $xoopsTpl->assign('categoryname', $categoryObj->getVar('cat_title'));
        $xoopsTpl->assign('allowedfileext', $categoryObj->getVar('allowedfileext'));
        $xoopsTpl->assign('allowedmimetypes', $categoryObj->getVar('allowedmimetypes'));
        $payload = [
            'aud'     => 'ajaxfineupload.php',
            'cat'     => $catId,
            'uid'     => $xoopsUser instanceof \XoopsUser ? $xoopsUser->id() : 0,
            'handler' => '\XoopsModules\\' . ucfirst($moduleDirName) . '\Common\FineimpuploadHandler',
            'moddir'  => $moduleDirName,
        ];
        $jwt     = TokenFactory::build('fineuploader', $payload, 60 * 30); // token good for 30 minutes
        $xoopsTpl->assign('jwt', $jwt);
        setcookie('jwt', $jwt);
        $fineup_debug = 'false';
        if (($xoopsUser instanceof \XoopsUser ? $xoopsUser->isAdmin() : false)
            && isset($_REQUEST['FINEUPLOADER_DEBUG'])) {
            $fineup_debug = 'true';
        }
        $xoopsTpl->assign('fineup_debug', $fineup_debug);
    }
}
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => constant('CO_' . $moduleDirNameUpper . '_IMAGES_UPLOAD')];
//require __DIR__ . '/footer.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
