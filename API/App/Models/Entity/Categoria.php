<?php
namespace App\Models\Entity;

error_reporting(E_ALL & ~E_NOTICE);

class Categoria {

    // database connection and table name
    private $conn;
    private $table_name = "Categoria";

    //atributos
    public $idCategoria;
    public $categoria;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
}