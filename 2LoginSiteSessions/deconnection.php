<?php 
    session_start();
    include_once('lib/php/fonctions.php'); 
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
   	
    <?php include('menu.php'); ?>
     <div id="contenu">
		<section>
            <article>  
                  
            <?php
                if( isset($_SESSION['id']) )
                {
                    echo '<info>Au revoir <b>'.$_SESSION['prenom'].' '.$_SESSION['nom'].'</b>, vous êtes déconnecté.</info></br></br>';
                    session_destroy();
                }
                else
                {
                    echo '<info>Vous n\'êtes pas connecté!</info></br></br>';
                }
                header('refresh:3;url=index.php');				
		        ?>
            </article>
        </section>
	</div>
</body>
</html>