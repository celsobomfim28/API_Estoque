<?php

namespace App\Models\Entity;

class Usuario{

    // database connection and table name
    private $conn;
    private $table_name = "Usuario";

    //atributes
    public $idUsuario;
    public $nome;
    public $email;
    public $senha;
    public $cpf;
    public $endereco;
    public $bairo;
    public $num;
    public $cep;
    public $tel;
    public $senharesettoken;
    public $senharesetexpire;
    public $sexo;
    public $datacriacao;
    public $Cidade_idCidade;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

}
?>