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
	<title>Profil</title>
    
    <!-- Feuilles de styles -->
	<link rel="stylesheet" href="css/styles.css" type="text/css">
</head>
<body>
    <header>
        <h1>Profil</h1>
    </header>

	<?php include_once('menu.php'); ?>
    <div id="contenu">
		<section>
            <article>

				<?php
				echo '<info>Bonjour <b>'.$_SESSION['prenom'].' '.$_SESSION['nom'].'</b>, vous êtes connecté.</info></br></br>';
				?>
				<table class="profil">
					<tr class="profil">
						<td><b>ID:</b></td>
						<td><?php echo $_SESSION['id']; ?></td>
					</tr>				
					<tr class="profil">
						<td><b>Nom:</b></td>
						<td><?php echo $_SESSION['nom']; ?></td>
					</tr>
					<tr class="profil">
						<td><b>Prénom:</b></td>
						<td><?php echo $_SESSION['prenom']; ?></td>
					</tr>
					<tr><td></td><td></td></tr>
					<tr class="profil">
						<td><b>Sexe:</b></td>
						<td><?php echo $_SESSION['sexe']; ?></td>
					</tr>
					<tr class="profil">
						<td><b>Date de naissance:</b></td>
						<td><?php echo $_SESSION['datenaissance']; ?></td>
					</tr>
					<tr class="profil">
						<td><b>Pays:</b></td>
						<td><?php echo $_SESSION['pays']; ?></td>
					</tr>
					<tr><td></td><td></td></tr>   
					<tr class="profil">
						<td><b>Date d'inscription:</b></td>
						<td><?php echo $_SESSION['dateinscription']; ?></td>
					</tr>
					<tr class="profil">
						<td><b>Email:</b></td>
						<td><?php echo $_SESSION['email']; ?></td>
					</tr>
					<tr class="profil">
						<td><b>Login:</b></td>
						<td><?php echo $_SESSION['login']; ?></td>
					</tr>
					<tr class="profil">
						<td><b>Mot de passe:</b></td>
						<td><?php echo $_SESSION['mdp']; ?></td>
					</tr>
					<tr class="profil">
						<td><b>Admin:</b></td>
						<td><?php echo $_SESSION['admin']; ?></td>
					</tr>
					<tr class="profil">
						<td><b>Adresse IP:</b></td>
						<td><?php echo $_SESSION['ip']; ?></td>
					</tr>					
					<tr class="profil">
						<td><b>Cookies:</b></td>
						<td><?php echo isset($_COOKIE['login'])?'Activés':'Desactivés'; ?></td>
					</tr>					
				</table>
            </article>
        </section>
	</div>
</body>
</html>
<?php
	}
?>