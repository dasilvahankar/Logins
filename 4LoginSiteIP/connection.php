<?php 
	//ajout des fonctions
    require_once('lib/php/fonctions.php');
	//on demarre la session
    session_start();
	
	// on vérifie si l'utilisateur est connecté		
	if( isset($_SESSION['id']) )
	{
		// l'utilisateur est déjà connecté
		header('refresh:0;url=index.php');
	}
	else
	{
?>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<title>Connection</title>
    
    <!-- Feuilles de styles -->
	<link rel="stylesheet" href="css/styles.css" type="text/css">
</head>
<body>
    <header>
        <h1>Connection</h1>
    </header>
   	
 	<?php include_once('menu.php'); ?>
     <div id="contenu">
		<section>
            <article>  
                  
				<?php
				if( isset($_POST['bouton']) )
				{
					//connection à la BD
					$connection = ConnectBD();
						
					//récuperation nettoyage des données du formulaire
					$login = secureData($_POST['login']);
					$mdp   = md5(secureData($_POST['mdp']));						

					//fonction qui connecte l'utilisateur
					$connecte = login($connection,$login,$mdp );

					if( $connecte )
					{
						//redirection vers la page d'accueil
						header('refresh:1;url=index.php');
						echo '<info>Bonjour <b>'.$_SESSION['prenom'].' '.$_SESSION['nom'].'</b>, vous êtes connecté.</info></br></br>';
					}
					else
					{
						echo '<erreur>Votre login ou mot de passe est erroné!</erreur>';
					}							
				}
				else
				{
					if( isset($_COOKIE['login']) )
					{
						echo 'Bienvenu(e) '.$_COOKIE['login'].', connectez-vous...<br/><br/>';
					}
					else
					{
						echo 'Bonjour, veuillez vous connecter...<br/><br/>';
					}					
				?>
					<form name="FormConnection" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
						<label><b>Login</b></label><br/>						
						<input type="text" name="login" placeholder="login" value="<?php //(isset($_COOKIE['login']))?$_COOKIE['login']:''; ?>" maxlength="10">@monsite.be<br/>
						<label><b>Mot de passe</b></label><br/>						
						<input type="password" name="mdp" placeholder="mot de passe" value="" maxlength="8"><br/><br/>
						<input type="submit" name="bouton" value="Connection"><input type="reset" name="Effacer" value="Effacer">
					</form>			  
				<?php  
				}

				?>
            </article>
        </section>
	</div>
</body>
</html>
<?php
	}
?>