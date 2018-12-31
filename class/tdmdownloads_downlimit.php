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
 * @copyright   Gregory Mage (Aka Mage) and Hossein Azizabadi (Aka voltan)
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage) and Hossein Azizabadi (Aka voltan)
 */

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

class TDMDownloads_downlimit extends XoopsObject
{
    // constructor
    public function __construct()
    {
        $this->initVar("downlimit_id", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("downlimit_lid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("downlimit_uid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("downlimit_hostname", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("downlimit_date", XOBJ_DTYPE_INT, null, false, 10);
    }
    public function TDMDownloads_downlimit()
    {
        $this->__construct();
    }
}

class TDMDownloadstdmdownloads_downlimitHandler extends XoopsPersistableObjectHandler
{
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, "tdmdownloads_downlimit", 'tdmdownloads_downlimit', 'downlimit_id', 'downlimit_lid');
    }
}
