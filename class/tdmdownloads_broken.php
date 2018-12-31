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

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class TDMDownloads_broken extends XoopsObject
{
    // constructor
    public function __construct()
    {
        $this->initVar("reportid", XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar("lid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("sender", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("ip", XOBJ_DTYPE_TXTBOX, null, false);
        //pour les jointures:
        $this->initVar("title", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("cid", XOBJ_DTYPE_INT, null, false, 5);
    }
    public function TDMDownloads_broken()
    {
        $this->__construct();
    }
    public function getForm($lid, $action = false)
    {
        global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $form = new XoopsThemeForm(_MD_TDMDOWNLOADS_BROKENFILE_REPORTBROKEN, 'brokenform', 'brokenfile.php', 'post');
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormCaptcha(), true);
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormHidden('lid', $lid));
        // Submit button
        $button_tray = new XoopsFormElementTray(_MD_TDMDOWNLOADS_BROKENFILE_REPORTBROKEN, '', '');
        $button_tray->addElement(new XoopsFormButton('', 'post', _MD_TDMDOWNLOADS_BROKENFILE_REPORTBROKEN, 'submit'));
        $form->addElement($button_tray);

        return $form;
    }
}

class TDMDownloadstdmdownloads_brokenHandler extends XoopsPersistableObjectHandler
{
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, "tdmdownloads_broken", 'tdmdownloads_broken', 'reportid', 'lid');
    }
}
