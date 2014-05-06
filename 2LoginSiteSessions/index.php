<?php 
    session_start();
    include_once('lib/php/fonctions.php'); 
?>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<title>Accueil</title>
    
    <!-- Feuilles de styles -->
	<link rel="stylesheet" href="css/styles.css" type="text/css">
</head>
<body>
    <header>
        <h1>Accueil</h1>
    </header>

    <?php include_once('menu.php'); ?>
    <div id="contenu">
        <section>
            <article>

			    <b>Mon adresse IP est:</b> <?php echo $_SERVER['SERVER_ADDR']; ?> <br/>
                <b>Votre adresse IP est:</b> <?php echo $_SERVER['REMOTE_ADDR']; ?>
                <br/><br/><br/><br/>
                Pour accéder a ce site, vous devez être connecté!

            </article>
        </section>
    </div>
	
	<footer>
	</footer>
</body>
</html>