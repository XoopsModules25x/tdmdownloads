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

function b_sitemap_tdmdownloads()
{
    $xoopsDB = \XoopsDatabaseFactory::getDatabaseConnection();
    $block   = sitemap_get_categoires_map($xoopsDB->prefix('tdmdownloads_cat'), 'cid', 'pid', 'title', 'viewcat.php?cid=', 'title');

    return $block;
}
