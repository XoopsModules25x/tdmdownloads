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
 * @param $options
 * @return array
 */

/**
 * @param $options
 * @return array
 */
function b_tdmdownloads_top_show($options)
{
    require dirname(__DIR__) . '/include/common.php';
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    // get the name of the file's directory to get the "owner" of the block, i.e. its module, and not the "user", where it is currently
    //$mydir          = basename(dirname(__DIR__));
    $moduleDirName = basename(dirname(__DIR__));
    $mymodule      = $moduleHandler->getByDirname($moduleDirName);
    //appel de la class
    /** @var \XoopsModules\Tdmdownloads\DownloadsHandler $downloadsHandler */
    $downloadsHandler   = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Downloads');
    $block              = [];
    $type_block         = $options[0];
    $nb_entree          = $options[1];
    $lenght_title       = $options[2];
    $use_logo           = $options[3];
    $use_description    = $options[4];
    $show_inforation    = $options[5];
    $logo_float         = $options[6];
    $logo_width         = $options[7];
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

    /** @var \xos_opal_Theme $xoTheme */
    $xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $moduleDirName . '/assets/css/blocks.css', null);
    /** @var \XoopsModules\Tdmdownloads\Utility $utility */
    $utility = new \XoopsModules\Tdmdownloads\Utility();
	$helper->loadLanguage('main');

    $categories = $utility->getItemIds('tdmdownloads_view', $moduleDirName);
    $criteria   = new \CriteriaCompo();
    $criteria->add(new \Criteria('cid', '(' . implode(',', $categories) . ')', 'IN'));
    if (!(0 == $options[0] && 1 === count($options))) {
        $criteria->add(new \Criteria('cid', '(' . implode(',', $options) . ')', 'IN'));
    }
    $criteria->add(new \Criteria('status', 0, '!='));
    switch ($type_block) {    // pour le bloc: dernier fichier
        case 'date':
            $criteria->setSort('date');
            $criteria->setOrder('DESC');
            break;
        // pour le bloc: plus téléchargé
        case 'hits':
            $criteria->setSort('hits');
            $criteria->setOrder('DESC');
            break;
        // pour le bloc: mieux noté
        case 'rating':
            $criteria->setSort('rating');
            $criteria->setOrder('DESC');
            break;
        // pour le bloc: aléatoire
        case 'random':
            $criteria->setSort('RAND()');
            break;
    }
    $criteria->setLimit($nb_entree);
    $downloadsArray = $downloadsHandler->getAll($criteria);
    foreach (array_keys($downloadsArray) as $i) {
        $block[$i]['lid']   = $downloadsArray[$i]->getVar('lid');
        $block[$i]['title'] = mb_strlen($downloadsArray[$i]->getVar('title')) > $lenght_title ? mb_substr($downloadsArray[$i]->getVar('title'), 0, $lenght_title) . '...' : $downloadsArray[$i]->getVar('title');
        $descriptionShort  = '';
        if (true == $use_description) {
            $description = $downloadsArray[$i]->getVar('description');
            //permet d'afficher uniquement la description courte
            if (false === mb_strpos($description, '[pagebreak]')) {
                $descriptionShort = mb_substr($description, 0, $lenght_description) . ' ...';
            } else {
                $descriptionShort = mb_substr($description, 0, mb_strpos($description, '[pagebreak]')) . ' ...';
            }
        }
        $block[$i]['description'] = $descriptionShort;
        $logourl                  = '';
        if (true == $use_logo) {
            if ('blank.gif' === $downloadsArray[$i]->getVar('logourl')) {
                $logourl = '';
            } else {
                $logourl = XOOPS_URL . '/uploads/' . $moduleDirName . '/images/shots/' . $downloadsArray[$i]->getVar('logourl');
            }
        }
        $block[$i]['logourl']       = $logourl;
        $block[$i]['logourl_class'] = $logo_float;
        $block[$i]['logourl_width'] = $logo_width;
        $block[$i]['hits']          = $downloadsArray[$i]->getVar('hits');
        $block[$i]['rating']        = number_format($downloadsArray[$i]->getVar('rating'), 1);
        $block[$i]['date']          = formatTimestamp($downloadsArray[$i]->getVar('date'), 's');
        $block[$i]['submitter']     = \XoopsUser::getUnameFromId($downloadsArray[$i]->getVar('submitter'));
        $block[$i]['inforation']    = $show_inforation;
    }

    return $block;
}

