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
 * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage)
 */

// The name of this module
define('_MI_TDMDOWNLOADS_NAME', 'TDMDownloads');
define('_MI_TDMDOWNLOADS_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_TDMDOWNLOADS_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
// A brief description of this module
define('_MI_TDMDOWNLOADS_DESC', 'Erstellt einen Downloadbereich in dem User verschiedene Dateien downloaden, einschicken und bewerten k&ouml;nnen.');
// Names of blocks for this module
define('_MI_TDMDOWNLOADS_BNAME1', 'Aktuelle Downloads');
define('_MI_TDMDOWNLOADS_BNAMEDSC1', 'Anzeige der aktuellen Downloads');
define('_MI_TDMDOWNLOADS_BNAME2', 'Top Downloads');
define('_MI_TDMDOWNLOADS_BNAMEDSC2', 'Anzeige der Top Downloads');
define('_MI_TDMDOWNLOADS_BNAME3', 'Top bewertete Downloads');
define('_MI_TDMDOWNLOADS_BNAMEDSC3', 'Anzeige der am besten bewerteten Downloads');
define('_MI_TDMDOWNLOADS_BNAME4', 'Downloads zufällig');
define('_MI_TDMDOWNLOADS_BNAMEDSC4', 'Zufällige Anzeige der Downloads');
define('_MI_TDMDOWNLOADS_BNAME5', 'Download-Suche');
define('_MI_TDMDOWNLOADS_BNAMEDSC5', 'Formular Download-Suche');
// Sub menu titles
define('_MI_TDMDOWNLOADS_SMNAME1', 'Einsenden');
define('_MI_TDMDOWNLOADS_SMNAME2', 'Dateiliste');
// Names of admin menu items
define('_MI_TDMDOWNLOADS_ADMENU1', 'Zusammenfassung');
define('_MI_TDMDOWNLOADS_ADMENU2', 'Verwaltung Kategorien');
define('_MI_TDMDOWNLOADS_ADMENU3', 'Verwaltung Dateien');
define('_MI_TDMDOWNLOADS_ADMENU4', 'Fehlerhafte Downloads');
define('_MI_TDMDOWNLOADS_ADMENU5', 'Änderungen Downloads');
define('_MI_TDMDOWNLOADS_ADMENU6', 'Verwaltung Extrafelder');
define('_MI_TDMDOWNLOADS_ADMENU7', 'Importe');
define('_MI_TDMDOWNLOADS_ADMENU8', 'Festlegung Berechtigungen');
define('_MI_TDMDOWNLOADS_ADMENU9', 'About');
// Preferences
define('_MI_TDMDOWNLOADS_POPULAR', 'Anzahl an Downloads, damit eine Downloaddatei als populär gilt');
define('_MI_TDMDOWNLOADS_AUTO_SUMMARY', 'Freigabe der automatischen Zusammenfassung');
define('_MI_TDMDOWNLOADS_SHOW_UPDATED', "Anzeige der Bilder 'updated' und 'neu'?");
define('_MI_TDMDOWNLOADS_USESHOTS', 'Screenshots anzeigen?');
define('_MI_TDMDOWNLOADS_IMGFLOAT', 'Bildausrichtung');
define('_MI_TDMDOWNLOADS_IMGFLOAT_LEFT', 'Links');
define('_MI_TDMDOWNLOADS_IMGFLOAT_RIGHT', 'Rechts');
define('_MI_TDMDOWNLOADS_SHOTWIDTH', 'Bildhöhe [in px]');
define('_MI_TDMDOWNLOADS_PLATEFORM', 'Plattformen');
define('_MI_TDMDOWNLOADS_PLATEFORM_DSC', 'Authorisierte Plattformen getrennt durch |');
define('_MI_TDMDOWNLOADS_USETELLAFRIEND', 'Das Tellafriend-Modul mit dem Link \'tell a friend\' verwenden?');
define('_MI_TDMDOWNLOADS_USETELLAFRIENDDSC', 'Sie müssen das Tellafriend-Modul installieren, damit Sie diese Option nützen können.');
define('_MI_TDMDOWNLOADS_USETAG', 'Das Tag-Modul zum Erstellen von Tags verwenden');
define('_MI_TDMDOWNLOADS_USETAGDSC', 'Sie müssen das Tag-Modul installieren, damit Sie diese Option nützen können.');
define('_MI_TDMDOWNLOADS_FORM_OPTIONS', 'Editor');
define('_MI_TDMDOWNLOADS_PERPAGE', 'Anzahl der Einträge je Seite');
define('_MI_TDMDOWNLOADS_NBDOWCOL', 'Anzahl der Spalten');
define('_MI_TDMDOWNLOADS_NEWDLS', 'Anzahl der neuen Downloads auf der Indexseite');
define('_MI_TDMDOWNLOADS_TOPORDER', 'Sortierung auf der Indexseite?');
define('_MI_TDMDOWNLOADS_TOPORDER1', 'Datum (absteigend)');
define('_MI_TDMDOWNLOADS_TOPORDER2', 'Datum (aufsteigend)');
define('_MI_TDMDOWNLOADS_TOPORDER3', 'Aufrufe (absteigend)');
define('_MI_TDMDOWNLOADS_TOPORDER4', 'Aufrufe (aufsteigend)');
define('_MI_TDMDOWNLOADS_TOPORDER5', 'Bewertung (absteigend)');
define('_MI_TDMDOWNLOADS_TOPORDER6', 'Bewertung (aufsteigend)');
define('_MI_TDMDOWNLOADS_TOPORDER7', 'Titel (absteigend)');
define('_MI_TDMDOWNLOADS_TOPORDER8', 'Titel (aufsteigend)');
define('_MI_TDMDOWNLOADS_PERPAGELISTE', 'Anzahl Downloads in der Dateiliste');
define('_MI_TDMDOWNLOADS_SEARCHORDER', 'Sortierung in der Dateiliste?');
define('_MI_TDMDOWNLOADS_SUBCATPARENT', 'Anzahl der Unterkategorien, die in der Hauptkategorie angezeigt werden');
define('_MI_TDMDOWNLOADS_NBCATCOL', 'Anzahl der Spalten in einer Kategorie');
define('_MI_TDMDOWNLOADS_BLDATE', 'Anzeige der aktuellen Downloads in der Indexseite und bei den Kategorien (Zusammenfassung)?');
define('_MI_TDMDOWNLOADS_BLPOP', 'Anzeige der populären Downloads in der Indexseite und bei den Kategorien (Zusammenfassung)?');
define('_MI_TDMDOWNLOADS_BLRATING', 'Anzeige der best bewerteten Downloads in der Indexseite und bei den Kategorien (Zusammenfassung)?');
define('_MI_TDMDOWNLOADS_NBBL', 'Anzahl der Downloads in einer Zusammenfassung?');
define('_MI_TDMDOWNLOADS_LONGBL', 'Titellänge in der Zusammenfassung');
define('_MI_TDMDOWNLOADS_BOOKMARK', 'Bookmark');
define('_MI_TDMDOWNLOADS_BOOKMARK_DSC', 'Anzeigen/verstecken Bookmark-Icons');
define('_MI_TDMDOWNLOADS_SOCIAL', 'Social Networks');
define('_MI_TDMDOWNLOADS_SOCIAL_DSC', 'Anzeigen/verstecken Social-Network-Icons');
define('_MI_TDMDOWNLOADS_DOWNLOADFLOAT', 'Ausrichtung Downloadseite');
define('_MI_TDMDOWNLOADS_DOWNLOADFLOAT_DSC', '<ul><li>Links nach rechts: Zeigt die Beschreibung auf der linken und die Infospalte auf der rechten Seite</li><li>Rechts nach links: Zeigt die Beschreibung auf der linken und die Infospalte auf der rechten Seite</li></ul>');
define('_MI_TDMDOWNLOADS_DOWNLOADFLOAT_LTR', 'Links nach rechts');
define('_MI_TDMDOWNLOADS_DOWNLOADFLOAT_RTL', 'Rechts nach links');
define('_MI_TDMDOWNLOADS_PERPAGEADMIN', 'Anzahl der Einträge je Seite in der Administration');
define('_MI_TDMDOWNLOADS_PERMISSIONDOWNLOAD', 'Wähle die Art der Downloadberechtigung');
define('_MI_TDMDOWNLOADS_PERMISSIONDOWNLOAD1', 'Berechtigung je Kategorie');
define('_MI_TDMDOWNLOADS_PERMISSIONDOWNLOAD2', 'Berechtigung je Datei');
define('_MI_TDMDOWNLOADS_DOWNLOAD_NAME', 'Hochgeladene Datei umbenennen?');
define('_MI_TDMDOWNLOADS_DOWNLOAD_NAMEDSC', "Wenn Sie die Option 'Nein' wählen, werden bereits auf dem Server gespeicherte Dateien überschrieben, wenn die hochgeladene Datei den selben Dateinamen hat.");
define('_MI_TDMDOWNLOADS_DOWNLOAD_PREFIX', 'Präfix für Dateiupload');
define('_MI_TDMDOWNLOADS_DOWNLOAD_PREFIXDSC', "Nur gültig, wenn die Option 'Hochgeladene Datei umbenennen' gewählt wurde.");
define('_MI_TDMDOWNLOADS_MAXUPLOAD_SIZE', 'Maximale Dateigröße für Datei-Upload');
define(
    '_MI_TDMDOWNLOADS_MAXUPLOAD_SIZE_DESC',
    "Die Auswahlbox ist entsprechend den Einstellungen 'post_max_size' und 'upload_max_filesize' in der php.ini limitiert.<br>Wenn Sie diese Werte erhöhen möchten dann müssen Sie zuerst die Einstellungen in der php.ini erhöhen. Danach ist ein Update des Modules erforderlich."
);
define('_MI_TDMDOWNLOADS_MAXUPLOAD_SIZE_MB', 'MB');
define('_MI_TDMDOWNLOADS_MIMETYPE', 'Zulässige Mime types ');
define('_MI_TDMDOWNLOADS_MIMETYPE_DSC', 'Eingabe der zulässige Mime types');
define('_MI_TDMDOWNLOADS_CHECKHOST', 'Direktes Verlinken der Downloads (leeching) unterdrücken?');
define('_MI_TDMDOWNLOADS_REFERERS', 'Diese Seiten dürfen Downloads direkt verlinken, getrennt durch ein&nbsp;|');
define('_MI_TDMDOWNLOADS_DOWNLIMIT', 'Downloadlimit');
define('_MI_TDMDOWNLOADS_DOWNLIMITDSC', 'Option Downloadlimit verwenden');
define('_MI_TDMDOWNLOADS_LIMITGLOBAL', 'Anzahl Downloads in 24 Stunden');
define('_MI_TDMDOWNLOADS_LIMITGLOBALDSC', 'Anzahl der zulässigen Downloads innerhalb von 24 Stunden je User. 0 bedeutet ohne Limit.');
define('_MI_TDMDOWNLOADS_LIMITLID', 'Anzahl Downloads in 24 Stunden je Datei');
define('_MI_TDMDOWNLOADS_LIMITLIDDSC', 'Anzahl der zulässigen Downloads innerhalb von 24 Stundenje Datei und je User. 0 bedeutet ohne Limit.');
define('_MI_TDMDOWNLOADS_USEPAYPAL', "Schaltfläche 'Spenden' von Paypal verwenden ");
define('_MI_TDMDOWNLOADS_CURRENCYPAYPAL', 'Währung für Spenden');
define('_MI_TDMDOWNLOADS_IMAGEPAYPAL', "Bild für Schaltfläche 'Spenden'");
define('_MI_TDMDOWNLOADS_IMAGEPAYPALDSC', 'Adresse für dieses Bild angeben');
define('_MI_TDMDOWNLOADS_PERPAGERSS', 'RSS Anzahl Downloads');
define('_MI_TDMDOWNLOADS_PERPAGERSSDSCC', 'Anzahl der Downloads, die auf RSS-Seiten angezeigt werden');
define('_MI_TDMDOWNLOADS_TIMECACHERSS', 'RSS cache time');
define('_MI_TDMDOWNLOADS_TIMECACHERSSDSC', 'Cache time für RSS Seiten in Minuten');
define('_MI_TDMDOWNLOADS_LOGORSS', 'Seitenlogo für RSS Seiten');
// Notifications
define('_MI_TDMDOWNLOADS_GLOBAL_NOTIFY', 'Global');
define('_MI_TDMDOWNLOADS_GLOBAL_NOTIFYDSC', 'Globale Download-Benachrichtigungsoptionen.');
define('_MI_TDMDOWNLOADS_CATEGORY_NOTIFY', 'Kategorie');
define('_MI_TDMDOWNLOADS_CATEGORY_NOTIFYDSC', 'Benachrichtigungsoptionen der aktuellen Kategorie.');
define('_MI_TDMDOWNLOADS_FILE_NOTIFY', 'Datei');
define('_MI_TDMDOWNLOADS_FILE_NOTIFYDSC', 'Benachrichtigungsoptionen der aktuellen Datei.');
define('_MI_TDMDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFY', 'Neue Kategorie');
define('_MI_TDMDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Benachrichtigung wenn eine neue Dateikategorie angelegt wird.');
define('_MI_TDMDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Benachrichtigung erhalten, wenn eine neue Dateikategorie angelegt wird.');
define('_MI_TDMDOWNLOADS_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische Benachrichtigung: Neue Kategorie wurde erstellt');
define('_MI_TDMDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFY', 'Dateiänderung angefragt');
define('_MI_TDMDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYCAP', 'Benachrichtigung bei Dateiänderungsmeldung.');
define('_MI_TDMDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYDSC', 'Benachrichtigung erhalten, wenn eine Dateiänderung gemeldet wird.');
define('_MI_TDMDOWNLOADS_GLOBAL_FILEMODIFY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische Benachrichtigung: Dateiänderungsanfrage');
define('_MI_TDMDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFY', 'Defekter Downloadlink übermittelt');
define('_MI_TDMDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYCAP', 'Benachrichtigung bei gemeldeten defekten Downloads.');
define('_MI_TDMDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYDSC', 'Benachrichtigung erhalten, wenn ein defekter Download gemeldet wird.');
define('_MI_TDMDOWNLOADS_GLOBAL_FILEBROKEN_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische Benachrichtigung: Defekter Downloadlink übermittelt');
define('_MI_TDMDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFY', 'Neue Datei übermittelt');
define('_MI_TDMDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYCAP', 'Benachrichtigung bei (wartenden) neuen gemeldeten Downloads.');
define('_MI_TDMDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYDSC', 'Benachrichtigung erhalten, wenn (wartende) neue Downloads gemeldet werden.');
define('_MI_TDMDOWNLOADS_GLOBAL_FILESUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische Benachrichtigung: Neue Datei übermittelt');
define('_MI_TDMDOWNLOADS_GLOBAL_NEWFILE_NOTIFY', 'Neue Datei');
define('_MI_TDMDOWNLOADS_GLOBAL_NEWFILE_NOTIFYCAP', 'Benachrichtigung wenn neuer Download gemeldet wird.');
define('_MI_TDMDOWNLOADS_GLOBAL_NEWFILE_NOTIFYDSC', 'Benachrichtigung erhalten, wenn ein neuer Download gemeldet wird.');
define('_MI_TDMDOWNLOADS_GLOBAL_NEWFILE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische Benachrichtigung: Neue Datei');
define('_MI_TDMDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFY', 'Datei in aktueller Kategorie gemeldet');
define('_MI_TDMDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYCAP', 'Benachrichtigung bei (wartenden) neuen Downloads für die aktuelle Kategorie.');
define('_MI_TDMDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYDSC', 'Benachrichtigung erhalten, wenn (wartende) neue Downloads für die aktuelle Kategorie gemeldet werden.');
define('_MI_TDMDOWNLOADS_CATEGORY_FILESUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische Benachrichtigung: Datei in aktueller Kategorie gemeldet');
define('_MI_TDMDOWNLOADS_CATEGORY_NEWFILE_NOTIFY', 'Neue Datei in Kategorie');
define('_MI_TDMDOWNLOADS_CATEGORY_NEWFILE_NOTIFYCAP', 'Benachrichtigung wenn neuer Download in die aktuelle Kategorie aufgenommen wird.');
define('_MI_TDMDOWNLOADS_CATEGORY_NEWFILE_NOTIFYDSC', 'Benachrichtigung erhalten, wenn ein neuer Download in die aktuelle Kategorie aufgenommen wird.');
define('_MI_TDMDOWNLOADS_CATEGORY_NEWFILE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische Benachrichtigung: Neue Datei in Kategorie');
define('_MI_TDMDOWNLOADS_FILE_APPROVE_NOTIFY', 'Datei freigegeben');
define('_MI_TDMDOWNLOADS_FILE_APPROVE_NOTIFYCAP', 'Benachrichtigung wenn Datei freigegeben wird.');
define('_MI_TDMDOWNLOADS_FILE_APPROVE_NOTIFYDSC', 'Benachrichtigung erhalten, wenn die Datei freigegeben wird.');
define('_MI_TDMDOWNLOADS_FILE_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} automatische Benachrichtigung: Datei freigegeben');
//1.62
define('_MI_TDMDOWNLOADS_SHOW_LATEST_FILES', 'Die letzten Dateien anzeigen');
define('_MI_TDMDOWNLOADS_SHOW_LATEST_FILES_DSC', 'Auf der Benutzerseite werden die letzten Dateien angezeigt');
//2.00
define('_MI_TDMDOWNLOADS_BACK_2_ADMIN', 'Zurück zur Administration');
define('_MI_TDMDOWNLOADS_OVERVIEW', 'Übersicht');
//help multi-page
define('_MI_TDMDOWNLOADS_DISCLAIMER', 'Disclaimer');
define('_MI_TDMDOWNLOADS_LICENSE', 'Lizenz');
define('_MI_TDMDOWNLOADS_SUPPORT', 'Support');
define('_MI_TDMDOWNLOADS_ADMENU_MIGRATE', 'Migration');
define('_MI_TDMDOWNLOADS_SHOW_DEV_TOOLS', 'Entwicklerschaltflächen anzeigen?');
define('_MI_TDMDOWNLOADS_SHOW_DEV_TOOLS_DESC', 'Wenn ja, dann wird das Register "Migration" und weitere Entwicklertools im Admin-Bereich angezeigt.');
define('_MI_TDMDOWNLOADS_BLOCKS_ADMIN', 'Blöcke');
define('_MI_TDMDOWNLOADS_SHOW_SAMPLE_BUTTON', 'Schaltfläche Testdaten anzeigen?');
define('_MI_TDMDOWNLOADS_SHOW_SAMPLE_BUTTON_DESC', 'Wenn ja, dann wird im Admin-Bereich die Schaltfläche "Testdaten" angezeigt. Nach der Installation ist diese standardmäßig aktiviert.');
define('_MI_TDMDOWNLOADS_MENU_HISTORY', 'Historie');
//Categories:
define('_MI_TDMDOWNLOADS_PREFERENCE_BREAK_GENERAL', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Allgemein ---</span> ');
define('_MI_TDMDOWNLOADS_PREFERENCE_BREAK_USER', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Benutzer ---</span> ');
define('_MI_TDMDOWNLOADS_PREFERENCE_BREAK_ADMIN', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Administration ---</span> ');
define('_MI_TDMDOWNLOADS_PREFERENCE_BREAK_DOWNLOADS', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Downloads ---</span> ');
define('_MI_TDMDOWNLOADS_PREFERENCE_BREAK_PAYPAL', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Paypal ---</span> ');
define('_MI_TDMDOWNLOADS_PREFERENCE_BREAK_RSS', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- RSS ---</span> ');
define('_MI_TDMDOWNLOADS_PREFERENCE_BREAK_COMNOTI', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Kommente und Benachrichtigungen ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_SEO_DSC', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Einstellungen für SEO-Rewriting, Metadate, usw. ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_INDEXCAT', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Index- und Kategorieseiten ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_INDEXCAT_DSC', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_CATEGORY', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Kategorieseite ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_CATEGORY_DSC', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_ITEM', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Einträgeseiten ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_ITEM_DSC', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_FORMAT', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Format ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_FORMAT_DSC', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_PRINT', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Druckseiten ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_PRINT_DSC', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_OTHERS', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- andere ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_OTHERS_DSC', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_PERMISSIONS', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Berechtigungen ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_PERMISSIONS_DSC', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_INDEX', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Indexseite ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_INDEX_DSC', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_SUBMIT', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Download einsenden ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_SUBMIT_DSC', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Layout für Download einsenden und Standardeinstellungen für Formulare ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_SEARCH', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- Suchseite ---</span> ');
define('_MI_TDMDOWNLOADS_CONFCAT_SEARCH_DSC', '<span style="color: #FF0000; font-size: Small;  font-weight: bold;">--- ---</span> ');
