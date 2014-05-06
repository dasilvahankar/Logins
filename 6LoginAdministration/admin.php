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
		// on vérifie si c'est un administateur
		// 1 = c'est un administrateur
		// 0 = ce n'est pas un administrateur
		if( $_SESSION['admin'] == 0 )
		{
			// ce n'est pas un administrateur
			header('refresh:0;url=index.php');
		}	
		else
		{
?>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Admin</title>
	
	<!-- Feuilles de styles -->
	<link rel="stylesheet" href="css/styles.css" type="text/css">
</head>
<body>
	<?php include('menu.php'); ?>
	<div id="contenu">
		<section>
			<article>
			
				<h1>Administration</h1>	
				<?php
				try
				{
					//connection à la BD
					$connection = ConnectBD();
					
					//vérification si une opération a été demandée
					if( isset($_GET['op']) && isset($_GET['id']) )
					{
						//on empêche un utilisateur faire des opérations sur lui-même
						if( $_SESSION['id'] == $_GET['id'] )
						{
							echo '<erreur>Vous ne pouvez pas faire des opérations sur vous-même!</erreur>';
						}
						else
						{
							//récuperation du rang d'administration de l'utilisateur séléctionné
							$resultat = $connection->query('SELECT admin FROM utilisateurs WHERE id='.$_GET['id']);
							//mise du résultat dans un tableau associatif (FETCH_ASSOC)
							$tab = $resultat->fetch(PDO::FETCH_ASSOC);						
						
							//on empêche un administrateur de faire des opérations sur un administrateur d'un niveau plus élevé (plus haut = RANG 1)
							//if( ($tab['admin'] < $_SESSION['admin']) && ($tab['admin'] != 0)  )
							//on empêche un administrateur de faire des opérations sur l'administrateur de RANG 1
							if( $tab['admin'] == 1 )
							{
								echo '<erreur>Vous ne pouvez pas faire des opérations sur un administrateur de rang plus élevé !</erreur>';
							}
							else
							{
								//séléction du type d'opération à faire
								switch($_GET['op'])
								{
									case 1://DELETE utilisateur
										//echo 'DELETE utilisateur avec le id = '.$_GET['id'];
										$connection->beginTransaction();
										$connection->exec('DELETE FROM utilisateurs WHERE id='.$_GET['id']);
										$connection->commit();								
									break;
									case 2://UPDATE admin
										if( $tab['admin'] == 0 )
										{
											//echo 'UPDATE utilisateur admin = 2'.$_GET['id'];		
											$rang = 2;
										}
										else
										{
											//echo 'UPDATE utilisateur admin = 0'.$_GET['id'];		
											$rang = 0;
										}
										$connection->beginTransaction();
										$connection->exec('UPDATE utilisateurs SET admin='.$rang.' WHERE id='.$_GET['id']);
										$connection->commit();
									break;
								}
							}
						}
					}
					
					//préparation de la requête
					$requete  = 'SELECT * FROM utilisateurs';
					//$requete .= ' WHERE admin = 0';
				
					//exécution de la requête pour récuperer tous les utilisateurs					
					$resultats = $connection->query($requete);
					
					//on vérifie si on a obtenu des résultats
					if( $resultats->rowCount() > 0 )
					{
						//création et mise en page des utilisateurs dans une table html
						echo '<br/><br/>
							<table class="admin">
								<tr class="noir">
									<th>ID</th>
									<th>Login</th>
									<th>Prénom</th>
									<th>Nom</th>
									<th>Email</th>
									<th>Date d\'inscription</th>
									<th>Admin</th>
									<th>Opérations</th>
								</tr>
							';
						//choix de la couleur de fond de la ligne
						$class = 'clair';
						//boucle d'affichage de TOUS les utilisateurs
						foreach( $resultats as $ligne )
						{
							//préparation de l'affichage si c'est un administrateur
							if( $ligne['admin'] > 0 )
							{
								$admin = 'oui';
								//si c'est un super administrateur, RANG 1
								if( $ligne['admin'] == 1 )
								{
									$adminimg = 'adminsuper.png';
								}
								else //c'est un simple administrateur, RANG 2, ...
								{
									$adminimg = 'adminsupprimer.png';
								}
							}
							else//ce n'est pas un administrateur
							{
								$admin    = 'non';
								$adminimg = 'adminajouter.png';							
							}
							//affichage d'une ligne utilisateur
							echo '
								<tr class="'.$class.'">
									<td><b>'.$ligne['id'].'</b></td>
									<td>'.$ligne['login'].'</td>
									<td>'.$ligne['prenom'].'</td>
									<td>'.$ligne['nom'].'</td>
									<td>'.$ligne['email'].'</td>
									<td>'.$ligne['dateinscription'].'</td>
									<td>'.$admin.'</td>
									<td><a alt="Supprimer l\'utilisateur" href="'.htmlspecialchars($_SERVER['PHP_SELF']).'?op=1&id='.$ligne['id'].'"><img src="img/defaut/supprimer.png"></a> 
										<a alt="Attribuer des droits d\'administrateur" href="'.htmlspecialchars($_SERVER['PHP_SELF']).'?op=2&id='.$ligne['id'].'"><img src="img/defaut/'.$adminimg.'"></a>
									</td>
								</tr>							
								';
							//changement de couleur de fond une fois sur deux
							$class = ($class=='fonce')?'clair':'fonce';								
						}
						echo '</table>';

						//on libére les résultats de la mémoire
						$resultats->closeCursor();		
						
						//on ferme la connexion à la BD
						unset( $connection );	
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
</body>
</html>		
<?php
		}
	}
?>


