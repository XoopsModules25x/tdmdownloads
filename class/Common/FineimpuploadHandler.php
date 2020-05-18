<?php

namespace XoopsModules\Tdmdownloads\Common;

/**
 * SystemFineImUploadHandler class to work with ajaxfineupload.php endpoint
 * to facilitate uploads for the system image manager
 *
 * Do not use or reference this directly from your client-side code.
 * Instead, this should be required via the endpoint.php or endpoint-cors.php
 * file(s).
 *
 * @license   MIT License (MIT)
 * @copyright Copyright (c) 2015-present, Widen Enterprises, Inc.
 * @link      https://github.com/FineUploader/php-traditional-server
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015-present, Widen Enterprises, Inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

use XoopsModules\Tdmdownloads;

//class FineImpUploadHandler extends \SystemFineUploadHandler

/**
 * Class FineimpuploadHandler
 * @package XoopsModules\Tdmdownloads\Common
 */
class FineimpuploadHandler extends \SystemFineUploadHandler
{
    /**
     * @var int
     */
    private $permUseralbum = 0;
    /**
     * @var int
     */
    private $imageId = 0;
    /**
     * @var string
     */
    private $imageName = null;
    /**
     * @var string
     */
    private $imageNameLarge = null;
    /**
     * @var string
     */
    private $imageNicename = null;
    /**
     * @var string
     */
    private $imagePath = null;
    /**
     * @var string
     */
    private $imageNameOrig = null;
    /**
     * @var string
     */
    private $imageMimetype = null;
    /**
     * @var int
     */
    private $imageSize = 0;
    /**
     * @var int
     */
    private $imageWidth = 0;
    /**
     * @var int
     */
    private $imageHeight = 0;
    /**
     * @var string
     */
    private $pathUpload = null;

    /**
     * XoopsFineImUploadHandler constructor.
     * @param \stdClass $claims claims passed in JWT header
     */
    public function __construct(\stdClass $claims)
    {
        parent::__construct($claims);
        $this->allowedMimeTypes  = ['image/gif', 'image/jpeg', 'image/png', 'application/zip'];
        $this->allowedExtensions = ['gif', 'jpeg', 'jpg', 'png', 'zip'];
    }

    /**
     * @param $target
     * @param $mimeType
     * @param $uid
     * @return array|bool
     */
    protected function storeUploadedFile($target, $mimeType, $uid)
    {
        $moduleDirName      = \basename(\dirname(\dirname(__DIR__)));
        $moduleDirNameUpper = \mb_strtoupper($moduleDirName);
        require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/header.php';
        $this->pathUpload = \constant($moduleDirNameUpper . '_' . 'UPLOAD_IMAGE_PATH');
        $utility          = new \XoopsModules\Tdmdownloads\Utility();
        /** @var \XoopsModules\Tdmdownloads\Helper $helper */
        $helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

        //        if ( WGGALLERY_PERM_SUBMITAPPR === $permissionsHandler->permGlobalSubmit()) {
        //            $this->permUseralbum = WGGALLERY_STATE_APPROVAL_VAL;
        //        } else {
        //            $this->permUseralbum = WGGALLERY_STATE_ONLINE_VAL;
        //        }

        $this->permUseralbum = 1; //TODO: handle an option, whether images should be online immediately or not

        $pathParts = \pathinfo($this->getName());

        $this->imageName      = \uniqid('img', true) . '.' . \strtolower($pathParts['extension']);
        $this->imageNicename  = \str_replace(['_', '-'], ' ', $pathParts['filename']);
        $this->imageNameLarge = \uniqid('imgl', true) . '.' . \strtolower($pathParts['extension']);
        $this->imagePath      = $this->pathUpload . '/large/' . $this->imageNameLarge;

        if (false === \move_uploaded_file($_FILES[$this->inputName]['tmp_name'], $this->imagePath)) {
            return false;
        }

        $this->imageNameOrig = $_FILES[$this->inputName]['name'];
        $this->imageMimetype = $_FILES[$this->inputName]['type'];
        $this->imageSize     = $_FILES[$this->inputName]['size'];

        $ret = $this->handleImageDB();
        if (false === $ret) {
            return [
                'error' => \sprintf(\_FAILSAVEIMG, $this->imageNicename),
            ];
        }

        // load watermark settings
        $albumObj  = $albumsHandler->get($this->claims->cat);
        $wmId      = $albumObj->getVar('alb_wmid');
        $wmTargetM = false;
        $wmTargetL = false;
        if (0 < $wmId) {
            $watermarksObj = $watermarksHandler->get($wmId);
            $wmTarget      = $watermarksObj->getVar('wm_target');
            if (\constant($moduleDirNameUpper . '_' . 'WATERMARK_TARGET_A') === $wmTarget || \constant($moduleDirNameUpper . '_' . 'WATERMARK_TARGET_M') === $wmTarget) {
                $wmTargetM = true;
            }
            if (\constant($moduleDirNameUpper . '_' . 'WATERMARK_TARGET_A') === $wmTarget || \constant($moduleDirNameUpper . '_' . 'WATERMARK_TARGET_L') === $wmTarget) {
                $wmTargetL = true;
            }
        }

        // create medium image
        // $ret = $this->resizeImage($this->pathUpload . '/medium/' . $this->imageName, $helper->getConfig('maxwidth_medium'), $helper->getConfig('maxheight_medium'));
        $ret = $utility->resizeImage($this->imagePath, $this->pathUpload . '/medium/' . $this->imageName, $helper->getConfig('maxwidth_medium'), $helper->getConfig('maxheight_medium'), $this->imageMimetype);
        if (false === $ret) {
            return ['error' => \sprintf(\constant($moduleDirNameUpper . '_' . 'FAILSAVEIMG_MEDIUM'), $this->imageNicename)];
        }
        if ('copy' === $ret) {
            \copy($this->pathUpload . '/large/' . $this->imageNameLarge, $this->pathUpload . '/medium/' . $this->imageName);
        }

        // create thumb
        // $ret = $this->resizeImage($this->pathUpload . '/thumbs/' . $this->imageName, $helper->getConfig('maxwidth_thumbs'), $helper->getConfig('maxheight_thumbs'));
        $ret = $utility->resizeImage($this->imagePath, $this->pathUpload . '/thumbs/' . $this->imageName, $helper->getConfig('maxwidth_thumbs'), $helper->getConfig('maxheight_thumbs'), $this->imageMimetype);
        if (false === $ret) {
            return ['error' => \sprintf(\constant($moduleDirNameUpper . '_' . 'FAILSAVEIMG_THUMBS'), $this->imageNicename)];
        }
        if ('copy' === $ret) {
            \copy($this->pathUpload . '/large/' . $this->imageNameLarge, $this->pathUpload . '/thumbs/' . $this->imageName);
        }

        // add watermark to large image
        if (true === $wmTargetL) {
            $imgWm = $this->pathUpload . '/large/' . $this->imageNameLarge;
            $resWm = $watermarksHandler->watermarkImage($wmId, $imgWm, $imgWm);
            if (true !== $resWm) {
                return ['error' => \sprintf(\constant($moduleDirNameUpper . '_' . 'FAILSAVEWM_LARGE'), $this->imageNicename, $resWm)];
            }
        }
        // add watermark to medium image
        if (true === $wmTargetM) {
            $imgWm = $this->pathUpload . '/medium/' . $this->imageName;
            $resWm = $watermarksHandler->watermarkImage($wmId, $imgWm, $imgWm);
            if (true !== $resWm) {
                return ['error' => \sprintf(\constant($moduleDirNameUpper . '_' . 'FAILSAVEWM_MEDIUM'), $this->imageNicename, $resWm)];
            }
        }

        return ['success' => true, 'uuid' => $uuid];
    }

