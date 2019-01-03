<?php
/*************************************************************************/
# Waiting Contents Extensible                                            #
# Plugin for module TDMDownloads                                         #
#                                                                        #
# Author                                                                 #
# Danordesign     -   flying.tux@gmail.com                               #
#                                                                        #
# Last modified on 19.10.2009                                            #
/*************************************************************************/
/**
 * @return array
 */
function b_waiting_tdmdownloads()
{
    $xoopsDB       = \XoopsDatabaseFactory::getDatabaseConnection();
    $moduleDirName = basename(dirname(dirname(__DIR__)));
    $ret           = [];

    // TDMdownloads waiting
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('tdmdownloads_downloads') . ' WHERE status=0');
    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/$moduleDirName/admin/downloads.php?op=liste&statut_display=0";
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    // TDMDownloads broken
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('tdmdownloads_broken'));
    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/$moduleDirName/admin/broken.php";
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_BROKENS;
    }
    $ret[] = $block;

    // TDMDownloads modreq
    $block  = [];
    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('tdmdownloads_mod'));
    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/$moduleDirName/admin/modified.php";
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_MODREQS;
    }
    $ret[] = $block;

    return $ret;
}
