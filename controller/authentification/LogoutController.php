<?php 

namespace Controller\Authentification;

class LogoutController
{
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('location: ./');
    }
}
