<?php 
	//ajout des fonctions
    require_once('lib/php/fonctions.php');
	//on demarre la session
    session_start();
	
	// on vérifie si l'utilisateur est connecté	
	if( !isset($_SESSION['id']) )
	{
		// l'utilisateur n'est pas connecté
		header('refresh:0;url=index.php');
	}		
	else
	{	
?>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<title>Discussion</title>
    
    <!-- Feuilles de styles -->
	<link rel="stylesheet" href="css/styles.css" type="text/css">
</head>
<body>
    <header>
        <h1>Discussion</h1>
    </header>

    <?php include_once('menu.php'); ?>
    <div id="contenu">
        <section>
            <article>
				<form name="FormDiscussion" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<label><b>Message: </b></label><input type="text" name="message" placeholder="votre message" maxlength="50"><br/><br/>
					<input type="submit" name="bouton" value="Discuter"><input type="reset" name="Effacer" value="Effacer">
				</form>		
				<br/><br/>

				<?php
				try
				{
					//connection à la BD
					$connection = ConnectBD();

					//est-ce que l'utilisateur viens d'envoyer un messsage?
					//oui = inscription du message dans la BD
					if( isset($_POST['bouton']) )
					{
						//echo '> '.date('y-m-d H:i:s').' : '.$_POST['message'];
						$date = date('y-m-d H:i:s');
						
						//inscription du message dans la BD
						$connection->beginTransaction();
						$connection->exec('INSERT INTO discussion VALUES(null,'.$_SESSION['id'].',"'.$date.'","'.$_POST['message'].'")');
						$connection->commit();
					}
					
					//préparation de la requête
					$requete  = 'SELECT login, datemessage, message';
					$requete .= ' FROM discussion, utilisateurs';
					$requete .= ' WHERE discussion.utilisateur = utilisateurs.id';
					$requete .= ' ORDER BY discussion.id DESC';
					
					//exécution de la requête pour récuperer les informations de l'utilisateur qui veut se connecter					
					$resultats = $connection->query($requete);
					
					//on vérifie si on a obtenu des résultats
					if( $resultats->rowCount() > 0 )
					{
						foreach( $resultats as $ligne )
						{
							//var_dump($ligne);
							//mise en gras du message de l'utilisateur qui est connecté
							if( $_SESSION['login'] == $ligne['login']  )
							{
								echo '<b>['.$ligne['datemessage'].'][ '.$ligne['login'].' ]:</b> '.$ligne['message'].'<br/>';
							}
							else
							{
								echo '['.$ligne['datemessage'].'][ '.$ligne['login'].' ]: '.$ligne['message'].'<br/>';
							}
						}

						//on libére les résultats de la mémoire
						$resultats->closeCursor();		
						
						//on ferme la connexion à la BD
						unset( $connection );	
					}	
					else
					{
						echo 'Soyez le 1er à discuter!<br/>';
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
				?>
				
            </article>
        </section>
    </div>
	
	<footer>
	</footer>
</body>
</html>
<?php
	}
?>