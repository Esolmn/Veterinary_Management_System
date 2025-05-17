<?php

    class Database {
        private $localhost = "localhost";
        private $username = "root";
        private $password = "Soliman28Jeds04";
        private $database = "vet_db";
        private $conn;

        public function __construct(){
            try {
                $db = "mysql:host=$this->localhost; dbname=$this->database; charset=utf8mb4";

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => false
                ];

                $this->conn = new PDO($db, $this->username, $this->password, $options);


            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        public function getConnection() {
            return $this->conn;
        }

        public function setConnection($conn) {
            $this->conn = $conn;
        }

        public function __destruct()
        {
            $this->conn = null;
        }
    }

?>