<?php declare(strict_types=1);

namespace XoopsModules\Tdmdownloads;

/**
 * Created by PhpStorm.
 * User: mamba
 * Date: 2015-09-16
 * Time: 01:20
 */

use XoopsModules\Tdmdownloads;

/**
 * Class Utilities
 * @package XoopsModules\Tdmdownloads
 */
class Utilities
{
    protected $db;
    protected $helper;
    /**
     * @param mixed $permtype
     * @param mixed $dirname
     */

    //    public static function __construct(\XoopsDatabase $db = null, $helper = null)

    //    {

    //        $this->db     = $db;

    //        $this->helper = $helper;

    //    }

    /**
     * @param $permtype
     * @param $dirname
     * @return mixed
     */
    public static function getItemIds($permtype, $dirname)
    {
        global $xoopsUser;

        $permissions = [];

        if (\is_array($permissions) && \array_key_exists($permtype, $permissions)) {
            return $permissions[$permtype];
        }

        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');

        $tdmModule = $moduleHandler->getByDirname($dirname);

        $groups = \is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = \xoops_getHandler('groupperm');

        return $grouppermHandler->getItemIds($permtype, $groups, $tdmModule->getVar('mid'));
    }

    /**
     * returns the number of updates downloads in the categories of children category
     *
     * @param $mytree
     * @param $categories
     * @param $entries
     * @param $cid
     *
     * @return int
     */
    public static function getNumbersOfEntries($mytree, $categories, $entries, $cid)
    {
        $count = 0;

        $child_arr = [];

        if (\in_array($cid, $categories)) {
            $child = $mytree->getAllChild($cid);

            foreach (\array_keys($entries) as $i) {
                if ($entries[$i]->getVar('cid') === $cid) {
                    ++$count;
                }

                foreach (\array_keys($child) as $j) {
                    if ($entries[$i]->getVar('cid') === $j) {
                        ++$count;
                    }
                }
            }
        }

        return $count;
    }

