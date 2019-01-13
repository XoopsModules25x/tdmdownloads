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
// index.php
define('_AM_TDMDOWNLOADS_INDEX_BROKEN', 'Es gibt %s Meldungen über fehlerhafte Downloads');
define('_AM_TDMDOWNLOADS_INDEX_CATEGORIES', 'Es gibt %s Kategorien');
define('_AM_TDMDOWNLOADS_INDEX_DOWNLOADS', 'Es gibt %s Dateien in Ihrer Datenbank');
define('_AM_TDMDOWNLOADS_INDEX_DOWNLOADSWAITING', 'Es warten %s Downloads auf Bestätigung');
define('_AM_TDMDOWNLOADS_INDEX_MODIFIED', 'Es gibt für %s Downloads eine Änderungsanfrage');
//category.php
define('_AM_TDMDOWNLOADS_CAT_NEW', 'Neue Kategorie');
define('_AM_TDMDOWNLOADS_CAT_LIST', 'Kategorieliste');
define('_AM_TDMDOWNLOADS_DELDOWNLOADS', 'mit den folgenden Downloads:');
define('_AM_TDMDOWNLOADS_DELSOUSCAT', 'Die folgenden Kategorien werden vollständig gelöscht:');
//downloads.php
define('_AM_TDMDOWNLOADS_DOWNLOADS_LISTE', 'Downloadliste');
define('_AM_TDMDOWNLOADS_DOWNLOADS_NEW', 'Neuer Download');
define('_AM_TDMDOWNLOADS_DOWNLOADS_SEARCH', 'Suche');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTESTOTAL', 'Anzahl Abstimmungen gesamt');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTESANONYME', 'Abstimmungen von anonymen Usern:');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTESUSER', 'Abstimmungen von registrierten Usern:');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTE_USER', 'Users');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTE_IP', 'IP Addresse');
define('_AM_TDMDOWNLOADS_DOWNLOADS_WAIT', 'Warten auf Freigabe');
//broken.php
define('_AM_TDMDOWNLOADS_BROKEN_SENDER', 'Meldende Person');
define('_AM_TDMDOWNLOADS_BROKEN_SURDEL', 'Sind Sie sicher, dass Sie diese Meldung löschen wollen?');
//modified.php
define('_AM_TDMDOWNLOADS_MODIFIED_MOD', 'Abgeänderte Version');
define('_AM_TDMDOWNLOADS_MODIFIED_ORIGINAL', 'Originalversion');
define('_AM_TDMDOWNLOADS_MODIFIED_SURDEL', 'Sind Sie sicher, dass Sie diesen Änderungsantrag löschen wollen?');
//field.php
define('_AM_TDMDOWNLOADS_DELDATA', 'mit folgenden Daten:');
define('_AM_TDMDOWNLOADS_FIELD_LIST', 'Feldliste');
define('_AM_TDMDOWNLOADS_FIELD_NEW', 'Neue Felder');
//about.php
define('_AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION', 'Schutz der Dateien');
define('_AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION_INFO1', "Um Ihre Dateien gegen unerwünschtes Downloaden (entgegen Ihrer definierten Berechtigungen) zu schützen, sollten Sie eine '.htaccess' Datei im Verzeichnis erstellen:");
define('_AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION_INFO2', 'mit folgendem Inhalt:');
//permissions.php
define('_AM_TDMDOWNLOADS_PERMISSIONS_4', 'Einen Download einsenden');
define('_AM_TDMDOWNLOADS_PERMISSIONS_8', 'Einen Änderungsantrag einsenden');
define('_AM_TDMDOWNLOADS_PERMISSIONS_16', 'Einen Download bewerten');
define('_AM_TDMDOWNLOADS_PERMISSIONS_32', 'Dateien hochladen');
define('_AM_TDMDOWNLOADS_PERMISSIONS_64', 'Automatische Freigabe von vorgeschlagenen Dateien');
define('_AM_TDMDOWNLOADS_PERM_AUTRES', 'Andere Berechtigungen');
define('_AM_TDMDOWNLOADS_PERM_AUTRES_DSC', 'Wählen Sie Gruppen, welche dürfen:');
define('_AM_TDMDOWNLOADS_PERM_DOWNLOAD', 'Download-Berechtigungen');
define('_AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC', 'Wähle Gruppen, die in den Kategorien herunterladen dürfen');
define('_AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC2', 'Wähle Gruppen, die Dateien herunterladen dürfen');
define('_AM_TDMDOWNLOADS_PERM_SUBMIT', 'Einsendeberechtigung');
define('_AM_TDMDOWNLOADS_PERM_SUBMIT_DSC', 'Wählen Sie die Gruppen, die Dateien in dieser Kategorie einsenden dürfen');
define('_AM_TDMDOWNLOADS_PERM_VIEW', 'Berechtigung zum Ansehen');
define('_AM_TDMDOWNLOADS_PERM_VIEW_DSC', 'Wählen Sie die Gruppen, die Dateien in dieser Kategorie ansehen dürfen');
// Import.php
define('_AM_TDMDOWNLOADS_IMPORT1', 'Import');
define('_AM_TDMDOWNLOADS_IMPORT_CAT_IMP', "Kategorien: '%s' importiert");
define('_AM_TDMDOWNLOADS_IMPORT_CONF_MYDOWNLOADS', 'Wollen sie die Daten von Mydownloads in TDMDownloads importieren');
define('_AM_TDMDOWNLOADS_IMPORT_CONF_WFDOWNLOADS', 'Wollen sie die Daten von WF-Downloads in TDMDownloads importieren');
define('_AM_TDMDOWNLOADS_IMPORT_DONT_DOWNLOADS', 'Keine Dateien zum Importieren vorhanden');
define('_AM_TDMDOWNLOADS_IMPORT_DONT_TOPIC', 'Keine Topics zum Importieren vorhanden');
define('_AM_TDMDOWNLOADS_IMPORT_DOWNLOADS', 'Dateien importieren');
define('_AM_TDMDOWNLOADS_IMPORT_DOWNLOADS_IMP', "Dateien: '%s' importiert;");
define('_AM_TDMDOWNLOADS_IMPORT_ERREUR', 'Wählen Sie das Upload-Verzeichnis (Pfad)');
define('_AM_TDMDOWNLOADS_IMPORT_ERROR_DATA', 'Fehler während des Importvorganges');
define('_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS', 'Import von Mydownloads');
define('_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS_PATH', 'Upload-Verzeichnis (Pfad) der Screenshots von Mydownloads angeben');
define('_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS_URL', 'Wählen Sie die entsprechende URL der Screenshots von Mydownloads');
define('_AM_TDMDOWNLOADS_IMPORT_NB_CAT', 'Es sind %s Kategorien zum Importieren vorhanden');
define('_AM_TDMDOWNLOADS_IMPORT_NB_DOWNLOADS', 'Es sind %s Dateien zum Importieren vorhanden');
define('_AM_TDMDOWNLOADS_IMPORT_NUMBER', 'Daten zum Importieren');
define('_AM_TDMDOWNLOADS_IMPORT_OK', 'Import erfolgreich beendet!');
define('_AM_TDMDOWNLOADS_IMPORT_VOTE_IMP', "Abstimmungen: '%s' importiert;");
define('_AM_TDMDOWNLOADS_IMPORT_WARNING',
       "<span style='color:#FF0000; font-size:16px; font-weight:bold'>Achtung !</span><br><br>Durch den Import werden alle Daten in TDMDownloads gelöscht. Es wird dringend empfohlen, zuerst ein Backup der Daten, wenn möglich der gesamten Webseite, zu erstellen.<br><br>TDM übernimmt keinerlei Haftung für verloren gegangene Daten.");