/**
 * @param $options
 *
 * @return string
 */
function b_tdmdownloads_top_edit($options)
{
    //appel de la class
    $moduleDirName   = basename(dirname(__DIR__));
    $categoryHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Category');
    $criteria        = new \CriteriaCompo();
    $criteria->setSort('cat_weight ASC, cat_title');
    $criteria->setOrder('ASC');
    $downloadscatArray = $categoryHandler->getAll($criteria);
    $form              = _MB_TDMDOWNLOADS_DISP . "&nbsp;\n";
    $form              .= '<input type="hidden" name="options[0]" value="' . $options[0] . "\">\n";
    $form              .= '<input name="options[1]" size="5" maxlength="255" value="' . $options[1] . '" type="text">&nbsp;' . _MB_TDMDOWNLOADS_FILES . "<br>\n";
    $form              .= _MB_TDMDOWNLOADS_CHARS . ' : <input name="options[2]" size="5" maxlength="255" value="' . $options[2] . "\" type=\"text\"><br>\n";
    if (false == $options[3]) {
        $checked_yes = '';
        $checked_no  = 'checked';
    } else {
        $checked_yes = 'checked';
        $checked_no  = '';
    }
    $form .= _MB_TDMDOWNLOADS_LOGO . ' : <input name="options[3]" value="1" type="radio" ' . $checked_yes . '>' . _YES . "&nbsp;\n";
    $form .= '<input name="options[3]" value="0" type="radio" ' . $checked_no . '>' . _NO . "<br>\n";
    if (false == $options[4]) {
        $checked_yes = '';
        $checked_no  = 'checked';
    } else {
        $checked_yes = 'checked';
        $checked_no  = '';
    }
    $form .= _MB_TDMDOWNLOADS_DESCRIPTION . ' : <input name="options[4]" value="1" type="radio" ' . $checked_yes . '>' . _YES . "&nbsp;\n";
    $form .= '<input name="options[4]" value="0" type="radio" ' . $checked_no . '>' . _NO . "<br>\n";
    if (false == $options[5]) {
        $checked_yes = '';
        $checked_no  = 'checked';
    } else {
        $checked_yes = 'checked';
        $checked_no  = '';
    }
    $form       .= _MB_TDMDOWNLOADS_INFORMATIONS . ' : <input name="options[5]" value="1" type="radio" ' . $checked_yes . '>' . _YES . "&nbsp;\n";
    $form       .= '<input name="options[5]" value="0" type="radio" ' . $checked_no . '>' . _NO . "<br><br>\n";
    $floatelect = new \XoopsFormSelect(_MB_TDMDOWNLOADS_FLOAT, 'options[6]', $options[6]);
    $floatelect->addOption('left', _MB_TDMDOWNLOADS_FLOAT_LEFT);
    $floatelect->addOption('right', _MB_TDMDOWNLOADS_FLOAT_RIGHT);
    $form .= _MB_TDMDOWNLOADS_FLOAT . ' : ' . $floatelect->render() . '<br>';
    $form .= _MB_TDMDOWNLOADS_WHITE . ' : <input name="options[7]" size="5" maxlength="255" value="' . $options[7] . "\" type=\"text\"><br>\n";
    $form .= _MB_TDMDOWNLOADS_CHARSDSC . ' : <input name="options[8]" size="5" maxlength="255" value="' . $options[8] . "\" type=\"text\"><br>\n";
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
    $form .= '<option value="0" ' . (!in_array(0, $options, false) ? '' : 'selected="selected"') . '>' . _MB_TDMDOWNLOADS_ALLCAT . "</option>\n";
    foreach (array_keys($downloadscatArray) as $i) {
        $form .= '<option value="' . $downloadscatArray[$i]->getVar('cat_cid') . '" ' . (!in_array($downloadscatArray[$i]->getVar('cat_cid'), $options, false) ? '' : 'selected') . '>' . $downloadscatArray[$i]->getVar('cat_title') . "</option>\n";
    }
    $form .= "</select>\n";

    return $form;
}
