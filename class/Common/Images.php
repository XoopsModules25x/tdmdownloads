<?php

declare(strict_types=1);

namespace XoopsModules\Tdmdownloads\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * @copyright      2020 XOOPS Project (https://xoops.org)
 * @license        GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link           https://xoops.org
 * @author         Wedega - Email:<webmaster@wedega.com> - Website:<https://wedega.com>
 */

use XoopsModules\Tdmdownloads\{
    Helper
};

/**
 * Class Object Images
 */
class Images extends \XoopsObject
{
    /**
     * Constructor
     *
     * @param null
     */
    public function __construct()
    {
        $this->initVar('img_id', \XOBJ_DTYPE_INT);
        $this->initVar('img_title', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('img_desc', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('img_name', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('img_namelarge', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('img_nameorig', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('img_mimetype', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('img_size', \XOBJ_DTYPE_INT);
        $this->initVar('img_resx', \XOBJ_DTYPE_INT);
        $this->initVar('img_resy', \XOBJ_DTYPE_INT);
        $this->initVar('img_downloads', \XOBJ_DTYPE_INT);
        $this->initVar('img_ratinglikes', \XOBJ_DTYPE_INT);
        $this->initVar('img_votes', \XOBJ_DTYPE_INT);
        $this->initVar('img_weight', \XOBJ_DTYPE_INT);
        $this->initVar('img_albid', \XOBJ_DTYPE_INT);
        $this->initVar('img_state', \XOBJ_DTYPE_INT);
        $this->initVar('img_date', \XOBJ_DTYPE_INT);
        $this->initVar('img_submitter', \XOBJ_DTYPE_INT);
        $this->initVar('img_ip', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('dohtml', \XOBJ_DTYPE_INT, 1, false);
    }

    /**
     * @static function &getInstance
     *
     * @param null
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }
    }

    /**
     * @return int
     */
    public function getNewInsertedIdImages()
    {
        return $GLOBALS['xoopsDB']->getInsertId();
    }

    /**
     * @public function getForm
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getFormImages($action = false)
    {
        $moduleDirName      = \basename(\dirname(__DIR__, 2));
        $moduleDirNameUpper = \mb_strtoupper($moduleDirName);
        $helper             = Helper::getInstance();
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        // Title
        $title = $this->isNew() ? \sprintf(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_ADD')) : \sprintf(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_EDIT'));
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        // Form Text ImgTitle
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_TITLE'), 'img_title', 50, 255, $this->getVar('img_title')));
        // Form editor ImgDesc
        $editorConfigs           = [];
        $editorConfigs['name']   = 'img_desc';
        $editorConfigs['value']  = $this->getVar('img_desc', 'e');
        $editorConfigs['rows']   = 5;
        $editorConfigs['cols']   = 40;
        $editorConfigs['width']  = '100%';
        $editorConfigs['height'] = '400px';
        $editorConfigs['editor'] = $helper->getConfig('editor');
        $form->addElement(new \XoopsFormEditor(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_DESC'), 'img_desc', $editorConfigs));
        // Form Text ImgName
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_NAME'), 'img_name', 50, 255, $this->getVar('img_name')), true);
        // Form Text ImgNameLarge
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_NAMELARGE'), 'img_namelarge', 50, 255, $this->getVar('img_namelarge')), true);
        // Form Text ImgOrigname
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_NAMEORIG'), 'img_nameorig', 50, 255, $this->getVar('img_nameorig')), true);
        // Form Text ImgMimetype
        $imgMimetype = $this->isNew() ? '0' : $this->getVar('img_mimetype');
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_MIMETYPE'), 'img_mimetype', 20, 150, $imgMimetype));
        // Form Text ImgSize
        $imgSize = $this->isNew() ? '0' : $this->getVar('img_size');
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_SIZE'), 'img_size', 20, 150, $imgSize));
        // Form Text ImgResx
        $imgResx = $this->isNew() ? '0' : $this->getVar('img_resx');
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_RESX'), 'img_resx', 20, 150, $imgResx));
        // Form Text ImgResy
        $imgResy = $this->isNew() ? '0' : $this->getVar('img_resy');
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_RESY'), 'img_resy', 20, 150, $imgResy));
        // Form Text ImgDownloads
        $imgDownloads = $this->isNew() ? '0' : $this->getVar('img_downloads');
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_DOWNLOADS'), 'img_downloads', 20, 150, $imgDownloads));
        // Form Text ImgRatinglikes
        $imgRatinglikes = $this->isNew() ? '0' : $this->getVar('img_ratinglikes');
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_RATINGLIKES'), 'img_ratinglikes', 20, 150, $imgRatinglikes));
        // Form Text ImgVotes
        $imgVotes = $this->isNew() ? '0' : $this->getVar('img_votes');
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_VOTES'), 'img_votes', 20, 150, $imgVotes));
        // Form Text ImgWeight
        $imgWeight = $this->isNew() ? '0' : $this->getVar('img_weight');
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_WEIGHT'), 'img_weight', 20, 150, $imgWeight));
        // Form Table albums
        /** @var \XoopsModules\Tdmdownloads\Common\ImagesHandler $albumsHandler */
        $albumsHandler  = $helper->getHandler('Albums');
        $imgAlbidSelect = new \XoopsFormSelect(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_ALBID'), 'img_albid', $this->getVar('img_albid'));
        $imgAlbidSelect->addOptionArray($albumsHandler->getList());
        $form->addElement($imgAlbidSelect, true);
        // Images handler
        $imagesHandler = $helper->getHandler('Images');
        // Form Select Images
        $imgStateSelect = new \XoopsFormSelect(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_STATE'), 'img_state', $this->getVar('img_state'));
        $imgStateSelect->addOption('Empty');
        $imgStateSelect->addOptionArray($imagesHandler->getList());
        $form->addElement($imgStateSelect, true);
        // Form Text Date Select ImgDate
        $imgDate = $this->isNew() ? 0 : $this->getVar('img_date');
        $form->addElement(new \XoopsFormTextDateSelect(\constant('CO_' . $moduleDirNameUpper . '_' . 'DATE'), 'img_date', '', $imgDate));
        // Form Select User ImgSubmitter
        $form->addElement(new \XoopsFormSelectUser(\constant('CO_' . $moduleDirNameUpper . '_' . 'SUBMITTER'), 'img_submitter', false, $this->getVar('img_submitter')));
        // Form Text ImgIp
        $form->addElement(new \XoopsFormText(\constant('CO_' . $moduleDirNameUpper . '_' . 'IMAGE_IP'), 'img_ip', 50, 255, $this->getVar('img_ip')));
        // To Save
        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false));
        return $form;
    }

    /**
     * Get Values
     * @param null $keys
     * @param null $format
     * @param null $maxDepth
     * @return array
     */
    public function getValuesImages($keys = null, $format = null, $maxDepth = null)
    {
        $moduleDirName      = \basename(\dirname(__DIR__, 2));
        $moduleDirNameUpper = \mb_strtoupper($moduleDirName);
        $helper             = Helper::getInstance();
        $ret                = $this->getValues($keys, $format, $maxDepth);
        $ret['id']          = $this->getVar('img_id');
        $ret['title']       = $this->getVar('img_title');
        $ret['desc']        = $this->getVar('img_desc', 'n');
        $ret['name']        = $this->getVar('img_name');
        $ret['namelarge']   = $this->getVar('img_namelarge');
        $ret['nameorig']    = $this->getVar('img_nameorig');
        $ret['mimetype']    = $this->getVar('img_mimetype');
        $ret['size']        = $this->getVar('img_size');
        $ret['resx']        = $this->getVar('img_resx');
        $ret['resy']        = $this->getVar('img_resy');
        $ret['downloads']   = $this->getVar('img_downloads');
        $ret['ratinglikes'] = $this->getVar('img_ratinglikes');
        $ret['votes']       = $this->getVar('img_votes');
        $ret['weight']      = $this->getVar('img_weight');
        $ret['albid']       = $this->getVar('img_albid');
        //$albums             = $helper->getHandler('Albums');
        //$albumsObj          = $albums->get($this->getVar('img_albid'));
        //if (isset($albumsObj) && is_object($albumsObj)) {
        //$ret['alb_name'] = $albumsObj->getVar('alb_name');
        //}
        $ret['state']      = $this->getVar('img_state');
        $ret['state_text'] = $helper->getStateText($this->getVar('img_state'));
        $ret['date']       = \formatTimestamp($this->getVar('img_date'), 's');
        $ret['submitter']  = \XoopsUser::getUnameFromId($this->getVar('img_submitter'));
        $ret['ip']         = $this->getVar('img_ip');
        $ret['large']      = \constant($moduleDirNameUpper . '_' . 'UPLOAD_IMAGE_URL') . '/large/' . $this->getVar('img_namelarge');
        $ret['medium']     = \constant($moduleDirNameUpper . '_' . 'UPLOAD_IMAGE_URL') . '/medium/' . $this->getVar('img_name');
        $ret['thumb']      = \constant($moduleDirNameUpper . '_' . 'UPLOAD_IMAGE_URL') . '/thumbs/' . $this->getVar('img_name');
        return $ret;
    }

    /**
     * Returns an array representation of the object
     *
     * @return array
     */
    public function toArrayImages()
    {
        $ret  = [];
        $vars = $this->getVars();
        foreach (\array_keys($vars) as $var) {
            $ret[$var] = $this->getVar('"{$var}"');
        }
        return $ret;
    }
}
