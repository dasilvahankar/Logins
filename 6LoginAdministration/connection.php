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
                  
				<form name="connection" method="post" action="trtconnection.php">
					<table>
						<tr>
							<td>
								<label><b>Login</b></label>
							</td>
							<td>&nbsp;</td>							
						</tr>
						<tr>
							<td>
								<input type="text" name="login" placeholder="login" value="<?php if(isset($_SESSION['form_login'])){echo $_SESSION['form_login'];} ?>" maxlength="10"><b>@monsite.be</b>
							</td>
							<td class="invalide">
								<?php if(isset($_SESSION['erreurform_login'])){echo $_SESSION['erreurform_login'];} ?>
							</td>							
						</tr>	
						<tr>
							<td>
								<label><b>Mot de passe</b></label>
							</td>
							<td>&nbsp;</td>							
						</tr>		
						<tr>
							<td>
								<input type="password" name="mdp" placeholder="mot de passe" value="<?php if(isset($_SESSION['form_mdp'])){ echo $_SESSION['form_mdp'];} ?>" maxlength="8">
							</td>
							<td class="invalide">
								<?php if(isset($_SESSION['erreurform_mdp'])){echo $_SESSION['erreurform_mdp'];} ?>
							</td>								
						</tr>	
						<tr><td>&nbsp;</td><td>&nbsp;</td></tr>		
						<tr colspan="2">
							<td><?php if(isset($_SESSION['erreur_connection'])){echo $_SESSION['erreur_connection'];} ?></td>
						</tr>		
						<tr><td>&nbsp;</td><td>&nbsp;</td></tr>								
						<tr>
							<td>
								<input type="submit" name="bouton" value="Connection"><input type="reset" name="Effacer" value="Effacer">
							</td>
							<td>&nbsp;</td>							
						</tr>
					</table>
				</form>			  

				
            </article>
        </section>
	</div>
</body>
</html>
<?php
	}
?>