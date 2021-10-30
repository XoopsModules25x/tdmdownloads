<?php

declare(strict_types=1);

use XoopsModules\Tdmdownloads\Helper;

$moduleDirName      = basename(dirname(__DIR__));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);
$helper = Helper::getInstance();
$helper->loadLanguage('common');
// extra module configs

$modversion['config'][] = [
    'name'        => 'imageConfigs',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'PREFERENCE_BREAK_CONFIG_IMAGE',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'IMAGE_CONFIG_DSC',
    'formtype'    => 'line_break',
    'valuetype'   => 'textbox',
    'default'     => 'odd',
    'category'    => 'group_header',
];
$modversion['config'][] = [
    'name'        => 'imageWidth',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'IMAGE_WIDTH',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'IMAGE_WIDTH_DSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 1200,
]; // =1024/16
$modversion['config'][] = [
    'name'        => 'imageHeight',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'IMAGE_HEIGHT',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'IMAGE_HEIGHT_DSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 800,
]; // =768/16
$modversion['config'][] = [
    'name'        => 'imageUploadPath',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'IMAGE_UPLOAD_PATH',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'IMAGE_UPLOAD_PATH_DSC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'uploads/' . $modversion['dirname'] . '/images',
];
