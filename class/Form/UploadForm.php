<?php

namespace XoopsModules\Tdmdownloads\Form;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Module: Tdmdownloads
 *
 * @category        Module
 * @package         tdmdownloads
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use Xmf\Request;
use XoopsModules\Tdmdownloads;

require_once \dirname(\dirname(__DIR__)) . '/include/common.php';

//$moduleDirName = basename(dirname(dirname(__DIR__)));
//$helper = Tdmdownloads\Helper::getInstance();
$permHelper = new \Xmf\Module\Helper\Permission();

\xoops_load('XoopsFormLoader');

/**
 * Class FieldForm
 */
class UploadForm extends \XoopsThemeForm
{
    public $targetObject;
    public $helper;

    /**
     * Constructor
     *
     * @param \XoopsModules\Tdmdownloads\Category $target
     */
    public function __construct($target)
    {
        $moduleDirName      = \basename(\dirname(\dirname(__DIR__)));
        $moduleDirNameUpper = \mb_strtoupper($moduleDirName);
        /** @var  \XoopsModules\Tdmdownloads\Helper $this->helper */
        $this->helper       = $target->helper;
        $this->targetObject = $target;

        $title = $this->targetObject->isNew() ? \sprintf(\constant('CO_' . $moduleDirNameUpper . '_' . 'FIELD_ADD')) : \sprintf(\constant('CO_' . $moduleDirNameUpper . '_' . 'FIELD_EDIT'));
        parent::__construct('', 'form', \xoops_getenv('SCRIPT_NAME'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        //include ID field, it's needed so the module knows if it is a new form or an edited form

        $hidden = new \XoopsFormHidden('fid', $this->targetObject->getVar('cat_cid'));
        $this->addElement($hidden);
        unset($hidden);

        $categoryHandler    = new \XoopsModules\Tdmdownloads\CategoryHandler();
        $start              = Request::getInt('start', 0);
        $catPaginationLimit = $this->helper->getConfig('userpager') ?: 10;

        $criteria = new \CriteriaCompo();
        $criteria->setOrder('DESC');
        $criteria->setLimit($catPaginationLimit);
        $criteria->setStart($start);

        $catCount = $categoryHandler->getCount($criteria);
        $catArray = $categoryHandler->getAll($criteria);

        // Form Select Category
        $categoryIdSelect = new \XoopsFormSelect(\constant('CO_' . $moduleDirNameUpper . '_' . 'SELECT'), 'cat_title', $this->targetObject->getVar('cat_cid'));
        $categoryIdSelect->setExtra('onchange="submit()"');
        //        $categoryIdSelect->addOption(0, '&nbsp;');

        foreach (\array_keys($catArray) as $i) {
            $catName = $catArray[$i]->getVar('cat_title');
            $catPid  = $catArray[$i]->getVar('cat_pid');
            if (0 < $catPid) {
                $categoryObj = $categoryHandler->get($catPid);
                if (\is_object($categoryObj)) {
                    $catName .= ' (' . $categoryObj->getVar('cat_title') . ')';
                } else {
                    $catName .= ' (' . \constant('CO_' . $moduleDirNameUpper . '_' . 'ERROR_CATPID') . ')';
                }
            }
            $categoryIdSelect->addOption($catArray[$i]->getVar('cat_cid'), $catName);
        }

        $this->addElement($categoryIdSelect);
        unset($categoryCriteria);

        $this->addElement(new \XoopsFormHidden('start', 0));
        $this->addElement(new \XoopsFormHidden('limit', 0));
    }
}
