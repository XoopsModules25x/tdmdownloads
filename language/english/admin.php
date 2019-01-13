<?php
/**
 * TDMDownloads
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
// index.php
define('_AM_TDMDOWNLOADS_INDEX_BROKEN', 'There are %s broken files reports');
define('_AM_TDMDOWNLOADS_INDEX_CATEGORIES', 'There are %s categories');
define('_AM_TDMDOWNLOADS_INDEX_DOWNLOADS', 'There are %s files in our database');
define('_AM_TDMDOWNLOADS_INDEX_DOWNLOADSWAITING', 'There are %s downloads waiting for approval');
define('_AM_TDMDOWNLOADS_INDEX_MODIFIED', 'There are %s downloads info modification requests');
//category.php
define('_AM_TDMDOWNLOADS_CAT_NEW', 'New category');
define('_AM_TDMDOWNLOADS_CAT_LIST', 'Categories List');
define('_AM_TDMDOWNLOADS_DELDOWNLOADS', 'with the following downloads:');
define('_AM_TDMDOWNLOADS_DELSOUSCAT', 'The following categories will be completely deleted:');
//downloads.php
define('_AM_TDMDOWNLOADS_DOWNLOADS_LISTE', 'Downloads List');
define('_AM_TDMDOWNLOADS_DOWNLOADS_NEW', 'New download');
define('_AM_TDMDOWNLOADS_DOWNLOADS_SEARCH', 'Search');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTESTOTAL', 'Total of votes');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTESANONYME', 'Votes by anonymous');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTESUSER', 'Votes by users');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTE_USER', 'Users');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTE_IP', 'IP Address');
define('_AM_TDMDOWNLOADS_DOWNLOADS_WAIT', 'Waiting for approval');
//broken.php
define('_AM_TDMDOWNLOADS_BROKEN_SENDER', 'Report Author');
define('_AM_TDMDOWNLOADS_BROKEN_SURDEL', 'Are you sure you want to delete this report?');
//modified.php
define('_AM_TDMDOWNLOADS_MODIFIED_MOD', 'Modified version');
define('_AM_TDMDOWNLOADS_MODIFIED_ORIGINAL', 'Original version');
define('_AM_TDMDOWNLOADS_MODIFIED_SURDEL', 'Are you sure to delete this download modification request?');
//field.php
define('_AM_TDMDOWNLOADS_DELDATA', 'With the following data:');
define('_AM_TDMDOWNLOADS_FIELD_LIST', 'Fields List');
define('_AM_TDMDOWNLOADS_FIELD_NEW', 'New fields');
//about.php
define('_AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION', 'Files Protection');
define('_AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION_INFO1', "To protect your files against unwanted downloads (compared to permissions), you have to create an '.htaccess' file in the folder:");
define('_AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION_INFO2', 'With the following content:');
//permissions.php
define('_AM_TDMDOWNLOADS_PERMISSIONS_4', 'Submit a download');
define('_AM_TDMDOWNLOADS_PERMISSIONS_8', 'Submit a modification');
define('_AM_TDMDOWNLOADS_PERMISSIONS_16', 'note a download');
define('_AM_TDMDOWNLOADS_PERMISSIONS_32', 'Upload files');
define('_AM_TDMDOWNLOADS_PERMISSIONS_64', 'Auto approve submitted files');
define('_AM_TDMDOWNLOADS_PERM_AUTRES', 'Other permissions');
define('_AM_TDMDOWNLOADS_PERM_AUTRES_DSC', 'Select groups that can:');
define('_AM_TDMDOWNLOADS_PERM_DOWNLOAD', 'Downloads Permissions');
define('_AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC', 'Select groups that can download in the categories');
define('_AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC2', 'Select groups that can download files');
define('_AM_TDMDOWNLOADS_PERM_SUBMIT', 'Submit Permission');
define('_AM_TDMDOWNLOADS_PERM_SUBMIT_DSC', 'Choose groups that can submit files to categories');
define('_AM_TDMDOWNLOADS_PERM_VIEW', 'View Permissions');
define('_AM_TDMDOWNLOADS_PERM_VIEW_DSC', 'Choose group than can view files in categories');
// Import.php
define('_AM_TDMDOWNLOADS_IMPORT1', 'Import');
define('_AM_TDMDOWNLOADS_IMPORT_CAT_IMP', "Categories: '%s' imported");
define('_AM_TDMDOWNLOADS_IMPORT_CONF_MYDOWNLOADS', 'Are you sure you want to import data from Mydownloads module to TDMDownloads');
define('_AM_TDMDOWNLOADS_IMPORT_CONF_WFDOWNLOADS', 'Are you sure you want to import data from WF-Downloads module to TDMDownloads');
define('_AM_TDMDOWNLOADS_IMPORT_DONT_DOWNLOADS', 'there are no files to import');
define('_AM_TDMDOWNLOADS_IMPORT_DONT_TOPIC', 'there are no files to import');
define('_AM_TDMDOWNLOADS_IMPORT_DOWNLOADS', 'files Import');
define('_AM_TDMDOWNLOADS_IMPORT_DOWNLOADS_IMP', "files: '%s' imported;");
define('_AM_TDMDOWNLOADS_IMPORT_ERREUR', 'Select Upload Directory (the path)');
define('_AM_TDMDOWNLOADS_IMPORT_ERROR_DATA', 'Error during the import of data');
define('_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS', 'Import from Mydownloads');
define('_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS_PATH', 'Select Upload Directory (the path) for screen shots of Mydownloads');
define('_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS_URL', 'Choose the corresponding URL  for screen shots of Mydownloads');
define('_AM_TDMDOWNLOADS_IMPORT_NB_CAT', 'There are %s categories to import');
define('_AM_TDMDOWNLOADS_IMPORT_NB_DOWNLOADS', 'There are %s files to import');
define('_AM_TDMDOWNLOADS_IMPORT_NUMBER', 'Data to import');
define('_AM_TDMDOWNLOADS_IMPORT_OK', 'Import successfully done!');
define('_AM_TDMDOWNLOADS_IMPORT_VOTE_IMP', "VOTES: '%s' imported;");
define('_AM_TDMDOWNLOADS_IMPORT_WARNING',
       "<span style='color:#FF0000; font-size:16px; font-weight:bold'>Attention !</span><br><br> Import will delete all data in TDMDownloads. It's highly recommended that you make a backup of all your data first, as well as of your website.<br><br>TDM is not responsible if you lose your data. Unfortunately, the screen shots cannot be copied.");
define('_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS', 'Import from WF Downloads (only for V3.23 RC5)');
define('_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS_CATIMG', 'Select Upload Directory (the path) for categories images of WF-Downloads');
define('_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS_SHOTS', 'Select Upload Directory (the path) for screen shots of WF-Downloads');
//Pour les options de filtre
define('_AM_TDMDOWNLOADS_ORDER', ' order: ');
define('_AM_TDMDOWNLOADS_TRIPAR', 'sorted by: ');
//Formulaire et tableau
define('_AM_TDMDOWNLOADS_FORMADD', 'Add');
define('_AM_TDMDOWNLOADS_FORMACTION', 'Action');
define('_AM_TDMDOWNLOADS_FORMAFFICHE', 'Display the field?');
define('_AM_TDMDOWNLOADS_FORMAFFICHESEARCH', 'Search field?');
define('_AM_TDMDOWNLOADS_FORMAPPROVE', 'Approve');
define('_AM_TDMDOWNLOADS_FORMCAT', 'Category');
define('_AM_TDMDOWNLOADS_FORMCOMMENTS', 'Number of comments');
define('_AM_TDMDOWNLOADS_FORMDATE', 'Date');
define('_AM_TDMDOWNLOADS_FORMDATEUPDATE', 'Update the date');
define('_AM_TDMDOWNLOADS_FORMDATEUPDATE_NO', 'No');
define('_AM_TDMDOWNLOADS_FORMDATEUPDATE_YES', 'Yes -->');
define('_AM_TDMDOWNLOADS_FORMDEL', 'Delete');
define('_AM_TDMDOWNLOADS_FORMDISPLAY', 'Display');
define('_AM_TDMDOWNLOADS_FORMEDIT', 'Edit');
define('_AM_TDMDOWNLOADS_FORMFILE', 'File');
define('_AM_TDMDOWNLOADS_FORMHITS', 'Hits');
define('_AM_TDMDOWNLOADS_FORMHOMEPAGE', 'Home Page');
define('_AM_TDMDOWNLOADS_FORMLOCK', 'Deactivate the download');
define('_AM_TDMDOWNLOADS_FORMIGNORE', 'Ignore');
define('_AM_TDMDOWNLOADS_FORMINCAT', 'In the category');
define('_AM_TDMDOWNLOADS_FORMIMAGE', 'Image');
define('_AM_TDMDOWNLOADS_FORMIMG', 'Logo');
define('_AM_TDMDOWNLOADS_FORMPAYPAL', 'Paypal address for donation');
define('_AM_TDMDOWNLOADS_FORMPATH', 'Files exist in: %s');
define('_AM_TDMDOWNLOADS_FORMPERMDOWNLOAD', 'Select groups that can download this file');
define('_AM_TDMDOWNLOADS_FORMPLATFORM', 'Platform: ');
define('_AM_TDMDOWNLOADS_FORMPOSTER', 'Posted by ');
define('_AM_TDMDOWNLOADS_FORMRATING', 'Note');
define('_AM_TDMDOWNLOADS_FORMSIZE', 'File size');
define('_AM_TDMDOWNLOADS_FORMSTATUS', 'Download Status');
define('_AM_TDMDOWNLOADS_FORMSTATUS_OK', 'Approved');
define('_AM_TDMDOWNLOADS_FORMSUBMITTER', 'Posted by');
define('_AM_TDMDOWNLOADS_FORMSUREDEL', "Are you sure you want to delete: <strong><span style='color:#ff0000'> %s </span></strong>");
define('_AM_TDMDOWNLOADS_FORMTEXT', 'Description');
define('_AM_TDMDOWNLOADS_FORMTEXTDOWNLOADS', "Description: <br><br>Use the delimiter '<strong>[pagebreak]</strong>' to define the size of the short description. <br> The short description allows to reduce the text size in the home page of the module and categories.");
define('_AM_TDMDOWNLOADS_FORMTITLE', 'Title');
define('_AM_TDMDOWNLOADS_FORMUPLOAD', 'Upload');
define('_AM_TDMDOWNLOADS_FORMURL', 'Download URL');
define('_AM_TDMDOWNLOADS_FORMVALID', 'Approve the download');
define('_AM_TDMDOWNLOADS_FORMVERSION', 'Version');
define('_AM_TDMDOWNLOADS_FORMVOTE', 'Votes');
define('_AM_TDMDOWNLOADS_FORMWEIGHT', 'Weight');
define('_AM_TDMDOWNLOADS_FORMWITHFILE', 'With the file: ');
//Message d'erreur
define('_AM_TDMDOWNLOADS_ERREUR_CAT', 'You cannot use this category (looping on itself)');
define('_AM_TDMDOWNLOADS_ERREUR_NOBMODDOWNLOADS', 'There are no modified downloads');
define('_AM_TDMDOWNLOADS_ERREUR_NOBROKENDOWNLOADS', 'There are no broken downloads');
define('_AM_TDMDOWNLOADS_ERREUR_NOCAT', 'You have to choose a category!');
define('_AM_TDMDOWNLOADS_ERREUR_NODESCRIPTION', 'You have to write a description');
define('_AM_TDMDOWNLOADS_ERREUR_NODOWNLOADS', 'There are no files to download');
define('_AM_TDMDOWNLOADS_ERREUR_NODOWNLOADSWAITING', 'There are no files waiting for approval');
define('_AM_TDMDOWNLOADS_ERREUR_SIZE', 'the file size must be a number');
//Message de redirection
define('_AM_TDMDOWNLOADS_REDIRECT_DELOK', 'Successfully deleted ');
define('_AM_TDMDOWNLOADS_REDIRECT_NOCAT', 'You have to create a category first');
define('_AM_TDMDOWNLOADS_REDIRECT_NODELFIELD', 'You can not delete this field (Basic Field)');
define('_AM_TDMDOWNLOADS_REDIRECT_SAVE', 'Successfully registered');
define('_AM_TDMDOWNLOADS_REDIRECT_DEACTIVATED', 'Successfully deactivated');
define('_AM_TDMDOWNLOADS_NOPERMSSET', 'Permission cannot be set: No Category created yet! Please create a Category first.');
//Bytes sizes
define('_AM_TDMDOWNLOADS_BYTES', 'Bytes');
define('_AM_TDMDOWNLOADS_KBYTES', 'kB');
define('_AM_TDMDOWNLOADS_MBYTES', 'MB');
define('_AM_TDMDOWNLOADS_GBYTES', 'GB');
define('_AM_TDMDOWNLOADS_TBYTES', 'TB');
// ---------------- Admin Others ----------------
define('_AM_TDMDOWNLOADS_MAINTAINEDBY', 'maintained by');
//2.00
//directories
define('_AM_TDMDOWNLOADS_AVAILABLE', "<span style='color : #008000;'>Available. </span>");
define('_AM_TDMDOWNLOADS_NOTAVAILABLE', "<span style='color : #ff0000;'>is not available. </span>");
define('_AM_TDMDOWNLOADS_NOTWRITABLE', "<span style='color : #ff0000;'>" . ' should have permission ( %1$d ), but it has ( %2$d )' . '</span>');
define('_AM_TDMDOWNLOADS_CREATETHEDIR', 'Create it');
define('_AM_TDMDOWNLOADS_SETMPERM', 'Set the permission');
define('_AM_TDMDOWNLOADS_DIRCREATED', 'The directory has been created');
define('_AM_TDMDOWNLOADS_DIRNOTCREATED', 'The directory can not be created');
define('_AM_TDMDOWNLOADS_PERMSET', 'The permission has been set');
define('_AM_TDMDOWNLOADS_PERMNOTSET', 'The permission can not be set');
define('_AM_TDMDOWNLOADS_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
define('_AM_TDMDOWNLOADS_UPGRADEFAILED1', "Update failed - couldn't add new fields");
define('_AM_TDMDOWNLOADS_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('_AM_TDMDOWNLOADS_ERROR_COLUMN', 'Could not create column in database : %s');
define('_AM_TDMDOWNLOADS_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('_AM_TDMDOWNLOADS_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('_AM_TDMDOWNLOADS_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');
define('_AM_TDMDOWNLOADS_FOLDERS_DELETED_OK', 'Upload Folders have been deleted');
// Error Msgs
define('_AM_TDMDOWNLOADS_ERROR_BAD_DEL_PATH', 'Could not delete %s directory');
define('_AM_TDMDOWNLOADS_ERROR_BAD_REMOVE', 'Could not delete %s');
define('_AM_TDMDOWNLOADS_ERROR_NO_PLUGIN', 'Could not load plugin');
define('_AM_TDMDOWNLOADS_NUMBYTES', '%s bytes');
