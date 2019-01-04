<?php

namespace XoopsModules\Tdmdownloads;

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
require_once $GLOBALS['xoops']->path('www/class/tree.php');

// xoops >2.5.9

/**
 * Class Tree
 * @package XoopsModules\Tdmdownloads
 */
class Tree extends \XoopsObjectTree
{
    /**
     * Tree constructor.
     * @param      $objectArr
     * @param      $myId
     * @param      $parentId
     * @param null $rootId
     */
    public function __construct(&$objectArr, $myId, $parentId, $rootId = null)
    {
        parent::__construct($objectArr, $myId, $parentId, $rootId);
    }

    /**
     * @param        $fieldName
     * @param        $key
     * @param        $ret
     * @param        $prefix_orig
     * @param string $prefix_curr
     */
    protected function makeArrayTreeOptions($fieldName, $key, &$ret, $prefix_orig, $prefix_curr = '')
    {
        if ($key > 0) {
            $value       = $this->tree[$key]['obj']->getVar($this->myId);
            $ret[$value] = $prefix_curr . $this->tree[$key]['obj']->getVar($fieldName);
            $prefix_curr .= $prefix_orig;
        }
        if (isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child'])) {
            foreach ($this->tree[$key]['child'] as $childKey) {
                $this->makeArrayTreeOptions($fieldName, $childKey, $ret, $prefix_orig, $prefix_curr);
            }
        }
    }

    /**
     * @param        $fieldName
     * @param string $prefix
     * @param int    $key
     *
     * @return array
     */
    public function makeArrayTree($fieldName, $prefix = '-', $key = 0)
    {
        $ret = [];
        $this->makeArrayTreeOptions($fieldName, $key, $ret, $prefix);

        return $ret;
    }
}
/* xoops 2.5.8
class Tree extends XoopsObjectTree {

    protected function makeArrayTreeOptions($fieldName, $key, &$ret, $prefix_orig, $prefix_curr = '')
    {
        if ($key > 0) {
            $value = $this->_tree[$key]['obj']->getVar($this->_myId);
            $ret[$value] = $prefix_curr . $this->_tree[$key]['obj']->getVar($fieldName);
            $prefix_curr .= $prefix_orig;
        }
        if (isset($this->_tree[$key]['child']) && !empty($this->_tree[$key]['child'])) {
            foreach ($this->_tree[$key]['child'] as $childKey) {
                $this->makeArrayTreeOptions($fieldName, $childKey, $ret, $prefix_orig, $prefix_curr);
            }
        }
    }

    public function makeArrayTree($fieldName, $prefix = '-', $key = 0) {
        $ret = array();
        $this->makeArrayTreeOptions($fieldName, $key, $ret, $prefix);

        return $ret;
    }
}*/
