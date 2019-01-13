<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Wfdownloads module
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         wfdownload
 * @since           3.23
 * @author          Xoops Development Team
 */
$moduleDirName      = basename(dirname(dirname(__DIR__)));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

define('CO_' . $moduleDirNameUpper . '_GDLIBSTATUS', 'GD library support: ');
define('CO_' . $moduleDirNameUpper . '_GDLIBVERSION', 'GD Library version: ');
define('CO_' . $moduleDirNameUpper . '_GDOFF', "<span style='font-weight: bold;'>Deaktivieren</span> (Keine Vorschaubilder verfügbar)");
define('CO_' . $moduleDirNameUpper . '_GDON', "<span style='font-weight: bold;'>Aktivieren</span> (Vorschaubilder verfügbar)");
define('CO_' . $moduleDirNameUpper . '_IMAGEINFO', 'Server-Status');
define('CO_' . $moduleDirNameUpper . '_MAXPOSTSIZE', 'Maximal erlaubte Größe für Senden (Einstellung post_max_size in php.ini): ');
define('CO_' . $moduleDirNameUpper . '_MAXUPLOADSIZE', 'Maximal erlaubte Größe Dateiupload (Einstellung upload_max_filesize in php.ini): ');
define('CO_' . $moduleDirNameUpper . '_MEMORYLIMIT', 'Speicherlimit (Einstellung memory_limit in php.ini): ');
define('CO_' . $moduleDirNameUpper . '_METAVERSION', "<span style='font-weight: bold;'>Downloads meta version:</span> ");
define('CO_' . $moduleDirNameUpper . '_OFF', "<span style='font-weight: bold;'>AUS</span>");
define('CO_' . $moduleDirNameUpper . '_ON', "<span style='font-weight: bold;'>AN</span>");
define('CO_' . $moduleDirNameUpper . '_SERVERPATH', 'Serverpfad zu XOOPS-Root: ');
define('CO_' . $moduleDirNameUpper . '_SERVERUPLOADSTATUS', 'Status Serveruploads: ');
define('CO_' . $moduleDirNameUpper . '_SPHPINI', "<span style='font-weight: bold;'>Aus der Datei PHP-ini ermittelte Information:</span>");
define('CO_' . $moduleDirNameUpper . '_UPLOADPATHDSC', 'Beachte. Der Pfad für das Hochladen *MUSS* den vollständigen Serverpfad zu Ihrem Upload-Verzeichnis enthalten.');
define('CO_' . $moduleDirNameUpper . '_PRINT', "<span style='font-weight: bold;'>Drucken</span>");
define('CO_' . $moduleDirNameUpper . '_PDF', "<span style='font-weight: bold;'>Erstelle PDF</span>");
define('CO_' . $moduleDirNameUpper . '_UPGRADEFAILED0', "Aktualiserung fehlgeschlagen - Umbenennung Feld '%s' nicht möglich");
define('CO_' . $moduleDirNameUpper . '_UPGRADEFAILED1', "Aktualiserung fehlgeschlagen - Hinzufügen Feld '%s' nicht möglich");
define('CO_' . $moduleDirNameUpper . '_UPGRADEFAILED2', "Aktualiserung fehlgeschlagen - Umbenennung Tabelle '%s' nicht möglich");
define('CO_' . $moduleDirNameUpper . '_ERROR_COLUMN', 'Erstellen Spalte in Datenbank nicht möglich: %s');
define('CO_' . $moduleDirNameUpper . '_ERROR_BAD_XOOPS', 'Dieses Modul benötigt XOOPS %s+ (%s installiert)');
define('CO_' . $moduleDirNameUpper . '_ERROR_BAD_PHP', 'Dieses Modul benötigt PHP Version %s+ (%s installiert)');
define('CO_' . $moduleDirNameUpper . '_ERROR_TAG_REMOVAL', 'Konnte Tags vom Tag-Modul nicht entfernen');
define('CO_' . $moduleDirNameUpper . '_FOLDERS_DELETED_OK', 'Upload-Verzeichnisse wurden gelöscht');
// Error Msgs
define('CO_' . $moduleDirNameUpper . '_ERROR_BAD_DEL_PATH', 'Konnte Verzeichnis %s nicht löschen');
define('CO_' . $moduleDirNameUpper . '_ERROR_BAD_REMOVE', 'Konnte %s nicht löschen');
define('CO_' . $moduleDirNameUpper . '_ERROR_NO_PLUGIN', 'Konnte Plugin nicht laden');
//Help
define('CO_' . $moduleDirNameUpper . '_DIRNAME', basename(dirname(dirname(__DIR__))));
define('CO_' . $moduleDirNameUpper . '_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('CO_' . $moduleDirNameUpper . '_BACK_2_ADMIN', 'Zurück zur Administration von ');
define('CO_' . $moduleDirNameUpper . '_OVERVIEW', 'Übersicht');
//help multi-page
define('CO_' . $moduleDirNameUpper . '_DISCLAIMER', 'Disclaimer');
define('CO_' . $moduleDirNameUpper . '_LICENSE', 'Lizenz');
define('CO_' . $moduleDirNameUpper . '_SUPPORT', 'Support');
//Sample Data
define('CO_' . $moduleDirNameUpper . '_ADD_SAMPLEDATA', 'Testdaten importieren (ALLE vorhandenen Daten werden gelöscht)');
define('CO_' . $moduleDirNameUpper . '_SAMPLEDATA_SUCCESS', 'Testdaten erfolgreich importiert');
define('CO_' . $moduleDirNameUpper . '_SAVE_SAMPLEDATA', 'Exportiere Tabellen zu YAML');
define('CO_' . $moduleDirNameUpper . '_SHOW_SAMPLE_BUTTON', 'Schaltfläche Testdaten anzeigen?');
define('CO_' . $moduleDirNameUpper . '_SHOW_SAMPLE_BUTTON_DESC', 'Wenn ja, dann wird im Admin-Bereich die Schaltfläche "Testdaten" angezeigt. Nach der Installation ist diese standardmäßig aktiviert.');
define('CO_' . $moduleDirNameUpper . '_EXPORT_SCHEMA', 'Export DB Schema für YAML');
define('CO_' . $moduleDirNameUpper . '_EXPORT_SCHEMA_SUCCESS', 'Export DB Schema zu YAML erfolgreich');
define('CO_' . $moduleDirNameUpper . '_EXPORT_SCHEMA_ERROR', 'ERROR: Export des DB Schema zu YAML fehlgeschlagen');
//letter choice
define('CO_' . $moduleDirNameUpper . '_BROWSETOTOPIC', "<span style='font-weight: bold;'>Einträge alphabetisch anzeigen</span>");
define('CO_' . $moduleDirNameUpper . '_OTHER', 'Andere');
define('CO_' . $moduleDirNameUpper . '_ALL', 'Alle');
// block defines
define('CO_' . $moduleDirNameUpper . '_ACCESSRIGHTS', 'Zugriffsberechtigungen');
define('CO_' . $moduleDirNameUpper . '_ACTION', 'Aktion');
define('CO_' . $moduleDirNameUpper . '_ACTIVERIGHTS', 'Verwaltungsrechte');
define('CO_' . $moduleDirNameUpper . '_BADMIN', 'Blockverwaltung');
define('CO_' . $moduleDirNameUpper . '_BLKDESC', 'Beschreibung');
define('CO_' . $moduleDirNameUpper . '_CBCENTER', 'Mitte Mitte Middle');
define('CO_' . $moduleDirNameUpper . '_CBLEFT', 'Mitte Links');
define('CO_' . $moduleDirNameUpper . '_CBRIGHT', 'Mitte Rechts');
define('CO_' . $moduleDirNameUpper . '_SBLEFT', 'Links');
define('CO_' . $moduleDirNameUpper . '_SBRIGHT', 'Rechts');
define('CO_' . $moduleDirNameUpper . '_SIDE', 'Anordnung');
define('CO_' . $moduleDirNameUpper . '_TITLE', 'Titel');
define('CO_' . $moduleDirNameUpper . '_VISIBLE', 'Sichtbar');
define('CO_' . $moduleDirNameUpper . '_VISIBLEIN', 'Sichtbar in');
define('CO_' . $moduleDirNameUpper . '_WEIGHT', 'Reihung');
define('CO_' . $moduleDirNameUpper . '_PERMISSIONS', 'Berechtigungen');
define('CO_' . $moduleDirNameUpper . '_BLOCKS', 'Blockverwaltung');
define('CO_' . $moduleDirNameUpper . '_BLOCKS_DESC', 'Verwaltung der Blöcke');
define('CO_' . $moduleDirNameUpper . '_BLOCKS_MANAGMENT', 'Verwalten');
define('CO_' . $moduleDirNameUpper . '_BLOCKS_ADDBLOCK', 'Neuen Block hinzufügen');
define('CO_' . $moduleDirNameUpper . '_BLOCKS_EDITBLOCK', 'Block bearbeiten');
define('CO_' . $moduleDirNameUpper . '_BLOCKS_CLONEBLOCK', 'Block klonen');
//myblocksadmin
define('CO_' . $moduleDirNameUpper . '_AGDS', 'Administratorengruppen');
define('CO_' . $moduleDirNameUpper . '_BCACHETIME', 'Cache Time');
//Template Admin
define('CO_' . $moduleDirNameUpper . '_TPLSETS', 'Template Management');
define('CO_' . $moduleDirNameUpper . '_GENERATE', 'Erstellen');
define('CO_' . $moduleDirNameUpper . '_FILENAME', 'Dateiname');
//Menu
define('CO_' . $moduleDirNameUpper . '_ADMENU_MIGRATE', 'Migrieren');
define('CO_' . $moduleDirNameUpper . '_FOLDER_YES', 'Ordner "%s" existiert');
define('CO_' . $moduleDirNameUpper . '_FOLDER_NO', 'Ordner "%s" existiert nicht. Erstellen Sie den entsprechenden Ordner mit den Rechten CHMOD 777.');
