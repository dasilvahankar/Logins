<?php
// fonction de connection � la BD
function connectBD()
{
	// connection � la BD
	$connection = mysqli_connect('127.0.0.1', 'root', '', 'monsite');
	
	// si la connection s'est effectu�e	
	if( $connection )
	{
		// on oblige mysql a prendre en compte le UTF8
		mysqli_set_charset( $connection , 'utf8' );
		
		return $connection;		
	}
	else  // la connection ne s'est pas effectu�e
	{
		return false;	
	}
}
?>