    /**
     * returns an image "new" or "updated"
     * @param $time
     * @param $status
     * @return string
     */
    public static function getStatusImage($time, $status)
    {
        $moduleDirName = \basename(\dirname(__DIR__));

        /** @var Tdmdownloads\Helper $helper */

        $helper = Tdmdownloads\Helper::getInstance();

        $count = 7;

        $new = '';

        $startdate = \time() - (86400 * $count);

        if (1 == $helper->getConfig('showupdated')) {
            if ($startdate < $time) {
                $language = $GLOBALS['xoopsConfig']['language'];

                if (!\is_dir(XOOPS_ROOT_PATH . "/modules/$moduleDirName/language/" . $language . '/')) {
                    $language = 'english';
                }

                $img_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName/language/" . $language . '/';

                $img_url = XOOPS_URL . "/modules/$moduleDirName/language/" . $language . '/';

                if (1 == $status) {
                    if (\is_readable($img_path . 'new.png')) {
                        $new = '&nbsp;<img src="' . $img_url . 'new.png" alt="' . _MD_TDMDOWNLOADS_INDEX_NEWTHISWEEK . '" title="' . _MD_TDMDOWNLOADS_INDEX_NEWTHISWEEK . '">';
                    } else {
                        $new = '&nbsp;<img src="' . XOOPS_URL . '/modules/' . $moduleDirName . '/language/english/new.png" alt="' . _MD_TDMDOWNLOADS_INDEX_NEWTHISWEEK . '" title="' . _MD_TDMDOWNLOADS_INDEX_NEWTHISWEEK . '">';
                    }
                } elseif (2 == $status) {
                    if (\is_readable($img_path . 'updated.png')) {
                        $new = '&nbsp;<img src="' . $img_url . 'updated.png" alt="' . _MD_TDMDOWNLOADS_INDEX_UPTHISWEEK . '" title="' . _MD_TDMDOWNLOADS_INDEX_UPTHISWEEK . '">';
                    } else {
                        $new = '&nbsp;<img src="' . XOOPS_URL . '/modules/' . $moduleDirName . '/language/english/updated.png" alt="' . _MD_TDMDOWNLOADS_INDEX_UPTHISWEEK . '" title="' . _MD_TDMDOWNLOADS_INDEX_UPTHISWEEK . '">';
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
    public static function getPopularImage($hits)
    {
        /** @var Tdmdownloads\Helper $helper */

        $helper = Tdmdownloads\Helper::getInstance();

        $moduleDirName = \basename(\dirname(__DIR__));

        $pop = '';

        if ($hits >= $helper->getConfig('popular')) {
            $language = $GLOBALS['xoopsConfig']['language'];

            if (!\is_dir(XOOPS_ROOT_PATH . "/modules/$moduleDirName/language/" . $language . '/')) {
                $language = 'english';
            }

            $img_path = XOOPS_ROOT_PATH . "/modules/$moduleDirName/language/" . $language . '/';

            $img_url = XOOPS_URL . "/modules/$moduleDirName/language/" . $language . '/';

            if (\is_readable($img_path . 'popular.png')) {
                $pop = '&nbsp;<img src="' . $img_url . 'popular.png" alt="' . _MD_TDMDOWNLOADS_INDEX_POPULAR . '" title="' . _MD_TDMDOWNLOADS_INDEX_POPULAR . '">';
            } else {
                $pop = '&nbsp;<img src ="' . XOOPS_URL . '/modules/' . $moduleDirName . '/language/english/popular.png" alt="' . _MD_TDMDOWNLOADS_INDEX_POPULAR . '" title="' . _MD_TDMDOWNLOADS_INDEX_POPULAR . '">';
            }
        }

        return $pop;
    }

    /**
     * @param mixed $global
     * @param mixed $key
     * @param mixed $default
     * @param mixed $type
     *
     * @return string
     */

    //    public static function convertFileSize($size)

    //    {

    //        if ($size > 0) {

    //            $mb = 1024 * 1024;

    //            if ($size > $mb) {

    //                $mysize = sprintf("%01.2f", $size / $mb) . " MB";

    //            } elseif ($size >= 1024) {

    //                $mysize = sprintf("%01.2f", $size / 1024) . " KB";

    //            } else {

    //                $mysize = sprintf(_AM_TDMDOWNLOADS_NUMBYTES, $size);

    //            }

    //            return $mysize;

    //        } else {

    //            return '';

    //        }

    //    }

    /**
     * @param        $global
     * @param        $key
     * @param string $default
     * @param string $type
     *
     * @return mixed|string
     */
    public static function cleanVars($global, $key, $default = '', $type = 'int')
    {
        switch ($type) {
            case 'string':
                if (\defined('FILTER_SANITIZE_ADD_SLASHES')) {
                    $ret = isset($global[$key]) ? \filter_var($global[$key]) : $default;
                } else {
                    $ret = isset($global[$key]) ? \filter_var($global[$key]) : $default;
                }
                break;
            case 'int':
            default:
                $ret = isset($global[$key]) ? \filter_var($global[$key], \FILTER_SANITIZE_NUMBER_INT) : $default;
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
     *
     * @return string
     */
    public static function getPathTree($mytree, $key, $category_array, $title, $prefix = '')
    {
        $category_parent = $mytree->getAllParent($key);

        $category_parent = \array_reverse($category_parent);

        $Path = '';

        foreach (\array_keys($category_parent) as $j) {
            $Path .= $category_parent[$j]->getVar($title) . $prefix;
        }

        $first_category = '';

        if (\array_key_exists($key, $category_array)) {
            $first_category = $category_array[$key]->getVar($title);
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
     *
     * @return string
     */
    public static function getPathTreeUrl(
        $mytree,
        $key,
        $category_array,
        $title,
        $prefix = '',
        $link = false,
        $order = 'ASC',
        $lasturl = false
    ) {
        global $xoopsModule;

        $category_parent = $mytree->getAllParent($key);

        if ('ASC' === $order) {
            $category_parent = \array_reverse($category_parent);

            if ($link) {
                $Path = '<a href="index.php">' . $xoopsModule->name() . '</a>' . $prefix;
            } else {
                $Path = $xoopsModule->name() . $prefix;
            }
        } else {
            $first_category = '';

            if (\array_key_exists($key, $category_array)) {
                $first_category = $category_array[$key]->getVar($title);
            }

            $Path = $first_category . $prefix;
        }

        foreach (\array_keys($category_parent) as $j) {
            if ($link) {
                $Path .= '<a href="viewcat.php?cid=' . $category_parent[$j]->getVar('cat_cid') . '">' . $category_parent[$j]->getVar($title) . '</a>' . $prefix;
            } else {
                $Path .= $category_parent[$j]->getVar($title) . $prefix;
            }
        }

        if ('ASC' === $order) {
            if (\array_key_exists($key, $category_array)) {
                if ($lasturl) {
                    $first_category = '<a href="viewcat.php?cid=' . $category_array[$key]->getVar('cat_cid') . '">' . $category_array[$key]->getVar($title) . '</a>';
                } else {
                    $first_category = $category_array[$key]->getVar($title);
                }
            } else {
                $first_category = '';
            }

            $Path .= $first_category;
        } else {
            if ($link) {
                $Path .= '<a href="index.php">' . $xoopsModule->name() . '</a>';
            } else {
                $Path .= $xoopsModule->name();
            }
        }

        return $Path;
    }

    /**
     * @param      $path
     * @param int  $mode
     * @param      $fileSource
     * @param null $fileTarget
     * @throws \RuntimeException
     */
    public static function createFolder($path, $mode, $fileSource, $fileTarget = null)
    {
        if (!@\mkdir($path, $mode) && !\is_dir($path)) {
            throw new \RuntimeException(\sprintf('Unable to create the %s directory', $path));
        }

        file_put_contents($path . '/index.html', '<script>history.go(-1);</script>');

        if (!empty($fileSource) && !empty($fileTarget)) {
            @\copy($fileSource, $fileTarget);
        }

        \chmod($path, $mode);
    }

    /**
     * @param $pathSource
     * @param $pathTarget
     * @throws \RuntimeException
     */
    public static function cloneFolder($pathSource, $pathTarget)
    {
        if (\is_dir($pathSource)) {
            // Create new dir

            if (!\mkdir($pathTarget) && !\is_dir($pathTarget)) {
                throw new \RuntimeException(\sprintf('Unable to create the %s directory', $pathTarget));
            }

            // check all files in dir, and process it

            $handle = \opendir($pathSource);

            if ($handle) {
                while ($file = \readdir($handle)) {
                    if ('.' !== $file && '..' !== $file) {
                        self::cloneFolder("$pathSource/$file", "$pathTarget/$file");
                    }
                }

                \closedir($handle);
            }
        } else {
            \copy($pathSource, $pathTarget);
        }
    }

    /**
     * Function responsible for checking if a directory exists, we can also write in and create an index.html file
     *
     * @param string $folder Le chemin complet du répertoire à vérifier
     *
     * @throws \RuntimeException
     */
    public static function prepareFolder($folder)
    {
        if (!@\mkdir($folder) && !\is_dir($folder)) {
            throw new \RuntimeException(\sprintf('Unable to create the %s directory', $folder));
        }

        file_put_contents($folder . '/index.html', '<script>history.go(-1);</script>');
    }
}
