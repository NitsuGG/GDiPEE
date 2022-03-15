<?php 
use \Core\Security\DataSanitize;

function req_view(array $pages){
	$req = [];
 
	foreach ($pages as $page) {
		array_push($req, './view/'.$page.'View.php');
	}
	return $req;
}