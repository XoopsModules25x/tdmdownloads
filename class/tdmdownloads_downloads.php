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

if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}

class TDMDownloads_downloads extends XoopsObject
{
    // constructor
    public function __construct()
    {
        $this->initVar('lid', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('cid', XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('url', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('homepage', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('version', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('size', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('platform', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, null, false);
        // Pour autoriser le html
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('logourl', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('submitter', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('status', XOBJ_DTYPE_INT, null, false, 2);
        $this->initVar('date', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('hits', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('rating', XOBJ_DTYPE_OTHER, null, false, 10);
        $this->initVar('votes', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('comments', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('top', XOBJ_DTYPE_INT, null, false, 2);
        $this->initVar('paypal', XOBJ_DTYPE_TXTBOX, null, false);

        //pour les jointures:
        $this->initVar('cat_title', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('cat_imgurl', XOBJ_DTYPE_TXTBOX, null, false);
    }
    public function get_new_enreg()
    {
        global $xoopsDB;
        $new_enreg = $xoopsDB->getInsertId();

        return $new_enreg;
    }
    public function TDMDownloads_downloads()
    {
        $this->__construct();
    }
    public function getForm($donnee = [], $erreur = false, $action = false)
    {
        global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsUser;
        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        //permission pour uploader
        $gpermHandler = xoops_getHandler('groupperm');
        $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        if ($xoopsUser) {
            if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
                $perm_upload = ($gpermHandler->checkRight('tdmdownloads_ac', 32, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
            } else {
                $perm_upload = true;
            }
        } else {
            $perm_upload = ($gpermHandler->checkRight('tdmdownloads_ac', 32, $groups, $xoopsModule->getVar('mid'))) ? true : false ;
        }
        //nom du formulaire selon l'action (editer ou ajouter):
        $title = $this->isNew() ? sprintf(_AM_TDMDOWNLOADS_FORMADD) : sprintf(_AM_TDMDOWNLOADS_FORMEDIT);

        //création du formulaire
        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        //titre
        $form->addElement(new XoopsFormText(_AM_TDMDOWNLOADS_FORMTITLE, 'title', 50, 255, $this->getVar('title')), true);
        // fichier
        $fichier = new XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMFILE, '<br><br>');
        $url = $this->isNew() ? 'http://' : $this->getVar('url');
        $formurl = new XoopsFormText(_AM_TDMDOWNLOADS_FORMURL, 'url', 75, 255, $url);
        $fichier->addElement($formurl, false);
        if (true === $perm_upload) {
            $fichier->addElement(new XoopsFormFile(_AM_TDMDOWNLOADS_FORMUPLOAD, 'attachedfile', $xoopsModuleConfig['maxuploadsize']), false);
        }
        $form->addElement($fichier);

        //catégorie
        $downloadscatHandler = xoops_getModuleHandler('tdmdownloads_cat', 'TDMDownloads');
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
        $downloadscat_arr = $downloadscatHandler->getall($criteria);
        if (0 == count($downloadscat_arr)) {
            redirect_header('index.php', 2, _NOPERM);
        }
        $mytree = new XoopsObjectTree($downloadscat_arr, 'cat_cid', 'cat_pid');
        $form->addElement($mytree->makeSelectElement('cid', 'cat_title', '--', $this->getVar('cid'), true, 0, '', _AM_TDMDOWNLOADS_FORMINCAT), true);

        //affichage des champs
        $downloadsfieldHandler = xoops_getModuleHandler('tdmdownloads_field', 'TDMDownloads');
        $criteria = new CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $downloads_field = $downloadsfieldHandler->getall($criteria);
        foreach (array_keys($downloads_field) as $i) {
            if (1 == $downloads_field[$i]->getVar('status_def')) {
                if (1 == $downloads_field[$i]->getVar('fid')) {
                    //page d'accueil
                    if (1 == $downloads_field[$i]->getVar('status')) {
                        $form->addElement(new XoopsFormText(_AM_TDMDOWNLOADS_FORMHOMEPAGE, 'homepage', 50, 255, $this->getVar('homepage')));
                    } else {
                        $form->addElement(new XoopsFormHidden('homepage', ''));
                    }
                }
                if (2 == $downloads_field[$i]->getVar('fid')) {
                    //version
                    if (1 == $downloads_field[$i]->getVar('status')) {
                        $form->addElement(new XoopsFormText(_AM_TDMDOWNLOADS_FORMVERSION, 'version', 10, 255, $this->getVar('version')));
                    } else {
                        $form->addElement(new XoopsFormHidden('version', ''));
                    }
                }
                if (3 == $downloads_field[$i]->getVar('fid')) {
                    //taille du fichier
                    if (1 == $downloads_field[$i]->getVar('status')) {
                        if ($this->isNew()) {
                            $size_value = $this->getVar('size');
                            if (false === $erreur) {
                                $type_value = '[Ko]';
                            } else {
                                $type_value = $donnee['type_size'];
                            }
                        } else {
                            $size_value_arr = explode(' ', $this->getVar('size'));
                            $size_value = $size_value_arr[0];
                            if (false === $erreur) {
                                $type_value = $size_value_arr[1];
                            } else {
                                $type_value = $donnee['type_size'];
                            }
                        }
                        $aff_size = new XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMSIZE, '');
                        $aff_size->addElement(new XoopsFormText('', 'size', 10, 255, $size_value));
                        $type = new XoopsFormSelect('', 'type_size', $type_value);
                        $type_arr = [_AM_TDMDOWNLOADS_BYTES => '[' . _AM_TDMDOWNLOADS_BYTES . ']', _AM_TDMDOWNLOADS_KBYTES => '[' . _AM_TDMDOWNLOADS_KBYTES . ']', _AM_TDMDOWNLOADS_MBYTES => '[' . _AM_TDMDOWNLOADS_MBYTES . ']', _AM_TDMDOWNLOADS_GBYTES => '[' . _AM_TDMDOWNLOADS_GBYTES . ']', _AM_TDMDOWNLOADS_TBYTES => '[' . _AM_TDMDOWNLOADS_TBYTES . ']'];
                        $type->addOptionArray($type_arr);
                        $aff_size->addElement($type);
                        $form->addElement($aff_size);
                    } else {
                        $form->addElement(new XoopsFormHidden('size', ''));
                        $form->addElement(new XoopsFormHidden('type_size', ''));
                    }
                }
                if (4 == $downloads_field[$i]->getVar('fid')) {
                    //plateforme
                    if (1 == $downloads_field[$i]->getVar('status')) {
                        $platformselect = new XoopsFormSelect(_AM_TDMDOWNLOADS_FORMPLATFORM, 'platform', explode('|', $this->getVar('platform')), 5, true);
                        $platform_array = explode('|', $xoopsModuleConfig['platform']);
                        foreach ($platform_array as $platform) {
                            $platformselect->addOption("$platform", $platform);
                        }
                        $form->addElement($platformselect, false);
                    } else {
                        $form->addElement(new XoopsFormHidden('platform', ''));
                    }
                }
            } else {
                $contenu = '';
                $contenu_iddata = '';
                $nom_champ = 'champ' . $downloads_field[$i]->getVar('fid');
                $downloadsfielddataHandler = xoops_getModuleHandler('tdmdownloads_fielddata', 'TDMDownloads');
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('lid', $this->getVar('lid')));
                $criteria->add(new Criteria('fid', $downloads_field[$i]->getVar('fid')));
                $downloadsfielddata = $downloadsfielddataHandler->getall($criteria);
                foreach (array_keys($downloadsfielddata) as $j) {
                    if (true === $erreur) {
                        $contenu = $donnee[$nom_champ];
                    } else {
                        if (!$this->isNew()) {
                            $contenu = $downloadsfielddata[$j]->getVar('data');
                        }
                    }
                    $contenu_iddata = $downloadsfielddata[$j]->getVar('iddata');
                }
                $iddata = 'iddata' . $downloads_field[$i]->getVar('fid');
                if (!$this->isNew()) {
                    $form->addElement(new XoopsFormHidden($iddata, $contenu_iddata));
                }
                if (1 == $downloads_field[$i]->getVar('status')) {
                    $form->addElement(new XoopsFormText($downloads_field[$i]->getVar('title'), $nom_champ, 50, 255, $contenu));
                } else {
                    $form->addElement(new XoopsFormHidden($nom_champ, ''));
                }
            }
        }
        //description
        $editor_configs           = [];
        $editor_configs['name']   = 'description';
        $editor_configs['value']  = $this->getVar('description', 'e');
        $editor_configs['rows']   = 20;
        $editor_configs['cols']   = 100;
        $editor_configs['width']  = '100%';
        $editor_configs['height'] = '400px';
        $editor_configs['editor'] = $xoopsModuleConfig['editor'];
        $form->addElement(new XoopsFormEditor(_AM_TDMDOWNLOADS_FORMTEXTDOWNLOADS, 'description', $editor_configs), true);
        //tag
        if (is_dir('../../tag') || is_dir('../tag')) {
            $dir_tag_ok = true;
        } else {
            $dir_tag_ok = false;
        }
        if ((1 == $xoopsModuleConfig['usetag']) and $dir_tag_ok) {
            $tagId = $this->isNew() ? 0 : $this->getVar('lid');
            if (true === $erreur) {
                $tagId = $donnee['TAG'];
            }
            require_once XOOPS_ROOT_PATH.'/modules/tag/class/formtag.php';
            $form->addElement(new TagFormTag('tag', 60, 255, $tagId, 0));
        }

        //image
        if ($xoopsModuleConfig['useshots']) {
            $uploaddir = XOOPS_ROOT_PATH . '/uploads/tdmdownloads/images/shots/' . $this->getVar('logourl');
            $downloadscat_img = $this->getVar('logourl') ? $this->getVar('logourl') : 'blank.gif';
            if (!is_file($uploaddir)) {
                $downloadscat_img = 'blank.gif';
            }
            $uploadirectory='/uploads/tdmdownloads/images/shots';
            $imgtray              = new XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMIMG, '<br>');
            $imgpath              =sprintf(_AM_TDMDOWNLOADS_FORMPATH, $uploadirectory);
            $imageselect          = new XoopsFormSelect($imgpath, 'logo_img', $downloadscat_img);
            $topics_array         = XoopsLists :: getImgListAsArray(XOOPS_ROOT_PATH . $uploadirectory);
            foreach ($topics_array as $image) {
                $imageselect->addOption("$image", $image);
            }
            $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"logo_img\", \"" . $uploadirectory . '", "", "' . XOOPS_URL . "\")'");
            $imgtray->addElement($imageselect, false);
            $imgtray -> addElement(new XoopsFormLabel('', "<br><img src='" . XOOPS_URL . '/' . $uploadirectory . '/' . $downloadscat_img . "' name='image3' id='image3' alt=''>"));
            $fileseltray= new XoopsFormElementTray('', '<br>');
            if (true === $perm_upload) {
                $fileseltray->addElement(new XoopsFormFile(_AM_TDMDOWNLOADS_FORMUPLOAD, 'attachedimage', $xoopsModuleConfig['maxuploadsize']), false);
            }
            $imgtray->addElement($fileseltray);
            $form->addElement($imgtray);
        }
        // pour changer de poster et pour ne pas mettre à jour la date:

        if ($xoopsUser) {
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                // auteur
                if ($this->isNew()) {
                    $submitter = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
                    $donnee['date_update'] = 0;
                } else {
                    $submitter = $this->getVar('submitter');
                    $v_date = $this->getVar('date');
                }
                if (true === $erreur) {
                    $date_update = $donnee['date_update'];
                    $v_status = $donnee['status'];
                    $submitter = $donnee['submitter'];
                } else {
                    $date_update = 'N';
                    $v_status = 1;
                }
                $form->addElement(new XoopsFormSelectUser(_AM_TDMDOWNLOADS_FORMSUBMITTER, 'submitter', true, $submitter, 1, false), true);

                // date
                if (!$this->isNew()) {
                    $selection_date = new XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMDATEUPDATE);
                    $date = new XoopsFormRadio('', 'date_update', $date_update);
                    $options = ['N' => _AM_TDMDOWNLOADS_FORMDATEUPDATE_NO . ' (' . formatTimestamp($v_date, 's') . ')', 'Y' => _AM_TDMDOWNLOADS_FORMDATEUPDATE_YES];
                    $date->addOptionArray($options);
                    $selection_date->addElement($date);
                    $selection_date->addElement(new XoopsFormTextDateSelect('', 'date', '', time()));
                    $form->addElement($selection_date);
                }
                $status = new XoopsFormCheckBox(_AM_TDMDOWNLOADS_FORMSTATUS, 'status', $v_status);
                $status->addOption(1, _AM_TDMDOWNLOADS_FORMSTATUS_OK);
                $form->addElement($status);
                //permissions pour télécharger
                if (2 == $xoopsModuleConfig['permission_download']) {
                    $memberHandler =  xoops_getHandler('member');
                    $group_list = $memberHandler->getGroupList();
                    $gpermHandler = xoops_getHandler('groupperm');
                    $full_list = array_keys($group_list);
                    global $xoopsModule;
                    if (!$this->isNew()) {
                        $item_ids_download = $gpermHandler->getGroupIds('tdmdownloads_download_item', $this->getVar('lid'), $xoopsModule->getVar('mid'));
                        $item_ids_downloa = array_values($item_ids_download);
                        $item_news_can_download_checkbox = new XoopsFormCheckBox(_AM_TDMDOWNLOADS_FORMPERMDOWNLOAD, 'item_download[]', $item_ids_download);
                    } else {
                        $item_news_can_download_checkbox = new XoopsFormCheckBox(_AM_TDMDOWNLOADS_FORMPERMDOWNLOAD, 'item_download[]', $full_list);
                    }
                    $item_news_can_download_checkbox->addOptionArray($group_list);
                    $form->addElement($item_news_can_download_checkbox);
                }
            }
        }
        //paypal
        if (true === $xoopsModuleConfig['use_paypal']) {
            $form->addElement(new XoopsFormText(_AM_TDMDOWNLOADS_FORMPAYPAL, 'paypal', 50, 255, $this->getVar('paypal')), false);
        } else {
            $form->addElement(new XoopsFormHidden('paypal', ''));
        }
        // captcha
        $form->addElement(new XoopsFormCaptcha(), true);
        // pour passer "lid" si on modifie la catégorie
        if (!$this->isNew()) {
            $form->addElement(new XoopsFormHidden('lid', $this->getVar('lid')));
            $form->addElement(new XoopsFormHidden('downloads_modified', true));
        }
        //pour enregistrer le formulaire
        $form->addElement(new XoopsFormHidden('op', 'save_downloads'));
        //bouton d'envoi du formulaire
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}

class TDMDownloadstdmdownloads_downloadsHandler extends XoopsPersistableObjectHandler
{
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'tdmdownloads_downloads', 'tdmdownloads_downloads', 'lid', 'title');
    }
}
