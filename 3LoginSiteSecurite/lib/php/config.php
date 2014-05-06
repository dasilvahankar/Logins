<?php
//fichier de configuration

//demande au php d'activer l'affichage des erreurs
ini_set('display_errors', 'On');
// demande d'affichage de tous les erreurs
ini_set('error_reporting', E_ALL);

//Accés à la base de données
$bd 	= 'mysql';
$serveur= '127.0.0.1';
$bdnom	= 'monsite';
$bdlog	= 'root';
$bdmdp	= '';

//Paramétres de création des cookies
$duree    = time()+7*24*3600; //durée de 7 jours
$chemin   = '/';
$domaine  = $_SERVER['SERVER_NAME'];
$https    = isset($_SERVER['HTTPS']);
$httponly = true;	
?>