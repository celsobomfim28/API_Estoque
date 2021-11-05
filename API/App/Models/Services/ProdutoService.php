<?php
    namespace App\Services;

    error_reporting(E_ALL & ~E_NOTICE);
    define('__ROOT__', dirname(dirname(__FILE__))); 
    require_once(__ROOT__.'/App/Models/DAO/ProdutoDAO.php'); 
    

    use \App\Models\DAO\ProdutoDAO;
    

    class ProdutoService{
        public function get($id = null) 
        {
            
            $produtoDAO = new ProdutoDAO();
            if ($id) {
                return $produtoDAO->select($id);
            } else {
                return $produtoDAO->selectAll();
            }
        }

        public function post() 
        {
            $produtoAO = new ProdutoDAO();
            $data = json_decode(file_get_contents("php://input"));

            return $produtoDAO->insert($data);
        }

        public function update($data) 
        {
            $produtoDAO = new ProdutoDAO();
            return $userDAO->update($data);
        }
    }