<?php
 ini_set('display_errors', 1); 
 ini_set('display_startup_errors', 1); 
 error_reporting(E_ALL);

 header('Access-Control-Allow-Origin: *');

 // Allow specific headers
 header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
 
 // Allow specific HTTP methods
 header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

class Database {
    // DB Params
    // private $servername = "localhost";
    // private $username = "u896018919_scribloADMIN";
    // private $password = "2P=u>3LQ*S@N";
    // private $dbname = "u896018919_scriblo";

    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "scriblo";


    // private $servername = "localhost";
    // private $username = "u896018919_preProdScriblo";
    // private $password = "f6[peCfF";
    // private $dbname = "u896018919_preProdScriblo";



    private $conn;

    // DB Connect
    public function connect() {
      $this->conn = null;

      try { 
        $this->conn = new PDO('mysql:host=' . $this->servername . ';dbname=' . $this->dbname, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
  }