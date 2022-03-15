<?php

namespace Controller\Authentification;

use Core\Authentification\User;
use Core\Security\DataSanitize;
use Model\Authentification\LoginModel;

class LoginController
{    
    /**
     * show
     * Show header, login page and footer.
     * @return void
     */
    public function show()
    {
        $data_sanitizer = new DataSanitize;
        $req = req_view(["bloc/Header", "bloc/ErrorMessage" ,"authentification/Login", "bloc/Footer"]);
        foreach ($req as $req) {
            require $req;
        }
    }
    
    /**
     * login
     * Check if user's data are correct and connect the user before 
     * show login page or redirect to profile page.
     * @return void
     */
    public function login()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {


            $data_sanitizer = new DataSanitize;
            $pseudo = $data_sanitizer->sanitize_var($_POST['email']);

            $password = $data_sanitizer->sanitize_var($_POST['password']);

            $login_model = new LoginModel;
            $data = $login_model->login($pseudo);

            if ($data) {
                if (password_verify($password, $data['password'])) {

                    $user = new User($data['id'], $data['email'], $data['email']);
                    $_SESSION['user'] = serialize($user);
                    header('location: ./historique');
                } else {
                    echo "Email ou mot de passe incorrect.";
                }
            } else {
                echo "Email ou mot de passe incorrect.";
            }

            $this->show();
        }
    }
}