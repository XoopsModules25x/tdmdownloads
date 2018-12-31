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

require __DIR__ . '/admin_header.php';
xoops_cp_header();
// pour file protection
$xoops_url = parse_url(XOOPS_URL);
$xoops_url = str_replace('www.', '', $xoops_url['host']);
$file_protection = _AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION_INFO1 . "<br><br>" . XOOPS_ROOT_PATH . "/uploads/tdmdownloads/downloads/" . "<br><br>" . _AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION_INFO2 . "<br><br>";
$file_protection .= "RewriteEngine on" . "<br>" . "RewriteCond %{HTTP_REFERER} !" . $xoops_url . "/.*$ [NC]<br>ReWriteRule \.*$ - [F]";
if (TDMDownloads_checkModuleAdmin()) {
    $about_admin = \Xmf\Module\Admin::getInstance();
    $about_admin->addInfoBox(_AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION);
    $about_admin->addInfoBoxLine(_AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION, $file_protection, '', '', 'information');
    echo $about_admin->displayNavigation('about.php');
    echo $about_admin->renderabout('gregory.mage@gmail.com', true);
}
xoops_cp_footer();
