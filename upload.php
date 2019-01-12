<?php
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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link            https://xoops.org/
 * @min_xoops       2.5.9
 * @author          Wedega - Email:<webmaster@wedega.com> - Website:<https://wedega.com>
 */

use Xmf\Request;
use XoopsModules\Tdmdownloads;

include_once __DIR__ . '/header.php';
// It recovered the value of argument op in URL$
$op    = Request::getString('op', 'form');
$albId = Request::getInt('alb_id', 0);
// Template
$GLOBALS['xoopsOption']['template_main'] = $moduleDirName . '_upload.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$GLOBALS['xoopsTpl']->assign('wggallery_icon_url_16', constant($moduleDirNameUpper . '_' . 'ICONS_URL') . '/16');

$categoryHandler = new \XoopsModules\Tdmdownloads\CategoryHandler();

// Form Create
if (isset($albId)) {
    $categoryObj = $categoryHandler->get($albId);
} else {
    $categoryObj = $categoryHandler->create();
}

if ($permissionsHandler->permGlobalSubmit()) {
//    $form = $categoryObj->getFormUploadToAlbum();

    $form               = new XoopsModules\Tdmdownloads\Form\FieldForm();
//    $form->display();

    $GLOBALS['xoopsTpl']->assign('form', $form->render());

    if (0 < $albId) {
        $GLOBALS['xoopsTpl']->assign('albId', $albId);

        $albumObj = $categoryHandler->get($albId);
        // get config for file type/extenstion
        $fileextions = $helper->getConfig('fileext');
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
        $xoopsTpl->assign('img_maxsize', $helper->getConfig('maxsize'));
        $xoopsTpl->assign('img_maxwidth', $helper->getConfig('maxwidth'));
        $xoopsTpl->assign('img_maxheight', $helper->getConfig('maxheight'));
        $xoopsTpl->assign('img_albname', $albumObj->getVar('alb_name'));
        $xoopsTpl->assign('allowedfileext', $albumObj->getVar('allowedfileext'));
        $xoopsTpl->assign('allowedmimetypes', $albumObj->getVar('allowedmimetypes'));
        $payload = [
            'aud'     => 'ajaxfineupload.php',
            'cat'     => $albId,
            'uid'     => $xoopsUser instanceof \XoopsUser ? $xoopsUser->id() : 0,
            'handler' => '\XoopsModules\\' . $moduleDirName . '\FineimpuploadHandler',
            'moddir'  => $moduleDirName,
        ];
        $jwt     = \Xmf\Jwt\TokenFactory::build('fineuploader', $payload, 60 * 30); // token good for 30 minutes
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
include __DIR__ . '/footer.php';
