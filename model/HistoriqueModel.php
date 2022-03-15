<?php

namespace Model;

class HistoriqueModel
{

    /**
     * historique
     * Show all data from measure
     * @return array
     */
    public function historique(): array
    {
        $dbModel = new DbModel;
        $result = $dbModel->getAll("measure");
        return $result;
    }

    /**
     * monitoring
     * Show the last data receive
     * @return void
     */
    public function monitoring(): bool | array
    {
        $dbModel = new DbModel;

        $query = $dbModel->select(["wind_speed", "deg", "cardinal", "production"])
            ->from("measure")
            ->orderBy(["id"])
            ->limit(1);

        $result = $dbModel->query($query);

        return $result;
    }
    
    /**
     * graph
     * Return wind_speed, production, datetime from measure
     * @return array
     */
    public function graph(): array | bool
    {
        $dbModel = new DbModel;
        $query = $dbModel->select(["wind_speed, production, datetime"])
                         ->from("measure")
                         ->orderBy(["id DESC"])
                         ->limit(300);
        $result = $dbModel->queryAll($query);
        return $result;
    }
}
