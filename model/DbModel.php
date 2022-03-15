<?php
namespace Model;

class DbModel
{
    //NE PAS TOUCHER! (sinon t'es mort.) -- Expert technique.
    private static $_db;
    private static $_join;
    private static $_joinAs;
    private static $motor = 'MariaDb';
    private $prefix;

    public function __construct()
    {
        self::$_join = '';
        self::$_joinAs = '';

        switch (self::$motor) {
            case 'MariaDb':
                $this->prefix = "";
                break;

            default:
                $this->prefix = "'";
                break;
        }
    }
  
    /**
     * setDb
     *Instantiate Database connexion.
     * @return void
     */
    private function setDb()
    {
        require './inc/db.php';
        return self::$_db = $db;
    }
   
    /**
     * getDb
     * Return database connexion
     * @return object $_db
     */
    public function getDb()
    {
        if (self::$_db == null) {
            $this->setDb();
        }
        return self::$_db;
    }

    /**
     * getAll
     * Obtain all information about table passed in parameters.
     * @param  string $table choose the table from which you want informations
     *
     * @return array
     */
    public function getAll(string $table)
    {
        $req = $this->getDb()->prepare('SELECT * FROM ' . $table);
        $req->execute();
        $var = $req->fetchAll(\PDO::FETCH_ASSOC);
        $req->closeCursor();
        return $var;
    }

    /**
     * query
     * execute a query with the object passed in parameters
     * @param  array $obj put the variable which contain your query
     * @param  array $params if you have a WHERE in your query place the param to execute
     *
     * @return array the first result found in the database
     */
    public function query(DbModel $obj, array $params = null)
    {
        $query = "";

        foreach ($obj as $obj => $value) {
            $query = $query . " " . $value;
        }

        $req = $this->getDb()->prepare("{$query}");
        $req->execute($params);
        $var = $req->fetch(\PDO::FETCH_ASSOC);
        $req->closeCursor();
        return $var;
        //return $req->queryString;
    }

    /**
     * queryAll
     * execute a query with the object passed in parameters
     * @param  object $obj put the variable which contain your query
     * @param  array $params if you have a WHERE in your query place the param to execute
     *
     * @return array all informations find in the database
     */
    public function queryAll(DbModel $obj, array $params = null)
    {
        $query = "";

        foreach ($obj as $obj => $value) {
            $query = $query . " " . $value;
        }
        $req = $this->getDb()->prepare("{$query}");
        $req->execute($params);
        $var = $req->fetchAll(\PDO::FETCH_ASSOC);
        $req->closeCursor();
        return $var;
        //return $req->queryString;
    }

    /**
     * modify
     *execute a query without fetch/fetchAll
     * @param  object $obj
     * @param  array $params
     *
     * @return void
     */
    public function modify(DbModel $obj, array $params = null)
    {
        $query = "";

        foreach ($obj as $obj => $value) {
            $query = $query . " " . $value;
        }
        $req = $this->getDb()->prepare("{$query}");
        $req->execute($params);
        $count = $req->rowCount();
        // $req->debugDumpParams();
        $req->closeCursor();
        // echo "<br><br>Nombre de lignes affectées : $count<br><br>";
    }

    /**
     * select
     * select column in the database
     * @param array $data ['id', 'name', 'age'] ** If you select different table put an AS on the column selected
     *
     * @return object
     */
    public function select(array $data)
    {
        $str = "";

        foreach ($data as $key => $value) {
            if ($key == 0) {
                $str = $value;
            } else {
                $str = "$str, $value";
            }
        }

        $str = "SELECT $str";
        $this->select = $str;

        return $this;
    }

    /**
     * from
     * specifies which table is selected in the database
     * @param  string $table name of the table
     *
     * @return object
     */
    public function from(string $table)
    {
        $str = "FROM $table";
        $this->from = $str;

        return $this;
    }

    /**
     * where
     * put a condition in the query
     * @param  string $params write your condition
     *
     * @return string
     */
    public function where(string $params)
    {
        $str = "WHERE $params";
        $this->where = $str;
        return $this;
    }

