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

class TDMDownloads_mod extends XoopsObject
{
    // constructor
    public function __construct()
    {
        $this->initVar("requestid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("lid", XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar("cid", XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar("title", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("url", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("homepage", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("version", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("size", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("platform", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("logourl", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("description", XOBJ_DTYPE_TXTAREA, null, false);
        // Pour autoriser le html
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
        $this->initVar("modifysubmitter", XOBJ_DTYPE_INT, null, false, 11);
    }

    public function TDMDownloads_mod($lid)
    {
        $this->__construct();
    }
    public function get_new_enreg()
    {
        global $xoopsDB;
        $new_enreg = $xoopsDB->getInsertId();

        return $new_enreg;
    }
    public function getForm($lid, $erreur, $donnee = array(), $action = false)
    {
        global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsUser;
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gperm_handler = xoops_gethandler('groupperm');
        $perm_upload = ($gperm_handler->checkRight('tdmdownloads_ac', 32, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
        //appel des class
        $downloads_Handler = xoops_getModuleHandler('tdmdownloads_downloads', 'TDMDownloads');
        $downloadscat_Handler = xoops_getModuleHandler('tdmdownloads_cat', 'TDMDownloads');

        $view_downloads = $downloads_Handler->get($lid);
        include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

        // affectation des variables
        if ($erreur == true) {
            $d_title = $donnee['title'];
            $d_cid = $donnee['cid'];
            $d_homepage = $donnee['homepage'];
            $d_version = $donnee['version'];
            $d_size = $donnee['size'];
            $d_platform = $donnee['platform'];
            $d_description = $donnee['description'];
        } else {
            $d_title = $view_downloads->getVar('title');
            $d_cid = $view_downloads->getVar('cid');
            $d_homepage = $view_downloads->getVar('homepage');
            $d_version = $view_downloads->getVar('version');
            $d_size = $view_downloads->getVar('size');
            $d_platform = $view_downloads->getVar('platform');
            $d_description = $view_downloads->getVar('description', 'e');
        }

        //nom du formulaire
        $title = sprintf(_AM_TDMDOWNLOADS_FORMEDIT);

        //création du formulaire
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //titre
        $form->addElement(new XoopsFormText(_AM_TDMDOWNLOADS_FORMTITLE, 'title', 50, 255, $d_title), true);
        // fichier
        $fichier = new XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMFILE, '<br /><br />');
        $url = $view_downloads->getVar('url');
        $formurl = new XoopsFormText(_AM_TDMDOWNLOADS_FORMURL, 'url', 75, 255, $url);
        $fichier->addElement($formurl, false);
        if ($perm_upload == true) {
            $fichier->addElement(new XoopsFormFile(_AM_TDMDOWNLOADS_FORMUPLOAD, 'attachedfile', $xoopsModuleConfig['maxuploadsize']), false);
        }
        $form->addElement($fichier);

        //catégorie
        $downloadscat_Handler = xoops_getModuleHandler('tdmdownloads_cat', 'TDMDownloads');
        $categories = TDMDownloads_MygetItemIds('tdmdownloads_submit', 'TDMDownloads');
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight ASC, cat_title');
        $criteria->setOrder('ASC');
        if ($xoopsUser) {
            if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
                $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
            }
        } else {
            $criteria->add(new Criteria('cat_cid', '(' . implode(',', $categories) . ')', 'IN'));
        }
        $downloadscat_arr = $downloadscat_Handler->getall($criteria);
        if (count($downloadscat_arr) == 0) {
            redirect_header('index.php', 2, _NOPERM);
        }
        $mytree = new XoopsObjectTree($downloadscat_arr, 'cat_cid', 'cat_pid');
        $form->addElement($mytree->makeSelectElement('cid', 'cat_title', '--', $d_cid, true, 0, '', _AM_TDMDOWNLOADS_FORMINCAT), true);

        //affichage des champs
        $downloadsfield_Handler = xoops_getModuleHandler('tdmdownloads_field', 'TDMDownloads');
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $downloadsfield_Handler->getall($criteria);
        foreach (array_keys($downloads_field) as $i) {
            if ($downloads_field[$i]->getVar('status_def') == 1) {
                if ($downloads_field[$i]->getVar('fid') == 1) {
                    //page d'accueil
                    if ($downloads_field[$i]->getVar('status') == 1) {
                        $form->addElement(new XoopsFormText(_AM_TDMDOWNLOADS_FORMHOMEPAGE, 'homepage', 50, 255, $d_homepage));
                    } else {
                        $form->addElement(new XoopsFormHidden('homepage', ''));
                    }
                }
                if ($downloads_field[$i]->getVar('fid') == 2) {
                    //version
                    if ($downloads_field[$i]->getVar('status') == 1) {
                        $form->addElement(new XoopsFormText(_AM_TDMDOWNLOADS_FORMVERSION, 'version', 10, 255, $d_version));
                    } else {
                        $form->addElement(new XoopsFormHidden('version', ''));
                    }
                }
                if ($downloads_field[$i]->getVar('fid') == 3) {
                    //taille du fichier
                    /*if ($downloads_field[$i]->getVar('status') == 1) {
                        $form->addElement(new XoopsFormText(_AM_TDMDOWNLOADS_FORMSIZE, 'size', 10, 255, $d_size));
                    } else {
                        $form->addElement(new XoopsFormHidden('size', ''));
                    }*/

                    if ($downloads_field[$i]->getVar('status') == 1) {
                        $size_value_arr = explode(' ', $view_downloads->getVar('size'));
                        $size_value = $size_value_arr[0];
                        if ($erreur == false) {
                            $type_value = $size_value_arr[1];
                        } else {
                            $type_value = $donnee['type_size'];
                        }
                        $aff_size = new XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMSIZE, '');
                        $aff_size->addElement(new XoopsFormText('', 'size', 10, 255, $size_value));
                        $type = new XoopsFormSelect('', 'type_size', $type_value);
                        //$type_arr = array('[o]' => _AM_TDMDOWNLOADS_BYTES, '[Ko]' => _AM_TDMDOWNLOADS_KBYTES, '[Mo]' => _AM_TDMDOWNLOADS_MBYTES, '[Go]' => _AM_TDMDOWNLOADS_GBYTES, '[To]' => _AM_TDMDOWNLOADS_TBYTES);
                        $type_arr = array(_AM_TDMDOWNLOADS_BYTES => '['._AM_TDMDOWNLOADS_BYTES.']', _AM_TDMDOWNLOADS_KBYTES => '['._AM_TDMDOWNLOADS_KBYTES.']', _AM_TDMDOWNLOADS_MBYTES => '['._AM_TDMDOWNLOADS_MBYTES.']', _AM_TDMDOWNLOADS_GBYTES => '['._AM_TDMDOWNLOADS_GBYTES.']', _AM_TDMDOWNLOADS_TBYTES => '['._AM_TDMDOWNLOADS_TBYTES.']');
                        $type->addOptionArray($type_arr);
                        $aff_size->addElement($type);
                        $form->addElement($aff_size);
                    } else {
                        $form->addElement(new XoopsFormHidden('size', ''));
                        $form->addElement(new XoopsFormHidden('type_size', ''));
                    }
                }
                if ($downloads_field[$i]->getVar('fid') == 4) {
                    //plateforme
                    if ($downloads_field[$i]->getVar('status') == 1) {
                        $platformselect = new XoopsFormSelect(_AM_TDMDOWNLOADS_FORMPLATFORM, 'platform', explode('|', $d_platform), 5, true);
                        $platform_array = explode('|', $xoopsModuleConfig['platform']);
                        foreach ($platform_array as $platform) {
                            $platformselect->addOption("$platform", $platform);
                        }
                        $form->addElement($platformselect, false);
                    //$form->addElement(new XoopsFormText(_AM_TDMDOWNLOADS_FORMPLATFORM, 'platform', 50, 255, $d_platform));
                    } else {
                        $form->addElement(new XoopsFormHidden('platform', ''));
                    }
                }
            } else {
                $contenu = '';
                $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                $downloadsfielddata_Handler = xoops_getModuleHandler('tdmdownloads_fielddata', 'TDMDownloads');
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $view_downloads->getVar('lid')));
                $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfielddata = $downloadsfielddata_Handler->getall($criteria);
                foreach (array_keys($downloadsfielddata) as $j) {
                    if ($erreur == true) {
                        $contenu = $donnee[$nom_champ];
                    } else {
                        $contenu = $downloadsfielddata[$j]->getVar('data');
                    }
                }
                if ($downloads_field[$i]->getVar('status') == 1) {
                    $form->addElement(new XoopsFormText($downloads_field[$i]->getVar('title'), $nom_champ, 50, 255, $contenu));
                } else {
                    $form->addElement(new XoopsFormHidden($nom_champ, ''));
                }
            }
        }
        //description
        $editor_configs=array();
        $editor_configs["name"] ="description";
        $editor_configs["value"] = $d_description;
        $editor_configs["rows"] = 20;
        $editor_configs["cols"] = 60;
        $editor_configs["width"] = "100%";
        $editor_configs["height"] = "400px";
        $editor_configs["editor"] = $xoopsModuleConfig['editor'];
        $form->addElement(new XoopsFormEditor(_AM_TDMDOWNLOADS_FORMTEXTDOWNLOADS, "description", $editor_configs), true);
        //image
        if ($xoopsModuleConfig['useshots']) {
            $uploaddir = XOOPS_ROOT_PATH . '/uploads/TDMDownloads/images/shots/' . $view_downloads->getVar('logourl');
            $downloadscat_img = $view_downloads->getVar('logourl') ? $view_downloads->getVar('logourl') : 'blank.gif';
            $uploadirectory='/uploads/TDMDownloads/images/shots';
            if (!is_file($uploaddir)) {
                $downloadscat_img = 'blank.gif';
            }
            $imgtray = new XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMIMG, '<br />');
            $imgpath=sprintf(_AM_TDMDOWNLOADS_FORMPATH, $uploadirectory);
            $imageselect= new XoopsFormSelect($imgpath, 'logo_img', $downloadscat_img);
            $topics_array = XoopsLists :: getImgListAsArray(XOOPS_ROOT_PATH . $uploadirectory);
            foreach ($topics_array as $image) {
                $imageselect->addOption("$image", $image);
            }
            $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"logo_img\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'");
            $imgtray->addElement($imageselect, false);
            $imgtray -> addElement(new XoopsFormLabel('', "<br /><img src='" . XOOPS_URL . "/" . $uploadirectory . "/" . $downloadscat_img . "' name='image3' id='image3' alt='' />"));
            $fileseltray= new XoopsFormElementTray('', '<br />');
            if ($perm_upload == true) {
                $fileseltray->addElement(new XoopsFormFile(_AM_TDMDOWNLOADS_FORMUPLOAD, 'attachedimage', $xoopsModuleConfig['maxuploadsize']), false);
            }
            $imgtray->addElement($fileseltray);
            $form->addElement($imgtray);
        }
        $form->addElement(new XoopsFormCaptcha(), true);
        $form->addElement(new XoopsFormHidden('lid', $lid));
        //pour enregistrer le formulaire
        $form->addElement(new XoopsFormHidden('op', 'save'));
        //bouton d'envoi du formulaire
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}

class TDMDownloadstdmdownloads_modHandler extends XoopsPersistableObjectHandler
{
    public function __construct(&$db)
    {
        parent::__construct($db, "tdmdownloads_mod", 'tdmdownloads_mod', 'requestid', 'lid');
    }
}
