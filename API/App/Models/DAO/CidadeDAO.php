<?php
    namespace App\Models\DAO;

    error_reporting(E_ALL & ~E_NOTICE);
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    define('__ROOT__', dirname(dirname(__FILE__))); 

    // include database and object files
    include_once (__ROOT__.'/Database.php');
    require_once (__ROOT__.'/App/Models/Entity/Cidade.php');
    include_once(__ROOT__.'/App/Utils/Encrypt.php');
    include_once(__ROOT__.'/App/Utils/Jwt.php');

    use \App\Utils\Encrypt;
    use \App\Utils\JwtAuth;
    use \Database;
    use \App\Models\Entity\Cidade;
    

    class CidadeDAO{

        function select(int $id) {
            
            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        
            $jwt = new JwtAuth();

            $respAuth = $jwt->validateToken();

            if($respAuth !== 'Ok'){
                throw new \Exception("Usuario não autorizado!");
            }

            // initialize object
            $usuario = new Cidade($db);
            $connPdo = $db;

            $sql = 'SELECT * FROM Cidade WHERE idCidade = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhuma cidade encontrado!");
            }
        }

        function selectAll() {

            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        

            $jwt = new JwtAuth();

            $respAuth = $jwt->validateToken();

            if($respAuth !== 'Ok'){
                throw new \Exception("Usuario não autorizado!");
            }

            // initialize object
            $usuario = new Cidade($db);
            $connPdo = $db;

            $sql = 'SELECT * FROM Cidade';
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhuma cidade encontrado!");
            }
        }

        function insert($data)
        {
            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        
            $jwt = new JwtAuth();

            $respAuth = $jwt->validateToken();

            if($respAuth !== 'Ok'){
                throw new \Exception("Usuario não autorizado!");
            }


            // initialize object
            $cidade = new Cidade($db);
            $connPdo = $db;
            
            $cidade = $this->findOne($data->{'cidade'}, $data->{'uf'});

            if($user){
                throw new \Exception("Cidade já existe!");
            }
            
            $sql = 'INSERT INTO Cidade (cidade, uf) 
            VALUES (:ci, :uf)';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':ci', $data->{'cidade'});
            $stmt->bindValue(':uf', $data->{'uf'});
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(\PDO::FETCH_ASSOC); 
                return $connPdo->lastInsertId();; 
            } else {
                throw new \Exception("Falha ao inserir cidade!");
            }
        }

        function findOne($cidade, $uf)
        {
            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        
            $jwt = new JwtAuth();

            $respAuth = $jwt->validateToken();

            if($respAuth !== 'Ok'){
                throw new \Exception("Usuario não autorizado!");
            }


            // initialize object
            $cidade = new Cidade($db);
            $connPdo = $db;

            $sql = 'SELECT * FROM Cidade WHERE cidade = :cidade and uf = :uf';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':cidade', $cidade);
            $stmt->bindValue('uf', $uf);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } 
        }

        function update($data){
             // instantiate database and product object
             $database = new Database();
             $db = $database->getConnection();
         
 
             $jwt = new JwtAuth();

            $respAuth = $jwt->validateToken();

            if($respAuth !== 'Ok'){
                throw new \Exception("Usuario não autorizado!");
            }

             // initialize object
             $cidade = new Cidade($db);
             $connPdo = $db;
             
             $sql = 'UPDATE Cidade
             SET cidade = :ci, uf = :uf
             WHERE idCidade = :id';

             $stmt = $connPdo->prepare($sql);
             $stmt->bindValue(':ci', $data->{'cidade'});
             $stmt->bindValue(':uf', $data->{'uf'});
             $stmt->bindValue(':id', $data->{'idCidade'});
             $stmt->execute();
 
             if ($stmt->rowCount() > 0) {
                 $result = $stmt->fetch(\PDO::FETCH_ASSOC); 
                 return $connPdo->lastInsertId();; 
             } else {
                 throw new \Exception("Falha ao atualizar cidade!");
             }
        }
}