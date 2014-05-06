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
		
		//validation du login entré par l'utilisateur
		valider_login( $_POST['login'] );
		//validation du mot de passe entré par l'utilisateur		
		valider_mdp( $_POST['mdp'] );									
		
		//on vérifie après validation, s'il y a eu une erreur
		if( $_SESSION['erreurform'] )
		{
			//s'il y a eu une erreur dans le formulaire on réinitialise l'erreur de connection
			$_SESSION['erreur_connection'] = '';	
			
			//renvoi vers la page où il y a le formulaire
			header('refresh:0;url=connection.php');
		}
		else
		{
			//connection à la BD
			$connection = ConnectBD();
				
			//récuperation nettoyage des données du formulaire
			$login = secureData($_POST['login']);
			$mdp   = md5(secureData($_POST['mdp']));						

			//fonction qui connecte l'utilisateur
			$connecte = login($connection,$login,$mdp );

			//si l'utilisateur a réussi à se connecter
			if( $connecte )
			{
				//redirection vers la page d'accueil
				header('refresh:1;url=index.php');
				//préparation du contenu à afficher dans cette page
				$contenu = '<info>Bonjour <b>'.$_SESSION['prenom'].' '.$_SESSION['nom'].'</b>, vous êtes connecté.</info><br/><br/>';
			}
			else
			{
				//on prépare l'erreur à afficher et on renvoit l'utilisateur dans le formulaire
				$_SESSION['erreur_connection'] = '<erreur>Votre login ou mot de passe est erroné!</erreur>';
				
				//renvoi vers la page où il y a le formulaire
				header('refresh:0;url=connection.php');
			}
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
	else
	{
		//accés à la page sans passer par le formulaire, renvoi à l'index
		header('refresh:1;url=index.php');
	}	
?>