    /**
     * @return bool
     */
    private function handleImageDB()
    {
        $moduleDirName = \basename(\dirname(\dirname(__DIR__)));
        require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/header.php';
        global $xoopsUser;

        $this->getImageDim();

        $helper = \XoopsModules\Tdmdownloads\Helper::getInstance();
        //      $imagesHandler = $helper->getHandler('Images');
        $imagesHandler = new \XoopsModules\Tdmdownloads\Common\ImagesHandler();
        $imagesObj     = $imagesHandler->create();
        // Set Vars
        $imagesObj->setVar('img_title', $this->imageNicename);
        $imagesObj->setVar('img_desc', '');
        $imagesObj->setVar('img_name', $this->imageName);
        $imagesObj->setVar('img_namelarge', $this->imageNameLarge);
        $imagesObj->setVar('img_nameorig', $this->imageNameOrig);
        $imagesObj->setVar('img_mimetype', $this->imageMimetype);
        $imagesObj->setVar('img_size', $this->imageSize);
        $imagesObj->setVar('img_resx', $this->imageWidth);
        $imagesObj->setVar('img_resy', $this->imageHeight);
        $imagesObj->setVar('img_albid', $this->claims->cat);
        $imagesObj->setVar('img_state', $this->permUseralbum);
        $imagesObj->setVar('img_date', \time());
        $imagesObj->setVar('img_submitter', $xoopsUser->id());
        $imagesObj->setVar('img_ip', $_SERVER['REMOTE_ADDR']);
        // Insert Data
        if ($imagesHandler->insert($imagesObj)) {
            $this->imageId = $imagesHandler->getInsertId();
            return true;
        }
        return false;
    }

    /**
     * @return bool|string
     */
    private function getImageDim()
    {
        switch ($this->imageMimetype) {
            case'image/png':
                $img = \imagecreatefrompng($this->imagePath);
                break;
            case'image/jpeg':
                $img = \imagecreatefromjpeg($this->imagePath);
                break;
            case'image/gif':
                $img = \imagecreatefromgif($this->imagePath);
                break;

            case'application/zip':
                $this->imageWidth  = 0;
                $this->imageHeight = 0;
                //                $img = imagecreatefromgif($this->imagePath);
                break;

            default:
                $this->imageWidth  = 0;
                $this->imageHeight = 0;
                return 'Unsupported format';
        }
        $this->imageWidth  = \imagesx($img);
        $this->imageHeight = \imagesy($img);

        \imagedestroy($img);

        return true;
    }
}
