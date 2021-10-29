<?php

declare(strict_types=1);
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
 * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Gregory Mage (Aka Mage)
 */
// index.php
define('_AM_TDMDOWNLOADS_INDEX_BROKEN', 'Il y a %s rapports de fichiers brisés');
define('_AM_TDMDOWNLOADS_INDEX_CATEGORIES', 'Il existe %s catégories');
define('_AM_TDMDOWNLOADS_INDEX_DOWNLOADS', 'Il y a %s fichiers dans notre base de données');
define('_AM_TDMDOWNLOADS_INDEX_DOWNLOADSWAITING', "Il y a %s téléchargements en attente d'approbation");
define('_AM_TDMDOWNLOADS_INDEX_MODIFIED', "Il y a %s demandes de modification d'informations dans les téléchargements");
//category.php
define('_AM_TDMDOWNLOADS_CAT_NEW', 'Nouvelle catégorie');
define('_AM_TDMDOWNLOADS_CAT_LIST', 'Liste des catégories');
define('_AM_TDMDOWNLOADS_DELDOWNLOADS', 'avec les téléchargements suivants :');
define('_AM_TDMDOWNLOADS_DELSOUSCAT', 'Les catégories suivantes seront complètement supprimées  :');
//downloads.php
define('_AM_TDMDOWNLOADS_DOWNLOADS_LISTE', 'Liste des téléchargements');
define('_AM_TDMDOWNLOADS_DOWNLOADS_NEW', 'Nouveau téléchargement');
define('_AM_TDMDOWNLOADS_DOWNLOADS_SEARCH', 'Recherche');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTESTOTAL', 'Total des votes');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTESANONYME', 'Votes des anonymes');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTESUSER', 'Votes des utilisateurs');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTE_USER', 'Utilisateurs');
define('_AM_TDMDOWNLOADS_DOWNLOADS_VOTE_IP', 'Adresse IP');
define('_AM_TDMDOWNLOADS_DOWNLOADS_WAIT', 'En attente de validation');
//broken.php
define('_AM_TDMDOWNLOADS_BROKEN_SENDER', "Signaler à l'auteur");
define('_AM_TDMDOWNLOADS_BROKEN_SURDEL', 'Êtes-vous sûr de que vouloir supprimer ce rapport ?');
//modified.php
define('_AM_TDMDOWNLOADS_MODIFIED_MOD', 'Version modifiée');
define('_AM_TDMDOWNLOADS_MODIFIED_ORIGINAL', 'Version originale');
define('_AM_TDMDOWNLOADS_MODIFIED_SURDEL', 'Êtes-vous sûr de vouloir supprimer cette demande de modification de téléchargement ?');
//field.php
define('_AM_TDMDOWNLOADS_DELDATA', 'Avec les données suivantes :');
define('_AM_TDMDOWNLOADS_FIELD_LIST', 'Liste de champs');
define('_AM_TDMDOWNLOADS_FIELD_NEW', 'Nouveaux champs');
//about.php
define('_AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION', 'Protection des fichiers');
define('_AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION_INFO1', 'Pour protéger vos fichiers contre les téléchargements indésirables (par rapport aux autorisations), vous devez créer un fichier « .htaccess » dans le dossier :');
define('_AM_TDMDOWNLOADS_ABOUT_FILEPROTECTION_INFO2', 'Avec le contenu suivant :');
//permissions.php
define('_AM_TDMDOWNLOADS_PERMISSIONS_4', 'Soumettre un téléchargement');
define('_AM_TDMDOWNLOADS_PERMISSIONS_8', 'Soumettre une modification');
define('_AM_TDMDOWNLOADS_PERMISSIONS_16', 'noter un téléchargement');
define('_AM_TDMDOWNLOADS_PERMISSIONS_32', 'Télécharger des fichiers');
define('_AM_TDMDOWNLOADS_PERMISSIONS_64', 'Approuver automatiquement les fichiers soumis');
define('_AM_TDMDOWNLOADS_PERM_AUTRES', 'Autres autorisations');
define('_AM_TDMDOWNLOADS_PERM_AUTRES_DSC', 'Sélectionnez les groupes qui peuvent :');
define('_AM_TDMDOWNLOADS_PERM_DOWNLOAD', 'Autorisations de téléchargements');
define('_AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC', 'Sélectionnez les groupes qui peuvent télécharger dans les catégories');
define('_AM_TDMDOWNLOADS_PERM_DOWNLOAD_DSC2', 'Sélectionnez les groupes qui peuvent télécharger des fichiers');
define('_AM_TDMDOWNLOADS_PERM_SUBMIT', "Soumettre à l'autorisation");
define('_AM_TDMDOWNLOADS_PERM_SUBMIT_DSC', 'Choisissez les groupes qui peuvent soumettre des fichiers à des catégories');
define('_AM_TDMDOWNLOADS_PERM_VIEW', 'Voir les autorisations');
define('_AM_TDMDOWNLOADS_PERM_VIEW_DSC', 'Choisissez les groupes qui peuvent voir les fichiers dans les catégories');
// Import.php
define('_AM_TDMDOWNLOADS_IMPORT1', 'Importation');
define('_AM_TDMDOWNLOADS_IMPORT_CAT_IMP', 'Catégories: « %s » importées');
define('_AM_TDMDOWNLOADS_IMPORT_CONF_MYDOWNLOADS', 'Êtes-vous sûr de vouloir importer les données du module Mydownloads vers TDMDownloads ?');
define('_AM_TDMDOWNLOADS_IMPORT_CONF_WFDOWNLOADS', 'Êtes-vous sûr de vouloir importer les données du module WF-Downloads vers TDMDownloads ?');
define('_AM_TDMDOWNLOADS_IMPORT_DONT_DOWNLOADS', "Il n'y a aucun fichier à importer");
define('_AM_TDMDOWNLOADS_IMPORT_DONT_TOPIC', "Il n'y a aucun fichier à importer");
define('_AM_TDMDOWNLOADS_IMPORT_DOWNLOADS', 'Importation de fichiers');
define('_AM_TDMDOWNLOADS_IMPORT_DOWNLOADS_IMP', '« %s » fichiers importés ;');
define('_AM_TDMDOWNLOADS_IMPORT_ERREUR', "Sélectionnez le répertoire de téléchargement (le chemin d'accès)");
define('_AM_TDMDOWNLOADS_IMPORT_ERROR_DATA', "Erreur lors de l'importation de données");
define('_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS', 'Importation à partir de Mydownloads');
define('_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS_PATH', "Sélectionnez le répertoire de téléchargement (le chemin d'accès) pour les captures d'écran de Mydownloads");
define('_AM_TDMDOWNLOADS_IMPORT_MYDOWNLOADS_URL', "Choisissez l'URL correspondante pour les captures d'écran de Mydownloads");
define('_AM_TDMDOWNLOADS_IMPORT_NB_CAT', "Il y a %s catégories d'importation");
define('_AM_TDMDOWNLOADS_IMPORT_NB_DOWNLOADS', 'Il y a %s fichiers à importer');
define('_AM_TDMDOWNLOADS_IMPORT_NUMBER', 'Données à importer');
define('_AM_TDMDOWNLOADS_IMPORT_OK', 'Importation effectuée avec succès !');
define('_AM_TDMDOWNLOADS_IMPORT_VOTE_IMP', '« %s » votes importés ;');
define(
    '_AM_TDMDOWNLOADS_IMPORT_WARNING',
    "<span style='color:#FF0000; font-size:16px; font-weight:bold'>Attention !</span><br><br>l'importation supprimera toutes les données de TDMDownloads. Il est fortement recommandé de faire une sauvegarde de toutes vos données d'abord, ainsi que de votre site internet.<br><br>TDM n'est pas responsable si vous perdez vos données. Malheureusement, les captures d'écran ne peuvent pas être copiées."
);
define('_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS', 'Importation de WF Downloads (Version 3.23 RC5 uniquement)');
define('_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS_CATIMG', "Sélectionnez le répertoire Upload (le chemin d'accès) pour les images des catégories de WF-Downloads");
define('_AM_TDMDOWNLOADS_IMPORT_WFDOWNLOADS_SHOTS', "Sélectionnez le répertoire Upload (le chemin d'accès) pour les captures d'écran de WF-Downloads");
//Pour les options de filtre
define('_AM_TDMDOWNLOADS_ORDER', ' ordre : ');
define('_AM_TDMDOWNLOADS_TRIPAR', 'triés par : ');
//Formulaire et tableau
define('_AM_TDMDOWNLOADS_FORMADD', 'Ajouter');
define('_AM_TDMDOWNLOADS_FORMACTION', 'Action');
define('_AM_TDMDOWNLOADS_FORMAFFICHE', 'Afficher le champ ?');
define('_AM_TDMDOWNLOADS_FORMAFFICHESEARCH', 'Champ de recherche ?');
define('_AM_TDMDOWNLOADS_FORMAPPROVE', 'Approuver');
define('_AM_TDMDOWNLOADS_FORMCAT', 'Catégorie');
define('_AM_TDMDOWNLOADS_FORMCOMMENTS', 'Nombre de commentaires');
define('_AM_TDMDOWNLOADS_FORMDATE', 'Date');
define('_AM_TDMDOWNLOADS_FORMDATEUPDATE', 'Mise à jour de la date');
define('_AM_TDMDOWNLOADS_FORMDATEUPDATE_NO', 'Non');
define('_AM_TDMDOWNLOADS_FORMDATEUPDATE_YES', 'Oui-->');
define('_AM_TDMDOWNLOADS_FORMDEL', 'Supprimer');
define('_AM_TDMDOWNLOADS_FORMDISPLAY', 'Afficher');
define('_AM_TDMDOWNLOADS_FORMEDIT', 'Editer');
define('_AM_TDMDOWNLOADS_FORMFILE', 'Fichier');
define('_AM_TDMDOWNLOADS_FORMHITS', 'Visites');
define('_AM_TDMDOWNLOADS_FORMHOMEPAGE', "Page d'accueil");
define('_AM_TDMDOWNLOADS_FORMLOCK', 'Désactiver le téléchargement');
define('_AM_TDMDOWNLOADS_FORMIGNORE', 'Ignorer');
define('_AM_TDMDOWNLOADS_FORMINCAT', 'Dans la catégorie');
define('_AM_TDMDOWNLOADS_FORMIMAGE', 'Image');
define('_AM_TDMDOWNLOADS_FORMIMG', 'Logo');
define('_AM_TDMDOWNLOADS_FORMPAYPAL', 'Adresse de PayPal pour les dons');
define('_AM_TDMDOWNLOADS_FORMPATH', 'Les fichiers existent dans : %s');
define('_AM_TDMDOWNLOADS_FORMPERMDOWNLOAD', 'Sélectionnez les groupes qui peuvent télécharger ce fichier');
define('_AM_TDMDOWNLOADS_FORMPLATFORM', 'Plateforme :');
define('_AM_TDMDOWNLOADS_FORMPOSTER', 'Posté par ');
define('_AM_TDMDOWNLOADS_FORMRATING', 'Note');
define('_AM_TDMDOWNLOADS_FORMSIZE', 'Taille du fichier');
define('_AM_TDMDOWNLOADS_FORMSIZE_WHEN_SUBMIT', "Taille du fichier<br><span style='font-size: small;'>Pour utiliser le système automatique de calcul de la taille du fichier, laissez ce champ vide.</span>");
define('_AM_TDMDOWNLOADS_FORMSTATUS', 'État du téléchargement');
define('_AM_TDMDOWNLOADS_FORMSTATUS_OK', 'Approuvé');
define('_AM_TDMDOWNLOADS_FORMSUBMITTER', 'Posté par');
define('_AM_TDMDOWNLOADS_FORMSUREDEL', "Êtes-vous sûr de vouloir supprimer : <strong><span style='color:#ff0000'> %s </span></strong>");
define('_AM_TDMDOWNLOADS_FORMTEXT', 'Description');
define('_AM_TDMDOWNLOADS_FORMTEXTDOWNLOADS', "Description :<br><br>utilisez le séparateur « <strong>[pagebreak]</strong> » pour définir la taille d'une courte description. <br>Cela permet de réduire la taille du texte dans la page d'accueil du module et des catégories.");
define('_AM_TDMDOWNLOADS_FORMTITLE', 'Titre');
define('_AM_TDMDOWNLOADS_FORMUPLOAD', 'Télécharger');
define('_AM_TDMDOWNLOADS_FORMURL', 'Lien du téléchargement');
define('_AM_TDMDOWNLOADS_FORMVALID', 'Activer le téléchargement');
define('_AM_TDMDOWNLOADS_FORMVERSION', 'Version');
define('_AM_TDMDOWNLOADS_FORMVOTE', 'Votes');
define('_AM_TDMDOWNLOADS_FORMWEIGHT', 'Poids');
define('_AM_TDMDOWNLOADS_FORMWITHFILE', 'Avec le fichier : ');
//Message d'erreur
define('_AM_TDMDOWNLOADS_ERREUR_CAT', 'Vous ne pouvez pas utiliser cette catégorie (en boucle sur elle-même)');
define('_AM_TDMDOWNLOADS_ERREUR_NOBMODDOWNLOADS', "Il n'y a aucune modification des téléchargements");
define('_AM_TDMDOWNLOADS_ERREUR_NOBROKENDOWNLOADS', "Il n'y a aucun liens de téléchargements brisés");
define('_AM_TDMDOWNLOADS_ERREUR_NOCAT', 'Vous devez choisir une catégorie !');
define('_AM_TDMDOWNLOADS_ERREUR_NODESCRIPTION', 'Vous devez écrire une description');
define('_AM_TDMDOWNLOADS_ERREUR_NODOWNLOADS', "Il n'y a aucun fichier à télécharger");
define('_AM_TDMDOWNLOADS_ERREUR_NODOWNLOADSWAITING', "Il n'y a téléchargements en attente d'approbation");
define('_AM_TDMDOWNLOADS_ERREUR_SIZE', "<span style='color: #FF0000; font-weight: bold;'>Le système n'a pas réussi à déterminer la taille du fichier, il est nécessaire de le faire manuellement.</span>");
//Message de redirection
define('_AM_TDMDOWNLOADS_REDIRECT_DELOK', 'Supprimé avec succès ');
define('_AM_TDMDOWNLOADS_REDIRECT_NOCAT', "Vous devez d'abord créer une catégorie");
define('_AM_TDMDOWNLOADS_REDIRECT_NODELFIELD', 'Vous ne pouvez pas supprimer ce champ (champ de base)');
define('_AM_TDMDOWNLOADS_REDIRECT_SAVE', 'Enregistré avec succès');
define('_AM_TDMDOWNLOADS_REDIRECT_DEACTIVATED', 'Désactivé avec succès');
define('_AM_TDMDOWNLOADS_NOPERMSSET', "Impossible de définir l'autorisation : aucune catégorie n'a encore été créée  ! Veuillez d'abord créer une catégorie.");
//Bytes sizes
define('_AM_TDMDOWNLOADS_BYTES', 'Octets');
define('_AM_TDMDOWNLOADS_KBYTES', 'Ko');
define('_AM_TDMDOWNLOADS_MBYTES', 'Mo');
define('_AM_TDMDOWNLOADS_GBYTES', 'Go');
define('_AM_TDMDOWNLOADS_TBYTES', 'To');
// ---------------- Admin Others ----------------
define('_AM_TDMDOWNLOADS_MAINTAINEDBY', 'maintained by');
//2.00
//directories
define('_AM_TDMDOWNLOADS_AVAILABLE', "<span style='color : #008000;'>Disponible. </span>");
define('_AM_TDMDOWNLOADS_NOTAVAILABLE', "<span style='color : #ff0000;'>n'est pas disponible. </span>");
define('_AM_TDMDOWNLOADS_NOTWRITABLE', "<span style='color : #ff0000;'>" . ' devrait avoir la permission ( %1$d ), mais il a ( %2$d )' . '</span>');
define('_AM_TDMDOWNLOADS_CREATETHEDIR', 'Create it');
define('_AM_TDMDOWNLOADS_SETMPERM', 'Définir la permission');
define('_AM_TDMDOWNLOADS_DIRCREATED', 'Le répertoire a été créé');
define('_AM_TDMDOWNLOADS_DIRNOTCREATED', 'Le répertoire ne peut pas être créé');
define('_AM_TDMDOWNLOADS_PERMSET', 'La permission a été définie');
define('_AM_TDMDOWNLOADS_PERMNOTSET', 'La permission ne peut pas être définie');
define('_AM_TDMDOWNLOADS_UPGRADEFAILED0', "La mise à jour a échoué - impossible de renommer le champ '%s'");
define('_AM_TDMDOWNLOADS_UPGRADEFAILED1', "Échec de la mise à jour - impossible d'ajouter de nouveaux champs");
define('_AM_TDMDOWNLOADS_UPGRADEFAILED2', "Échec de la mise à jour - impossible de renommer la table '%s'");
define('_AM_TDMDOWNLOADS_ERROR_COLUMN', 'Impossible de créer la colonne dans la base de données : %s');
define('_AM_TDMDOWNLOADS_ERROR_BAD_XOOPS', 'Ce module nécessite XOOPS %s+ (%s installé)');
define('_AM_TDMDOWNLOADS_ERROR_BAD_PHP', 'Ce module nécessite la version PHP %s+ (%s installée)');
define('_AM_TDMDOWNLOADS_ERROR_TAG_REMOVAL', 'Impossible de supprimer les balises du module de balises');
define('_AM_TDMDOWNLOADS_FOLDERS_DELETED_OK', 'Les dossiers de téléchargement ont été supprimés');
// Error Msgs
define('_AM_TDMDOWNLOADS_ERROR_BAD_DEL_PATH', 'Impossible de supprimer le répertoire %s');
define('_AM_TDMDOWNLOADS_ERROR_BAD_REMOVE', 'Impossible de supprimer %s');
define('_AM_TDMDOWNLOADS_ERROR_NO_PLUGIN', 'Impossible de charger le plug-in');
define('_AM_TDMDOWNLOADS_NUMBYTES', '%s octets');
