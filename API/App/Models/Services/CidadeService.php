<?php
    namespace App\Services;

    error_reporting(E_ALL & ~E_NOTICE);
    define('__ROOT__', dirname(dirname(__FILE__))); 
    require_once(__ROOT__.'/App/Models/DAO/CidadeDAO.php'); 
    

    use \App\Models\DAO\CidadeDAO;
    

    class CidadeService{
        public function get($id = null) 
        {
            

            $cidadeDAO = new CidadeDAO();
            if ($id) {
                return $cidadeDAO->select($id);
            } else {
                return $cidadeDAO->selectAll();
            }
        }

        public function post() 
        {
            $cidadeAO = new CidadeDAO();
            $data = json_decode(file_get_contents("php://input"));

            return $cidadeDAO->insert($data);
        }

        public function update($data) 
        {
            $cidadeDAO = new CidadeDAO();
            return $userDAO->update($data);
        }
    }