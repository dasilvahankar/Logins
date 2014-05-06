<?php 
	//ajout des fonctions
    require_once('lib/php/fonctions.php');
	//on demarre la session
    session_start();
?>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<title>page 1</title>
    
    <!-- Feuilles de styles -->
	<link rel="stylesheet" href="css/styles.css" type="text/css">
</head>
<body>
    <header>
        <h1>Page 1</h1>
    </header>

    <?php include_once('menu.php'); ?>
    <div id="contenu">
        <section>
            <article>
				<?php
					//gestion par les variables de SESSION du nombre de visites sur la page
					if( isset($_SESSION['page1']) )
					{
						$_SESSION['page1']++;
					}
					else
					{
						$_SESSION['page1'] = 1;
					}
					echo '<p>Vous avez visité <b>'.$_SESSION['page1'].'</b>x la page '.$_SERVER['PHP_SELF'].'</p>';
				?>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
				<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>
				<p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</p>
				
				<?php
					if( isset($_POST['bouton']) )
					{
						//appel de la fonction valider pour valider le champ "md5"
						if( valider_md5($_POST['md5']) )
						{
							$_SESSION['ErreurMd5Msg'] = '';
							echo '<b>md5(</b> '.$_POST['md5'].' <b>) = [</b> '.md5($_POST['md5']).' <b>]</b><br/><br/>';						
						}
						else
						{
							$_SESSION['ErreurMd5Msg'] = '<erreur>veuillez entrer des données!</erreur>';
						}
					}
				?>
								
				<form name="md5" method="post" action="page1.php">
					<table>
						<tr>
							<td>
								<b><label><b>Données à crypter</b></label></b>
							</td>
							<td>&nbsp;</td>							
						</tr>					
						<tr>
							<td>
								<input type="text" name="md5" placeholder="données à crypter" maxlength="32">
							</td>
							<td class="invalide">
								<?php echo isset($_SESSION['ErreurMd5Msg']) ? $_SESSION['ErreurMd5Msg'] : '' ?>
							</td>							
						</tr>	
						<tr><td>&nbsp;</td><td>&nbsp;</td></tr>	
						<tr>
							<td>
								<input type="submit" name="bouton" value="Crypter en MD5"><input type="reset" name="Effacer" value="Effacer">
							</td>
							<td>&nbsp;</td>							
						</tr>
					</table>
				</form>
            </article>
        </section>
    </div>
	
	<footer>
	</footer>
</body>
</html>