<?php 

namespace Model\Authentification;

use Model\DbModel;

class LoginModel
{    
    /**
     * login
     * Return an array if $pseudo is in the data base
     * @param  string $pseudo
     * @return bool
     * @return array
     */
    public function login(string $email): bool | array
    {

        $dbModel = new DbModel;

        $params = [":email" => $email];

        $query =$dbModel->select(["*"])
                ->from("users")
                ->where("email = :email");

        $result = $dbModel->query($query, $params);

        return $result;
    }
}
