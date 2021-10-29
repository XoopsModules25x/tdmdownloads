<?php

declare(strict_types=1);

namespace XoopsModules\Tdmdownloads;

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

use Xmf\Module\Helper\Permission;

/** @var Helper $helper */

/**
 * Class Category
 * @package XoopsModules\Tdmdownloads
 */
class Category extends \XoopsObject
{
    // constructor
    public $helper;
    public $permHelper;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->helper     = Helper::getInstance();
        $this->permHelper = new Permission();
        $this->initVar('cat_cid', \XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('cat_pid', \XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('cat_title', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cat_imgurl', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cat_description_main', \XOBJ_DTYPE_TXTAREA, null, false);
        // Pour autoriser le html
        $this->initVar('dohtml', \XOBJ_DTYPE_INT, 1, false);
        $this->initVar('cat_weight', \XOBJ_DTYPE_INT, 0, false, 11);
    }

    /**
     * @param null|\XoopsDatabase $db
     * @return mixed
     */
    public function getNewEnreg($db = null)
    {
        $newEnreg = 0;
        /** @var \XoopsMySQLDatabase $db */
        if (null !== $db) {
            $newEnreg = $db->getInsertId();
        }
        return $newEnreg;
    }

    /**
     * @param bool $action
     *
     * @return \XoopsThemeForm
     */
    public function getForm($action = false)
    {
        $helper = Helper::getInstance();
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $moduleDirName = \basename(\dirname(__DIR__));
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        //nom du formulaire selon l'action (editer ou ajouter):
        $title = $this->isNew() ? \sprintf(_AM_TDMDOWNLOADS_FORMADD) : \sprintf(_AM_TDMDOWNLOADS_FORMEDIT);
        //création du formulaire
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //titre
        $form->addElement(new \XoopsFormText(_AM_TDMDOWNLOADS_FORMTITLE, 'cat_title', 50, 255, $this->getVar('cat_title')), true);
        //editeur
        $editorConfigs           = [];
        $editorConfigs['name']   = 'cat_description_main';
        $editorConfigs['value']  = $this->getVar('cat_description_main', 'e');
        $editorConfigs['rows']   = 20;
        $editorConfigs['cols']   = 160;
        $editorConfigs['width']  = '100%';
        $editorConfigs['height'] = '400px';
        $editorConfigs['editor'] = $helper->getConfig('editor');
        $form->addElement(new \XoopsFormEditor(_AM_TDMDOWNLOADS_FORMTEXT, 'cat_description_main', $editorConfigs), false);
        //image
        $categoryImage  = $this->getVar('cat_imgurl') ?: 'blank.gif';
        $uploadirectory = '/uploads/' . $moduleDirName . '/images/cats';
        $imgtray        = new \XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMIMG, '<br>');
        $imgpath        = \sprintf(_AM_TDMDOWNLOADS_FORMPATH, $uploadirectory);
        $imageselect    = new \XoopsFormSelect($imgpath, 'downloadscat_img', $categoryImage);
        $topics_array   = \XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . $uploadirectory);
        foreach ($topics_array as $image) {
            $imageselect->addOption($image, $image);
        }
        $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"downloadscat_img\", \"" . $uploadirectory . '", "", "' . XOOPS_URL . "\")'");
        $imgtray->addElement($imageselect, false);
        $imgtray->addElement(new \XoopsFormLabel('', "<br><img src='" . XOOPS_URL . '/' . $uploadirectory . '/' . $categoryImage . "' name='image3' id='image3' alt=''>"));
        $fileseltray = new \XoopsFormElementTray('', '<br>');
        $fileseltray->addElement(new \XoopsFormFile(_AM_TDMDOWNLOADS_FORMUPLOAD, 'attachedfile', $helper->getConfig('maxuploadsize')), false);
        $fileseltray->addElement(new \XoopsFormLabel(''), false);
        $imgtray->addElement($fileseltray);
        $form->addElement($imgtray);
        // Pour faire une sous-catégorie
        $categoryHandler = Helper::getInstance()->getHandler('Category');
        $criteria        = new \CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        $downloadscatArray = $categoryHandler->getAll($criteria);
        $mytree            = new \XoopsModules\Tdmdownloads\Tree($downloadscatArray, 'cat_cid', 'cat_pid');
        $form->addElement($mytree->makeSelectElement('cat_pid', 'cat_title', '--', $this->getVar('cat_pid'), true, 0, '', _AM_TDMDOWNLOADS_FORMINCAT), true);
        //poids de la catégorie
        $form->addElement(new \XoopsFormText(_AM_TDMDOWNLOADS_FORMWEIGHT, 'cat_weight', 5, 5, $this->getVar('cat_weight', 'e')), false);
        //permissions
        /** @var \XoopsMemberHandler $memberHandler */
        $memberHandler = \xoops_getHandler('member');
        $group_list    = $memberHandler->getGroupList();
        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = \xoops_getHandler('groupperm');
        $full_list        = \array_keys($group_list);
        global $xoopsModule;
        if (!$this->isNew()) {
            $groups_ids_view                   = $grouppermHandler->getGroupIds('tdmdownloads_view', $this->getVar('cat_cid'), $xoopsModule->getVar('mid'));
            $groups_ids_submit                 = $grouppermHandler->getGroupIds('tdmdownloads_submit', $this->getVar('cat_cid'), $xoopsModule->getVar('mid'));
            $groups_ids_download               = $grouppermHandler->getGroupIds('tdmdownloads_download', $this->getVar('cat_cid'), $xoopsModule->getVar('mid'));
            $groups_ids_view                   = \array_values($groups_ids_view);
            $groups_news_can_view_checkbox     = new \XoopsFormCheckBox(_AM_TDMDOWNLOADS_PERM_VIEW_DSC, 'groups_view[]', $groups_ids_view);
            $groups_ids_submit                 = \array_values($groups_ids_submit);
            $groups_news_can_submit_checkbox   = new \XoopsFormCheckBox(_AM_TDMDOWNLOADS_PERM_SUBMIT_DSC, 'groups_submit[]', $groups_ids_submit);
            $groups_ids_download               = \array_values($groups_ids_download);
            $groups_news_can_download_checkbox = new \XoopsFormCheckBox(_AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC, 'groups_download[]', $groups_ids_download);
        } else {
            $groups_news_can_view_checkbox     = new \XoopsFormCheckBox(_AM_TDMDOWNLOADS_PERM_VIEW_DSC, 'groups_view[]', $full_list);
            $groups_news_can_submit_checkbox   = new \XoopsFormCheckBox(_AM_TDMDOWNLOADS_PERM_SUBMIT_DSC, 'groups_submit[]', $full_list);
            $groups_news_can_download_checkbox = new \XoopsFormCheckBox(_AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC, 'groups_download[]', $full_list);
        }
        // pour voir
        $groups_news_can_view_checkbox->addOptionArray($group_list);
        $form->addElement($groups_news_can_view_checkbox);
        // pour editer
        $groups_news_can_submit_checkbox->addOptionArray($group_list);
        $form->addElement($groups_news_can_submit_checkbox);
        // pour télécharger
        if (1 == $helper->getConfig('permission_download')) {
            $groups_news_can_download_checkbox->addOptionArray($group_list);
            $form->addElement($groups_news_can_download_checkbox);
        }
        // pour passer "cid" si on modifie la catégorie
        if (!$this->isNew()) {
            $form->addElement(new \XoopsFormHidden('cat_cid', $this->getVar('cat_cid')));
            $form->addElement(new \XoopsFormHidden('categorie_modified', true));
        }
        //pour enregistrer le formulaire
        $form->addElement(new \XoopsFormHidden('op', 'save_cat'));
        //boutton d'envoi du formulaire
        $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', 'submit', false));
        return $form;
    }
}
