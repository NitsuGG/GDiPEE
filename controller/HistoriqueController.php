<?php

namespace Controller;

use Model\HistoriqueModel;
use \Core\Security\DataSanitize;
use Exception;

class HistoriqueController
{    
    /**
     * show
     * Show history of anemometer's, monitoring and the graph
     * @return void
     */
    public function show(): void
    {
        if (!isset($_SESSION['user'])) {
            header('location: ./');
        }
        //Tableau historique
        $historiqueModel = new HistoriqueModel;
        $result_historique = $historiqueModel->historique();
        $data_sanitizer = new DataSanitize;
        $result_historique = ($result_historique) ? $data_sanitizer->sanitize_array($result_historique) : false;

        //Monitoring
        $monitoring_model = new HistoriqueModel;
        $result_monitoring = $monitoring_model->monitoring();
        $result_monitoring = ($result_monitoring) ? $data_sanitizer->sanitize_array($result_monitoring) : false;


        //Graph
        $graphModel = new HistoriqueModel;
        $result_graph = $graphModel->graph();
        $result_graph = ($result_graph) ? $data_sanitizer->sanitize_array($result_graph) : false;

        $wind_speed = [];
        $production = [];
        $datetime = [];

        foreach ($result_graph as $key => $value) {
            $wind_speed[] = $value["wind_speed"];
            $production[] = $value["production"];
            $datetime[] = $value["datetime"];
        }

        $wind_speed = json_encode($wind_speed);
        $production = json_encode($production);
        $datetime = json_encode($datetime);


        $file = "./assets/ValRef.txt";
        $file = fopen($file, "r");
        $valref = fread($file, 1000);
        $valref = intval($valref);


        $req = req_view(["bloc/header","bloc\ErrorMessage", "page/historique", "bloc/footer"]);
        foreach ($req as $req) {
            require $req;
        }
    }

    public function send_ref()
    {
        $newData = null;
        $data_sanitizer = new DataSanitize;
        try{
            if (isset($_POST['alert_slider'])) {
                $newData = $data_sanitizer->sanitize_var($_POST['alert_slider']);
                $file = "./assets/ValRef.txt";
                if (is_writable($file)) {
                    $file = fopen($file, "a");
                    ftruncate($file, 0);
                    fwrite($file, $newData);
                    fclose($file);
                }
                $result["error"] = false;
                $result["message"] = "La valeur de réference à bien été modifiée: $newData km/h.";
            }else {
                throw new Exception("Erreur lors de l'envoie de la valeurs : $newData");
            }
        }catch (\Exception $th) {
            $result['error'] = true;
            $result['message'] = $th->getMessage();
        }
        
        echo $result['message'];
        
    }
    
}