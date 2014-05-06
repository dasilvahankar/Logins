<?php		
	// on demarre la session
	session_start();
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
				// on vérifie si l'utilisateur est connecté
				if( isset($_SESSION['id']) )
				{
					// on vérifie si c'est un administateur
					// 1 = c'est un administrateur
					// 0 = ce n'est pas un administrateur
					if( $_SESSION['admin'] == 1 )
					{
						echo '<img src="img/admin.png"><br/><br/>Vous avez accés!<br/><br/>';
					}
					else
					{
						//ce n'est pas un administrateur
						header('refresh:0;url=index.php');
					}
				}
				else
				{
					// l'utilisateur n'est pas connecté
					header('refresh:0;url=index.php');
				}
			?>
          </article>
        </section>
	</div>
</body>
</html>