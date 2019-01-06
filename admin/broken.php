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

// Template
$templateMain = 'tdmdownloads_admin_broken.tpl';

/** @var \XoopsModules\Tdmdownloads\Helper $helper */
$helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

//On recupere la valeur de l'argument op dans l'URL$
$op = \Xmf\Request::getString('op', 'list');

//Les valeurs de op qui vont permettre d'aller dans les differentes parties de la page
switch ($op) {
    // Vue liste
    case 'list':
        //Affichage de la partie haute de l'administration de Xoops
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation(basename(__FILE__)));

        $criteria = new \CriteriaCompo();
        if (\Xmf\Request::hasVar('limit', 'REQUEST')) {
            $criteria->setLimit(\Xmf\Request::getInt('limit', 0, 'REQUEST'));
            $limit = \Xmf\Request::getInt('limit', 0, 'REQUEST');
        } else {
            $criteria->setLimit($helper->getConfig('perpageadmin'));
            $limit = $helper->getConfig('perpageadmin');
        }
        if (\Xmf\Request::hasVar('start', 'REQUEST')) {
            $criteria->setStart(\Xmf\Request::getInt('start', 0, 'REQUEST'));
            $start = \Xmf\Request::getInt('start', 0, 'REQUEST');
        } else {
            $criteria->setStart(0);
            $start = 0;
        }
        $criteria->setSort('reportid');
        $criteria->setOrder('ASC');
        //pour faire une jointure de table
        $brokenHandler->table_link   = $brokenHandler->db->prefix('tdmdownloads_downloads'); // Nom de la table en jointure
        $brokenHandler->field_link   = 'lid'; // champ de la table en jointure
        $brokenHandler->field_object = 'lid'; // champ de la table courante
        $downloadsbroken_arr         = $brokenHandler->getByLink($criteria);
        $numrows                     = $brokenHandler->getCount($criteria);
        $pagenav                     = '';
        if ($numrows > $limit) {
            $pagenav = new \XoopsPageNav($numrows, $limit, $start, 'start', 'op=list&limit=' . $limit);
            $pagenav = $pagenav->renderNav(4);
        }
        //Affichage du tableau des téléchargements brisés
        if ($numrows > 0) {
            $GLOBALS['xoopsTpl']->assign('broken_count', $numrows);
            $broken = [];
            foreach (array_keys($downloadsbroken_arr) as $i) {
                $broken = [
                    'lid'      => $downloadsbroken_arr[$i]->getVar('lid'),
                    'reportid' => $downloadsbroken_arr[$i]->getVar('reportid'),
                    'title'    => $downloadsbroken_arr[$i]->getVar('title'),
                    'cid'      => $downloadsbroken_arr[$i]->getVar('cid'),
                    'sender'   => XoopsUser::getUnameFromId($downloadsbroken_arr[$i]->getVar('sender')),
                    'ip'       => $downloadsbroken_arr[$i]->getVar('ip'),
                ];
                $GLOBALS['xoopsTpl']->append('broken_list', $broken);
                unset($broken);
            }
        } else {
            $GLOBALS['xoopsTpl']->assign('error', _AM_TDMDOWNLOADS_ERREUR_NOBROKENDOWNLOADS);
        }
        break;
    // permet de suprimmer le rapport de téléchargment brisé
    case 'del_brokendownloads':
        $obj = $brokenHandler->get(\Xmf\Request::getInt('broken_id', 0, 'REQUEST'));
        if (\Xmf\Request::hasVar('ok', 'REQUEST') && 1 == \Xmf\Request::getInt('ok', 0, 'REQUEST')) {
            if (!$GLOBALS['xoopsSecurity']->check()) {
                redirect_header('downloads.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
            }
            if ($brokenHandler->delete($obj)) {
                redirect_header('broken.php', 1, _AM_TDMDOWNLOADS_REDIRECT_DELOK);
            }
            $GLOBALS['xoopsTpl']->assign('error', $obj->getHtmlErrors());
        } else {
            //Affichage de la partie haute de l'administration de Xoops
            xoops_cp_header();
            $adminObject = \Xmf\Module\Admin::getInstance();
            $GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('broken.php'));
            $adminObject->addItemButton(_MI_TDMDOWNLOADS_ADMENU4, 'broken.php?op=list', 'list');
            $GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
            xoops_confirm(['ok' => 1, 'broken_id' => \Xmf\Request::getInt('broken_id', 0, 'REQUEST'), 'op' => 'del_brokendownloads'], $_SERVER['REQUEST_URI'], _AM_TDMDOWNLOADS_BROKEN_SURDEL . '<br>');
        }
        break;
}
// Local icons path
if (is_object($helper->getModule())) {
    $pathModIcon16 = $helper->getModule()->getInfo('modicons16');
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');

    $GLOBALS['xoopsTpl']->assign('pathModIcon16', XOOPS_URL . '/modules/' . $moduleDirName . '/' . $pathModIcon16);
    $GLOBALS['xoopsTpl']->assign('pathModIcon32', $pathModIcon32);
}
//Affichage de la partie basse de l'administration de Xoops
require_once __DIR__ . '/admin_footer.php';
