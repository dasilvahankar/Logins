<?php
//ajout du fichier de configuration
require_once('config.php');	

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
			//mise à jour de l'adresse IP de l'utilisateur
			$connection->beginTransaction();
			$connection->exec('UPDATE utilisateurs SET ip="'.$_SERVER['REMOTE_ADDR'].'" WHERE login="'.$login.'" AND mdp="'.$mdp.'"');
			$connection->commit();

			//!!! 2éme requête afin de récupérer les modifications du UPDATE précedent
			$resultats = $connection->query('SELECT * FROM utilisateurs WHERE login="'.$login.'" AND mdp="'.$mdp.'"');

			//mise des résultats dans un tableau associatif (FETCH_ASSOC)
			$tab = $resultats->fetch(PDO::FETCH_ASSOC);
			//var_dump( $tab );

			//appel de la la fonction qui crée la session utilisateur
			createSession( $tab );
		
			//on libére les résultats de la mémoire
			$resultats->closeCursor();		
			
			//on ferme la connexion à la BD
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
		echo '<erreur>Erreur [0022]: Erreur lors de la requête, veuillez contacter votre administrateur!</erreur>';		
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
//	> variables de SESSION (voir au dessous)
function createSession( $tabData )
{
	$_SESSION['id']     		= $tabData['id'];
	$_SESSION['nom']    		= $tabData['nom'];
	$_SESSION['prenom'] 		= $tabData['prenom'];
	$_SESSION['sexe']  			= $tabData['sexe'];							
	$_SESSION['datenaissance']  = $tabData['datenaissance'];
	$_SESSION['pays']  			= $tabData['pays'];
	$_SESSION['dateinscription']= $tabData['dateinscription'];
	$_SESSION['email']  		= $tabData['email'];
	$_SESSION['login'] 			= $tabData['login'];
	$_SESSION['mdp']  			= $tabData['mdp'];
	$_SESSION['admin'] 			= $tabData['admin'];
	$_SESSION['ip']				= $tabData['ip'];
}
// valider( $md5 ): fonction qui valide les données d'un formulaire
// ENTREE:
//	> [ $md5 ]: donnée à valider
// SORTIE: 
//	> [ true  ]: si la donnée est valide
//	> [ false ]: si la donnée est incorrecte
function valider_md5( $md5 )
{
	//si le champ est vide
	if( empty($md5) )
	{
		return false;
	}
	else
	{
		return true;
	}
}
// valider_login( $login ): fonction qui valide le champ login
// ENTREE:
//	> [ $login ]: login à valider
// SORTIE: 
//	> variables de SESSION (voir au dessous)
function valider_login( $login )
{
	//on sauvegarde les données du champ dans le cas où il y a une erreur et qu'il faut revenir en arriére
	$_SESSION['form_login'] = $login;	
	
	//LOGIN: 4 à 10 caractéres alphabétiques
    if( !preg_match('/^[a-zA-Z]{4,10}$/', $login) )
	{
		$_SESSION['erreurform']       = true;
		$_SESSION['erreurform_login'] = 'login invalide';
		return false;		
	}
	else
	{
		$_SESSION['erreurform_login'] = '';
		return true;		
	}
}
// valider_mdp( $mdp ): fonction qui valide le champ du mot de passe
// ENTREE:
//	> [ $mdp ]: mot de passe à valider
// SORTIE: 
//	> variables de SESSION (voir au dessous)
function valider_mdp( $mdp )
{
	//on sauvegarde les données du champ dans le cas où il y a une erreur et qu'il faut revenir en arriére
	$_SESSION['form_mdp'] = $mdp;	
	
	//MDP: 3 à 8 caractéres alphabétiques
    if( !preg_match('/^[a-zA-Z]{3,8}$/', $mdp) )
	{
		$_SESSION['erreurform']     = true;
		$_SESSION['erreurform_mdp'] = 'mot de passe invalide';	
		return false;
	}
	else
	{
		$_SESSION['erreurform_mdp'] = '';
		return true;
	}
}
// valider_message( $message ): fonction qui valide le message envoyé par l'utilisateur
// ENTREE:
//	> [ $message ]: message à valider
// SORTIE: 
//	> [ true  ]: si la donnée est valide
//	> [ false ]: si la donnée est invalide
//	> variables de SESSION (voir au dessous)
function valider_message( $message )
{
	//MESSAGE: 1 à 50 caractéres alphabétiques et ne doit pas commencer par espace
    if( !preg_match('/^[^ ][ -¿]{1,50}$/', $message) )
	{
		$_SESSION['erreurform_msg'] = 'Vous êtes muet?';	
		return false;
	}
	else
	{
		$_SESSION['erreurform_msg'] = '';
		return true;
	}
}
// valider_nom( $nom ): fonction qui valide le nom
// ENTREE:
//	> [ $nom ]: nom à valider
// SORTIE: 
//	> variables de SESSION (voir au dessous)
function valider_nom( $nom )
{
	//on sauvegarde les données du champ dans le cas où il y a une erreur et qu'il faut revenir en arriére	
	$_SESSION['form_nom'] = $nom;	
	
	//NOM: 2 à 20 caractéres et ne doit pas commencer par espace
    if( !preg_match("/^[^ -][a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð '-]{2,20}$/", $nom) )
	{
		$_SESSION['erreurform']     = true;
		$_SESSION['erreurform_nom'] = 'nom invalide';
		return false;		
	}
	else
	{
		$_SESSION['erreurform_nom'] = '';
		return true;		
	}
}
// valider_prenom( $prenom ): fonction qui valide le prenom
// ENTREE:
//	> [ $prenom ]: prenom à valider
// SORTIE: 
//	> variables de SESSION (voir au dessous)
function valider_prenom( $prenom )
{
	//on sauvegarde les données du champ dans le cas où il y a une erreur et qu'il faut revenir en arriére	
	$_SESSION['form_prenom'] = $prenom;	
	
	//PRENOM: 2 à 20 caractéres et ne doit pas commencer par espace
    if( !preg_match("/^[^ -][a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð '-]{2,20}$/", $prenom) )
	{
		$_SESSION['erreurform']		   = true;
		$_SESSION['erreurform_prenom'] = 'prenom invalide';
		return false;		
	}
	else
	{
		$_SESSION['erreurform_prenom'] = '';
		return true;		
	}
}
// valider_prenom( $sexe ): fonction qui valide le prenom
// ENTREE:
//	> [ $sexe ]: prenom à valider
// SORTIE: 
//	> variables de SESSION (voir au dessous)
function valider_sexe( $sexe )
{
	//on sauvegarde les données du champ dans le cas où il y a une erreur et qu'il faut revenir en arriére	
	$_SESSION['form_sexe'] = $sexe;	
	
	//SEXE: choix parmis F = féminin M = masculin A = autre
    if( !preg_match("/^[FMA]{1}$/", $sexe) )
	{
		$_SESSION['erreurform']      = true;
		$_SESSION['erreurform_sexe'] = 'sexe invalide';
		return false;		
	}
	else
	{
		$_SESSION['erreurform_sexe'] = '';
		return true;		
	}
}
// valider_pays( $pays ): fonction qui valide le pays
// ENTREE:
//	> [ $pays ]: pays à valider
// SORTIE: 
//	> variables de SESSION (voir au dessous)
function valider_pays( $pays )
{
	//on sauvegarde les données du champ dans le cas où il y a une erreur et qu'il faut revenir en arriére	
	$_SESSION['form_pays'] = $pays;		
	
	//création du tableau des pays
	$tabPays = creer_pays();
	//var_dump( $pays );
	
	//vérification si le code pays du formulaire est présent dans notre tableau
	if( !array_key_exists( $pays,$tabPays ) )
	{
		$_SESSION['erreurform']      = true;
		$_SESSION['erreurform_pays'] = 'pays invalide';	
		return false;		
	}
	else
	{
		$_SESSION['erreurform_pays'] = '';	
		return true;		
	}
}
// valider_datenaissance( $datenaissance ): fonction qui valide la date de naissance
// ENTREE:
//	> [ $datenaissance ]: date de naissance à valider
// SORTIE: 
//	> variables de SESSION (voir au dessous)
function valider_datenaissance( $datenaissance )
{
	//on sauvegarde les données du champ dans le cas où il y a une erreur et qu'il faut revenir en arriére
	$_SESSION['form_datenaissance'] = $datenaissance;	
	
	// /!\ ATTENTION à la DATE de naissance
	// FORMULAIRE
	// elle se trouve au format 'd-m-Y' exemple: 29-04-2014
	//MYSQL
	// elle DOIT être au format 'Y-m-d' exemple: 2014-04-29
	//COMPARER
	// elle doit être au format de MYSQL pour pouvoir comparer
	// 28-04-1996 < 29-04-1914 = va retourner vrai alors que 28-04-1996 est une date plus grande
	
	$format = 'd-m-Y';
	$date = DateTime::createFromFormat($format, $datenaissance);

	//on vérifie si la date du formulaire existe
	if( !($date && ($date->format($format) == $datenaissance)) )
	{
		$_SESSION['erreurform']        			= true;
		$_SESSION['erreurform_datenaissance']   = 'date inconnue';	
	}
    else
	{
		//création de la date du jour
		//$maintenant = new DateTime('NOW');
		//echo $maintenant->format('d-m-Y');
		
		//configuration des régles de validation: maximum 100 ans et être majeur
		//date d'il y a 100 ans
		$cent = new DateTime('now');
		$cent->modify('-100 year');
		//date d'il y a 18 ans
		$dixhuit = new DateTime('now');
		$dixhuit->modify('-18 year');

		//si âge < 18 ou âge > 100 ans
		if( ($date<$cent) || ($dixhuit<$date)  )
		{
			$_SESSION['erreurform']					= true;
			$_SESSION['erreurform_datenaissance']   = 'date invalide';	
			//$_SESSION['erreurform_datenaissance']   = $cent.' > '.$datenaissance.' > '.$dixhuit;	
			return false;			
		}
		else//âge validée
		{
			$_SESSION['erreurform_datenaissance']   = '';	
			return true;			
		}		
	}
}
// valider_email( $email ): fonction qui valide l'email
// ENTREE:
//	> [ $email ]: email à valider
// SORTIE: 
//	> variables de SESSION (voir au dessous)
function valider_email( $email )  
{  
	//on sauvegarde les données du champ dans le cas où il y a une erreur et qu'il faut revenir en arriére	
	$_SESSION['form_email'] = $email;	
	
	if( !preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9])+([a-zA-Z0-9\._-])*\.([a-zA-Z])+$/', $email))  
	{  
		$_SESSION['erreurform']       = true;	
		$_SESSION['erreurform_email'] = ' ex: moi@msn.be';	
		return false;		
	}
	else
	{
		$_SESSION['erreurform_email'] = '';	
		return true;
	}	
} 
// verifier_login( $login ): fonction qui vérifie l'unicité du login
// ENTREE:
//	> [ $login ]: login à vérifier
// SORTIE: 
//	> [ true  ]: si login n'existe pas dans la table
//	> [ false ]: si login existe pas dans la table
//	> variables de SESSION (voir au dessous)
function verifier_login( $login )  
{  
	//on sauvegarde les données du champ dans le cas où il y a une erreur et qu'il faut revenir en arriére	
	$_SESSION['form_login'] = $login;
	
	//connection à la BD
	$connection = ConnectBD();
		
	//On vérifie si on est bien connecté
	if( $connection )
	{
		try
		{
			//exécution de la requête pour vérifier si le login existe déjà dans la table utilisateurs
			$resultats = $connection->query('SELECT login FROM utilisateurs WHERE login="'.$login.'"');

			//on vérifie si on a obtenu des résultats, si oui le login existe déjà
			if( $resultats->rowCount() > 0 )
			{
				//on déclenche l'erreur
				$_SESSION['erreurform'] = true;
				$_SESSION['erreurform_login'] = 'login existe déjà';
				return false;
			}
			else
			{
				$_SESSION['erreurform_login'] = '';		
				return true;			
			}	
		}
		catch(PDOException $e) // en cas d'erreur
		{
			// on affiche un message d'erreur ainsi que les erreurs
			echo '<erreur>Erreur [0023]: Erreur lors de la requête, veuillez contacter votre administrateur!</erreur>';		
			echo '<erreur>Erreur : '.$e->getMessage().'</erreur><br/>';
			echo '<erreur>N° : '.$e->getCode().'</erreur><br/>';
			
			//on arrête l'exécution s'il y a du code après
			exit();
		} 	
	}
} 
// creer_pays( ): fonction qui crée la liste de pays
// ENTREE:
//	> ---
// SORTIE: 
//	> [ $pays  ]: un tableau avec la liste des pays
function creer_pays()
{
	$tabPays = array(
	 'AF' => array('FR' => 'Afghanistan', 'EN' => 'Afghanistan'),
	 'ZA' => array('FR' => 'Afrique du Sud', 'EN' => 'South Africa'),
	 'AL' => array('FR' => 'Albanie', 'EN' => 'Albania'),
	 'DZ' => array('FR' => 'Algérie', 'EN' => 'Algeria'),
	 'DE' => array('FR' => 'Allemagne', 'EN' => 'Germany'),
	 'AD' => array('FR' => 'Andorre', 'EN' => 'Andorra'),
	 'AO' => array('FR' => 'Angola', 'EN' => 'Angola'),
	 'AI' => array('FR' => 'Anguilla', 'EN' => 'Anguilla'),
	 'AQ' => array('FR' => 'Antarctique', 'EN' => 'Antarctica'),
	 'AG' => array('FR' => 'Antigua-et-Barbuda', 'EN' => 'Antigua & Barbuda'),
	 'AN' => array('FR' => 'Antilles néerlandaises', 'EN' => 'Netherlands Antilles'),
	 'SA' => array('FR' => 'Arabie saoudite', 'EN' => 'Saudi Arabia'),
	 'AR' => array('FR' => 'Argentine', 'EN' => 'Argentina'),
	 'AM' => array('FR' => 'Arménie', 'EN' => 'Armenia'),
	 'AW' => array('FR' => 'Aruba', 'EN' => 'Aruba'),
	 'AU' => array('FR' => 'Australie', 'EN' => 'Australia'),
	 'AT' => array('FR' => 'Autriche', 'EN' => 'Austria'),
	 'AZ' => array('FR' => 'Azerbaïdjan', 'EN' => 'Azerbaijan'),
	 'BJ' => array('FR' => 'Bénin', 'EN' => 'Benin'),
	 'BS' => array('FR' => 'Bahamas', 'EN' => 'Bahamas, The'),
	 'BH' => array('FR' => 'Bahreïn', 'EN' => 'Bahrain'),
	 'BD' => array('FR' => 'Bangladesh', 'EN' => 'Bangladesh'),
	 'BB' => array('FR' => 'Barbade', 'EN' => 'Barbados'),
	 'PW' => array('FR' => 'Belau', 'EN' => 'Palau'),
	 'BE' => array('FR' => 'Belgique', 'EN' => 'Belgium'),
	 'BZ' => array('FR' => 'Belize', 'EN' => 'Belize'),
	 'BM' => array('FR' => 'Bermudes', 'EN' => 'Bermuda'),
	 'BT' => array('FR' => 'Bhoutan', 'EN' => 'Bhutan'),
	 'BY' => array('FR' => 'Biélorussie', 'EN' => 'Belarus'),
	 'MM' => array('FR' => 'Birmanie', 'EN' => 'Myanmar (ex-Burma)'),
	 'BO' => array('FR' => 'Bolivie', 'EN' => 'Bolivia'),
	 'BA' => array('FR' => 'Bosnie-Herzégovine', 'EN' => 'Bosnia and Herzegovina'),
	 'BW' => array('FR' => 'Botswana', 'EN' => 'Botswana'),
	 'BR' => array('FR' => 'Brésil', 'EN' => 'Brazil'),
	 'BN' => array('FR' => 'Brunei', 'EN' => 'Brunei Darussalam'),
	 'BG' => array('FR' => 'Bulgarie', 'EN' => 'Bulgaria'),
	 'BF' => array('FR' => 'Burkina Faso', 'EN' => 'Burkina Faso'),
	 'BI' => array('FR' => 'Burundi', 'EN' => 'Burundi'),
	 'CI' => array('FR' => 'Côte d\'Ivoire', 'EN' => 'Ivory Coast (see Cote d\'Ivoire)'),
	 'KH' => array('FR' => 'Cambodge', 'EN' => 'Cambodia'),
	 'CM' => array('FR' => 'Cameroun', 'EN' => 'Cameroon'),
	 'CA' => array('FR' => 'Canada', 'EN' => 'Canada'),
	 'CV' => array('FR' => 'Cap-Vert', 'EN' => 'Cape Verde'),
	 'CL' => array('FR' => 'Chili', 'EN' => 'Chile'),
	 'CN' => array('FR' => 'Chine', 'EN' => 'China'),
	 'CY' => array('FR' => 'Chypre', 'EN' => 'Cyprus'),
	 'CO' => array('FR' => 'Colombie', 'EN' => 'Colombia'),
	 'KM' => array('FR' => 'Comores', 'EN' => 'Comoros'),
	 'CG' => array('FR' => 'Congo', 'EN' => 'Congo'),
	 'KP' => array('FR' => 'Corée du Nord', 'EN' => 'Korea, Demo. People\'s Rep. of'),
	 'KR' => array('FR' => 'Corée du Sud', 'EN' => 'Korea, (South) Republic of'),
	 'CR' => array('FR' => 'Costa Rica', 'EN' => 'Costa Rica'),
	 'HR' => array('FR' => 'Croatie', 'EN' => 'Croatia'),
	 'CU' => array('FR' => 'Cuba', 'EN' => 'Cuba'),
	 'DK' => array('FR' => 'Danemark', 'EN' => 'Denmark'),
	 'DJ' => array('FR' => 'Djibouti', 'EN' => 'Djibouti'),
	 'DM' => array('FR' => 'Dominique', 'EN' => 'Dominica'),
	 'EG' => array('FR' => 'Égypte', 'EN' => 'Egypt'),
	 'AE' => array('FR' => 'Émirats arabes unis', 'EN' => 'United Arab Emirates'),
	 'EC' => array('FR' => 'Équateur', 'EN' => 'Ecuador'),
	 'ER' => array('FR' => 'Érythrée', 'EN' => 'Eritrea'),
	 'ES' => array('FR' => 'Espagne', 'EN' => 'Spain'),
	 'EE' => array('FR' => 'Estonie', 'EN' => 'Estonia'),
	 'US' => array('FR' => 'États-Unis', 'EN' => 'United States'),
	 'ET' => array('FR' => 'Éthiopie', 'EN' => 'Ethiopia'),
	 'FI' => array('FR' => 'Finlande', 'EN' => 'Finland'),
	 'FR' => array('FR' => 'France', 'EN' => 'France'),
	 'GE' => array('FR' => 'Géorgie', 'EN' => 'Georgia'),
	 'GA' => array('FR' => 'Gabon', 'EN' => 'Gabon'),
	 'GM' => array('FR' => 'Gambie', 'EN' => 'Gambia, the'),
	 'GH' => array('FR' => 'Ghana', 'EN' => 'Ghana'),
	 'GI' => array('FR' => 'Gibraltar', 'EN' => 'Gibraltar'),
	 'GR' => array('FR' => 'Grèce', 'EN' => 'Greece'),
	 'GD' => array('FR' => 'Grenade', 'EN' => 'Grenada'),
	 'GL' => array('FR' => 'Groenland', 'EN' => 'Greenland'),
	 'GP' => array('FR' => 'Guadeloupe', 'EN' => 'Guinea, Equatorial'),
	 'GU' => array('FR' => 'Guam', 'EN' => 'Guam'),
	 'GT' => array('FR' => 'Guatemala', 'EN' => 'Guatemala'),
	 'GN' => array('FR' => 'Guinée', 'EN' => 'Guinea'),
	 'GQ' => array('FR' => 'Guinée équatoriale', 'EN' => 'Equatorial Guinea'),
	 'GW' => array('FR' => 'Guinée-Bissao', 'EN' => 'Guinea-Bissau'),
	 'GY' => array('FR' => 'Guyana', 'EN' => 'Guyana'),
	 'GF' => array('FR' => 'Guyane française', 'EN' => 'Guiana, French'),
	 'HT' => array('FR' => 'Haïti', 'EN' => 'Haiti'),
	 'HN' => array('FR' => 'Honduras', 'EN' => 'Honduras'),
	 'HK' => array('FR' => 'Hong Kong', 'EN' => 'Hong Kong, (China)'),
	 'HU' => array('FR' => 'Hongrie', 'EN' => 'Hungary'),
	 'BV' => array('FR' => 'Ile Bouvet', 'EN' => 'Bouvet Island'),
	 'CX' => array('FR' => 'Ile Christmas', 'EN' => 'Christmas Island'),
	 'NF' => array('FR' => 'Ile Norfolk', 'EN' => 'Norfolk Island'),
	 'KY' => array('FR' => 'Iles Cayman', 'EN' => 'Cayman Islands'),
	 'CK' => array('FR' => 'Iles Cook', 'EN' => 'Cook Islands'),
	 'FO' => array('FR' => 'Iles Féroé', 'EN' => 'Faroe Islands'),
	 'FK' => array('FR' => 'Iles Falkland', 'EN' => 'Falkland Islands (Malvinas)'),
	 'FJ' => array('FR' => 'Iles Fidji', 'EN' => 'Fiji'),
	 'GS' => array('FR' => 'Iles Géorgie du Sud et Sandwich du Sud', 'EN' => 'S. Georgia and S. Sandwich Is.'),
	 'HM' => array('FR' => 'Iles Heard et McDonald', 'EN' => 'Heard and McDonald Islands'),
	 'MH' => array('FR' => 'Iles Marshall', 'EN' => 'Marshall Islands'),
	 'PN' => array('FR' => 'Iles Pitcairn', 'EN' => 'Pitcairn Island'),
	 'SB' => array('FR' => 'Iles Salomon', 'EN' => 'Solomon Islands'),
	 'SJ' => array('FR' => 'Iles Svalbard et Jan Mayen', 'EN' => 'Svalbard and Jan Mayen Islands'),
	 'TC' => array('FR' => 'Iles Turks-et-Caicos', 'EN' => 'Turks and Caicos Islands'),
	 'VI' => array('FR' => 'Iles Vierges américaines', 'EN' => 'Virgin Islands, U.S.'),
	 'VG' => array('FR' => 'Iles Vierges britanniques', 'EN' => 'Virgin Islands, British'),
	 'CC' => array('FR' => 'Iles des Cocos (Keeling)', 'EN' => 'Cocos (Keeling) Islands'),
	 'UM' => array('FR' => 'Iles mineures éloignées des États-Unis', 'EN' => 'US Minor Outlying Islands'),
	 'IN' => array('FR' => 'Inde', 'EN' => 'India'),
	 'ID' => array('FR' => 'Indonésie', 'EN' => 'Indonesia'),
	 'IR' => array('FR' => 'Iran', 'EN' => 'Iran, Islamic Republic of'),
	 'IQ' => array('FR' => 'Iraq', 'EN' => 'Iraq'),
	 'IE' => array('FR' => 'Irlande', 'EN' => 'Ireland'),
	 'IS' => array('FR' => 'Islande', 'EN' => 'Iceland'),
	 'IL' => array('FR' => 'Israël', 'EN' => 'Israel'),
	 'IT' => array('FR' => 'Italie', 'EN' => 'Italy'),
	 'JM' => array('FR' => 'Jamaïque', 'EN' => 'Jamaica'),
	 'JP' => array('FR' => 'Japon', 'EN' => 'Japan'),
	 'JO' => array('FR' => 'Jordanie', 'EN' => 'Jordan'),
	 'KZ' => array('FR' => 'Kazakhstan', 'EN' => 'Kazakhstan'),
	 'KE' => array('FR' => 'Kenya', 'EN' => 'Kenya'),
	 'KG' => array('FR' => 'Kirghizistan', 'EN' => 'Kyrgyzstan'),
	 'KI' => array('FR' => 'Kiribati', 'EN' => 'Kiribati'),
	 'KW' => array('FR' => 'Koweït', 'EN' => 'Kuwait'),
	 'LA' => array('FR' => 'Laos', 'EN' => 'Lao People\'s Democratic Republic'),
	 'LS' => array('FR' => 'Lesotho', 'EN' => 'Lesotho'),
	 'LV' => array('FR' => 'Lettonie', 'EN' => 'Latvia'),
	 'LB' => array('FR' => 'Liban', 'EN' => 'Lebanon'),
	 'LR' => array('FR' => 'Liberia', 'EN' => 'Liberia'),
	 'LY' => array('FR' => 'Libye', 'EN' => 'Libyan Arab Jamahiriya'),
	 'LI' => array('FR' => 'Liechtenstein', 'EN' => 'Liechtenstein'),
	 'LT' => array('FR' => 'Lituanie', 'EN' => 'Lithuania'),
	 'LU' => array('FR' => 'Luxembourg', 'EN' => 'Luxembourg'),
	 'MO' => array('FR' => 'Macao', 'EN' => 'Macao, (China)'),
	 'MG' => array('FR' => 'Madagascar', 'EN' => 'Madagascar'),
	 'MY' => array('FR' => 'Malaisie', 'EN' => 'Malaysia'),
	 'MW' => array('FR' => 'Malawi', 'EN' => 'Malawi'),
	 'MV' => array('FR' => 'Maldives', 'EN' => 'Maldives'),
	 'ML' => array('FR' => 'Mali', 'EN' => 'Mali'),
	 'MT' => array('FR' => 'Malte', 'EN' => 'Malta'),
	 'MP' => array('FR' => 'Mariannes du Nord', 'EN' => 'Northern Mariana Islands'),
	 'MA' => array('FR' => 'Maroc', 'EN' => 'Morocco'),
	 'MQ' => array('FR' => 'Martinique', 'EN' => 'Martinique'),
	 'MU' => array('FR' => 'Maurice', 'EN' => 'Mauritius'),
	 'MR' => array('FR' => 'Mauritanie', 'EN' => 'Mauritania'),
	 'YT' => array('FR' => 'Mayotte', 'EN' => 'Mayotte'),
	 'MX' => array('FR' => 'Mexique', 'EN' => 'Mexico'),
	 'FM' => array('FR' => 'Micronésie', 'EN' => 'Micronesia, Federated States of'),
	 'MD' => array('FR' => 'Moldavie', 'EN' => 'Moldova, Republic of'),
	 'MC' => array('FR' => 'Monaco', 'EN' => 'Monaco'),
	 'MN' => array('FR' => 'Mongolie', 'EN' => 'Mongolia'),
	 'MS' => array('FR' => 'Montserrat', 'EN' => 'Montserrat'),
	 'MZ' => array('FR' => 'Mozambique', 'EN' => 'Mozambique'),
	 'NP' => array('FR' => 'Népal', 'EN' => 'Nepal'),
	 'NA' => array('FR' => 'Namibie', 'EN' => 'Namibia'),
	 'NR' => array('FR' => 'Nauru', 'EN' => 'Nauru'),
	 'NI' => array('FR' => 'Nicaragua', 'EN' => 'Nicaragua'),
	 'NE' => array('FR' => 'Niger', 'EN' => 'Niger'),
	 'NG' => array('FR' => 'Nigeria', 'EN' => 'Nigeria'),
	 'NU' => array('FR' => 'Nioué', 'EN' => 'Niue'),
	 'NO' => array('FR' => 'Norvège', 'EN' => 'Norway'),
	 'NC' => array('FR' => 'Nouvelle-Calédonie', 'EN' => 'New Caledonia'),
	 'NZ' => array('FR' => 'Nouvelle-Zélande', 'EN' => 'New Zealand'),
	 'OM' => array('FR' => 'Oman', 'EN' => 'Oman'),
	 'UG' => array('FR' => 'Ouganda', 'EN' => 'Uganda'),
	 'UZ' => array('FR' => 'Ouzbékistan', 'EN' => 'Uzbekistan'),
	 'PE' => array('FR' => 'Pérou', 'EN' => 'Peru'),
	 'PK' => array('FR' => 'Pakistan', 'EN' => 'Pakistan'),
	 'PA' => array('FR' => 'Panama', 'EN' => 'Panama'),
	 'PG' => array('FR' => 'Papouasie-Nouvelle-Guinée', 'EN' => 'Papua New Guinea'),
	 'PY' => array('FR' => 'Paraguay', 'EN' => 'Paraguay'),
	 'NL' => array('FR' => 'Pays-Bas', 'EN' => 'Netherlands'),
	 'PH' => array('FR' => 'Philippines', 'EN' => 'Philippines'),
	 'PL' => array('FR' => 'Pologne', 'EN' => 'Poland'),
	 'PF' => array('FR' => 'Polynésie française', 'EN' => 'French Polynesia'),
	 'PR' => array('FR' => 'Porto Rico', 'EN' => 'Puerto Rico'),
	 'PT' => array('FR' => 'Portugal', 'EN' => 'Portugal'),
	 'QA' => array('FR' => 'Qatar', 'EN' => 'Qatar'),
	 'CF' => array('FR' => 'République centrafricaine', 'EN' => 'Central African Republic'),
	 'CD' => array('FR' => 'République démocratique du Congo', 'EN' => 'Congo, Democratic Rep. of the'),
	 'DO' => array('FR' => 'République dominicaine', 'EN' => 'Dominican Republic'),
	 'CZ' => array('FR' => 'République tchèque', 'EN' => 'Czech Republic'),
	 'RE' => array('FR' => 'Réunion', 'EN' => 'Reunion'),
	 'RO' => array('FR' => 'Roumanie', 'EN' => 'Romania'),
	 'GB' => array('FR' => 'Royaume-Uni', 'EN' => 'Saint Pierre and Miquelon'),
	 'RU' => array('FR' => 'Russie', 'EN' => 'Russia (Russian Federation)'),
	 'RW' => array('FR' => 'Rwanda', 'EN' => 'Rwanda'),
	 'SN' => array('FR' => 'Sénégal', 'EN' => 'Senegal'),
	 'EH' => array('FR' => 'Sahara occidental', 'EN' => 'Western Sahara'),
	 'KN' => array('FR' => 'Saint-Christophe-et-Niévès', 'EN' => 'Saint Kitts and Nevis'),
	 'SM' => array('FR' => 'Saint-Marin', 'EN' => 'San Marino'),
	 'PM' => array('FR' => 'Saint-Pierre-et-Miquelon', 'EN' => 'Saint Pierre and Miquelon'),
	 'VA' => array('FR' => 'Saint-Siège ', 'EN' => 'Vatican City State (Holy See)'),
	 'VC' => array('FR' => 'Saint-Vincent-et-les-Grenadines', 'EN' => 'Saint Vincent and the Grenadines'),
	 'SH' => array('FR' => 'Sainte-Hélène', 'EN' => 'Saint Helena'),
	 'LC' => array('FR' => 'Sainte-Lucie', 'EN' => 'Saint Lucia'),
	 'SV' => array('FR' => 'Salvador', 'EN' => 'El Salvador'),
	 'WS' => array('FR' => 'Samoa', 'EN' => 'Samoa'),
	 'AS' => array('FR' => 'Samoa américaines', 'EN' => 'American Samoa'),
	 'ST' => array('FR' => 'Sao Tomé-et-Principe', 'EN' => 'Sao Tome and Principe'),
	 'SC' => array('FR' => 'Seychelles', 'EN' => 'Seychelles'),
	 'SL' => array('FR' => 'Sierra Leone', 'EN' => 'Sierra Leone'),
	 'SG' => array('FR' => 'Singapour', 'EN' => 'Singapore'),
	 'SI' => array('FR' => 'Slovénie', 'EN' => 'Slovenia'),
	 'SK' => array('FR' => 'Slovaquie', 'EN' => 'Slovakia'),
	 'SO' => array('FR' => 'Somalie', 'EN' => 'Somalia'),
	 'SD' => array('FR' => 'Soudan', 'EN' => 'Sudan'),
	 'LK' => array('FR' => 'Sri Lanka', 'EN' => 'Sri Lanka (ex-Ceilan)'),
	 'SE' => array('FR' => 'Suède', 'EN' => 'Sweden'),
	 'CH' => array('FR' => 'Suisse', 'EN' => 'Switzerland'),
	 'SR' => array('FR' => 'Suriname', 'EN' => 'Suriname'),
	 'SZ' => array('FR' => 'Swaziland', 'EN' => 'Swaziland'),
	 'SY' => array('FR' => 'Syrie', 'EN' => 'Syrian Arab Republic'),
	 'TW' => array('FR' => 'Taïwan', 'EN' => 'Taiwan'),
	 'TJ' => array('FR' => 'Tadjikistan', 'EN' => 'Tajikistan'),
	 'TZ' => array('FR' => 'Tanzanie', 'EN' => 'Tanzania, United Republic of'),
	 'TD' => array('FR' => 'Tchad', 'EN' => 'Chad'),
	 'TF' => array('FR' => 'Terres australes françaises', 'EN' => 'French Southern Territories - TF'),
	 'IO' => array('FR' => 'Territoire britannique de l\'Océan Indien', 'EN' => 'British Indian Ocean Territory'),
	 'TH' => array('FR' => 'Thaïlande', 'EN' => 'Thailand'),
	 'TL' => array('FR' => 'Timor Oriental', 'EN' => 'Timor-Leste (East Timor)'),
	 'TG' => array('FR' => 'Togo', 'EN' => 'Togo'),
	 'TK' => array('FR' => 'Tokélaou', 'EN' => 'Tokelau'),
	 'TO' => array('FR' => 'Tonga', 'EN' => 'Tonga'),
	 'TT' => array('FR' => 'Trinité-et-Tobago', 'EN' => 'Trinidad & Tobago'),
	 'TN' => array('FR' => 'Tunisie', 'EN' => 'Tunisia'),
	 'TM' => array('FR' => 'Turkménistan', 'EN' => 'Turkmenistan'),
	 'TR' => array('FR' => 'Turquie', 'EN' => 'Turkey'),
	 'TV' => array('FR' => 'Tuvalu', 'EN' => 'Tuvalu'),
	 'UA' => array('FR' => 'Ukraine', 'EN' => 'Ukraine'),
	 'UY' => array('FR' => 'Uruguay', 'EN' => 'Uruguay'),
	 'VU' => array('FR' => 'Vanuatu', 'EN' => 'Vanuatu'),
	 'VE' => array('FR' => 'Venezuela', 'EN' => 'Venezuela'),
	 'VN' => array('FR' => 'ViÃªt Nam', 'EN' => 'Viet Nam'),
	 'WF' => array('FR' => 'Wallis-et-Futuna', 'EN' => 'Wallis and Futuna'),
	 'YE' => array('FR' => 'Yémen', 'EN' => 'Yemen'),
	 'YU' => array('FR' => 'Yougoslavie', 'EN' => 'Saint Pierre and Miquelon'),
	 'ZM' => array('FR' => 'Zambie', 'EN' => 'Zambia'),
	 'ZW' => array('FR' => 'Zimbabwe', 'EN' => 'Zimbabwe'),
	 'MK' => array('FR' => 'ex-République yougoslave de Macédoine', 'EN' => 'Macedonia, TFYR')
	);
	
	return $tabPays;
}
?>