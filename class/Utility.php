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

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats; // getServerStats Trait

    use Common\FilesManagement; // Files Management Trait

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
        $moduleHandler = xoops_getHandler('module');
        $tdmModule     = $moduleHandler->getByDirname($dirname);
        $groups        = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = xoops_getHandler('groupperm');
        $categories       = $grouppermHandler->getItemIds($permtype, $groups, $tdmModule->getVar('mid'));

        return $categories;
    }

    /**
     * retourne le nombre de téléchargements dans le catégories enfants d'une catégorie
     * @param \XoopsModules\Tdmdownloads\Tree        $mytree
     * @param                                        $categories
     * @param                                        $entries
     * @param                                        $cid
     * @return int
     */
    public function getNumbersOfEntries($mytree, $categories, $entries, $cid)
    {
        $count     = 0;
        $child_arr = [];
        if (in_array($cid, $categories, true)) {
            $child = $mytree->getAllChild($cid);
            foreach (array_keys($entries) as $i) {
                /** @var \XoopsModules\Tdmdownloads\Downloads[] $entries */
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
        $count     = 7;
        $new       = '';
        $startdate = (time() - (86400 * $count));
        if (1 == $xoopsModuleConfig['showupdated']) {
            if ($startdate < $time) {
                $language = $GLOBALS['xoopsConfig']['language'];
                if (!is_dir(XOOPS_ROOT_PATH . '/modules/tdmdownloads/language/' . $language . '/')) {
                    $language = 'english';
                }
                $img_path = XOOPS_ROOT_PATH . '/modules/tdmdownloads/language/' . $language . '/';
                $img_url  = XOOPS_URL . '/modules/tdmdownloads/language/' . $language . '/';
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
            $img_url  = XOOPS_URL . '/modules/tdmdownloads/language/' . $language . '/';
            if (is_readable($img_path . 'popular.png')) {
                $pop = '&nbsp;<img src="' . $img_url . 'popular.png" alt="' . _MD_TDMDOWNLOADS_INDEX_POPULAR . '" title="' . _MD_TDMDOWNLOADS_INDEX_POPULAR . '">';
            } else {
                $pop = '&nbsp;<img src ="' . XOOPS_URL . '/modules/tdmdownloads/language/english/popular.png" alt="' . _MD_TDMDOWNLOADS_INDEX_POPULAR . '" title="' . _MD_TDMDOWNLOADS_INDEX_POPULAR . '">';
            }
        }

        return $pop;
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
        $categoryParent = $mytree->getAllParent($key);
        $categoryParent = array_reverse($categoryParent);
        $path            = '';
        foreach (array_keys($categoryParent) as $j) {
            /** @var \XoopsModules\Tdmdownloads\Category[] $categoryParent */
            $path .= $categoryParent[$j]->getVar($title) . $prefix;
        }
        if (array_key_exists($key, $category_array)) {
            /** @var \XoopsModules\Tdmdownloads\Category[] $category_array */
            $firstCategory = $category_array[$key]->getVar($title);
        } else {
            $firstCategory = '';
        }
        $path .= $firstCategory;

        return $path;
    }

    /**
     * @param \XoopsModules\Tdmdownloads\Tree $mytree
     * @param                                 $key
     * @param                                 $category_array
     * @param                                 $title
     * @param string                          $prefix
     * @param bool                            $link
     * @param string                          $order
     * @param bool                            $lasturl
     * @return string
     */
    public function getPathTreeUrl($mytree, $key, $category_array, $title, $prefix = '', $link = false, $order = 'ASC', $lasturl = false)
    {
        global $xoopsModule;
        $categoryParent = $mytree->getAllParent($key);
        if ('ASC' === $order) {
            $categoryParent = array_reverse($categoryParent);
            if (true === $link) {
                $path = '<a href="index.php">' . $xoopsModule->name() . '</a>' . $prefix;
            } else {
                $path = $xoopsModule->name() . $prefix;
            }
        } else {
            if (array_key_exists($key, $category_array)) {
                /** @var \XoopsModules\Tdmdownloads\Category[] $category_array */
                $firstCategory = $category_array[$key]->getVar($title);
            } else {
                $firstCategory = '';
            }
            $path = $firstCategory . $prefix;
        }
        foreach (array_keys($categoryParent) as $j) {
            /** @var \XoopsModules\Tdmdownloads\Category[] $categoryParent */
            if (true === $link) {
                $path .= '<a href="viewcat.php?cid=' . $categoryParent[$j]->getVar('cat_cid') . '">' . $categoryParent[$j]->getVar($title) . '</a>' . $prefix;
            } else {
                $path .= $categoryParent[$j]->getVar($title) . $prefix;
            }
        }
        if ('ASC' === $order) {
            if (array_key_exists($key, $category_array)) {
                if (true === $lasturl) {
                    $firstCategory = '<a href="viewcat.php?cid=' . $category_array[$key]->getVar('cat_cid') . '">' . $category_array[$key]->getVar($title) . '</a>';
                } else {
                    $firstCategory = $category_array[$key]->getVar($title);
                }
            } else {
                $firstCategory = '';
            }
            $path .= $firstCategory;
        } else {
            if (true === $link) {
                $path .= '<a href="index.php">' . $xoopsModule->name() . '</a>';
            } else {
                $path .= $xoopsModule->name();
            }
        }

        return $path;
    }
	 /**
     * Utility::StringSizeConvert()
     *
     * @param mixed $stringSize
     * @return mixed|int
     */
	public static function StringSizeConvert($stringSize){
        if ($stringSize != '') {
            $kb = 1024;
            $mb = 1024*1024;
            $gb = 1024*1024*1024;
			$size_value_arr = explode(' ', $stringSize);
			
            if ($size_value_arr[1] == 'B') {
                $mysize = $size_value_arr[0];
            } elseif ($size_value_arr[1] == 'K') {
                $mysize = $size_value_arr[0] * $kb;
            } elseif ($size_value_arr[1] == 'M') {
                $mysize = $size_value_arr[0] * $mb;
            } else {
                $mysize = $size_value_arr[0] * $gb;
            }
            return $mysize;
        } else {
            return 0;
        }
    }
	 /**
     * Utility::SizeConvertString()
     *
     * @param mixed $sizeString
     * @return mixed|string
     */
	public static function SizeConvertString($sizeString){
		$mysizeString = '';
		if ($sizeString != '') {
			$size_value_arr = explode(' ', $sizeString);
			if (array_key_exists (0, $size_value_arr) == true && array_key_exists (1, $size_value_arr) == true){
				if ($size_value_arr[0] != ''){
					$mysizeString = '';
					switch ($size_value_arr[1]) {
						case 'B':
							$mysizeString = $size_value_arr[0] . ' ' . _AM_TDMDOWNLOADS_BYTES;
							break;
							
						case 'K':
							$mysizeString = $size_value_arr[0] . ' ' . _AM_TDMDOWNLOADS_KBYTES;
							break;
							
						case 'M':
							$mysizeString = $size_value_arr[0] . ' ' . _AM_TDMDOWNLOADS_MBYTES;
							break;
							
						case 'G':
							$mysizeString = $size_value_arr[0] . ' ' . _AM_TDMDOWNLOADS_GBYTES;
							break;
							
						case 'T':
							$mysizeString = $size_value_arr[0] . ' ' . _AM_TDMDOWNLOADS_TBYTES;
							break;
					}					
					return $mysizeString;
				}
			}
		}		
		return $mysizeString;
    }
	
	 /**
     * Utility::GetFileSize()
     *
     * @param mixed $url
     * @return mixed|string
     */
    public static function GetFileSize($url)
    {
		if (function_exists('curl_init') && false !== ($curlHandle  = curl_init($url))) {
			curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curlHandle, CURLOPT_HEADER, TRUE);
			curl_setopt($curlHandle, CURLOPT_NOBODY, TRUE);	
			curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);			
			$curlReturn = curl_exec($curlHandle);
			if (false === $curlReturn) {
				trigger_error(curl_error($curlHandle));
				$size = 0;
			} else {
				$size = curl_getinfo($curlHandle, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
			}
			curl_close($curlHandle);
			if ($size <= 0){
				return 0;
			} else {			
				return Utility::FileSizeConvert($size);
			}
		} else {
			return 0;
		}
    }
	
	/**
     * Utility::FileSizeConvert()
     *
     * @param mixed $size
     * @return mixed|string
     */
	public static function FileSizeConvert($size){
        if ($size > 0) {
            $kb = 1024;
            $mb = 1024*1024;
            $gb = 1024*1024*1024;
            if ($size >= $gb) {
                $mysize = sprintf ("%01.2f",$size/$gb) . " " . 'G';
            } elseif ($size >= $mb) {
                $mysize = sprintf ("%01.2f",$size/$mb) . " " . 'M';
            } elseif ($size >= $kb) {
                $mysize = sprintf ("%01.2f",$size/$kb) . " " . 'K';
            } else {
                $mysize = sprintf ("%01.2f",$size) . " " . 'B';
            }

            return $mysize;
        } else {
            return '';
        }
    }
}
