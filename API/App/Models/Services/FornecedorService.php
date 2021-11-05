<?php
    namespace App\Services;
    
    error_reporting(E_ALL & ~E_NOTICE);
    define('__ROOT__', dirname(dirname(__FILE__))); 
    use \App\Models\DAO\FornecedorDAO;
    require_once(__ROOT__.'/App/Models/DAO/FornecedorDAO.php'); 

    class FornecedorService
    {
        
        public function get($id = null) 
        {
            $fornecedorDAO = new FornecedorDAO();
            if ($id) {
                return $fornecedorDAO->select($id);
            } else {
                return $fornecedorDAO->selectAll();
            }
        }

        public function post() 
        {
            $fornecedorDAO = new FornecedorDAO();
            $data = json_decode(file_get_contents("php://input"));

            return $fornecedorDAO->insert($data);
        }
        
        public function update($data) 
        {
            $fornecedorDAO = new FornecedorDAO();
            $data = json_decode(file_get_contents("php://input"));
            return $fornecedorDAO->update($data);
        }

        public function delete() 
        {
            
        }
    }