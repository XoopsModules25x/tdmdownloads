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

function b_tdmdownloads_top_show($options)
{
    require_once XOOPS_ROOT_PATH."/modules/TDMDownloads/include/functions.php";
    //appel de la class
    $downloadsHandler = xoops_getModuleHandler('tdmdownloads_downloads', 'TDMDownloads');
    $block = array();
    $type_block = $options[0];
    $nb_entree = $options[1];
    $lenght_title = $options[2];
    $use_logo = $options[3];
    $use_description = $options[4];
    $show_inforation = $options[5];
    $logo_float = $options[6];
    $logo_white = $options[7];
    $lenght_description = $options[8];

    array_shift($options);
    array_shift($options);
    array_shift($options);
    array_shift($options);
    array_shift($options);
    array_shift($options);
    array_shift($options);
    array_shift($options);
    array_shift($options);

    // Add styles
    global $xoTheme;
    $xoTheme->addStylesheet(XOOPS_URL . '/modules/TDMDownloads/css/blocks.css', null);

    $categories = TDMDownloads_MygetItemIds('tdmdownloads_view', 'TDMDownloads');
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    if (!(count($options) == 1 && $options[0] == 0)) {
        $criteria->add(new Criteria('cid', '(' . implode(',', $options) . ')', 'IN'));
    }
    $criteria->add(new Criteria('status', 0, '!='));
    switch ($type_block) {    // pour le bloc: dernier fichier
        case "date":
            $criteria->setSort('date');
            $criteria->setOrder('DESC');
        break;
        // pour le bloc: plus téléchargé
        case "hits":
            $criteria->setSort('hits');
            $criteria->setOrder('DESC');
        break;
        // pour le bloc: mieux noté
        case "rating":
            $criteria->setSort('rating');
            $criteria->setOrder('DESC');
        break;
        // pour le bloc: aléatoire
        case "random":
            $criteria->setSort('RAND()');
        break;
    }
    $criteria->setLimit($nb_entree);
    $downloads_arr = $downloadsHandler->getall($criteria);
    foreach (array_keys($downloads_arr) as $i) {
        $block[$i]['lid'] = $downloads_arr[$i]->getVar('lid');
        $block[$i]['title'] = strlen($downloads_arr[$i]->getVar('title')) > $lenght_title ? substr($downloads_arr[$i]->getVar('title'), 0, ($lenght_title))."..." : $downloads_arr[$i]->getVar('title');
        $description_short = '';
        if ($use_description === true) {
            $description = $downloads_arr[$i]->getVar('description');
            //permet d'afficher uniquement la description courte
            if (strpos($description, '[pagebreak]')==false) {
                $description_short = substr($description, 0, $lenght_description) . ' ...';
            } else {
                $description_short = substr($description, 0, strpos($description, '[pagebreak]')) . ' ...';
            }
        }
        $block[$i]['description'] = $description_short;
        $logourl = '';
        if ($use_logo === true) {
            if ($downloads_arr[$i]->getVar('logourl') == 'blank.gif') {
                $logourl = '';
            } else {
                $logourl = XOOPS_URL . '/uploads/tdmdownloads/images/shots/'. $downloads_arr[$i]->getVar('logourl');
            }
        }
        $block[$i]['logourl'] = $logourl;
        $block[$i]['logourl_class'] = $logo_float;
        $block[$i]['logourl_width'] = $logo_white;
        $block[$i]['hits'] = $downloads_arr[$i]->getVar("hits");
        $block[$i]['rating'] = number_format($downloads_arr[$i]->getVar("rating"), 1);
        $block[$i]['date'] = formatTimeStamp($downloads_arr[$i]->getVar("date"), "s");
        $block[$i]['submitter'] = XoopsUser::getUnameFromId($downloads_arr[$i]->getVar('submitter'));
        $block[$i]['inforation'] = $show_inforation;
    }

    return $block;
}

