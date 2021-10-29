<?php

declare(strict_types=1);
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
/**
 * @param $category
 * @param $item_id
 * @return mixed
 */
function tdmdownloads_notify_iteminfo($category, $item_id)
{
    global $xoopsModule, $xoopsModuleConfig, $xoopsConfig;
    $moduleDirName = basename(dirname(__DIR__));
    $item_id       = (int)$item_id;
    if (empty($xoopsModule) || $xoopsModule->getVar('dirname') !== $moduleDirName) {
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname($moduleDirName);
        /** @var \XoopsConfigHandler $configHandler */
        $configHandler = xoops_getHandler('config');
        $config        = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
    } else {
        $module = $xoopsModule;
        $config = $xoopsModuleConfig;
    }
    if ('global' === $category) {
        $item['name'] = '';
        $item['url']  = '';
        return $item;
    }
    global $xoopsDB;
    if ('category' === $category) {
        // Assume we have a valid category id
        $sql    = 'SELECT title FROM ' . $xoopsDB->prefix('tdmdownloads_cat') . ' WHERE cid = ' . $item_id;
        $result = $xoopsDB->query($sql); // TODO: error check
        if ($result instanceof \mysqli_result) {
            $result_array = $xoopsDB->fetchArray($result);
        }
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/viewcat.php?cid=' . $item_id;
        return $item;
    }
    if ('file' === $category) {
        // Assume we have a valid file id
        $sql    = 'SELECT cid,title FROM ' . $xoopsDB->prefix('tdmdownloads_downloads') . ' WHERE lid = ' . $item_id;
        $result = $xoopsDB->query($sql); // TODO: error check
        if ($result instanceof \mysqli_result) {
            $result_array = $xoopsDB->fetchArray($result);
        }
        $item['name'] = $result_array['title'];
        $item['url']  = XOOPS_URL . '/modules/' . $module->getVar('dirname') . '/singlefile.php?cid=' . $result_array['cid'] . '&amp;lid=' . $item_id;
        return $item;
    }
    return null;
}
