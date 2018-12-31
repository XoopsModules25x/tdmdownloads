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

//**********************************************************************************************************************
// ModuleName_checkModuleAdmin
//**********************************************************************************************************************
// return true if moduladmin framworks exists.
//**********************************************************************************************************************
function TDMDownloads_checkModuleAdmin()
{
    if (file_exists($GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))) {
        require_once $GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php');

        return true;
    } else {
        echo xoops_error("Error: You don't use the Frameworks \"admin module\". Please install this Frameworks");

        return false;
    }
}

function TDMDownloads_MygetItemIds($permtype, $dirname)
{
    global $xoopsUser;
    static $permissions = [];
    if (is_array($permissions) && array_key_exists($permtype, $permissions)) {
        return $permissions[$permtype];
    }
    $moduleHandler = xoops_getHandler('module');
    $tdmModule = $moduleHandler->getByDirname($dirname);
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gpermHandler = xoops_getHandler('groupperm');
    $categories = $gpermHandler->getItemIds($permtype, $groups, $tdmModule->getVar('mid'));

    return $categories;
}

/**
* retourne le nombre de téléchargements dans le catégories enfants d'une catégorie
**/

function TDMDownloads_NumbersOfEntries($mytree, $categories, $entries, $cid)
{
    $count = 0;
    $child_arr = [];
    if (in_array($cid, $categories)) {
        $child = $mytree->getAllChild($cid);
        foreach (array_keys($entries) as $i) {
            if ($entries[$i]->getVar('cid') == $cid) {
                $count++;
            }
            foreach (array_keys($child) as $j) {
                if ($entries[$i]->getVar('cid') == $j) {
                    $count++;
                }
            }
        }
    }

    return $count;
}

/**
* retourne une image "nouveau" ou "mise à jour"
**/

function TDMDownloads_Thumbnail($time, $status)
{
    global $xoopsModuleConfig;
    $count = 7;
    $new = '';
    $startdate = (time()-(86400 * $count));
    if ($xoopsModuleConfig['showupdated'] == 1) {
        if ($startdate < $time) {
            $language = $GLOBALS['xoopsConfig']['language'];
            if (!is_dir(XOOPS_ROOT_PATH . '/modules/tdmdownloads/language/' . $language . '/')) {
                $language = 'english';
            }
            $img_path = XOOPS_ROOT_PATH . '/modules/tdmdownloads/language/' . $language . '/';
            $img_url = XOOPS_URL . '/modules/tdmdownloads/language/' . $language . '/';
            if ($status==1) {
                if (is_readable($img_path . 'new.png')) {
                    $new = '&nbsp;<img src="' . $img_url . 'new.png" alt="' . _MD_TDMDOWNLOADS_INDEX_NEWTHISWEEK . '" title="' . _MD_TDMDOWNLOADS_INDEX_NEWTHISWEEK . '">';
                } else {
                    $new = '&nbsp;<img src="' . XOOPS_URL . '/modules/tdmdownloads/language/english/new.png" alt="' . _MD_TDMDOWNLOADS_INDEX_NEWTHISWEEK . '" title="' . _MD_TDMDOWNLOADS_INDEX_NEWTHISWEEK . '">';
                }
            } elseif ($status==2) {
                if (is_readable($img_path . 'updated.png')) {
                    $new = '&nbsp;<img src="' . $img_url . 'updated.png" alt="' . _MD_TDMDOWNLOADS_INDEX_UPTHISWEEK . '" title="' . _MD_TDMDOWNLOADS_INDEX_UPTHISWEEK . '">';
                } else {
                    $new = '&nbsp;<img src="' . XOOPS_URL . '/modules/tdmdownloads/language/english/updated.png" alt="' . _MD_TDMDOWNLOADS_INDEX_UPTHISWEEK . '" title="' . _MD_TDMDOWNLOADS_INDEX_UPTHISWEEK . '">';
                }
            }
        }
    }

    return $new;
}

/**
* retourne une image "populaire"
**/

