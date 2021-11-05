<?php
    namespace App\Services;

    error_reporting(E_ALL & ~E_NOTICE);
    define('__ROOT__', dirname(dirname(__FILE__))); 
    require_once(__ROOT__.'/App/Models/DAO/CategoriaDAO.php'); 
    

    use \App\Models\DAO\CategoriaDAO;
    

    class CategoriaService{
        public function get($id = null) 
        {
            

            $categoriaDAO = new CategoriaDAO();
            if ($id) {
                return $categoriaDAO->select($id);
            } else {
                return $categoriaDAO->selectAll();
            }
        }

        public function post() 
        {
            $categoriaAO = new CategoriaDAO();
            $data = json_decode(file_get_contents("php://input"));

            return $categoriaDAO->insert($data);
        }

        public function update($data) 
        {
            $categoriaDAO = new CategoriaDAO();
            return $userDAO->update($data);
        }
    }