<?php
    namespace App\Services;

    define('__ROOT__', dirname(dirname(__FILE__))); 
    use \App\Models\DAO\UsuarioDAO;
    require_once(__ROOT__.'/App/Models/DAO/UsuarioDAO.php'); 

    class UserService
    {
        
        public function get($id = null) 
        {
            $userDAO = new UsuarioDAO();
            if ($id) {
                return $userDAO->select($id);
            } else {
                return $userDAO->selectAll();
            }
        }

        public function post() 
        {
            $userDAO = new UsuarioDAO();
            $data = json_decode(file_get_contents("php://input"));

            return $userDAO->insert($data);
        }

        public function login(){

            $userDAO = new UsuarioDAO();
            $data = json_decode(file_get_contents("php://input"));
            return $userDAO->login($data->{'email'}, $data->{'senha'});
        }

        public function update($passresetToken=null, $passresetexp=null) 
        {
            if($passresetexp && $passresetexp){
                $userDAO = new UsuarioDAO();
                $data = json_decode(file_get_contents("php://input"));
                $data->{'senharesetexpire'} = $passresetexp;
                $data->{'senharesettoken'} = $passresetToken;

                return $userDAO->update($data);
            }
            $userDAO = new UsuarioDAO();
            $data = json_decode(file_get_contents("php://input"));
            return $userDAO->update($data);
        }

        public function verifyReset(){

            $userDAO = new UsuarioDAO();
            $data = json_decode(file_get_contents("php://input"));

            return $userDAO->resetSenha($data);
        }

        public function delete() 
        {
            
        }
    }