function TDMDownloads_Popular($hits)
{
    global $xoopsModuleConfig;
    $pop = '';
    if ($hits >= $xoopsModuleConfig['popular']) {
        $language = $GLOBALS['xoopsConfig']['language'];
        if (!is_dir(XOOPS_ROOT_PATH . '/modules/tdmdownloads/language/' . $language . '/')) {
            $language = 'english';
        }
        $img_path = XOOPS_ROOT_PATH . '/modules/tdmdownloads/language/' . $language . '/';
        $img_url = XOOPS_URL . '/modules/tdmdownloads/language/' . $language . '/';
        if (is_readable($img_path . 'popular.png')) {
            $pop = '&nbsp;<img src="' . $img_url . 'popular.png" alt="' . _MD_TDMDOWNLOADS_INDEX_POPULAR . '" title="' . _MD_TDMDOWNLOADS_INDEX_POPULAR . '">';
        } else {
            $pop = '&nbsp;<img src ="' . XOOPS_URL . '/modules/tdmdownloads/language/english/popular.png" alt="' . _MD_TDMDOWNLOADS_INDEX_POPULAR . '" title="' . _MD_TDMDOWNLOADS_INDEX_POPULAR . '">';
        }
    }

    return $pop;
}

function trans_size($size)
{
    if ($size>0) {
        $mb = 1024*1024;
        if ($size > $mb) {
            $mysize = sprintf('%01.2f', $size / $mb) . ' MB';
        } elseif ($size >= 1024) {
            $mysize = sprintf('%01.2f', $size / 1024) . ' KB';
        } else {
            $mysize = sprintf(_AM_TDMDOWNLOADS_NUMBYTES, $size);
        }

        return $mysize;
    } else {
        return '';
    }
}

function TDMDownloads_CleanVars(&$global, $key, $default = '', $type = 'int')
{
    switch ($type) {
        case 'string':
            $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_SANITIZE_MAGIC_QUOTES) : $default;
            break;
        case 'int': default:
            $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_SANITIZE_NUMBER_INT) : $default;
            break;
    }
    if ($ret === false) {
        return $default;
    }

    return $ret;
}

function TDMDownloads_PathTree($mytree, $key, $category_array, $title, $prefix = '')
{
    $category_parent = $mytree->getAllParent($key);
    $category_parent = array_reverse($category_parent);
    $Path = '';
    foreach (array_keys($category_parent) as $j) {
        $Path .= $category_parent[$j]->getVar($title) . $prefix;
    }
    if (array_key_exists($key, $category_array)) {
        $first_category = $category_array[$key]->getVar($title);
    } else {
        $first_category = '';
    }
    $Path .= $first_category;

    return $Path;
}

function TDMDownloads_PathTreeUrl($mytree, $key, $category_array, $title, $prefix = '', $link = false, $order = 'ASC', $lasturl = false)
{
    global $xoopsModule;
    $category_parent = $mytree->getAllParent($key);
    if ($order == 'ASC') {
        $category_parent = array_reverse($category_parent);
        if ($link === true) {
            $Path = '<a href="index.php">' . $xoopsModule->name() . '</a>' . $prefix;
        } else {
            $Path = $xoopsModule->name() . $prefix;
        }
    } else {
        if (array_key_exists($key, $category_array)) {
            $first_category = $category_array[$key]->getVar($title);
        } else {
            $first_category = '';
        }
        $Path = $first_category . $prefix;
    }
    foreach (array_keys($category_parent) as $j) {
        if ($link === true) {
            $Path .= '<a href="viewcat.php?cid=' . $category_parent[$j]->getVar('cat_cid') . '">' . $category_parent[$j]->getVar($title) . '</a>' . $prefix;
        } else {
            $Path .= $category_parent[$j]->getVar($title) . $prefix;
        }
    }
    if ($order == 'ASC') {
        if (array_key_exists($key, $category_array)) {
            if ($lasturl === true) {
                $first_category = '<a href="viewcat.php?cid=' . $category_array[$key]->getVar('cat_cid') . '">' . $category_array[$key]->getVar($title) . '</a>';
            } else {
                $first_category = $category_array[$key]->getVar($title);
            }
        } else {
            $first_category = '';
        }
        $Path .= $first_category;
    } else {
        if ($link === true) {
            $Path .= '<a href="index.php">' . $xoopsModule->name() . '</a>';
        } else {
            $Path .= $xoopsModule->name();
        }
    }

    return $Path;
}