    /**
     * join
     * specifies wich table is join on the query
     * @param array $join ['tableToJoin' => 'id tableToJoin', 'currentTable' => 'id currentTable']
     *
     * @return self
     */
    public function join(array $join, string $prefix = null)
    {
        $str = "";
        $key = array_keys($join);
        $firstValue = $join[$key[0]];
        $lastValue = $join[$key[1]];

        $str = "$prefix JOIN $key[0] ON $key[1].$lastValue = $key[0].$firstValue";
        self::$_join = self::$_join . ' ' . $str;
        $this->join = self::$_join;

        return $this;
    }
    /**
     * joinAs
     * specifies wich table is join on the query
     * @param  array $join ['tableToJoin' => 'id tableToJoin','alias tableToJoin', 'currentTable' => 'id currentTable']
     *
     * @return string
     */
    public function joinAs(array $join, string $prefix = null)
    {
        $str = "";
        $key = array_keys($join);
        $firstValue = $join[$key[0]];
        $lastValue = $join[$key[2]];

        $str = "$prefix JOIN $key[0] AS " . $join[0] . " ON $key[2].$lastValue = " . $join[0] . ".$firstValue";

        self::$_joinAs = self::$_joinAs . ' ' . $str;
        $this->joinAs = self::$_joinAs;
        return $this;
    }

    /**
     * orderBy
     * specifies how the respons is ordered
     * @param  array $data ['', '', '']
     *
     * @return string
     */
    public function orderBy(array $data)
    {
        $str = "";

        foreach ($data as $key => $value) {
            if ($key == 0) {
                $str = $value;
            } else {
                $str = "$str, $value";
            }
        }

        $str = "ORDER BY $str";
        $this->orderBy = $str;
        return $this;
    }

    /**
     * groupBy
     * specifies how the respons is ordered
     * @param  string $params write what you want to group
     *
     * @return string
     */
    public function groupBy(string $params)
    {
        $str = "GROUP BY $params";
        $this->groupBy = $str;
        return $this;
    }

    /**
     * having
     *
     * @param  string $params
     *
     * @return string
     */
    public function having(string $params)
    {
        $str = "HAVING $params";
        $this->having = $str;
        return $this;
    }

    /**
     * update
     * specifies which table is update
     * @param  string $table choose the table to update
     *
     * @return object
     */
    public function update(string $table)
    {
        $str = "UPDATE $table";
        $this->update = $str;
        return $this;
    }

    /**
     * set
     * set the new values to update
     * @param  array $data ['column1' => 'new value', 'value2' => 'new value'...]
     *
     * @return string
     */
    public function set(array $data)
    {
        $str = "";
        $keys = array_keys($data);

        foreach ($data as $key => $value) {
            if ($key == $keys[0]) {
                $str = "$key = " . $this->prefix . "$value" . $this->prefix;
            } else {
                $str = "$str, $key = " . $this->prefix . "$value" . $this->prefix;
            }
        }

        $str = "SET $str";
        $this->set = $str;
        return $this;
    }

    /**
     * delete
     * specifies which table must be deleted
     * you can add a "*" as parameter for MariaDb or other SQL engines
     * MariaDb -> DELETE FROM...
     * MyIsam -> DELETE * FROM...
     * @param  string $table choose the table to delete
     *
     * @return object
     */
    public function delete(string $param = null)
    {
        $str = "DELETE $param";
        $this->delete = $str;
        return $this;
    }

    /**
     * union
     * create an union in the query
     * @return string
     */
    public function union()
    {
        $str = "UNION";
        $this->union = $str;
        return $this;
    }

    /**
     * insertInto
     * specifies in which table we have to insert
     * @param  string $table choose the table wich you want to insert into
     *
     * @return object
     */
    public function insertInto(string $table)
    {
        $str = "INSERT INTO $table";
        $this->insertInto = $str;
        return $this;
    }

    /**
     * values
     * specifies the values to insert
     * @param  array $data ['', '', '']
     *
     * @return string
     */
    public function values(array $data)
    {
        $str = "";

        foreach ($data as $key => $value) {
            if ($key == 0) {
                $str = $this->prefix . "$value" . $this->prefix;
            } else {
                $str = "$str, " . $this->prefix . "$value" . $this->prefix;
            }
        }

        $str = "VALUES ($str)";
        $this->values = $str;
        return $this;
    }

    /**
     * limit
     * define the limit of the query
     * @param  int $limit
     * @param  int $offset
     *
     * @return string
     */
    public function limit(int $limit, int $offset = null)
    {
        $str = "";

        if ($offset == null) {
            $str = "LIMIT $limit";
        } else {
            $str = "LIMIT $offset, $limit";
        }

        $this->limit = $str;
        return $this;
    }
}
