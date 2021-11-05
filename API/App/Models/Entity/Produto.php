<?php

namespace App\Models\Entity;

error_reporting(E_ALL & ~E_NOTICE);

class Produto{

    // database connection and table name
    private $conn;
    private $table_name = "Produto";

    //atributes
    public $idProduto;
    public $descricao;
    public $peso;
    public $controlado;
    public $qtdemin;
    public $Catogoria_idCategoria;
    public $Fornecedor_idFornecedor;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

}
?>