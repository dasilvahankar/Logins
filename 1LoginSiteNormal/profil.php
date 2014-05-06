<?php include_once('lib/php/fonctions.php'); ?>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<title>Profil</title>
    
    <!-- Feuilles de styles -->
	<link rel="stylesheet" href="css/styles.css" type="text/css">
</head>
<body>
    <header>
        <h1>Profil</h1>
    </header>

	<?php include('menu.php'); ?>
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
						$login = $_POST['login'];
						$mdp   = md5($_POST['mdp']);						
						
						//On prépare la commande sql d'inscription
						$requete = 'SELECT * FROM utilisateurs WHERE login="'.$login.'" AND mdp="'.$mdp.'"';
			
						//Exécution de la requête
						$resultat = mysqli_query( $connection , $requete );

						// Retourne FALSE en cas d'échec. Pour des requêtes SELECT, SHOW, DESCRIBE ou EXPLAIN réussies, mysqli_query() retournera un objet mysqli_result. 
						// Pour les autres types de requêtes ayant réussies, mysqli_query() retournera TRUE. 
						if( $resultat )
						{
							// On vérifie si on a bien un résultat
							// mysqli_num_rows() = renvoie le nombre de résultats trouvés							
							if( mysqli_num_rows($resultat) != 0 )
							{
								$tableau = mysqli_fetch_assoc( $resultat );
								echo '<info>Bonjour <b>'.$tableau['prenom'].' '.$tableau['nom'].'</b>, vous êtes connecté.</info></br></br>';
							?>	
							<label><b>Nom</b></label> <input type="text" name="nom" value="<?php echo $tableau['nom']; ?>" disabled><br/>
							<label><b>Prénom</b></label> <input type="text" name="prenom" value="<?php echo $tableau['prenom']; ?>" disabled><br/><br/>
							
							<label><b>Sexe</b></label> <?php echo $tableau['sexe']; ?><br/>
							<label><b>Date de naissance</b></label><input type="date" name="datenaissance" value="<?php echo $tableau['prenom']; ?>" disabled><br/>
							
							<label><b>Pays</b></label><input type="text" name="pays" value="<?php echo $tableau['pays']; ?>" disabled><br/><br/>	
							<label><b>Login</b></label><input type="text" name="login" value="<?php echo $tableau['login']; ?>" disabled><br/>
							<label><b>Mot de passe</b></label><input type="text" name="mdp" value="<?php echo $tableau['mdp']; ?>" disabled><br/>

							<label><b>Date d'inscription</b></label><input type="date" name="dateinscription" value="<?php echo $tableau['dateinscription']; ?>" disabled><br/>							
							<label><b>Email</b></label><input type="date" name="email" value="<?php echo $tableau['email']; ?>" disabled><br/>							
							<label><b>Admin</b></label><input type="date" name="admin" value="<?php echo $tableau['admin']; ?>" disabled><br/>														
							<?php	
								mysqli_free_result( $resultat );							
							}
							else
							{
								echo '<erreur>Votre login ou mot de passe est erroné!</erreur>';
							}							
						}						
						else
						{
							echo '<erreur>Erreur [002]: Erreur lors de la requête, veuillez contacter votre administrateur!</erreur>';						
						}						
					}
					else
					{
						echo '<erreur>Erreur [001]: Impossible de se connecter à la BD, veuillez contacter votre administrateur!</erreur>';						
					}
				}
				else
				{
					header('refresh:0;url=connection.php');
				}
				?>

            </article>
        </section>
	</div>
</body>
</html>