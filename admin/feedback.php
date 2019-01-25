<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo para Solicitação de Recursos
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
###
### =============================================================

use XoopsModules\Tdmdownloads;

require_once __DIR__ . '/admin_header.php';
$helper->loadLanguage('modinfo');
$moduleDirName = basename(dirname(__DIR__));
require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/functions.php';
$op = isset($_GET['op']) ? $_GET['op'] : 'feature';
if (isset($_GET)) {
    foreach ($_GET as $k => $v) {
        ${$k} = $v;
    }
}

if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}

xoops_cp_header();

switch ($op) {
    case 'save':
        $yname          = $_POST['yname'];
        $yemail         = $_POST['yemail'];
        $ydomain        = $_POST['ydomain'];
        $feedback_type  = $_POST['feedback_type'];
        $feedback_other = $_POST['feedback_other'];
        $title         = ucfirst($moduleDirName) . ' - FeedBack from ' . $ydomain;
        $body           = '<b>' . $yname . ' (' . $yemail . ') - ' . $ydomain . '</b><br>';
        $body           .= 'Type: ' . $feedback_type . (!empty($feedback_other) ? ' - ' . $feedback_other : '') . '<br>';
        $body           .= prepareContent($_POST['feedback_content']);
        $xoopsMailer    = xoops_getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setToEmails($helper->getModule()->getInfo('author_mail')); //'publish@mastop.com.br'
        $xoopsMailer->setFromEmail($yemail);
        $xoopsMailer->setFromName($yname);
        $xoopsMailer->setSubject($title);
        $xoopsMailer->multimailer->isHTML(true);
        $xoopsMailer->setBody($body);
        $xoopsMailer->send();
        $msg = '
            <div align="center" style="width: 80%; padding: 10px; padding-top:0px; padding-bottom: 5px; border: 2px solid #9C9C9C; background-color: #F2F2F2; margin-right:auto;margin-left:auto;">
            <h3>' . MPU_ADM_FEEDSUCCESS . '</h3>
            </div>
            ';
    // no break
    case 'feature':
    default:
        echo !empty($msg) ? $msg . '<br>' : '';
        $form['title'] = MPU_ADM_FEEDBACKN;
        $form['op']     = 'save';
        require_once XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/include/feedbackform.php';
        $feedbackform->display();
        break;
}

//echo $utility::checkVerModule($helper);

require_once __DIR__ . '/admin_footer.php';
