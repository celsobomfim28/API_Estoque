<?php

    class Database{

        //Mysql
        
        public $conn;

        // get the database connection
        public function getConnection(){
            $DBDRIVE = 'mysql';
            $DBHOST = 'localhost';
            $DBNAME = 'Estoque';
            $DBUSER = 'root';
            $DBPASS = '16051986';
  
            $this->conn = null;
  
            try{
                $this->conn = new PDO("mysql:host=" . $DBHOST. ";dbname=" . $DBNAME, $DBUSER, $DBPASS);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Connection error: " . $exception->getMessage();
            }
  
            return $this->conn;
        }

    
    }
    ?>

