<?php

namespace App\Models\Entity;

error_reporting(E_ALL & ~E_NOTICE);

class Fornecedor{

    // database connection and table name
    private $conn;
    private $table_name = "Fornecedor";

    //atributes
    public $idFornecedor;
    public $fornecedor;
    public $endereco;
    public $bairo;
    public $num;
    public $cep;
    public $tel;
    public $contato;
    public $cnpj;
    public $insc;
    public $Cidade_idCidade;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

}
?>