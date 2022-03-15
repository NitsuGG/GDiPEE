<?php
session_start();
ob_start();

use Core\Security\DataSanitize;

require './inc/ViewFunction.php';
require './vendor/autoload.php';

try{
$getUrl = $_GET['url'];
$router = new \Core\Router\Router($getUrl);
$data_sanitize = new DataSanitize;
$data_sanitize->generate_csrf_token(29723, 623458);

$data_sanitize->csrf_verification($_SESSION['csrf_token']);


//Login
$router->get("/", "Authentification\Login#show");
$router->post("/", "Authentification\Login#login");

/* //Partie register 
$router->get("/register/", "Authentification\Register#show");
$router->post("/register/", "Authentification\Register#register"); */

//Page d'acceuil
$router->get("/historique/", "Historique#show");
$router->post("/historique/", "Historique#send_ref");

//logout
$router->get("/logout/", "Authentification\Logout#logout");



$router->run();
}catch(\Exception $th){

    $result['error'] = true;
    $result['message'] = $th->getMessage();
    $req = req_view(["bloc/header", "bloc\ErrorMessage","bloc/footer"]);
        foreach ($req as $req) {
            require $req;
        }
}