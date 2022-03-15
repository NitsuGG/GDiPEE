<?php

$host = 'localhost';
$database = 'GPiDEE';
$user = 'root';
$password = 'root';

try
{
	$db = new PDO('mysql:host='.$host.';charset=utf8;dbname='.$database, $user, $password);

	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // PDO::ERRMODE_EXCEPTION --> Afficher les erreurs uniquement. Mettre PDO::ERRMODE_WARNING pour renvoyer les alertes.
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	
} catch (PDOException $e){
	die('Erreur : ' . $e->getMessage());
}