<?php 
	//ajout des fonctions
    require_once('lib/php/fonctions.php');
	
	//
	// session_set_cookie_params( lifetime , path , domain , secure , httponly )
	// = seulement pour les cookies de session !!!
	// = setcookie( 'login' , $_SESSION['login'] )
	//
	// lifetime	: spécifie la durée de vie du cookie en secondes. La valeur de 0 signifie : "Jusqu'à ce que le navigateur soit éteint". La valeur par défaut est 0.
	// path		: le chemin dans le domaine où le cookie sera accessible. Utilisez un simple slash ('/') pour tous les chemins du domaine. 
	// domain	: le domaine du cookie, par exemple 'www.php.net'. Pour rendre les cookies visibles sur tous les sous-domaines, le domaine doit être préfixé avec un point, tel que '.php.net'.
	// secure	: si TRUE, le cookie ne sera envoyé que sur une connexion sécurisée. Par défaut, cette option est à off.
	// httponly	: si TRUE, marque le cookie pour qu'il ne soit accessible que via le protocole HTTP. Cela signifie que le cookie ne sera pas accessible par les langage de script, comme Javascript. Cette configuration permet de limiter les attaques comme les attaques XSS (bien qu'elle n'est pas supporté par tous les navigateurs).
	//

	//configuration des cookies qui ne durent que pendant une session
	//avant session_start() !!!
	//session_set_cookie_params($duree,$chemin,$domaine,$https,$httponly); 	

	//on demarre la session
    session_start();
	
	// création d'un cookie si la personne est connectée
	if( !isset($_SESSION['id']) )
	{
		if( isset($_COOKIE['login']) )
		{	
			//echo '<img src="img/cookie.png"> Login avec des COOKIES sur '.$_SERVER['SERVER_NAME'].' :)<br/>';
			login(connectBD(),$_COOKIE['login'],$_COOKIE['mdp']);	
		}
		else
		{
			//echo 'Pas de COOKIES sur '.$_SERVER['SERVER_NAME'].' :(<br/>';		
		}
	}
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

            </article>
        </section>
    </div>
	
	<footer>
	</footer>
</body>
</html>