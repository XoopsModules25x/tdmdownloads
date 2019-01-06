<?php

namespace XoopsModules\Tdmdownloads;

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
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Broken
 * @package XoopsModules\Tdmdownloads
 */
class Broken extends \XoopsObject
{
    // constructor

    public function __construct()
    {
        parent::__construct();
        $this->initVar('reportid', XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('lid', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('sender', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('ip', XOBJ_DTYPE_TXTBOX, null, false);
        //pour les jointures:
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cid', XOBJ_DTYPE_INT, null, false, 5);
    }

    /**
     * @param      $lid
     * @param bool $action
     *
     * @return \XoopsThemeForm
     */
    public function getForm($lid, $action = false)
    {
        //        global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $form = new \XoopsThemeForm(_MD_TDMDOWNLOADS_BROKENFILE_REPORTBROKEN, 'brokenform', 'brokenfile.php', 'post');
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new \XoopsFormCaptcha(), true);
        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormHidden('lid', $lid));
        // Submit button
        $buttonTray = new \XoopsFormElementTray(_MD_TDMDOWNLOADS_BROKENFILE_REPORTBROKEN, '', '');
        $buttonTray->addElement(new \XoopsFormButton('', 'post', _MD_TDMDOWNLOADS_BROKENFILE_REPORTBROKEN, 'submit'));
        $form->addElement($buttonTray);

        return $form;
    }
}
