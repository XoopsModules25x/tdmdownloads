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

class TDMObjectTree extends XoopsObjectTree {

    function __constrcut(){
    }
    function _makeArrayTreeOptions( $fieldName, $key, &$ret, $prefix_orig, $prefix_curr = '' ) {
        if ( $key > 0 ) {
            $value = $this->_tree[$key]['obj']->getVar( $this->_myId );
            $ret[$value] = $prefix_curr . $this->_tree[$key]['obj']->getVar( $fieldName );
            $prefix_curr .= $prefix_orig;

        }
        if ( isset( $this->_tree[$key]['child'] ) && !empty( $this->_tree[$key]['child'] ) ) {
            foreach ( $this->_tree[$key]['child'] as $childkey ) {
                $this->_makeArrayTreeOptions( $fieldName, $childkey, $ret, $prefix_orig, $prefix_curr );
            }
        }
    }
    function makeArrayTree( $fieldName, $prefix = '-', $key = 0) {
        $ret = array();
        $this->_makeArrayTreeOptions( $fieldName, $key, $ret, $prefix );

        return $ret;
    }
}
