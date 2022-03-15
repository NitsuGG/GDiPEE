<?php 

namespace Controller\Authentification;

use Model\Authentification\RegisterModel;
use Core\Security\DataSanitize;

class RegisterController
{
    public function show()
    {
        $req = req_view(["bloc/Header", "authentification/Register", "bloc/Footer"]);
        foreach ($req as $req) {
            require $req;
        }
    }

    public function register()
    {
        if (isset($_POST['last_name']) && isset($_POST['first_name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {

            $data_sanitizer = new DataSanitize;

            $last_name = $data_sanitizer->sanitize_var($_POST['last_name']);

            $first_name = $data_sanitizer->sanitize_var($_POST['first_name']);
        
            $email = $data_sanitizer->sanitize_var($_POST['email']);
        
            $password = $data_sanitizer->sanitize_var($_POST['password']);

            $password_confirm = $data_sanitizer->sanitize_var($_POST['password_confirm']);

        
            if ($password == $password_confirm) {
                
                $password = password_hash($password, PASSWORD_ARGON2ID);
        
                $register_model = new RegisterModel;
                $result = $register_model->register($first_name, $last_name, $email, $password, $password_confirm);
                header('location: ./');
        
                if (!$result) {
                    $result['error'] = true;
                    $result['message'] = "Une erreur s'est produite durant l'enregistrement de votre compte.";
                }
        
            }else{
                echo "Les mots de passes ne correspondent pas.";
            }
        }
    }
}
