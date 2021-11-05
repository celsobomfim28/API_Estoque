<?php

namespace App\Models\Entity;

error_reporting(E_ALL & ~E_NOTICE);

class Cidade {

    // database connection and table name
    private $conn;
    private $table_name = "Cidade";

    //atributos
    public $idCidade;
    public $cidade;
    public $uf;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
}