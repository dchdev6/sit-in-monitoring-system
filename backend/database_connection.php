<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(0);

date_default_timezone_set('Asia/Manila');

class Database {
    private static $instance;
    private $con;
    private $db_host = "localhost"; 
    private $db_username = "root"; 
    private $db_password = ""; 
    private $db_name = "ccs_system"; 
    private $db_port = "3306"; 

    private function __construct() {
        $this->con = mysqli_connect(
            $this->db_host, 
            $this->db_username, 
            $this->db_password, 
            $this->db_name, 
            $this->db_port
        );
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->con;
    }
}

?>
