<?php
//fichier de configuration

//demande au php d'activer l'affichage des erreurs
ini_set('display_errors', 'On');
// demande d'affichage de tous les erreurs
ini_set('error_reporting', E_ALL);

//acc�s � la base de donn�es
$bd 	= 'mysql';
$serveur= '127.0.0.1';
$bdnom	= 'monsite4';
$bdlog	= 'root';
$bdmdp	= '';

//param�tres de cr�ation des cookies
$duree    = time()+7*24*3600; //dur�e de 7 jours
$chemin   = '/';
$domaine  = $_SERVER['SERVER_NAME'];
$https    = isset($_SERVER['HTTPS']);
$httponly = true;	

//Interdiction d'acc�s
/*
if( isset($_COOKIE['login']) )
{
	if( $_COOKIE['login'] == 'dupuis' )
	{
		echo 'Site en maintenance';
		exit();
		//header('refresh:0;url=http://www.'.$_COOKIE['login'].'.com');		
	}
}
*/
?>