define('_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS', 'Import von WF Downloads(nur V3.2 RC2)');
define('_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS_CATIMG', 'Upload-Verzeichnis (Pfad) für Kategoriebilder von WF-Downloads angeben');
define('_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS_SHOTS', 'Upload-Verzeichnis (Pfad) für Screenshots von WF-Downloads angeben');
//Pour les options de filtre
define('_AM_TDMDOWNLOADS_ORDER', ' Sortierung: ');
define('_AM_TDMDOWNLOADS_TRIPAR', 'Sortierung nach: ');
//Formulaire et tableau
define('_AM_TDMDOWNLOADS_FORMADD', 'Hinzufügen');
define('_AM_TDMDOWNLOADS_FORMACTION', 'Aktion');
define('_AM_TDMDOWNLOADS_FORMAFFICHE', 'Feld anzeigen?');
define('_AM_TDMDOWNLOADS_FORMAFFICHESEARCH', 'Suchfeld?');
define('_AM_TDMDOWNLOADS_FORMAPPROVE', 'Bestätigen');
define('_AM_TDMDOWNLOADS_FORMCAT', 'Kategorie');
define('_AM_TDMDOWNLOADS_FORMCOMMENTS', 'Anzahl Kommentare');
define('_AM_TDMDOWNLOADS_FORMDATE', 'Datum');
define('_AM_TDMDOWNLOADS_FORMDATEUPDATE', 'Datum aktualisieren');
define('_AM_TDMDOWNLOADS_FORMDATEUPDATE_NO', 'Nein');
define('_AM_TDMDOWNLOADS_FORMDATEUPDATE_YES', 'Ja -->');
define('_AM_TDMDOWNLOADS_FORMDEL', 'Löschen');
define('_AM_TDMDOWNLOADS_FORMDISPLAY', 'Anzeigen');
define('_AM_TDMDOWNLOADS_FORMEDIT', 'Bearbeiten');
define('_AM_TDMDOWNLOADS_FORMFILE', 'Datei');
define('_AM_TDMDOWNLOADS_FORMHITS', 'Aufrufe');
define('_AM_TDMDOWNLOADS_FORMHOMEPAGE', 'Home Page');
define('_AM_TDMDOWNLOADS_FORMLOCK', 'Download deaktivieren');
define('_AM_TDMDOWNLOADS_FORMIGNORE', 'Ignorieren');
define('_AM_TDMDOWNLOADS_FORMINCAT', 'in der Kategorie');
define('_AM_TDMDOWNLOADS_FORMIMAGE', 'Bild');
define('_AM_TDMDOWNLOADS_FORMIMG', 'Logo');
define('_AM_TDMDOWNLOADS_FORMPAYPAL', 'Paypal-Addresse für Spenden');
define('_AM_TDMDOWNLOADS_FORMPATH', 'Dateien existieren in: %s');
define('_AM_TDMDOWNLOADS_FORMPERMDOWNLOAD', 'Wählen Sie die Gruppen, die diese Datei herunterladen dürfen');
define('_AM_TDMDOWNLOADS_FORMPLATFORM', 'Plattform: ');
define('_AM_TDMDOWNLOADS_FORMPOSTER', 'Eingesendet von ');
define('_AM_TDMDOWNLOADS_FORMRATING', 'Bewertung');
define('_AM_TDMDOWNLOADS_FORMSIZE', 'Dateigröße');
define('_AM_TDMDOWNLOADS_FORMSTATUS', 'Download Status');
define('_AM_TDMDOWNLOADS_FORMSTATUS_OK', 'Bestätigt');
define('_AM_TDMDOWNLOADS_FORMSUBMITTER', 'Eingesendet von');
define('_AM_TDMDOWNLOADS_FORMSUREDEL', 'Wollen Sie wirklich löschen: <b><span style="color:#ff0000"> %s </span></b>');
define('_AM_TDMDOWNLOADS_FORMTEXT', 'Bescheibung');
define('_AM_TDMDOWNLOADS_FORMTEXTDOWNLOADS', "Bescheibung: <br><br>Verwenden Sie den Delimiter '<b>[pagebreak]</b>' um die Größe der Kurzbeschreibung zu definieren. <br> Durch eine Kurzbeschreibung kann die Textmenge in Ihrer Homepage möglichst klein gehalten werden.");
define('_AM_TDMDOWNLOADS_FORMTITLE', 'Titel');
define('_AM_TDMDOWNLOADS_FORMUPLOAD', 'Hochladen');
define('_AM_TDMDOWNLOADS_FORMURL', 'Download URL');
define('_AM_TDMDOWNLOADS_FORMVALID', 'Download aktivieren');
define('_AM_TDMDOWNLOADS_FORMVERSION', 'Version');
define('_AM_TDMDOWNLOADS_FORMVOTE', 'Bewertungen');
define('_AM_TDMDOWNLOADS_FORMWEIGHT', 'Reihung');
define('_AM_TDMDOWNLOADS_FORMWITHFILE', 'Mit Datei: ');
//Message d'erreur
define('_AM_TDMDOWNLOADS_ERREUR_CAT', 'Sie können diese Kategorie nicht verwendet (Verweis auf sich selbst)');
define('_AM_TDMDOWNLOADS_ERREUR_NOBMODDOWNLOADS', 'Es gibt keine geänderten Downloads');
define('_AM_TDMDOWNLOADS_ERREUR_NOBROKENDOWNLOADS', 'Es gibt keine fehlerhaften Downloads');
define('_AM_TDMDOWNLOADS_ERREUR_NOCAT', 'Bitte Kategorie wählen!');
define('_AM_TDMDOWNLOADS_ERREUR_NODESCRIPTION', 'Bitte Beschreibung angeben');
define('_AM_TDMDOWNLOADS_ERREUR_NODOWNLOADS', 'Keine Dateien für Download vorhanden');
define('_AM_TDMDOWNLOADS_ERREUR_NODOWNLOADSWAITING', 'Es warten keine Dateien auf Freigabe');
define('_AM_TDMDOWNLOADS_ERREUR_SIZE', 'Die Dateigröße muss ein numerischer Wert sein');
//Message de redirection
define('_AM_TDMDOWNLOADS_REDIRECT_DELOK', 'Daten erfolgreich gelöscht ');
define('_AM_TDMDOWNLOADS_REDIRECT_NOCAT', 'Sie müssen zuerst eine Kategorie erstellen');
define('_AM_TDMDOWNLOADS_REDIRECT_NODELFIELD', 'Sie können dieses Feld nicht löschen (Standardfeld)');
define('_AM_TDMDOWNLOADS_REDIRECT_SAVE', 'Daten erfolgreich gespeichert');
define('_AM_TDMDOWNLOADS_REDIRECT_DEACTIVATED', 'Erfolgreich deaktiviert');
define('_AM_TDMDOWNLOADS_NOPERMSSET', 'Berechtigungen konnten nicht gesetzt werden. Grund: Noch keine Kategorie vorhanden! Sie müssen zuerst eine Kategorie erstellen.');
//Bytes sizes
define('_AM_TDMDOWNLOADS_BYTES', 'bytes');
define('_AM_TDMDOWNLOADS_KBYTES', 'KB');
define('_AM_TDMDOWNLOADS_MBYTES', 'MB');
define('_AM_TDMDOWNLOADS_GBYTES', 'GB');
define('_AM_TDMDOWNLOADS_TBYTES', 'TB');
// ---------------- Admin Others ----------------
define('_AM_TDMDOWNLOADS_MAINTAINEDBY', 'Unterstützt von');
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
