<?php declare(strict_types=1);

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
    /** @var \XoopsMySQLDatabase $xoopsDB */

    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();

    $moduleDirName = basename(dirname(__DIR__, 2));

    $ret = [];

    // TDMdownloads waiting

    $block = [];

    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('tdmdownloads_downloads') . ' WHERE status=0');

    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/$moduleDirName/admin/downloads.php?op=liste&statut_display=0";

        [$block['pendingnum']] = $xoopsDB->fetchRow($result);

        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }

    $ret[] = $block;

    // TDMDownloads broken

    $block = [];

    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('tdmdownloads_broken'));

    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/$moduleDirName/admin/broken.php";

        [$block['pendingnum']] = $xoopsDB->fetchRow($result);

        $block['lang_linkname'] = _PI_WAITING_BROKENS;
    }

    $ret[] = $block;

    // TDMDownloads modreq

    $block = [];

    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('tdmdownloads_mod'));

    if ($result) {
        $block['adminlink'] = XOOPS_URL . "/modules/$moduleDirName/admin/modified.php";

        [$block['pendingnum']] = $xoopsDB->fetchRow($result);

        $block['lang_linkname'] = _PI_WAITING_MODREQS;
    }

    $ret[] = $block;

    return $ret;
}
