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

if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
class TDMDownloads_field extends XoopsObject
{
// constructor
    function __construct()
    {
        $this->XoopsObject();
        $this->initVar("fid",XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("title",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("img",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("weight",XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("status",XOBJ_DTYPE_INT,null,false,5);
        $this->initVar("search",XOBJ_DTYPE_INT,null,false,5);
        $this->initVar("status_def",XOBJ_DTYPE_INT,null,false,5);

        //pour les jointures
        $this->initVar("data",XOBJ_DTYPE_TXTAREA, null, false);
    }
    function TDMDownloads_field()
    {
        $this->__construct();
    }
    function get_new_enreg()
    {
        global $xoopsDB;
        $new_enreg = $xoopsDB->getInsertId();

        return $new_enreg;
    }
    function getForm($action = false)
    {
        global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

        //nom du formulaire selon l'action (editer ou ajouter):
        $title = $this->isNew() ? sprintf(_AM_TDMDOWNLOADS_FORMADD) : sprintf(_AM_TDMDOWNLOADS_FORMEDIT);

        //création du formulaire
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //titre
        if ($this->getVar('status_def') == 1) {
            $form->addElement(new xoopsFormLabel (_AM_TDMDOWNLOADS_FORMTITLE, $this->getVar('title')));
            $form->addElement(new XoopsFormHidden('title', $this->getVar('title')));
        } else {
            $form->addElement(new XoopsFormText(_AM_TDMDOWNLOADS_FORMTITLE, 'title', 50, 255, $this->getVar('title')), true);
        }
        //image
        $downloadsfield_img = $this->getVar('img') ? $this->getVar('img') : 'blank.gif';
        $uploadirectory='/uploads/TDMDownloads/images/field';
        $imgtray = new XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMIMAGE,'<br />');
        $imgpath=sprintf(_AM_TDMDOWNLOADS_FORMPATH, $uploadirectory );
        $imageselect= new XoopsFormSelect($imgpath, 'downloadsfield_img',$downloadsfield_img);
           $topics_array = XoopsLists :: getImgListAsArray( XOOPS_ROOT_PATH . $uploadirectory );
        foreach ($topics_array as $image) {
               $imageselect->addOption("$image", $image);
        }
        $imageselect->setExtra( "onchange='showImgSelected(\"image3\", \"downloadsfield_img\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
        $imgtray->addElement($imageselect,false);
        $imgtray -> addElement( new XoopsFormLabel( '', "<br /><img src='" . XOOPS_URL . "/" . $uploadirectory . "/" . $downloadsfield_img . "' name='image3' id='image3' alt='' /><br />" ) );
        $fileseltray= new XoopsFormElementTray('','<br />');
        $fileseltray->addElement(new XoopsFormFile(_AM_TDMDOWNLOADS_FORMUPLOAD , 'attachedfile', $xoopsModuleConfig['maxuploadsize']), false);
        $fileseltray->addElement(new XoopsFormLabel('' ), false);
        $imgtray->addElement($fileseltray);
        $form->addElement($imgtray);
        //poids du champ
        $form->addElement(new XoopsFormText(_AM_TDMDOWNLOADS_FORMWEIGHT, 'weight', 5, 5, $this->getVar('weight', 'e')), false);
        // affiché?
        $status = $this->getVar('status') ? $this->getVar('status') : 0;
        $form->addElement(new XoopsFormRadioYN(_AM_TDMDOWNLOADS_FORMAFFICHE, 'status', $status));
        // affiché dans le champ de recherche?
        $search = $this->getVar('search') ? $this->getVar('search') : 0;
        $form->addElement(new XoopsFormRadioYN(_AM_TDMDOWNLOADS_FORMAFFICHESEARCH, 'search', $search));
        // pour passer "fid" si on modifie le champ
        if (!$this->isNew()) {
            $form->addElement(new XoopsFormHidden('fid', $this->getVar('fid')));
            $form->addElement(new XoopsFormHidden('status_def', $this->getVar('status_def')));
        } else {
            $form->addElement(new XoopsFormHidden('status_def', 0));
        }
        //pour enregistrer le formulaire
        $form->addElement(new XoopsFormHidden('op', 'save_field'));
        //boutton d'envoi du formulaire
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}

class TDMDownloadstdmdownloads_fieldHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        parent::__construct($db, "tdmdownloads_field", 'tdmdownloads_field', 'fid', 'title');
    }
}
