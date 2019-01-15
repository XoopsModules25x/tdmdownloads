<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Funções Padrão para o Módulo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
###
### =============================================================

use XoopsModules\Tdmdownloads;

/**
 * @param $id
 * @return bool
 */
function turnoffPermissions($id)
{
    global $xoopsModule, $grouppermHandler;
    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('gperm_itemid', $id));
    $criteria->add(new \Criteria('gperm_modid', $xoopsModule->getVar('mid')));
    $criteria->add(new \Criteria('gperm_name', 'mpu_mpublish_acesso'));
    if ($old_perms = $grouppermHandler->getObjects($criteria)) {
        foreach ($old_perms as $p) {
            $grouppermHandler->delete($p);
        }
    }
    xoops_comment_delete($xoopsModule->getVar('mid'), $id);

    return true;
}

/**
 * @param $id
 * @return bool
 */
function deletePermissions($id)
{
    global $xoopsModule;
//    require_once XOOPS_ROOT_PATH . '/modules/' . MPU_MOD_DIR . '/class/Publish.class.php';
    $mpu_classe =new \XoopsModules\Tdmdownloads\Publish();
    $todos      = $mpu_classe->PegaTudo(new \Criteria('mpb_10_idpai', $id));
    if (!empty($todos)) {
        foreach ($todos as $v) {
            turnoffPermissions($v->getVar('mpb_10_id'));
            xoops_comment_delete($xoopsModule->getVar('mid'), $v->getVar('mpb_10_id'));
        }

        return true;
    }

    return false;
}

/**
 * @param $id
 * @param $grupos_ids
 * @return bool
 */
function insertPermission($id, $grupos_ids)
{
    global $xoopsModule;
    $grouppermHandler = xoops_getHandler('groupperm');
    foreach ($grupos_ids as $gid) {
        $perm = $grouppermHandler->create();
        $perm->setVar('gperm_name', 'mpu_mpublish_acesso');
        $perm->setVar('gperm_itemid', $id);
        $perm->setVar('gperm_groupid', $gid);
        $perm->setVar('gperm_modid', $xoopsModule->getVar('mid'));
        $grouppermHandler->insert($perm);
    }

    return true;
}

/**
 * @param $content
 * @return mixed|string|string[]|null
 */
function prepareContent($content)
{
    global $xoopsUser, $xoopsConfig;
    if (is_object($xoopsUser)) {
        if ($xoopsUser->cleanVars()) {
            foreach ($xoopsUser->cleanVars as $k => $v) {
                $content = str_replace('{' . $k . '}', $v, $content);
            }
        }
    }
    foreach ($xoopsConfig as $k => $v) {
        if (!is_array($v)) {
            $content = str_replace('{' . $k . '}', $v, $content);
        }
    }
    $content = str_replace('{banner}', xoops_getbanner(), $content);
    if (!empty($_GET['busca']) && is_array($_GET['busca'])) {
        $search_string = MPU_MOD_HIGHLIGHT_SEARCH;
        $found         = 0;
        $bgs           = [
            '#ffff66',
            '#a0ffff',
            '#99ff99',
            '#ff9999',
            '#880000',
            '#00aa00',
            '#886800',
            '#004699',
            '#990099'
        ];
        $colors        = ['black', 'black', 'black', 'black', 'white', 'white', 'white', 'white', 'white'];
        $ctrl          = 0;
        $busca         = array_unique($_GET['busca']);
        foreach ($busca as $v) {
            if (false !== stripos(strip_tags($content), $v)) {
                $cfundo        = $bgs[$ctrl];
                $ctexto        = $colors[$ctrl];
                $busca[0]      = '~' . $v . '(?![^<]*>)~';
                $busca[1]      = '~' . strtolower($v) . '(?![^<]*>)~';
                $busca[2]      = '~' . strtoupper($v) . '(?![^<]*>)~';
                $busca[3]      = '~' . ucfirst(strtolower($v)) . '(?![^<]*>)~';
                $troca[0]      = '<span style="font-weight:bold; color: ' . $ctexto . '; background-color: ' . $cfundo . ';">' . $v . '</span>';
                $troca[1]      = '<span style="font-weight:bold; color: ' . $ctexto . '; background-color: ' . $cfundo . ';">' . strtolower($v) . '</span>';
                $troca[2]      = '<span style="font-weight:bold; color: ' . $ctexto . '; background-color: ' . $cfundo . ';">' . strtoupper($v) . '</span>';
                $troca[3]      = '<span style="font-weight:bold; color: ' . $ctexto . '; background-color: ' . $cfundo . ';">' . ucfirst(strtolower($v)) . '</span>';
                $content       = preg_replace($busca, $troca, $content);
                $search_string .= '<span style="font-weight:bold; color: ' . $ctexto . '; background-color: ' . $cfundo . ';">' . $v . '</span>, ';
                $found         = 1;
                if (8 == $ctrl) {
                    $ctrl = 0;
                } else {
                    ++$ctrl;
                }
            }
        }
        if ($found) {
            $search_string = substr($search_string, 0, -2) . '<br><br>';
            $content       = $search_string . $content;
        }
    }

    return $content;
}

/**
 * @param        $repository
 * @param string $default
 * @return string
 */
function getLatestTagUrl($repository, $default = 'master') {
    $file = @json_decode(@file_get_contents("https://api.github.com/repos/$repository/releases", false,
                                            stream_context_create(['http' => ['header' => "User-Agent:Publisher\r\n"]])
    ));

    return sprintf("https://github.com/$repository/archive/%s.zip", $file ? reset($file)->tag_name : $default);
}

