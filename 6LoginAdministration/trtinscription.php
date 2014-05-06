<?php 
	//ajout des fonctions
    require_once('lib/php/fonctions.php');
	//on demarre la session
    session_start();
	
	if( isset( $_POST['bouton']) )
	{
		//initialisation du contenu à afficher, vide si on n'affiche rien
		$contenu = '';
		//on part du principe qu'il n'y a pas d'erreur dans le formulaire
		$_SESSION['erreurform'] = false;
		
		//on récupere les données du formulaire et on les sécurise
		$nom 				= secureData($_POST['nom']);
		$prenom 			= secureData($_POST['prenom']);
		$sexe 				= secureData($_POST['sexe']);
		$datenaissance		= secureData($_POST['datenaissance']);
		//création d'une variable de type date
		$datenaissance		= date_create_from_format('d-m-Y',$datenaissance);
		$pays 				= secureData($_POST['pays']); 
		$login				= secureData($_POST['login']);
		$mdp 				= md5(secureData($_POST['mdp']));						
		$dateinscription 	= date('y-m-d');
		$email 				= secureData($_POST['login']).'@monsite.be';
		$admin 				= 0;		
		
		//validation des champs du formulaire
		valider_nom( $_POST['nom'] );
		valider_prenom( $_POST['prenom'] );
		valider_datenaissance( $_POST['datenaissance'] );
		valider_sexe( $_POST['sexe'] );
		valider_pays( $_POST['pays'] );
		valider_mdp( $_POST['mdp'] );
		
		//si le login est valide
		if( valider_login($_POST['login']) )
		{
			//on vérifie si le login est en double
			verifier_login( $login );
		}
		
		//on vérifie après validation, s'il y a eu une erreur
		if( $_SESSION['erreurform'] )
		{
			//renvoi vers la page où il y a le formulaire
			header('refresh:0;url=inscription.php');
		}
		else
		{
			//préparation de la date au format MYSQL
			$datenaissance = date_format($datenaissance, 'Y-m-d');		
			
			//connection à la BD
			$connection = ConnectBD();
				
			//On vérifie si on est bien connecté
			if( $connection )
			{
				try
				{
					//on tente d'exécuter les requêtes suivantes dans une transaction
					//on lance la transaction
					$connection->beginTransaction();
					
					//on exécute la commande sql d'inscription
					$connection->exec('INSERT INTO utilisateurs VALUES(null,"'.$nom.'","'.$prenom.'","'.$sexe.'","'.$datenaissance.'","'.$pays.'","'.$login.'","'.$mdp.'","'.$dateinscription.'","'.$email.'",'.$admin.',"'.$_SERVER['REMOTE_ADDR'].'")');

					//si jusque là tout se passe bien on valide la transaction
					$connection->commit();

					//appel de la fonction qui connecte l'utilisateur
					login($connection,$login,$mdp);
					
					// la fonction login ferme déjà la connection à la BD
					// unset( $connection );
					
					$contenu = '<info>Vous êtes inscrit!</info>';
					header('refresh:1;url=index.php');	
				}
				catch(PDOException $e) // en cas d'erreur
				{
					// on annule la transaction
					$connection->rollback();
					
					// on affiche un message d'erreur ainsi que les erreurs
					$contenu  = '<erreur>Erreur [0021]: Erreur lors de la requête, veuillez contacter votre administrateur!</erreur>';		
					$contenu .= '<erreur>Erreur : '.$e->getMessage().'</erreur><br/>';
					$contenu .= '<erreur>N° : '.$e->getCode().'</erreur><br/>';
					
					//on arrête l'exécution s'il y a du code après
					exit();
				}	
			}
			else
			{
				$contenu = '<erreur>Erreur [001]: Impossible de se connecter à la BD, veuillez contacter votre administrateur!</erreur>';						
			}
		?>
		
		<html lang="fr">
		<head>
			<meta charset="utf-8">
			<title>TRT Connection</title>
			
			<!-- Feuilles de styles -->
			<link rel="stylesheet" href="css/styles.css" type="text/css">
		</head>
		<body>
			<?php include('menu.php'); ?>
			<div id="contenu">
				<section>
					<article>
					
						<h1>TRT Connection</h1>	
				
						<?php
							//affichage du contenu de la page
							echo $contenu;
						?>
				
					</article>
				</section>
			</div>
		</body>
		</html>			
		
		<?php
		}
	}
	else
	{
		//accés à la page sans passer par le formulaire, renvoi à l'index
		header('refresh:1;url=inscription.php');
	}	
?>