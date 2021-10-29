<?php

declare(strict_types=1);

namespace XoopsModules\Tdmdownloads\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * @copyright      2020 XOOPS Project (https://xoops.org)
 * @license        GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link           https://xoops.org
 * @author         Wedega - Email:<webmaster@wedega.com> - Website:<https://wedega.com>
 */

/**
 * Class Object Handler Images
 */
class ImagesHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     * @param \XoopsDatabase|null $db
     */
    public function __construct(?\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'tdmdownloads_images', Images::class, 'img_id', 'img_name');
    }

    /**
     * retrieve a field
     *
     * @param int        $id field id
     * @param null|mixed $fields
     * @return \XoopsObject|null reference to the {@link Get} object
     */
    public function get($id = null, $fields = null)
    {
        return parent::get($id, $fields);
    }

    /**
     * get inserted id
     *
     * @param null
     * @return int reference to the {@link Get} object
     */
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     * Get Count Images in the database
     * @param int    $albId
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    public function getCountImages($albId = 0, $start = 0, $limit = 0, $sort = 'img_id ASC, img_name', $order = 'ASC')
    {
        $crCountImages = new \CriteriaCompo();
        $crCountImages = $this->getImagesCriteria($crCountImages, $albId, $start, $limit, $sort, $order);
        return parent::getCount($crCountImages);
    }

    /**
     * Get All Images in the database
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getAllImages($start = 0, $limit = 0, $sort = 'img_id ASC, img_name', $order = 'ASC')
    {
        $crAllImages = new \CriteriaCompo();
        $crAllImages = $this->getImagesCriteria($crAllImages, 0, $start, $limit, $sort, $order);
        return parent::getAll($crAllImages);
    }

    /**
     * Get Criteria Images
     * @param        $crImages
     * @param        $albId
     * @param int    $start
     * @param int    $limit
     * @param string $sort
     * @param string $order
     * @return int
     */
    private function getImagesCriteria($crImages, $albId, $start, $limit, $sort, $order)
    {
        if ($albId > 0) {
            $crImages->add(new \Criteria('img_albid', $albId));
        }
        $crImages->setStart($start);
        $crImages->setLimit($limit);
        $crImages->setSort($sort);
        $crImages->setOrder($order);
        return $crImages;
    }

    /**
     * delete all copies of a specific image
     * @param $imageName
     * @return bool
     */
    public function unlinkImages($imageName)
    {
        \unlink(\constant($moduleDirNameUpper . '_' . 'UPLOAD_IMAGE_PATH') . '/large/' . $imageName);
        if (\file_exists(\constant($moduleDirNameUpper . '_' . 'UPLOAD_IMAGE_PATH') . '/large/' . $imageName)) {
            return false;
        }
        \unlink(\constant($moduleDirNameUpper . '_' . 'UPLOAD_IMAGE_PATH') . '/medium/' . $imageName);
        if (\file_exists(\constant($moduleDirNameUpper . '_' . 'UPLOAD_IMAGE_PATH') . '/medium/' . $imageName)) {
            return false;
        }
        \unlink(\constant($moduleDirNameUpper . '_' . 'UPLOAD_IMAGE_PATH') . '/thumbs/' . $imageName);
        if (\file_exists(\constant($moduleDirNameUpper . '_' . 'UPLOAD_IMAGE_PATH') . '/thumbs/' . $imageName)) {
            return false;
        }
        return true;
    }
}
