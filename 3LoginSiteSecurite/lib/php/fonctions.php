<?php
//ajout du fichier de configuration
require_once('lib/php/config.php');	

// connectBD( ): fonction de connection à la BD
// ENTREE:
//	> ---
// SORTIE: 
//	> [ $connection ]: si tout se passe bien
//	> [ exit()      ]: s'il y a un problème de connection
function connectBD()
{
	try // essai de connection à la BD
	{
        // connection à la BD avec PDO
		$connection = new PDO($GLOBALS['bd'].':host='.$GLOBALS['serveur'].';dbname='.$GLOBALS['bdnom'],$GLOBALS['bdlog'],$GLOBALS['bdmdp']);
		
		// on oblige mysql a prendre en compte le UTF8
		$connection->exec('SET NAMES utf8');
		
        // renvoi de des données de connection à la BD
		return $connection;		
	}
	catch(Exception $e) // en cas d'erreur la connection ne s'effectue pas 
	{
		echo '<erreur>Erreur [001]: Impossible de se connecter à la BD, veuillez contacter votre administrateur!</erreur><br/>';		
		echo '<erreur>Erreur : '.$e->getMessage().'</erreur><br/>';
		echo '<erreur>N° : '.$e->getCode().'</erreur><br/>';			
        
        //on arrête l'exécution s'il y a du code après
        exit();
	}    
}
// secureData( array ): fonction pour sécuriser les données d'un formulaire
// ENTREE:
//	> [ $data ]: les données à sécuriser
// SORTIE: 
//	> [ $data ]: les données sécurisées
function secureData( $data )
{
	//suppression des espaces extra, tabulations, CR (Carriage Return = caractére d'interligne)
	$data = trim( $data );
	//enlevement des slashes
	$data = stripslashes( $data );
	//transformation des caractéres html en identités html
	$data = htmlspecialchars( $data );

	//renvoi des données nettoyées
	return $data;
}
// login( $connection , $login , $mdp ): fonction pour se connecter au site
// ENTREE:
//	> [ $connection ]: lien de la connection à la BD où se trouvent les données de l'utilisateur
//	> [ $login ]	 : login à vérifier
//	> [ $mdp ]		 : mot de passe à vérifier
// SORTIE: 
//	> [ true ]       : données de connection correctes, l'utilisateur peut se connecter
//	> [ false ]      : données de connection incorrectes, l'utilisateur ne peut pas se connecter
function login( $connection , $login , $mdp )
{
	try
	{
		//exécution de la requête pour récuperer les informations de l'utilisateur qui veut se connecter
		$resultats = $connection->query('SELECT * FROM utilisateurs WHERE login="'.$login.'" AND mdp="'.$mdp.'"');
		
		//affichage de la requête
		//echo $resultats->querystring;

		//on vérifie si on a obtenu des résultats
		//rowCount    = nombre de lignes
		//columnCount = nombre de colonnes
		if( $resultats->rowCount() > 0 )
		{
			$tab = $resultats->fetch(PDO::FETCH_ASSOC);
			//var_dump( $tab );

			//appel de la la fonction qui crée la session utilisateur
			createSession( $tab );
		
			//On libére les résultats de la mémoire
			$resultats->closeCursor();		

			// on ferme la connexion à la BD
			unset( $connection );							
			
			//création des cookies de connection
			if( !isset($_COOKIE['login']) )
			{	
				//		
				// setcookie( name, value, expire, path, domain, secure, httponly)
				//
				// name		: nom du cookie
				// value	: valeur du cookie
				//
				setcookie('login',$_SESSION['login'],$GLOBALS['duree'],$GLOBALS['chemin'],$GLOBALS['domaine'],$GLOBALS['https'],$GLOBALS['httponly']); 
				setcookie('mdp',$_SESSION['mdp'],$GLOBALS['duree'],$GLOBALS['chemin'],$GLOBALS['domaine'],$GLOBALS['https'],$GLOBALS['httponly']); 
			}
			return true;
		}
		else
		{
			return false;
		}	
	}
	catch(PDOException $e) // en cas d'erreur
	{
		// on affiche un message d'erreur ainsi que les erreurs
		echo '<erreur>Erreur [002]: Erreur lors de la requête, veuillez contacter votre administrateur!</erreur>';		
		echo '<erreur>Erreur : '.$e->getMessage().'</erreur><br/>';
		echo '<erreur>N° : '.$e->getCode().'</erreur><br/>';
		
		//on arrête l'exécution s'il y a du code après
		exit();
	} 	
}						
// createSession( array ): fonction qui crée les variables de session d'un utilisateur
// ENTREE:
//	> [ $data ]: un tableau de données à mettre sous session
// SORTIE: 
//	> ---
function createSession( $data )
{
	$_SESSION['id']     		= $data['id'];
	$_SESSION['nom']    		= $data['nom'];
	$_SESSION['prenom'] 		= $data['prenom'];
	$_SESSION['sexe']  			= $data['sexe'];							
	$_SESSION['datenaissance']  = $data['datenaissance'];
	$_SESSION['pays']  			= $data['pays'];
	$_SESSION['dateinscription']= $data['dateinscription'];
	$_SESSION['email']  		= $data['email'];
	$_SESSION['login'] 			= $data['login'];
	$_SESSION['mdp']  			= $data['mdp'];
	$_SESSION['admin'] 			= $data['admin'];
}
?>