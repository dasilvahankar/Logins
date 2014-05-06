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
	<link rel="stylesheet" href="css/jquery-ui.css" type="text/css">
    <!-- Javascript -->	
	<script src="lib/js/jquery-1.10.2.js"></script>
	<script src="lib/js/jquery-ui.js"></script>	
	 <script>
		$(function() 
		{
			//DATE VALIDE = être majeur et avoir 100 ans maximum
			$( "#datepicker" ).datepicker({ 
				minDate: "-100Y", 
				maxDate: "-18Y", 
				dateFormat: "dd-mm-yy",
				changeMonth: true, 
				changeYear: true
			});
		});
	</script>	
</head>
<body>
    <header>
        <h1>Inscription</h1>
    </header>

	<?php include_once('menu.php'); ?>
    <div id="contenu">
		<section>
            <article>

				<form name="inscription" method="post" action="trtinscription.php">
				<table>
					<tr>
						<td>
							<label><b>Nom (2 à 20 caractéres et ne doit pas commencer par espace)</b></label>
						</td>
						<td>&nbsp;</td>								
					</tr>				
					<tr>
						<td>
							<input type="text" name="nom" placeholder="nom" value="<?php if(isset($_SESSION['form_nom'])){ echo $_SESSION['form_nom'];} ?>" maxlength="20">
						</td>
						<td class="invalide">
							<?php if(isset($_SESSION['erreurform_nom'])){echo $_SESSION['erreurform_nom'];} ?>
						</td>								
					</tr>					
					<tr>
						<td>
							<label><b>Prénom</b></label>
						</td>
						<td>&nbsp;</td>								
					</tr>				
					<tr>
						<td>
							<input type="text" name="prenom" placeholder="prénom" value="<?php if(isset($_SESSION['form_prenom'])){ echo $_SESSION['form_prenom'];} ?>" maxlength="20">
						</td>
						<td class="invalide">
							<?php if(isset($_SESSION['erreurform_prenom'])){echo $_SESSION['erreurform_prenom'];} ?>
						</td>								
					</tr>
					<tr>
						<td><label><b>Sexe</b></label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
						<?php
							if( isset($_SESSION['form_sexe']) )
							{
								switch( $_SESSION['form_sexe'] )
								{
									case 'F':
										echo '<input type="radio" name="sexe" value="F" checked>F';
										echo '<input type="radio" name="sexe" value="M">M';
										echo '<input type="radio" name="sexe" value="A">Autre';
									break;
									case 'M':
										echo '<input type="radio" name="sexe" value="F">F';
										echo '<input type="radio" name="sexe" value="M" checked>M';
										echo '<input type="radio" name="sexe" value="A">Autre';
									break;
									case 'A':
										echo '<input type="radio" name="sexe" value="F">F';
										echo '<input type="radio" name="sexe" value="M">M';
										echo '<input type="radio" name="sexe" value="A" checked>Autre';
									break;									
								}
							}
							else
							{
								echo '<input type="radio" name="sexe" value="F" checked>F';
								echo '<input type="radio" name="sexe" value="M">M';
								echo '<input type="radio" name="sexe" value="A">Autre';							
							}
						?>
						</td>
						<td class="invalide">
							<?php if(isset($_SESSION['erreurform_sexe'])){echo $_SESSION['erreurform_sexe'];} ?>
						</td>	
					</tr>
					<tr>
						<td><label><b>Date de naissance</b></label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><input id="datepicker" type="text" name="datenaissance" placeholder="date de naissance"" value="<?php if(isset($_SESSION['form_datenaissance'])){ echo $_SESSION['form_datenaissance'];} ?>"></td>
						<td class="invalide">
							<?php if(isset($_SESSION['erreurform_datenaissance'])){echo $_SESSION['erreurform_datenaissance'];} ?>
						</td>
					</tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
					<tr>
						<td><label><b>Pays</b></label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
						<?php
							//création du tableau des pays
							$pays = creer_pays();
						
							//création de la liste des pays dynamiquement
							//"\n" pour faire la mise en page du code lors de l'affichage
							echo '<select name="pays">'."\n";
							foreach( $pays as $code=>$nom )
							{
								echo '<option value="'.$code.'" ';
								if( isset($_SESSION['form_pays']) )
								{
									//on vérifie si le choix du pays déjà choisi
									//est le même que le pays qui est fait
									if( $code == $_SESSION['form_pays'] )
									{
										echo 'selected';
									}
								}
								else
								{
									//on sélectionne la Belgique comme pays par défaut
									if( $code == 'BE' )
									{
										echo 'selected';
									}									
								}
								
								echo '>'.$pays[$code]['FR'].'</option>'."\n";
							}
							echo '</select><br/><br/>';
						?>						
						</td>
						<td class="invalide">
							<?php if(isset($_SESSION['erreurform_pays'])){echo $_SESSION['erreurform_pays'];} ?>
						</td>		
					</tr>
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
					<tr>
						<td><label><b>Adresse IP</b></label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo $_SERVER['REMOTE_ADDR']; ?></td>
						<td>&nbsp;</td>
					</tr>					
					<tr><td>&nbsp;</td><td>&nbsp;</td></tr>	
					<tr>
						<td>
							<input type="submit" name="bouton" value="Inscription"><input type="reset" name="Effacer" value="Effacer">
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