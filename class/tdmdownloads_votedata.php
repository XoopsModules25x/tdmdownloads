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

if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}

class TDMDownloads_votedata extends XoopsObject
{
    // constructor
    public function __construct()
    {
        $this->initVar('ratingid', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('lid', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('ratinguser', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('rating', XOBJ_DTYPE_OTHER, null, false, 3);
        $this->initVar('ratinghostname', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('ratingtimestamp', XOBJ_DTYPE_INT, null, false, 10);
    }
    public function TDMDownloads_votedata()
    {
        $this->__construct();
    }

    public function getForm($lid, $action = false)
    {
        global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        if (!$this->isNew()) {
            $rating = 11;
        } else {
            $rating = $this->getVar('rating');
        }
        $form = new XoopsThemeForm(_MD_TDMDOWNLOADS_SINGLEFILE_RATHFILE, 'rateform', 'ratefile.php', 'post');
        $form->setExtra('enctype="multipart/form-data"');
        $rating = new XoopsFormSelect(_MD_TDMDOWNLOADS_RATEFILE_VOTE, 'rating', $rating);
        $options = ['11' => '--', '10' => '10', '9' => '9', '8' => '8', '7' => '7', '6' => '6', '5' => '5', '4' => '4', '3' => '3', '2' => '2', '1' => '1', '0' => '0'];
        $rating->addOptionArray($options);
        $form->addElement($rating, true);
        $form->addElement(new XoopsFormCaptcha(), true);
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormHidden('lid', $lid));
        // Submit button
        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormButton('', 'post', _MD_TDMDOWNLOADS_RATEFILE_RATE, 'submit'));
        $form->addElement($button_tray);

        return $form;
    }
}

class TDMDownloadstdmdownloads_votedataHandler extends XoopsPersistableObjectHandler
{
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'tdmdownloads_votedata', 'tdmdownloads_votedata', 'ratingid', 'lid');
    }
}
