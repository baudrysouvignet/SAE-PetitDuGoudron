<?php

require_once '../config/config.php';

class DatabaseManager
{
    protected $pdo;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données ! " . $e->getMessage());
        }
    }


    /**
     * @param string $request
     * @param array $param
     * @param string $pdoOptions
     *
     * @return array
     *
     * Function for create a request to the database and return the result
     */
    public function select(
        string $request,
        array $param = [],
        string $pdoOptions = PDO::FETCH_ASSOC
    ):array
    {
        try {
            $query = $this->pdo->prepare($request);
            $query->execute($param);
            return $query->fetchAll($pdoOptions);
        } catch (PDOException $e) {
            die("La requête SQL à échoué: " . $e->getMessage());
        }

    }
}