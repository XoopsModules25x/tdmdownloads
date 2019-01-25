<?php
### =============================================================
### Mastop InfoDigital - Paix�o por Internet
### =============================================================
### Formul�rio de Conte�do
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital � 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
###
### =============================================================

use XoopsModules\Tdmdownloads;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/** @var Tdmdownloads\Helper $helper */
$helper = Tdmdownloads\Helper::getInstance();

$feedbackform = new \XoopsThemeForm($form['title'], 'mpu_feedbackform', $_SERVER['PHP_SELF'], 'post', true);
$feedbackform->addElement(new \XoopsFormText(MPU_ADM_YNAME, 'yname', 35, 50, $xoopsUser->getVar('name')));
$feedbackform->addElement(new \XoopsFormText(MPU_ADM_YEMAIL, 'yemail', 35, 50, $xoopsConfig['adminmail']));
$feedbackform->addElement(new \XoopsFormText(MPU_ADM_YSITE, 'ydomain', 35, 50, XOOPS_URL));
$feedback_category_tray = new \XoopsFormElementTray(MPU_ADM_FEEDTYPE, '&nbsp;&nbsp;&nbsp;');
$category_select        = new \XoopsFormSelect('', 'feedback_type', MPU_ADM_TSUGGESTION);
$category_select->addOptionArray([
                                     MPU_ADM_TSUGGESTION => MPU_ADM_TSUGGESTION,
                                     MPU_ADM_TBUGS       => MPU_ADM_TBUGS,
                                     MPU_ADM_TESTIMONIAL => MPU_ADM_TESTIMONIAL,
                                     MPU_ADM_TFEATURES   => MPU_ADM_TFEATURES,
                                     MPU_ADM_TOTHERS     => MPU_ADM_TOTHERS
                                 ]);
$feedback_category_tray->addElement($category_select);
$feedback_category_tray->addElement(new \XoopsFormText(MPU_ADM_TOTHERS, 'feedback_other', 25, 50));
$feedbackform->addElement($feedback_category_tray);

if (!$helper->getConfig('mpu_conf_wysiwyg')) {
    $feedbackform->addElement(new \XoopsFormDhtmlTextArea(MPU_ADM_DESC, 'feedback_content'));
} else {
    $feedbackwysiwyg_url = XOOPS_URL . $helper->getConfig('mpu_conf_wysiwyg_path');
    if ($helper->getConfig('mpu_conf_gzip')) {
    } else {
    }
    $textarea = new \XoopsFormTextArea(MPU_ADM_DESC, 'feedback_content', '', 20);
    $textarea->setExtra("style='width: 100%' class='mpu_wysiwyg'");
    $feedbackform->addElement($textarea);
}
$feedbackform->addElement(new \XoopsFormHidden('op', $form['op']));
$feedbackbotoes_tray  = new \XoopsFormElementTray('', '&nbsp;&nbsp;');
$feedbackbotao_cancel = new \XoopsFormButton('', 'cancelar', _CANCEL);
$feedbackbotoes_tray->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
$feedbackbotao_cancel->setExtra("onclick=\"document.location= '" . XOOPS_URL . '/modules/' . MPU_MOD_DIR . "/admin/index.php'\"");
$feedbackbotoes_tray->addElement($feedbackbotao_cancel);
$feedbackform->addElement($feedbackbotoes_tray);
