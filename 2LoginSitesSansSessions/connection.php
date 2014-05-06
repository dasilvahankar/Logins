<?php 
    include_once('lib/php/fonctions.php'); 
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
                        
						
						    mysqli_free_result( $resultat );			
                            header('refresh:2;url=index.php');
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
            ?>
				<form name="FormConnection" method="post" action="connection.php">
					<label><b>Login</b></label><br/>						
					<input type="text" name="login" placeholder="login" value="" maxlength="10">@monsite.be<br/>
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