<?php 
//ajout des fonctions
require_once('lib/php/fonctions.php');
//on demarre la session
session_start();

// on vérifie si l'utilisateur est connecté			
if( isset($_SESSION['id']) )
{
	// l'utilisateur est connecté, pas besoin de s'inscrire
	header('refresh:0;url=index.php');
}	
else
{
?>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<title>Inscription</title>
    
    <!-- Feuilles de styles -->
	<link rel="stylesheet" href="css/styles.css" type="text/css">
</head>
<body>
    <header>
        <h1>Inscription</h1>
    </header>

	<?php include_once('menu.php'); ?>
    <div id="contenu">
		<section>
            <article>

				<?php
				if( isset($_POST['bouton']) )
				{
					//Connection à la BD
					$connection = ConnectBD();
					
					//On vérifie si on est bien connecté
					if( $connection )
					{
						$nom 				= secureData($_POST['nom']);
						$prenom 			= secureData($_POST['prenom']);
						$sexe 				= secureData($_POST['sexe']);
						$datenaissance		= secureData($_POST['aa']).'/'.secureData($_POST['mm']).'/'.secureData($_POST['jj']);
						$pays 				= secureData($_POST['pays']);
						$login				= secureData($_POST['login']);
						$mdp 				= md5(secureData($_POST['mdp']));						
						$dateinscription 	= date('y-m-d');
						$email 				= secureData($_POST['login']).'@monsite.be';
						$admin 				= 0;
						
						try
						{
							//on tente d'exécuter les requêtes suivantes dans une transaction
							//on lance la transaction
							$connection->beginTransaction();
							
							//on exécute la commande sql d'inscription
							$connection->exec('INSERT INTO utilisateurs VALUES(null,"'.$nom.'","'.$prenom.'","'.$sexe.'","'.$datenaissance.'","'.$pays.'","'.$login.'","'.$mdp.'","'.$dateinscription.'","'.$email.'",'.$admin.')');

							//obligatoire de faire l'appel de lastInsertId() AVANT le faire le commit(), sinon cela retourne 0 !!!
							//$tab['id']				= $connection->lastInsertId();
							
							//si jusque là tout se passe bien on valide la transaction
							$connection->commit();

							//appel de la fonction qui connecte l'utilisateur
							login($connection,$login,$mdp);
							
							// la fonction login ferme déjà la connection à la BD
							// unset( $connection );
							
							echo '<info>Vous êtes inscrit!</info>';
							header('refresh:0;url=index.php');	
						}
						catch(PDOException $e) // en cas d'erreur
						{
							// on annule la transaction
							$connection->rollback();
							
							// on affiche un message d'erreur ainsi que les erreurs
							echo '<erreur>Erreur [002]: Erreur lors de la requête, veuillez contacter votre administrateur!</erreur>';		
							echo '<erreur>Erreur : '.$e->getMessage().'</erreur><br/>';
							echo '<erreur>N° : '.$e->getCode().'</erreur><br/>';
							
							//on arrête l'exécution s'il y a du code après
							exit();
						}					
					}
					else
					{
						echo '<erreur>Erreur [001]: Impossible de se connecter à la BD, veuillez contacter votre administrateur!</erreur>';						
					}
				}
				else
				{
				
				?>
				<form name="FormInscription" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<label><b>Nom</b></label><br/>
					<input type="text" name="nom" placeholder="nom" value="" maxlength="20"><br/>
					<label><b>Prénom</b></label><br/>					
					<input type="text" name="prenom" placeholder="prénom" value="" maxlength="20"><br/><br/>
					
					<label><b>Sexe</b></label><br/>	
					<input type="radio" name="sexe" value="F" checked>F<input type="radio" name="sexe" value="M">M<input type="radio" name="sexe" value="A">Autre<br/>
					<label><b>Date de naissance</b></label><br/>	
					<input type="text" name="aa" placeholder="aaaa" maxlength="4" size="5"><input type="text" name="mm" placeholder="mm" maxlength="2" size="2"><input type="text" name="jj" placeholder="jj" maxlength="2" size="2"><br/>
					<label><b>Pays</b></label><br/>	
					<input type="text" name="pays" placeholder="pays" value="Belgique" maxlength="30"><br/><br/>	

					<label><b>Login</b></label><br/>						
					<input type="text" name="login" placeholder="login" value="" maxlength="10">@monsite.be<br/>
					<label><b>Mot de passe</b></label><br/>						
					<input type="password" name="mdp" placeholder="mot de passe" value="" maxlength="8"><br/><br/>
					<input type="submit" name="bouton" value="Inscription"><input type="reset" name="Effacer" value="Effacer">
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