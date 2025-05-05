<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Manila');

// Check if the Database class already exists before declaring it
if (!class_exists('Database')) {
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
            
            // Check connection
            if (mysqli_connect_errno()) {
                die("Database connection failed: " . mysqli_connect_error());
            }
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
}

// For backward compatibility
$conn = Database::getInstance()->getConnection();
?>
