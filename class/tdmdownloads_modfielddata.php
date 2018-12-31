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
class TDMDownloads_modfielddata extends XoopsObject
{
    // constructor
    public function __construct()
    {
        $this->initVar("modiddata", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("fid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("lid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("moddata", XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
    }
    public function TDMDownloads_modfielddata()
    {
        $this->__construct();
    }
}

class TDMDownloadstdmdownloads_modfielddataHandler extends XoopsPersistableObjectHandler
{
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, "tdmdownloads_modfielddata", 'tdmdownloads_modfielddata', 'modiddata', 'moddata');
    }
}
