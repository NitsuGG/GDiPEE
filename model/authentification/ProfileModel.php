<?php
namespace Model\Authentification;
use Model\DbModel;

class ProfileModel
{    
    /**
     * upload
     * Upload the article with character, date and the image file.
     * @param  string $personnage
     * @param  string $date
     * @param  string $filename
     * @return void
     */
    public function upload(string $personnage, string $date, string $filename)
    {
        $dbModel = new DbModel;
        $params = [":personnage" => $personnage,
                   ":date" => $date,
                   ":filename" => $filename
                   ];
        
        $query = $dbModel->insertInto("article")
                         ->values([
                             "null",
                             ":personnage",
                             ":date",
                             ":filename"
                             ]);
                             
        $result = $dbModel->query($query, $params);

        return $result;
    }
}