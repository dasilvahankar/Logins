<?php 
	//ajout des fonctions
    require_once('lib/php/fonctions.php');
	//on demarre la session
    session_start();
	
	if( isset($_SESSION['id']) )
	{
		$contenu = '<info>Au revoir <b>'.$_SESSION['prenom'].' '.$_SESSION['nom'].'</b>, vous êtes déconnecté.</info></br></br>';

		//effacement des cookies 'login' et 'mdp'		
		setcookie('login',$_SESSION['login'],time()-3600,$GLOBALS['chemin'],$GLOBALS['domaine'],$GLOBALS['https'],$GLOBALS['httponly']); 
		setcookie('mdp',$_SESSION['mdp'],time()-3600,$GLOBALS['chemin'],$GLOBALS['domaine'],$GLOBALS['https'],$GLOBALS['httponly']); 
		
		//fermeture de la session utilisateur
		session_destroy();
	}
	else
	{
		$contenu = '<info>Vous n\'êtes pas connecté!</info></br></br>';
	}
	header('refresh:2;url=index.php');				
?>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<title>Déconnection</title>
    
    <!-- Feuilles de styles -->
	<link rel="stylesheet" href="css/styles.css" type="text/css">
</head>
<body>
    <header>
        <h1>Déconnection</h1>
    </header>
   	
	<?php include_once('menu.php'); ?>
     <div id="contenu">
		<section>
            <article>  
                  
				<?php
				echo $contenu;
		        ?>
				
            </article>
        </section>
	</div>
</body>
</html>