<?php namespace XoopsModules\Tdmdownloads\Form;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Module: Tdmdownloads
 *
 * @category        Module
 * @package         tdmdownloads
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use Xmf\Request;
use XoopsModules\Tdmdownloads;

require_once dirname(dirname(__DIR__)) . '/include/common.php';

$moduleDirName = basename(dirname(dirname(__DIR__)));
//$helper = Tdmdownloads\Helper::getInstance();
$permHelper = new \Xmf\Module\Helper\Permission();

xoops_load('XoopsFormLoader');

/**
 * Class FieldForm
 */
class FieldForm extends \XoopsThemeForm
{
    public $targetObject;

    /**
     * Constructor
     *
     * @param $target
     */
    public function __construct($target)
    {
        //  global $helper;
        $this->helper       = $target->helper;
        $this->targetObject = $target;

        $title = $this->targetObject->isNew() ? sprintf(AM_TDMDOWNLOADS_FIELD_ADD) : sprintf(AM_TDMDOWNLOADS_FIELD_EDIT);
        parent::__construct($title, 'form', xoops_getenv('PHP_SELF'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        //include ID field, it's needed so the module knows if it is a new form or an edited form

        $hidden = new \XoopsFormHidden('fid', $this->targetObject->getVar('fid'));
        $this->addElement($hidden);
        unset($hidden);

        // Fid
        $this->addElement(new \XoopsFormLabel(AM_TDMDOWNLOADS_FIELD_FID, $this->targetObject->getVar('fid'), 'fid'));
        // Title
        $this->addElement(new \XoopsFormText(AM_TDMDOWNLOADS_FIELD_TITLE, 'title', 50, 255, $this->targetObject->getVar('title')), false);
        // Img
        $img = $this->targetObject->getVar('img') ?: 'blank.png';

        $uploadDir   = '/uploads/tdmdownloads/images/';
        $imgtray     = new \XoopsFormElementTray(AM_TDMDOWNLOADS_FIELD_IMG, '<br>');
        $imgpath     = sprintf(AM_TDMDOWNLOADS_FORMIMAGE_PATH, $uploadDir);
        $imageselect = new \XoopsFormSelect($imgpath, 'img', $img);
        $imageArray  = \XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . $uploadDir);
        foreach ($imageArray as $image) {
            $imageselect->addOption((string)$image, $image);
        }
        $imageselect->setExtra("onchange='showImgSelected(\"image_img\", \"img\", \"" . $uploadDir . '", "", "' . XOOPS_URL . "\")'");
        $imgtray->addElement($imageselect);
        $imgtray->addElement(new \XoopsFormLabel('', "<br><img src='" . XOOPS_URL . '/' . $uploadDir . '/' . $img . "' name='image_img' id='image_img' alt='' />"));
        $fileseltray = new \XoopsFormElementTray('', '<br>');
        $fileseltray->addElement(new \XoopsFormFile(AM_TDMDOWNLOADS_FORMUPLOAD, 'img', xoops_getModuleOption('maxsize')));
        $fileseltray->addElement(new \XoopsFormLabel(''));
        $imgtray->addElement($fileseltray);
        $this->addElement($imgtray);
        // Weight
        $this->addElement(new \XoopsFormText(AM_TDMDOWNLOADS_FIELD_WEIGHT, 'weight', 50, 255, $this->targetObject->getVar('weight')), false);
        // Status
        $this->addElement(new \XoopsFormText(AM_TDMDOWNLOADS_FIELD_STATUS, 'status', 50, 255, $this->targetObject->getVar('status')), false);
        // Search
        $this->addElement(new \XoopsFormText(AM_TDMDOWNLOADS_FIELD_SEARCH, 'search', 50, 255, $this->targetObject->getVar('search')), false);
        // Status_def
        $this->addElement(new \XoopsFormText(AM_TDMDOWNLOADS_FIELD_STATUS_DEF, 'status_def', 50, 255, $this->targetObject->getVar('status_def')), false);

        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
