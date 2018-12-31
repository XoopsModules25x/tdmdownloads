<?php
// $Id: comment_new.php 4 2011-02-04 20:31:45Z Kris_fr $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

require __DIR__ . '/header.php';
$com_itemid = isset($_GET['com_itemid']) ? intval($_GET['com_itemid']) : 0;
if ($com_itemid > 0) {
    // Get file title
    $sql = 'SELECT title, cid FROM ' . $xoopsDB->prefix('tdmdownloads_downloads') . ' WHERE lid=' . $com_itemid;
    $result = $xoopsDB->query($sql);
    if ($result) {
        $categories = TDMDownloads_MygetItemIds('tdmdownloads_view', 'TDMDownloads');
        $row = $xoopsDB->fetchArray($result);
        if (!in_array($row['cid'], $categories)) {
            redirect_header(XOOPS_URL, 2, _NOPERM);
            exit();
        }
        $com_replytitle = $row['title'];
        include XOOPS_ROOT_PATH.'/include/comment_new.php';
    }
}