function b_tdmdownloads_top_edit($options)
{
    //appel de la class
    $downloadscatHandler = xoops_getModuleHandler('tdmdownloads_cat', 'TDMDownloads');
    $criteria = new CriteriaCompo();
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $downloadscat_arr = $downloadscatHandler->getall($criteria);
    $form = _MB_TDMDOWNLOADS_DISP . "&nbsp;\n";
    $form .= "<input type=\"hidden\" name=\"options[0]\" value=\"" . $options[0] . "\">\n";
    $form .= "<input name=\"options[1]\" size=\"5\" maxlength=\"255\" value=\"" . $options[1] . "\" type=\"text\">&nbsp;" . _MB_TDMDOWNLOADS_FILES . "<br>\n";
    $form .= _MB_TDMDOWNLOADS_CHARS . " : <input name=\"options[2]\" size=\"5\" maxlength=\"255\" value=\"" . $options[2] . "\" type=\"text\"><br>\n";
    if ($options[3] === false) {
        $checked_yes = '';
        $checked_no = 'checked';
    } else {
        $checked_yes = 'checked';
        $checked_no = '';
    }
    $form .= _MB_TDMDOWNLOADS_LOGO . " : <input name=\"options[3]\" value=\"1\" type=\"radio\" " . $checked_yes . ">" . _YES . "&nbsp;\n";
    $form .= "<input name=\"options[3]\" value=\"0\" type=\"radio\" " . $checked_no . ">" . _NO . "<br>\n";
    if ($options[4] === false) {
        $checked_yes = '';
        $checked_no = 'checked';
    } else {
        $checked_yes = 'checked';
        $checked_no = '';
    }
    $form .= _MB_TDMDOWNLOADS_DESCRIPTION . " : <input name=\"options[4]\" value=\"1\" type=\"radio\" " . $checked_yes . ">" . _YES . "&nbsp;\n";
    $form .= "<input name=\"options[4]\" value=\"0\" type=\"radio\" " . $checked_no . ">" . _NO . "<br>\n";
    if ($options[5] === false) {
        $checked_yes = '';
        $checked_no = 'checked';
    } else {
        $checked_yes = 'checked';
        $checked_no = '';
    }
    $form .= _MB_TDMDOWNLOADS_INFORMATIONS . " : <input name=\"options[5]\" value=\"1\" type=\"radio\" " . $checked_yes . ">" . _YES . "&nbsp;\n";
    $form .= "<input name=\"options[5]\" value=\"0\" type=\"radio\" " . $checked_no . ">" . _NO . "<br><br>\n";
    $floatelect = new XoopsFormSelect(_MB_TDMDOWNLOADS_FLOAT, 'options[6]', $options[6]);
    $floatelect->addOption("left", _MB_TDMDOWNLOADS_FLOAT_LEFT);
    $floatelect->addOption("right", _MB_TDMDOWNLOADS_FLOAT_RIGHT);
    $form .= _MB_TDMDOWNLOADS_FLOAT." : ".$floatelect->render().'<br>';
    $form .= _MB_TDMDOWNLOADS_WHITE . " : <input name=\"options[7]\" size=\"5\" maxlength=\"255\" value=\"" . $options[7] . "\" type=\"text\"><br>\n";
    $form .= _MB_TDMDOWNLOADS_CHARSDSC . " : <input name=\"options[8]\" size=\"5\" maxlength=\"255\" value=\"" . $options[8] . "\" type=\"text\"><br>\n";
    array_shift($options);
    array_shift($options);
    array_shift($options);
    array_shift($options);
    array_shift($options);
    array_shift($options);
    array_shift($options);
    array_shift($options);
    array_shift($options);
    $form .= _MB_TDMDOWNLOADS_CATTODISPLAY . "<br><select name=\"options[]\" multiple=\"multiple\" size=\"5\">\n";
    $form .= "<option value=\"0\" " . (array_search(0, $options) === false ? '' : 'selected="selected"') . ">" . _MB_TDMDOWNLOADS_ALLCAT . "</option>\n";
    foreach (array_keys($downloadscat_arr) as $i) {
        $form .= "<option value=\"" . $downloadscat_arr[$i]->getVar('cat_cid') . "\" " . (array_search($downloadscat_arr[$i]->getVar('cat_cid'), $options) === false ? '' : 'selected="selected"') . ">".$downloadscat_arr[$i]->getVar('cat_title')."</option>\n";
    }
    $form .= "</select>\n";

    return $form;
}
