<?php declare(strict_types=1);

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

use XoopsModules\Tdmdownloads;

/**
 * Class Modified
 * @package XoopsModules\Tdmdownloads
 */
class Modified extends \XoopsObject
{
    // constructor

    public function __construct()
    {
        $this->initVar('requestid', \XOBJ_DTYPE_INT, null, false, 11);

        $this->initVar('lid', \XOBJ_DTYPE_INT, null, false, 11);

        $this->initVar('cid', \XOBJ_DTYPE_INT, null, false, 5);

        $this->initVar('title', \XOBJ_DTYPE_TXTBOX, null, false);

        $this->initVar('url', \XOBJ_DTYPE_TXTBOX, null, false);

        $this->initVar('homepage', \XOBJ_DTYPE_TXTBOX, null, false);

        $this->initVar('version', \XOBJ_DTYPE_TXTBOX, null, false);

        $this->initVar('size', \XOBJ_DTYPE_TXTBOX, null, false);

        $this->initVar('platform', \XOBJ_DTYPE_TXTBOX, null, false);

        $this->initVar('logourl', \XOBJ_DTYPE_TXTBOX, null, false);

        $this->initVar('description', \XOBJ_DTYPE_TXTAREA, null, false);

        // Pour autoriser le html

        $this->initVar('dohtml', \XOBJ_DTYPE_INT, 1, false);

        $this->initVar('modifysubmitter', \XOBJ_DTYPE_INT, null, false, 11);
    }

    /**
     * @param null $db
     * @return int
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
     * @param       $lid
     * @param       $erreur
     * @param array $donnee
     * @param bool  $action
     * @return \XoopsThemeForm
     */
    public function getForm($lid, $erreur, $donnee = [], $action = false)
    {
        global $xoopsDB, $xoopsModule, $xoopsUser;

        /** @var \XoopsModules\Tdmdownloads\Helper $helper */

        $helper = \XoopsModules\Tdmdownloads\Helper::getInstance();

        $moduleDirName = \basename(\dirname(__DIR__));

        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }

        $groups = \is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

        /** @var \XoopsGroupPermHandler $grouppermHandler */

        $grouppermHandler = \xoops_getHandler('groupperm');

        $perm_upload = $grouppermHandler->checkRight('tdmdownloads_ac', 32, $groups, $xoopsModule->getVar('mid'));

        //appel des class

        /** @var \XoopsModules\Tdmdownloads\DownloadsHandler $downloadsHandler */

        $downloadsHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Downloads');

        //        $categoryHandler  = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Category');

        $viewDownloads = $downloadsHandler->get($lid);

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        // affectation des variables

        if (true === $erreur) {
            $d_title = $donnee['title'];

            $d_cid = $donnee['cid'];

            $d_homepage = $donnee['homepage'];

            $d_version = $donnee['version'];

            $d_platform = $donnee['platform'];

            $d_description = $donnee['description'];
        } else {
            $d_title = $viewDownloads->getVar('title');

            $d_cid = $viewDownloads->getVar('cid');

            $d_homepage = $viewDownloads->getVar('homepage');

            $d_version = $viewDownloads->getVar('version');

            $d_platform = $viewDownloads->getVar('platform');

            $d_description = $viewDownloads->getVar('description', 'e');
        }

        //nom du formulaire

        $title = \sprintf(_AM_TDMDOWNLOADS_FORMEDIT);

