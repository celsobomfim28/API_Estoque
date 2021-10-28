<?php
    namespace App\Services;

    define('__ROOT__', dirname(dirname(__FILE__))); 
    use \App\Models\User;
    require_once(__ROOT__.'/App/Models/User.php'); 

    class UserService
    {
        
        public function get($id = null) 
        {
            if ($id) {
                return User::select($id);
            } else {
                return User::selectAll();
            }
        }

        public function post() 
        {
            $data = $_POST;

            return User::insert($data);
        }

        public function update() 
        {
            
        }

        public function delete() 
        {
            
        }
    }