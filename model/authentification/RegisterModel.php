<?php 

namespace Model\Authentification;

use Model\DbModel;

class RegisterModel
{
    public function register(string $first_name, string $last_name, string $email, string $password): ?array
    {

        $dbModel = new DbModel;

        $params = [
            ":first_name" => $first_name,
            ":last_name" => $last_name,
            ":email" => $email,
            ":password" => $password
        ];

        $query = $dbModel->insertInto("users")
                         ->values([
                             "null",
                             ":first_name",
                             ":last_name",
                             ":email",
                             ":password"
                        ]);
        $result = $query->modify($query, $params);

         return $result;
    }
}
