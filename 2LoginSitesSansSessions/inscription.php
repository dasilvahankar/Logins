<?php 
    include_once('lib/php/fonctions.php'); 
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
						$nom 				= $_POST['nom'];
						$prenom 			= $_POST['prenom'];
						$sexe 				= $_POST['sexe'];
						$datenaissance		= $_POST['aa'].'/'.$_POST['mm'].'/'.$_POST['jj'];
						$pays 				= $_POST['pays'];
						$login				= $_POST['login'];
						$mdp 				= md5($_POST['mdp']);						
						$dateinscription 	= date('y-m-d');
						$email 				= $_POST['login'].'@monsite.be';
						$admin 				= 0;
						
						//On prépare la commande sql d'inscription
						$requete = 'INSERT INTO utilisateurs VALUES(null,"'.$nom.'","'.$prenom.'","'.$sexe.'","'.$datenaissance.'","'.$pays.'","'.$login.'","'.$mdp.'","'.$dateinscription.'","'.$email.'",'.$admin.')';
			
						//Exécution de la requête
						$resultat = mysqli_query( $connection , $requete );

						// Retourne FALSE en cas d'échec. Pour des requêtes SELECT, SHOW, DESCRIBE ou EXPLAIN réussies, mysqli_query() retournera un objet mysqli_result. 
						// Pour les autres types de requêtes ayant réussies, mysqli_query() retournera TRUE. 
						if( $resultat )
						{
							echo '<info>Vous êtes inscrit!</info>';
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
				
				?>
				<form name="FormInscription" method="post" action="inscription.php">
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