        //création du formulaire

        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);

        $form->setExtra('enctype="multipart/form-data"');

        //titre

        $form->addElement(new \XoopsFormText(_AM_TDMDOWNLOADS_FORMTITLE, 'title', 50, 255, $d_title), true);

        // fichier

        $fichier = new \XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMFILE, '<br><br>');

        $url = $viewDownloads->getVar('url');

        $formurl = new \XoopsFormText(_AM_TDMDOWNLOADS_FORMURL, 'url', 75, 255, $url);

        $fichier->addElement($formurl, false);

        if ($perm_upload) {
            $fichier->addElement(new \XoopsFormFile(_AM_TDMDOWNLOADS_FORMUPLOAD, 'attachedfile', $helper->getConfig('maxuploadsize')), false);
        }

        $form->addElement($fichier);

        //catégorie

        /** @var \XoopsModules\Tdmdownloads\CategoryHandler $categoryHandler */

        $categoryHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Category');

        /** @var \XoopsModules\Tdmdownloads\Utility $utility */

        $utility = new \XoopsModules\Tdmdownloads\Utility();

        $categories = $utility->getItemIds('tdmdownloads_submit', $moduleDirName);

        $criteria = new \CriteriaCompo();

        $criteria->setSort('cat_weight ASC, cat_title');

        $criteria->setOrder('ASC');

        if ($xoopsUser) {
            if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
                $criteria->add(new \Criteria('cat_cid', '(' . \implode(',', $categories) . ')', 'IN'));
            }
        } else {
            $criteria->add(new \Criteria('cat_cid', '(' . \implode(',', $categories) . ')', 'IN'));
        }

        $downloadscatArray = $categoryHandler->getAll($criteria);

        if (empty($downloadscatArray)) {
            \redirect_header('index.php', 2, \_NOPERM);
        }

        $mytree = new \XoopsModules\Tdmdownloads\Tree($downloadscatArray, 'cat_cid', 'cat_pid');

        $form->addElement($mytree->makeSelectElement('cid', 'cat_title', '--', $d_cid, true, 0, '', _AM_TDMDOWNLOADS_FORMINCAT), true);

        //affichage des champs

        $fieldHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Field');

        $criteria = new \CriteriaCompo();

        $criteria->setSort('weight ASC, title');

        $criteria->setOrder('ASC');

        $downloads_field = $fieldHandler->getAll($criteria);

        foreach (\array_keys($downloads_field) as $i) {
            /** @var \XoopsModules\Tdmdownloads\Field[] $downloads_field */

            if (1 == $downloads_field[$i]->getVar('status_def')) {
                if (1 == $downloads_field[$i]->getVar('fid')) {
                    //page d'accueil

                    if (1 == $downloads_field[$i]->getVar('status')) {
                        $form->addElement(new \XoopsFormText(_AM_TDMDOWNLOADS_FORMHOMEPAGE, 'homepage', 50, 255, $d_homepage));
                    } else {
                        $form->addElement(new \XoopsFormHidden('homepage', ''));
                    }
                }

                if (2 == $downloads_field[$i]->getVar('fid')) {
                    //version

                    if (1 == $downloads_field[$i]->getVar('status')) {
                        $form->addElement(new \XoopsFormText(_AM_TDMDOWNLOADS_FORMVERSION, 'version', 10, 255, $d_version));
                    } else {
                        $form->addElement(new \XoopsFormHidden('version', ''));
                    }
                }

                if (3 == $downloads_field[$i]->getVar('fid')) {
                    //taille du fichier

                    if (1 == $downloads_field[$i]->getVar('status')) {
                        $size_value_arr = \explode(' ', $viewDownloads->getVar('size'));

                        $aff_size = new \XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMSIZE, '');

                        $aff_size->addElement(new \XoopsFormText('', 'sizeValue', 13, 13, $size_value_arr[0]));

                        if (!\array_key_exists(1, $size_value_arr)) {
                            $size_value_arr[1] = 'K';
                        }

                        $type = new \XoopsFormSelect('', 'sizeType', $size_value_arr[1]);

                        $typeArray = [
                            'B' => _AM_TDMDOWNLOADS_BYTES,
                            'K' => _AM_TDMDOWNLOADS_KBYTES,
                            'M' => _AM_TDMDOWNLOADS_MBYTES,
                            'G' => _AM_TDMDOWNLOADS_GBYTES,
                            'T' => _AM_TDMDOWNLOADS_TBYTES,
                        ];

                        $type->addOptionArray($typeArray);

                        $aff_size->addElement($type);

                        $form->addElement($aff_size);
                    } else {
                        $form->addElement(new \XoopsFormHidden('size', ''));

                        $form->addElement(new \XoopsFormHidden('type_size', ''));
                    }
                }

                if (4 == $downloads_field[$i]->getVar('fid')) {
                    //plateforme

                    if (1 == $downloads_field[$i]->getVar('status')) {
                        $platformselect = new \XoopsFormSelect(_AM_TDMDOWNLOADS_FORMPLATFORM, 'platform', \explode('|', $d_platform), 5, true);

                        $platformArray = \explode('|', $helper->getConfig('platform'));

                        foreach ($platformArray as $platform) {
                            $platformselect->addOption((string)$platform, $platform);
                        }

                        $form->addElement($platformselect, false);
                        //$form->addElement(new \XoopsFormText(_AM_TDMDOWNLOADS_FORMPLATFORM, 'platform', 50, 255, $d_platform));
                    } else {
                        $form->addElement(new \XoopsFormHidden('platform', ''));
                    }
                }
            } else {
                $contenu = '';

                $fieldName = 'champ' . $downloads_field[$i]->getVar('fid');

                $fielddataHandler = \XoopsModules\Tdmdownloads\Helper::getInstance()->getHandler('Fielddata');

                $criteria = new \CriteriaCompo();

                $criteria->add(new \Criteria('lid', $viewDownloads->getVar('lid')));

                $criteria->add(new \Criteria('fid', $downloads_field[$i]->getVar('fid')));

                $downloadsfielddata = $fielddataHandler->getAll($criteria);

                foreach (\array_keys($downloadsfielddata) as $j) {
                    /** @var \XoopsModules\Tdmdownloads\Fielddata[] $downloadsfielddata */

                    if (true === $erreur) {
                        $contenu = $donnee[$fieldName];
                    } else {
                        $contenu = $downloadsfielddata[$j]->getVar('data');
                    }
                }

                if (1 == $downloads_field[$i]->getVar('status')) {
                    $form->addElement(new \XoopsFormText($downloads_field[$i]->getVar('title'), $fieldName, 50, 255, $contenu));
                } else {
                    $form->addElement(new \XoopsFormHidden($fieldName, ''));
                }
            }
        }

        //description

        $editorConfigs = [];

        $editorConfigs['name'] = 'description';

        $editorConfigs['value'] = $d_description;

        $editorConfigs['rows'] = 20;

        $editorConfigs['cols'] = 60;

        $editorConfigs['width'] = '100%';

        $editorConfigs['height'] = '400px';

        $editorConfigs['editor'] = $helper->getConfig('editor');

        $form->addElement(new \XoopsFormEditor(_AM_TDMDOWNLOADS_FORMTEXTDOWNLOADS, 'description', $editorConfigs), true);

        //image

        if ($helper->getConfig('useshots')) {
            $uploaddir = XOOPS_ROOT_PATH . '/uploads/' . $moduleDirName . '/images/shots/' . $viewDownloads->getVar('logourl');

            $categoryImage = $viewDownloads->getVar('logourl') ?: 'blank.gif';

            $uploadirectory = '/uploads/' . $moduleDirName . '/images/shots';

            if (!\is_file($uploaddir)) {
                $categoryImage = 'blank.gif';
            }

            $imgtray = new \XoopsFormElementTray(_AM_TDMDOWNLOADS_FORMIMG, '<br>');

            $imgpath = \sprintf(_AM_TDMDOWNLOADS_FORMPATH, $uploadirectory);

            $imageselect = new \XoopsFormSelect($imgpath, 'logo_img', $categoryImage);

            $topics_array = \XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . $uploadirectory);

            foreach ($topics_array as $image) {
                $imageselect->addOption($image, $image);
            }

            $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"logo_img\", \"" . $uploadirectory . '", "", "' . XOOPS_URL . "\")'");

            $imgtray->addElement($imageselect, false);

            $imgtray->addElement(new \XoopsFormLabel('', "<br><img src='" . XOOPS_URL . '/' . $uploadirectory . '/' . $categoryImage . "' name='image3' id='image3' alt=''>"));

            $fileseltray = new \XoopsFormElementTray('', '<br>');

            if ($perm_upload) {
                $fileseltray->addElement(new \XoopsFormFile(_AM_TDMDOWNLOADS_FORMUPLOAD, 'attachedimage', $helper->getConfig('maxuploadsize')), false);
            }

            $imgtray->addElement($fileseltray);

            $form->addElement($imgtray);
        }

        $form->addElement(new \XoopsFormCaptcha(), true);

        $form->addElement(new \XoopsFormHidden('lid', $lid));

        //pour enregistrer le formulaire

        $form->addElement(new \XoopsFormHidden('op', 'save'));

        //bouton d'envoi du formulaire

        $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', 'submit', false));

        return $form;
    }
}
