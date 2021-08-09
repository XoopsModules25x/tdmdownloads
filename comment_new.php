<?php declare(strict_types=1);

//
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2020 XOOPS.org                        //
//                       <https://xoops.org>                             //
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
$com_itemid = \Xmf\Request::getInt('com_itemid', 0, 'GET');
if ($com_itemid > 0) {
    // Get file title

    $sql = 'SELECT title, cid FROM ' . $xoopsDB->prefix('tdmdownloads_downloads') . ' WHERE lid=' . $com_itemid;

    $result = $xoopsDB->query($sql);

    if ($result instanceof \mysqli_result) {
        $categories = $utility->getItemIds('tdmdownloads_view', $moduleDirName);

        $row = $xoopsDB->fetchArray($result);

        if (!in_array($row['cid'], $categories)) {
            redirect_header(XOOPS_URL, 2, _NOPERM);
        }

        $com_replytitle = $row['title'];

        require XOOPS_ROOT_PATH . '/include/comment_new.php';
    }
}
