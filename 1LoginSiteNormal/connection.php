<?php include_once('lib/php/fonctions.php'); ?>
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

	<?php include('menu.php'); ?>
    <div id="contenu">
		<section>
            <article>
				<form name="FormConnection" method="post" action="profil.php">
					<label><b>Login</b></label><br/>						
					<input type="text" name="login" placeholder="login" value="" maxlength="10">@monsite.be<br/>
					<label><b>Mot de passe</b></label><br/>						
					<input type="password" name="mdp" placeholder="mot de passe" value="" maxlength="8"><br/><br/>
					<input type="submit" name="bouton" value="Connection"><input type="reset" name="Effacer" value="Effacer">
				</form>
            </article>
        </section>
	</div>
</body>
</html>