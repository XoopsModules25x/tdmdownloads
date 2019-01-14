<?php

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
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      mamba <mambax7@gmail.com>
 */
trait VersionChecks
{
    /**
     *
     * Verifies XOOPS version meets minimum requirements for this module
     * @static
     * @param \XoopsModule|null $module
     *
     * @param null|string       $requiredVer
     * @return bool true if meets requirements, false if not
     */
    public static function checkVerXoops(\XoopsModule $module = null, $requiredVer = null)
    {
        $moduleDirName      = basename(dirname(dirname(__DIR__)));
        $moduleDirNameUpper = mb_strtoupper($moduleDirName);
        if (null === $module) {
            $module = \XoopsModule::getByDirname($moduleDirName);
        }
        xoops_loadLanguage('admin', $moduleDirName);

        //check for minimum XOOPS version
        $currentVer = mb_substr(XOOPS_VERSION, 6); // get the numeric part of string
        if (null === $requiredVer) {
            $requiredVer = '' . $module->getInfo('min_xoops'); //making sure it's a string
        }
        $success = true;

        if (version_compare($currentVer, $requiredVer, '<')) {
            $success = false;
            $module->setErrors(sprintf(constant('CO_' . $moduleDirNameUpper . '_ERROR_BAD_XOOPS'), $requiredVer, $currentVer));
        }

        return $success;
    }

    /**
     *
     * Verifies PHP version meets minimum requirements for this module
     * @static
     * @param \XoopsModule|null $module
     *
     * @return bool true if meets requirements, false if not
     */
    public static function checkVerPhp(\XoopsModule $module = null)
    {
        $moduleDirName      = basename(dirname(dirname(__DIR__)));
        $moduleDirNameUpper = mb_strtoupper($moduleDirName);
        xoops_loadLanguage('admin', $module->dirname());
        // check for minimum PHP version
        $success = true;
        $verNum  = PHP_VERSION;
        $reqVer  =& $module->getInfo('min_php');
        if (false !== $reqVer && '' !== $reqVer) {
            if (version_compare($verNum, $reqVer, '<')) {
                $module->setErrors(sprintf(constant('CO_' . $moduleDirNameUpper . '_ERROR_BAD_PHP'), $reqVer, $verNum));
                $success = false;
            }
        }

        return $success;
    }

    /**
     *
     * compares current module version with latest GitHub release
     * @static
     * @param \Xmf\Module\Helper $helper
     * @param string             $location
     * @param string             $default
     *
     * @return string link to the latest module version, if newer
     */

    public static function checkVerModule($helper, $location = 'github', $default = 'master')
    {
        $moduleDirName = basename(dirname(dirname(__DIR__)));
        $update        = '';
        $repository    = 'XoopsModules25x/' . $moduleDirName;
        //        $repository    = 'XoopsModules25x/publisher'; //for testing only
        $ret = '';

        if ('github' === $location) {
            $file              = @json_decode(@file_get_contents("https://api.github.com/repos/$repository/releases", false, stream_context_create(['http' => ['header' => "User-Agent:Publisher\r\n"]])));
            $latestVersionLink = sprintf("https://github.com/$repository/archive/%s.zip", $file ? reset($file)->tag_name : $default);
            //            $latestVersion     = substr(strrchr($latestVersionLink, '/'), 1, -4);
            $latestVersion = $file[0]->tag_name;
            $prerelease    = $file[0]->prerelease;
            if ('master' !== $latestVersionLink) {
                $update = '<span><strong> Latest Release: </strong>' . '   <a href="' . $latestVersionLink . '">' . $latestVersion . '</a> </span><br><br>';
            }
            //"PHP-standardized" version
            $latestVersion = mb_strtolower($latestVersion);
            if (false !== mb_strpos($latestVersion, 'final')) {
                $latestVersion = str_replace('_', '', mb_strtolower($latestVersion));
                $latestVersion = str_replace('final', '', mb_strtolower($latestVersion));
            }

            $moduleVersion = ($helper->getModule()->getInfo('version') . '_' . $helper->getModule()->getInfo('module_status'));
            //"PHP-standardized" version
            $moduleVersion = str_replace(' ', '', mb_strtolower($moduleVersion));
            //          $moduleVersion = '3.0'; //for testing only
            //          $moduleDirName = 'publisher'; //for testing only

            $ret .= "<div align='center'>";

            //            $ret .= "<a href='https://xoops.org/'><img src='../assets/images/icons/32/xoopsmicrobutton.gif'></a><br>";
            //            if (version_compare($moduleVersion, $latestVersion, '<')) {
            if (version_compare($moduleVersion, $latestVersion, '<') && !$prerelease) {
                //                $ret .= "| ";
                $ret .= " <span style='color: #FF0000; font-size:11px'  ";
                $ret .= $update;
                //                $ret .= "<a href='https://github.com/XoopsModules25x/$moduleDirName/releases/'>";
                $ret .= '</span>';
                $ret .= "<img src='https://img.shields.io/github/release/XoopsModules25x/$moduleDirName.svg?style=flat'></a>";
            }
        }
        $GLOBALS['xoopsTpl']->assign('latestModRelease', $ret);
        return $ret;
    }
}
