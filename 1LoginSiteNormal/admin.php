<?php		
	// on demarre la session
	session_start();
?>
<html>
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />		
		<title>Administration</title>
	</head>
    <body BGCOLOR="#CCCCCC" TEXT="#17212F" STYLE="font-family:Arial">
		<div align="center">
		<h1>Administration</h1>	
		<?php
		// on vérifie si l'utilisateur est connecté
		if( isset($_SESSION['nom']) )
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
				// ce n'est pas un administrateur
				echo '<img src="img/cadenas.png"><br/><br/>Vous n\'avez pas accés!<br/><br/>';
				header('refresh:0;url=index.php');
			}
		}
		else
		{
			// l'utilisateur n'est pas passé par le formulaire
			echo '<img src="img/cadenas.png"><br/><br/>Vous n\'avez pas accés!<br/><br/>';
			header('refresh:3;url=index.php');
		}
			
		include('liens.php');
		?>
		</div>
	</body>
</html>