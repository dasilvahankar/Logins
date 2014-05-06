<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="page1.php">Page 1</a></li>
        <li><a href="page2.php">Page 2</a></li>
        <?php
            if( isset($_SESSION['id']) )
            {
                echo '<li><a href="deconnection.php">Deconnection</a></li>
                      <li><a href="profil.php">Profil</a></li>';
					  
                if( $_SESSION['admin'] == 1 )
				{
					echo '<li><a href="admin.php">Administration</a></li>';
				}
            }
            else
            {
                echo '<li><a href="inscription.php">Inscription</a></li>
                      <li><a href="connection.php">Connection</a></li>';
            }
        ?>
    </ul>
</nav>
