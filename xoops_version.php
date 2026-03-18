<?php

declare(strict_types=1);
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
 * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage)
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');
require_once __DIR__ . '/preloads/autoloader.php';
$moduleDirName = basename(__DIR__);
xoops_load('xoopseditorhandler');
$editorHandler = \XoopsEditorHandler::getInstance();
$xoopsUrl      = parse_url(XOOPS_URL);
$utility       = new \XoopsModules\Tdmdownloads\Utility();
$modversion    = [
    'name'                => _MI_TDMDOWNLOADS_NAME,
    'version'             => '2.1.0',
    'module_status'       => 'RC 1',
    'release_date'        => '2026/02/08',
    'description'         => _MI_TDMDOWNLOADS_DESC,
    'credits'             => 'Mage, Mamba, Goffy, Heyula',
    'author'              => 'Mage',
    'nickname'            => 'Mage',
    'module_website_url'  => 'www.xoops.org',
    'module_website_name' => 'Support site',
    'help'                => 'page=help',
    'license'             => 'GNU GPL 2.0 or later',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html',
    'official'            => 0,
    // ------------------- Folders & Files -------------------
    'dirname'             => $moduleDirName,
    'image'               => 'assets/images/logoModule.png',
    'modicons16'          => 'assets/images/icons/16',
    'modicons32'          => 'assets/images/icons/32',
    'release_file'        => XOOPS_URL . '/modules/' . $moduleDirName . '/docs/changelog.txt',
    'onInstall'           => 'include/oninstall.php',
    'onUpdate'            => 'include/onupdate.php',
    // ------------------- Min Requirements -------------------
    'min_php'             => '7.4',
    'min_xoops'           => '2.5.11',
    'min_admin'           => '1.2',
    'min_db'              => ['mysql' => '5.5'],
    // ------------------- Admin Menu -------------------
    'hasAdmin'            => 1,
    'system_menu'         => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    // ------------------- Mysql -------------------
    'sqlfile'             => ['mysql' => 'sql/mysql.sql'],
    // ------------------- Tables -------------------
    'tables'              => [
        $moduleDirName . '_broken',
        $moduleDirName . '_cat',
        $moduleDirName . '_downloads',
        $moduleDirName . '_mod',
        $moduleDirName . '_votedata',
        $moduleDirName . '_field',
        $moduleDirName . '_fielddata',
        $moduleDirName . '_modfielddata',
        $moduleDirName . '_downlimit',
    ],
    // ------------------- Menu -------------------
    'hasMain'             => 1,
    'sub'                 => [
        [
            'name' => _MI_TDMDOWNLOADS_SMNAME1,
            'url'  => 'submit.php',
        ],
        [
            'name' => _MI_TDMDOWNLOADS_SMNAME2,
            'url'  => 'search.php',
        ],
    ],
    // ------------------- Search -------------------
    'hasSearch'           => 1,
    'search'              => [
        'file' => 'include/search.inc.php',
        'func' => 'tdmdownloads_search',
    ],
];
// Pour les blocs
$modversion['blocks'][] = [
    'file'        => 'tdmdownloads_top.php',
    'name'        => _MI_TDMDOWNLOADS_BNAME1,
    'description' => _MI_TDMDOWNLOADS_BNAMEDSC1,
    'show_func'   => 'b_tdmdownloads_top_show',
    'edit_func'   => 'b_tdmdownloads_top_edit',
    'options'     => 'date|10|19|1|1|1|left|90|400|0',
    'template'    => $moduleDirName . '_block_new.tpl',
];
$modversion['blocks'][] = [
    'file'        => 'tdmdownloads_top.php',
    'name'        => _MI_TDMDOWNLOADS_BNAME2,
    'description' => _MI_TDMDOWNLOADS_BNAMEDSC2,
    'show_func'   => 'b_tdmdownloads_top_show',
    'edit_func'   => 'b_tdmdownloads_top_edit',
    'options'     => 'hits|10|19|1|1|1|left|90|400|0',
    'template'    => $moduleDirName . '_block_top.tpl',
];
$modversion['blocks'][] = [
    'file'        => 'tdmdownloads_top.php',
    'name'        => _MI_TDMDOWNLOADS_BNAME3,
    'description' => _MI_TDMDOWNLOADS_BNAMEDSC3,
    'show_func'   => 'b_tdmdownloads_top_show',
    'edit_func'   => 'b_tdmdownloads_top_edit',
    'options'     => 'rating|10|19|1|1|1|left|90|400|0',
    'template'    => $moduleDirName . '_block_rating.tpl',
];
$modversion['blocks'][] = [
    'file'        => 'tdmdownloads_top.php',
    'name'        => _MI_TDMDOWNLOADS_BNAME4,
    'description' => _MI_TDMDOWNLOADS_BNAMEDSC4,
    'show_func'   => 'b_tdmdownloads_top_show',
    'edit_func'   => 'b_tdmdownloads_top_edit',
    'options'     => 'random|10|19|1|1|1|left|90|400|0',
    'template'    => $moduleDirName . '_block_random.tpl',
];
$modversion['blocks'][] = [
    'file'        => 'tdmdownloads_search.php',
    'name'        => _MI_TDMDOWNLOADS_BNAME5,
    'description' => _MI_TDMDOWNLOADS_BNAMEDSC5,
    'show_func'   => 'b_tdmdownloads_search_show',
    'edit_func'   => '',
    'options'     => '',
    'template'    => $moduleDirName . '_block_search.tpl',
];
// Commentaires
$modversion['hasComments']                     = 1;
$modversion['comments']['itemName']            = 'lid';
$modversion['comments']['pageName']            = 'singlefile.php';
$modversion['comments']['extraParams']         = ['cid'];
$modversion['comments']['callbackFile']        = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'tdmdownloads_com_approve';
$modversion['comments']['callback']['update']  = 'tdmdownloads_com_update';
// Templates
$modversion['templates'] = [
    // Admin
    ['file' => $moduleDirName . '_admin_about.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => $moduleDirName . '_admin_header.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => $moduleDirName . '_admin_index.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => $moduleDirName . '_admin_footer.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => $moduleDirName . '_admin_category.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => $moduleDirName . '_admin_downloads.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => $moduleDirName . '_admin_broken.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => $moduleDirName . '_admin_modified.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => $moduleDirName . '_admin_field.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => $moduleDirName . '_admin_import.tpl', 'description' => '', 'type' => 'admin'],
    ['file' => $moduleDirName . '_admin_permissions.tpl', 'description' => '', 'type' => 'admin'],
    // Blocks styles
    ['file' => $moduleDirName . '_block_styledefault.tpl', 'description' => '', 'type' => 'block'],
    ['file' => $moduleDirName . '_block_stylesimple.tpl', 'description' => '', 'type' => 'block'],
    // User
    ['file' => $moduleDirName . '_brokenfile.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_download.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_index.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_modfile.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_ratefile.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_singlefile.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_submit.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_viewcat.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_liste.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_rss.tpl', 'description' => ''],
    //uploads
    ['file' => $moduleDirName . '_trigger_uploads.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_upload.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_header.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_footer.tpl', 'description' => ''],
    ['file' => $moduleDirName . '_breadcrumbs.tpl', 'description' => ''],
];
// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_TDMDOWNLOADS_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_TDMDOWNLOADS_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_TDMDOWNLOADS_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_TDMDOWNLOADS_SUPPORT, 'link' => 'page=support'],
];
// Préférences
$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_GENERAL',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'odd',
    'category'    => 'group_header',
];
$modversion['config'][] = [
    'name'        => 'popular',
    'title'       => '_MI_TDMDOWNLOADS_POPULAR',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 100,
];
$modversion['config'][] = [
    'name'        => 'autosummary',
    'title'       => '_MI_TDMDOWNLOADS_AUTO_SUMMARY',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$modversion['config'][] = [
    'name'        => 'showupdated',
    'title'       => '_MI_TDMDOWNLOADS_SHOW_UPDATED',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
$modversion['config'][] = [
    'name'        => 'useshots',
    'title'       => '_MI_TDMDOWNLOADS_USESHOTS',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
$modversion['config'][] = [
    'name'        => 'shotwidth',
    'title'       => '_MI_TDMDOWNLOADS_SHOTWIDTH',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 90,
];
$modversion['config'][] = [
    'name'        => 'img_float',
    'title'       => '_MI_TDMDOWNLOADS_IMGFLOAT',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'left',
    'options'     => [_MI_TDMDOWNLOADS_IMGFLOAT_LEFT => 'left', _MI_TDMDOWNLOADS_IMGFLOAT_RIGHT => 'Aaright'],
];
$modversion['config'][] = [
    'name'        => 'platform',
    'title'       => '_MI_TDMDOWNLOADS_PLATEFORM',
    'description' => '_MI_TDMDOWNLOADS_PLATEFORM_DSC',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => 'None|XOOPS 2.0.x|XOOPS 2.2.x|XOOPS 2.3.x|XOOPS 2.4.x|XOOPS 2.5.x|XOOPS 2.6.x|Other',
];
$modversion['config'][] = [
    'name'        => 'usetellafriend',
    'title'       => '_MI_TDMDOWNLOADS_USETELLAFRIEND',
    'description' => '_MI_TDMDOWNLOADS_USETELLAFRIENDDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$modversion['config'][] = [
    'name'        => 'usetag',
    'title'       => '_MI_TDMDOWNLOADS_USETAG',
    'description' => '_MI_TDMDOWNLOADS_USETAGDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
//xoops_load('xoopseditorhandler');
//$editorHandler = \XoopsEditorHandler::getInstance();
$modversion['config'][] = [
    'name'        => 'editor',
    'title'       => '_MI_TDMDOWNLOADS_FORM_OPTIONS',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'dhtmltextarea',
    'options'     => array_flip($editorHandler->getList()),
];
$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_USER',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'odd',
    'category'    => 'group_header',
];
$modversion['config'][] = [
    'name'        => 'perpage',
    'title'       => '_MI_TDMDOWNLOADS_PERPAGE',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];
$modversion['config'][] = [
    'name'        => 'nb_dowcol',
    'title'       => '_MI_TDMDOWNLOADS_NBDOWCOL',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 1,
    'options'     => ['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5],
];
$modversion['config'][] = [
    'name'        => 'newdownloads',
    'title'       => '_MI_TDMDOWNLOADS_NEWDLS',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];
$modversion['config'][] = [
    'name'        => 'toporder',
    'title'       => '_MI_TDMDOWNLOADS_TOPORDER',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 1,
    'options'     => [
        '_MI_TDMDOWNLOADS_TOPORDER1' => 1,
        '_MI_TDMDOWNLOADS_TOPORDER2' => 2,
        '_MI_TDMDOWNLOADS_TOPORDER3' => 3,
        '_MI_TDMDOWNLOADS_TOPORDER4' => 4,
        '_MI_TDMDOWNLOADS_TOPORDER5' => 5,
        '_MI_TDMDOWNLOADS_TOPORDER6' => 6,
        '_MI_TDMDOWNLOADS_TOPORDER7' => 7,
        '_MI_TDMDOWNLOADS_TOPORDER8' => 8,
    ],
];
$modversion['config'][] = [
    'name'        => 'perpageliste',
    'title'       => '_MI_TDMDOWNLOADS_PERPAGELISTE',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 15,
];
$modversion['config'][] = [
    'name'        => 'searchorder',
    'title'       => '_MI_TDMDOWNLOADS_SEARCHORDER',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 8,
    'options'     => [
        '_MI_TDMDOWNLOADS_TOPORDER1' => 1,
        '_MI_TDMDOWNLOADS_TOPORDER2' => 2,
        '_MI_TDMDOWNLOADS_TOPORDER3' => 3,
        '_MI_TDMDOWNLOADS_TOPORDER4' => 4,
        '_MI_TDMDOWNLOADS_TOPORDER5' => 5,
        '_MI_TDMDOWNLOADS_TOPORDER6' => 6,
        '_MI_TDMDOWNLOADS_TOPORDER7' => 7,
        '_MI_TDMDOWNLOADS_TOPORDER8' => 8,
    ],
];
$modversion['config'][] = [
    'name'        => 'nbsouscat',
    'title'       => '_MI_TDMDOWNLOADS_SUBCATPARENT',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 5,
];
$modversion['config'][] = [
    'name'        => 'nb_catcol',
    'title'       => '_MI_TDMDOWNLOADS_NBCATCOL',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 3,
    'options'     => ['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5],
];
$modversion['config'][] = [
    'name'        => 'bldate',
    'title'       => '_MI_TDMDOWNLOADS_BLDATE',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
$modversion['config'][] = [
    'name'        => 'blpop',
    'title'       => '_MI_TDMDOWNLOADS_BLPOP',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
$modversion['config'][] = [
    'name'        => 'blrating',
    'title'       => '_MI_TDMDOWNLOADS_BLRATING',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
$modversion['config'][] = [
    'name'        => 'nbbl',
    'title'       => '_MI_TDMDOWNLOADS_NBBL',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 5,
];
$modversion['config'][] = [
    'name'        => 'longbl',
    'title'       => '_MI_TDMDOWNLOADS_LONGBL',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 20,
];
$modversion['config'][] = [
    'name'        => 'show_bookmark',
    'title'       => '_MI_TDMDOWNLOADS_BOOKMARK',
    'description' => '_MI_TDMDOWNLOADS_BOOKMARK_DSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
$modversion['config'][] = [
    'name'        => 'show_social',
    'title'       => '_MI_TDMDOWNLOADS_SOCIAL',
    'description' => '_MI_TDMDOWNLOADS_SOCIAL_DSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
$modversion['config'][] = [
    'name'        => 'download_float',
    'title'       => '_MI_TDMDOWNLOADS_DOWNLOADFLOAT',
    'description' => '_MI_TDMDOWNLOADS_DOWNLOADFLOAT_DSC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'ltr',
    'options'     => [_MI_TDMDOWNLOADS_DOWNLOADFLOAT_LTR => 'ltr', _MI_TDMDOWNLOADS_DOWNLOADFLOAT_RTL => 'rtl'],
];
$modversion['config'][] = [
    'name'        => 'show_latest_files',
    'title'       => '_MI_TDMDOWNLOADS_SHOW_LATEST_FILES',
    'description' => '_MI_TDMDOWNLOADS_SHOW_LATEST_FILES_DSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_ADMIN',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'odd',
    'category'    => 'group_header',
];
$modversion['config'][] = [
    'name'        => 'perpageadmin',
    'title'       => '_MI_TDMDOWNLOADS_PERPAGEADMIN',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 15,
];
$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_DOWNLOADS',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'odd',
    'category'    => 'group_header',
];
$modversion['config'][] = [
    'name'        => 'permission_download',
    'title'       => '_MI_TDMDOWNLOADS_PERMISSIONDOWNLOAD',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 1,
    'options'     => ['_MI_TDMDOWNLOADS_PERMISSIONDOWNLOAD1' => 1, '_MI_TDMDOWNLOADS_PERMISSIONDOWNLOAD2' => 2],
];
$modversion['config'][] = [
    'name'        => 'newnamedownload',
    'title'       => '_MI_TDMDOWNLOADS_DOWNLOAD_NAME',
    'description' => '_MI_TDMDOWNLOADS_DOWNLOAD_NAMEDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
$modversion['config'][] = [
    'name'        => 'prefixdownloads',
    'title'       => '_MI_TDMDOWNLOADS_DOWNLOAD_PREFIX',
    'description' => '_MI_TDMDOWNLOADS_DOWNLOAD_PREFIXDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'downloads_',
];
$iniPostMaxSize         = \XoopsModules\Tdmdownloads\Utility::returnBytes(ini_get('post_max_size'));
$iniUploadMaxFileSize   = \XoopsModules\Tdmdownloads\Utility::returnBytes(ini_get('upload_max_filesize'));
$maxSize                = min($iniPostMaxSize, $iniUploadMaxFileSize);
if ($maxSize > 10000 * 1048576) {
    $increment = 500;
}
if ($maxSize <= 10000 * 1048576) {
    $increment = 200;
}
if ($maxSize <= 5000 * 1048576) {
    $increment = 100;
}
if ($maxSize <= 2500 * 1048576) {
    $increment = 50;
}
if ($maxSize <= 1000 * 1048576) {
    $increment = 20;
}
if ($maxSize <= 500 * 1048576) {
    $increment = 10;
}
if ($maxSize <= 100 * 1048576) {
    $increment = 2;
}
if ($maxSize <= 50 * 1048576) {
    $increment = 1;
}
if ($maxSize <= 25 * 1048576) {
    $increment = 0.5;
}
$optionMaxsize = [];
$i             = $increment;
while ($i * 1048576 <= $maxSize) {
    $optionMaxsize[$i . ' ' . _MI_TDMDOWNLOADS_MAXUPLOAD_SIZE_MB] = $i * 1048576;
    $i                                                            += $increment;
}
$modversion['config'][] = [
    'name'        => 'maxuploadsize',
    'title'       => '_MI_TDMDOWNLOADS_MAXUPLOAD_SIZE',
    'description' => '_MI_TDMDOWNLOADS_MAXUPLOAD_SIZE_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 1048576,
    'options'     => $optionMaxsize,
];
$modversion['config'][] = [
    'name'        => 'mimetypes',
    'title'       => '_MI_TDMDOWNLOADS_MIMETYPE',
    'description' => '_MI_TDMDOWNLOADS_MIMETYPE_DSC',
    'formtype'    => 'select_multi',
    'valuetype'   => 'array',
    'default'     => [
        'image/gif',
        'image/jpeg',
        'image/png',
        'application/zip',
        'application/rar',
        'application/pdf',
        'application/x-gtar',
        'application/x-tar',
        'application/msword',
        'application/vnd.ms-excel',
        'application/vnd.oasis.opendocument.text',
        'application/vnd.oasis.opendocument.spreadsheet',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ],
    'options'     => require $GLOBALS['xoops']->path('include/mimetypes.inc.php'),
];
//---------------- picture -------------------------
require_once __DIR__ . '/config/imageconfig.php';
//---------------- picture -------------------------
$modversion['config'][] = [
    'name'        => 'check_host',
    'title'       => '_MI_TDMDOWNLOADS_CHECKHOST',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$xoopsUrl               = parse_url(XOOPS_URL);
$modversion['config'][] = [
    'name'        => 'referers',
    'title'       => '_MI_TDMDOWNLOADS_REFERERS',
    'description' => '',
    'formtype'    => 'textarea',
    'valuetype'   => 'array',
    'default'     => [$xoopsUrl['host']],
];
$modversion['config'][] = [
    'name'        => 'downlimit',
    'title'       => '_MI_TDMDOWNLOADS_DOWNLIMIT',
    'description' => '_MI_TDMDOWNLOADS_DOWNLIMITDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$modversion['config'][] = [
    'name'        => 'limitglobal',
    'title'       => '_MI_TDMDOWNLOADS_LIMITGLOBAL',
    'description' => '_MI_TDMDOWNLOADS_LIMITGLOBALDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];
$modversion['config'][] = [
    'name'        => 'limitlid',
    'title'       => '_MI_TDMDOWNLOADS_LIMITLID',
    'description' => '_MI_TDMDOWNLOADS_LIMITLIDDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 2,
];
$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_PAYPAL',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'odd',
    'category'    => 'group_header',
];
$modversion['config'][] = [
    'name'        => 'use_paypal',
    'title'       => '_MI_TDMDOWNLOADS_USEPAYPAL',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
$modversion['config'][] = [
    'name'        => 'currency_paypal',
    'title'       => '_MI_TDMDOWNLOADS_CURRENCYPAYPAL',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'EUR',
    'options'     => [
        'AUD' => 'AUD',
        'BRL' => 'BRL',
        'CAD' => 'CAD',
        'CHF' => 'CHF',
        'CZK' => 'CZK',
        'DKK' => 'DKK',
        'EUR' => 'EUR',
        'GBP' => 'GBP',
        'HKD' => 'HKD',
        'HUF' => 'HUF',
        'ILS' => 'ILS',
        'JPY' => 'JPY',
        'MXN' => 'MXN',
        'NOK' => 'NOK',
        'NZD' => 'NZD',
        'PHP' => 'PHP',
        'PLN' => 'PLN',
        'SEK' => 'SEK',
        'SGD' => 'SGD',
        'THB' => 'THB',
        'TWD' => 'TWD',
        'USD' => 'USD',
    ],
];
$modversion['config'][] = [
    'name'        => 'image_paypal',
    'title'       => '_MI_TDMDOWNLOADS_IMAGEPAYPAL',
    'description' => '_MI_TDMDOWNLOADS_IMAGEPAYPALDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'https://www.paypal.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif',
];
$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_RSS',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'odd',
    'category'    => 'group_header',
];
$modversion['config'][] = [
    'name'        => 'perpagerss',
    'title'       => '_MI_TDMDOWNLOADS_PERPAGERSS',
    'description' => '_MI_TDMDOWNLOADS_PERPAGERSSDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 10,
];
$modversion['config'][] = [
    'name'        => 'timecacherss',
    'title'       => '_MI_TDMDOWNLOADS_TIMECACHERSS',
    'description' => '_MI_TDMDOWNLOADS_TIMECACHERSSDSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 60,
];
$modversion['config'][] = [
    'name'        => 'logorss',
    'title'       => '_MI_TDMDOWNLOADS_LOGORSS',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '/modules/' . $moduleDirName . '/assets/images/mydl_slogo.png',
];
$modversion['config'][] = [
    'name'        => 'break',
    'title'       => '_MI_TDMDOWNLOADS_CONFCAT_OTHERS',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'odd',
    'category'    => 'group_header',
];
/**
 * Make Sample button visible?
 */
$modversion['config'][] = [
    'name'        => 'displaySampleButton',
    'title'       => '_MI_TDMDOWNLOADS_SHOW_SAMPLE_BUTTON',
    'description' => '_MI_TDMDOWNLOADS_SHOW_SAMPLE_BUTTON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
/**
 * Show Developer Tools?
 */
$modversion['config'][] = [
    'name'        => 'displayDeveloperTools',
    'title'       => '_MI_TDMDOWNLOADS_SHOW_DEV_TOOLS',
    'description' => '_MI_TDMDOWNLOADS_SHOW_DEV_TOOLS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
// ------------------- Notifications -------------------
$modversion['config'][]                    = [
    'name'        => 'break',
    'title'       => '_MI_TDMDOWNLOADS_PREFERENCE_BREAK_COMNOTI',
    'description' => '',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'odd',
    'category'    => 'group_header',
];
$modversion['hasNotification']             = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'tdmdownloads_notify_iteminfo';
$modversion['notification']['category'][]  = [
    'name'           => 'global',
    'title'          => _MI_TDMDOWNLOADS_GLOBAL_NOTIFY,
    'description'    => _MI_TDMDOWNLOADS_GLOBAL_NOTIFYDSC,
    'subscribe_from' => ['index.php', 'viewcat.php', 'singlefile.php'],
];
$modversion['notification']['category'][]  = [
    'name'           => 'category',
    'title'          => _MI_TDMDOWNLOADS_CATEGORY_NOTIFY,
    'description'    => _MI_TDMDOWNLOADS_CATEGORY_NOTIFYDSC,
    'subscribe_from' => ['viewcat.php', 'singlefile.php'],
    'item_name'      => 'cid',
    'allow_bookmark' => 1,
];
$modversion['notification']['category'][]  = [
    'name'           => 'file',
    'title'          => _MI_TDMDOWNLOADS_FILE_NOTIFY,
    'description'    => _MI_TDMDOWNLOADS_FILE_NOTIFYDSC,
    'subscribe_from' => 'singlefile.php',
    'item_name'      => 'lid',
    'allow_bookmark' => 1,
];
$modversion['notification']['event'][]     = [
    'name'          => 'new_category',
    'category'      => 'global',
    'title'         => _MI_TDMDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFY,
    'caption'       => _MI_TDMDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYCAP,
    'description'   => _MI_TDMDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYDSC,
    'mail_template' => 'global_newcategory_notify',
    'mail_subject'  => _MI_TDMDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYSBJ,
];
$modversion['notification']['event'][]     = [
    'name'          => 'file_modify',
    'category'      => 'global',
    'admin_only'    => 1,
    'title'         => _MI_TDMDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFY,
    'caption'       => _MI_TDMDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYCAP,
    'description'   => _MI_TDMDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYDSC,
    'mail_template' => 'global_filemodify_notify',
    'mail_subject'  => _MI_TDMDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYSBJ,
];
$modversion['notification']['event'][]     = [
    'name'          => 'file_submit',
    'category'      => 'global',
    'admin_only'    => 1,
    'title'         => _MI_TDMDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFY,
    'caption'       => _MI_TDMDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYCAP,
    'description'   => _MI_TDMDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYDSC,
    'mail_template' => 'global_filesubmit_notify',
    'mail_subject'  => _MI_TDMDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYSBJ,
];
$modversion['notification']['event'][]     = [
    'name'          => 'file_broken',
    'category'      => 'global',
    'admin_only'    => 1,
    'title'         => _MI_TDMDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFY,
    'caption'       => _MI_TDMDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYCAP,
    'description'   => _MI_TDMDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYDSC,
    'mail_template' => 'global_filebroken_notify',
    'mail_subject'  => _MI_TDMDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYSBJ,
];
$modversion['notification']['event'][]     = [
    'name'          => 'new_file',
    'category'      => 'global',
    'title'         => _MI_TDMDOWNLOADS_GLOBAL_NEWFILE_NOTIFY,
    'caption'       => _MI_TDMDOWNLOADS_GLOBAL_NEWFILE_NOTIFYCAP,
    'description'   => _MI_TDMDOWNLOADS_GLOBAL_NEWFILE_NOTIFYDSC,
    'mail_template' => 'global_newfile_notify',
    'mail_subject'  => _MI_TDMDOWNLOADS_GLOBAL_NEWFILE_NOTIFYSBJ,
];
$modversion['notification']['event'][]     = [
    'name'          => 'file_submit',
    'category'      => 'category',
    'admin_only'    => 1,
    'title'         => _MI_TDMDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFY,
    'caption'       => _MI_TDMDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYCAP,
    'description'   => _MI_TDMDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYDSC,
    'mail_template' => 'category_filesubmit_notify',
    'mail_subject'  => _MI_TDMDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYSBJ,
];
$modversion['notification']['event'][]     = [
    'name'          => 'new_file',
    'category'      => 'category',
    'title'         => _MI_TDMDOWNLOADS_CATEGORY_NEWFILE_NOTIFY,
    'caption'       => _MI_TDMDOWNLOADS_CATEGORY_NEWFILE_NOTIFYCAP,
    'description'   => _MI_TDMDOWNLOADS_CATEGORY_NEWFILE_NOTIFYDSC,
    'mail_template' => 'category_newfile_notify',
    'mail_subject'  => _MI_TDMDOWNLOADS_CATEGORY_NEWFILE_NOTIFYSBJ,
];
$modversion['notification']['event'][]     = [
    'name'          => 'approve',
    'category'      => 'file',
    'invisible'     => 1,
    'title'         => _MI_TDMDOWNLOADS_FILE_APPROVE_NOTIFY,
    'caption'       => _MI_TDMDOWNLOADS_FILE_APPROVE_NOTIFYCAP,
    'description'   => _MI_TDMDOWNLOADS_FILE_APPROVE_NOTIFYDSC,
    'mail_template' => 'file_approve_notify',
    'mail_subject'  => _MI_TDMDOWNLOADS_FILE_APPROVE_NOTIFYSBJ,
];
