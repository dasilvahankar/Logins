<?php
	//require_once('config.php');	
	require_once('fonctions.php');	

	//connection à la BD
	$connection = ConnectBD();
		
	//On vérifie si on est bien connecté
	if( $connection )
	{
		try
		{
			//on tente d'exécuter les requêtes suivantes dans une transaction
			//on lance la transaction
			$connection->beginTransaction();
			
			$tabPays = creer_pays();
			
			foreach( $tabPays as $code=>$nom )
			{
				//on exécute la commande sql d'inscription
				$connection->exec('INSERT INTO pays VALUES(null,"'.$code.'","'.$tabPays[$code]['FR'].'","'.$tabPays[$code]['EN'].'")');
			}
			//si jusque là tout se passe bien on valide la transaction
			$connection->commit();

			$contenu = '<info>Table pays crée!</info>';
		}
		catch(PDOException $e) // en cas d'erreur
		{
			// on annule la transaction
			$connection->rollback();
			
			// on affiche un message d'erreur ainsi que les erreurs
			$contenu  = '<erreur>Erreur [0021]: Erreur lors de la requête, veuillez contacter votre administrateur!</erreur>';		
			$contenu .= '<erreur>Erreur : '.$e->getMessage().'</erreur><br/>';
			$contenu .= '<erreur>N° : '.$e->getCode().'</erreur><br/>';
			
			//on arrête l'exécution s'il y a du code après
			exit();
		}	
	}
	else
	{
		$contenu = '<erreur>Erreur [001]: Impossible de se connecter à la BD, veuillez contacter votre administrateur!</erreur>';						
	}
?>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<title>Script BD</title>
    
    <!-- Feuilles de styles -->
	<link rel="stylesheet" href="../../css/styles.css" type="text/css">
</head>
<body>
    <header>
        <h1>Script BD</h1>
    </header>

    <div id="contenu">
        <section>
            <article>

				<?php echo $contenu; ?>
                <br/><br/><br/><br/>

            </article>
        </section>
    </div>
	
	<footer>
	</footer>
</body>
</html>