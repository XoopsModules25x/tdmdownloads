<?php

namespace XoopsModules\Tdmdownloads;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */


use Xmf\Request;
use XoopsModules\Tdmdownloads;
use XoopsModules\Tdmdownloads\Common;

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits
    use Common\ServerStats; // getServerStats Trait
    use Common\FilesManagement; // Files Management Trait

    /**
     * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
     * www.gsdesign.ro/blog/cut-html-string-without-breaking-the-tags
     * www.cakephp.org
     *
     * @param string  $text         String to truncate.
     * @param int $length       Length of returned string, including ellipsis.
     * @param string  $ending       Ending to be appended to the trimmed string.
     * @param bool $exact        If false, $text will not be cut mid-word
     * @param bool $considerHtml If true, HTML tags would be handled correctly
     *
     * @return string Trimmed string.
     */
    public static function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
    {
        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (mb_strlen(preg_replace('/<.*?' . '>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?' . '>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = mb_strlen($ending);
            $open_tags = [];
            $truncate = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } elseif (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags, true);
                        if (false !== $pos) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag
                    } elseif (preg_match('/^<\s*([^\s>!]+).*?' . '>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, mb_strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length + $content_length > $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($left >= $entity[1] + 1 - $entities_length) {
                                $left--;
                                $entities_length += mb_strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= mb_substr($line_matchings[2], 0, $left + $entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                }
                $truncate .= $line_matchings[2];
                $total_length += $content_length;

                // if the maximum length is reached, get off the loop
                if ($total_length >= $length) {
                    break;
                }
            }
        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            }
            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if ($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }

    /**
     * @param \Xmf\Module\Helper $helper
     * @param array|null         $options
     * @return \XoopsFormDhtmlTextArea|\XoopsFormEditor
     */
    public static function getEditor($helper = null, $options = null)
    {
        /** @var Tdmdownloads\Helper $helper */
        if (null === $options) {
            $options = [];
            $options['name'] = 'Editor';
            $options['value'] = 'Editor';
            $options['rows'] = 10;
            $options['cols'] = '100%';
            $options['width'] = '100%';
            $options['height'] = '400px';
        }

        $isAdmin = $helper->isUserAdmin();

        if (class_exists('XoopsFormEditor')) {
            if ($isAdmin) {
                $descEditor = new \XoopsFormEditor(ucfirst($options['name']), $helper->getConfig('editorAdmin'), $options, $nohtml = false, $onfailure = 'textarea');
            } else {
                $descEditor = new \XoopsFormEditor(ucfirst($options['name']), $helper->getConfig('editorUser'), $options, $nohtml = false, $onfailure = 'textarea');
            }
        } else {
            $descEditor = new \XoopsFormDhtmlTextArea(ucfirst($options['name']), $options['name'], $options['value'], '100%', '100%');
        }

        //        $form->addElement($descEditor);

        return $descEditor;
    }

    //--------------- Custom module methods -----------------------------

    /**
     * @param $permtype
     * @param $dirname
     * @return mixed
     */
    public function getItemIds($permtype, $dirname)
    {
        global $xoopsUser;
        static $permissions = [];
        if (is_array($permissions) && array_key_exists($permtype, $permissions)) {
            return $permissions[$permtype];
        }
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler    = xoops_getHandler('module');
        $tdmModule        = $moduleHandler->getByDirname($dirname);
        $groups           = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = xoops_getHandler('groupperm');
        $categories       = $grouppermHandler->getItemIds($permtype, $groups, $tdmModule->getVar('mid'));

        return $categories;
    }

    /**
     * retourne le nombre de téléchargements dans le catégories enfants d'une catégorie
     * @param $mytree
     * @param $categories
     * @param $entries
     * @param $cid
     * @return int
     */
    public function getNumbersOfEntries($mytree, $categories, $entries, $cid)
    {
        $count = 0;
        $child_arr = [];
        if (in_array($cid, $categories, true)) {
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
     * @param $time
     * @param $status
     * @return string
     */
    public function getStatusImage($time, $status)
    {
        global $xoopsModuleConfig;
        $count = 7;
        $new = '';
        $startdate = (time() - (86400 * $count));
        if (1 == $xoopsModuleConfig['showupdated']) {
            if ($startdate < $time) {
                $language = $GLOBALS['xoopsConfig']['language'];
                if (!is_dir(XOOPS_ROOT_PATH . '/modules/tdmdownloads/language/' . $language . '/')) {
                    $language = 'english';
                }
                $img_path = XOOPS_ROOT_PATH . '/modules/tdmdownloads/language/' . $language . '/';
                $img_url = XOOPS_URL . '/modules/tdmdownloads/language/' . $language . '/';
                if (1 == $status) {
                    if (is_readable($img_path . 'new.png')) {
                        $new = '&nbsp;<img src="' . $img_url . 'new.png" alt="' . _MD_TDMDOWNLOADS_INDEX_NEWTHISWEEK . '" title="' . _MD_TDMDOWNLOADS_INDEX_NEWTHISWEEK . '">';
                    } else {
                        $new = '&nbsp;<img src="' . XOOPS_URL . '/modules/tdmdownloads/language/english/new.png" alt="' . _MD_TDMDOWNLOADS_INDEX_NEWTHISWEEK . '" title="' . _MD_TDMDOWNLOADS_INDEX_NEWTHISWEEK . '">';
                    }
                } elseif (2 == $status) {
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
     * @param $hits
     * @return string
     */
    public function getPopularImage($hits)
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

    /**
     * @param $size
     * @return string
     */
    public static function trans_size($size)
    {
        if ($size > 0) {
            $mb = 1024 * 1024;
            if ($size > $mb) {
                $mysize = sprintf('%01.2f', $size / $mb) . ' MB';
            } elseif ($size >= 1024) {
                $mysize = sprintf('%01.2f', $size / 1024) . ' KB';
            } else {
                $mysize = sprintf(_AM_TDMDOWNLOADS_NUMBYTES, $size);
            }

            return $mysize;
        }

        return '';
    }

    /**
     * @param        $global
     * @param        $key
     * @param string $default
     * @param string $type
     * @return mixed|string
     */
    public static function cleanVars(&$global, $key, $default = '', $type = 'int')
    {
        switch ($type) {
            case 'string':
                $ret = isset($global[$key]) ? filter_var($global[$key], FILTER_SANITIZE_MAGIC_QUOTES) : $default;
                break;
            case 'int':
            default:
                $ret = isset($global[$key]) ? filter_var($global[$key], FILTER_SANITIZE_NUMBER_INT) : $default;
                break;
        }
        if (false === $ret) {
            return $default;
        }

        return $ret;
    }

    /**
     * @param        $mytree
     * @param        $key
     * @param        $category_array
     * @param        $title
     * @param string $prefix
     * @return string
     */
    public function getPathTree($mytree, $key, $category_array, $title, $prefix = '')
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

    /**
     * @param        $mytree
     * @param        $key
     * @param        $category_array
     * @param        $title
     * @param string $prefix
     * @param bool   $link
     * @param string $order
     * @param bool   $lasturl
     * @return string
     */
    public function getPathTreeUrl($mytree, $key, $category_array, $title, $prefix = '', $link = false, $order = 'ASC', $lasturl = false)
    {
        global $xoopsModule;
        $category_parent = $mytree->getAllParent($key);
        if ('ASC' === $order) {
            $category_parent = array_reverse($category_parent);
            if (true === $link) {
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
            if (true === $link) {
                $Path .= '<a href="viewcat.php?cid=' . $category_parent[$j]->getVar('cat_cid') . '">' . $category_parent[$j]->getVar($title) . '</a>' . $prefix;
            } else {
                $Path .= $category_parent[$j]->getVar($title) . $prefix;
            }
        }
        if ('ASC' === $order) {
            if (array_key_exists($key, $category_array)) {
                if (true === $lasturl) {
                    $first_category = '<a href="viewcat.php?cid=' . $category_array[$key]->getVar('cat_cid') . '">' . $category_array[$key]->getVar($title) . '</a>';
                } else {
                    $first_category = $category_array[$key]->getVar($title);
                }
            } else {
                $first_category = '';
            }
            $Path .= $first_category;
        } else {
            if (true === $link) {
                $Path .= '<a href="index.php">' . $xoopsModule->name() . '</a>';
            } else {
                $Path .= $xoopsModule->name();
            }
        }

        return $Path;
    }
}
