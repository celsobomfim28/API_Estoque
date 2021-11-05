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
    require_once (__ROOT__.'/App/Models/Entity/Fornecedor.php');
    include_once(__ROOT__.'/App/Utils/Encrypt.php');
    include_once(__ROOT__.'/App/Utils/Jwt.php');

    use \App\Utils\Encrypt;
    use \App\Utils\JwtAuth;
    use \Database;
    use \App\Models\Entity\Fornecedor;
    
  
    class FornecedorDAO{
    
        function select(int $id) {
            
            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        

            // initialize object
            $fornecedor = new Fornecedor($db);
            $connPdo = $db;

            $jwt = new JwtAuth();

            $respAuth = $jwt->validateToken();

            if($respAuth !== 'Ok'){
                throw new \Exception("Usuario não autorizado!");
            }


            $sql = 'SELECT * FROM Fornecedor WHERE idFornecedor = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum fornecedor encontrado!");
            }
        }

        function selectAll() {

            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        

            // initialize object
            $fornecedor = new Fornecedor($db);
            $connPdo = $db;

            $jwt = new JwtAuth();

            $respAuth = $jwt->validateToken();

            if($respAuth !== 'Ok'){
                throw new \Exception("Usuario não autorizado!");
            }

            $sql = 'SELECT * FROM Fornecedor';
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum fornecedor encontrado!");
            }
        }

        function insert($data)
        {
            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        

            // initialize object
            $fornecedor = new Fornecedor($db);
            $connPdo = $db;

            $jwt = new JwtAuth();

            $respAuth = $jwt->validateToken();

            if($respAuth !== 'Ok'){
                throw new \Exception("Usuario não autorizado!");
            }

            $encr = new Encrypt();
            $newSenha = $encr->base64url_encode($data->{'senha'});
            
            $sql = 'INSERT INTO Fornecedor (fornecedor, endereco, bairo, num, cep, contato, tel, 
            cnpj, insc, Cidade_idCidade) 
            VALUES (:for, :en, :ba, :nu, :cep, :con, :tel, :cnp, :insc, :cid)';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':for', $data->{'fornecedor'});
            $stmt->bindValue(':en', $data->{'endereco'});
            $stmt->bindValue(':ba', $data->{'bairo'});
            $stmt->bindValue(':nu', $data->{'num'});
            $stmt->bindValue(':cep', $data->{'cep'});
            $stmt->bindValue(':con', $data->{'contato'});
            $stmt->bindValue(':tel', $data->{'tel'});
            $stmt->bindValue(':cnp', $data->{'cnpj'});
            $stmt->bindValue(':insc', $data->{'insc'});
            $stmt->bindValue(':cid', $data->{'Cidade_idCidade'});
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(\PDO::FETCH_ASSOC); 
                return $connPdo->lastInsertId();; 
            } else {
                throw new \Exception("Falha ao inserir fornecedor(a)!");
            }
        }

        function update($data){

            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        

            // initialize object
            $fornecedor = new Fornecedor($db);
            $connPdo = $db;

            $jwt = new JwtAuth();

            $respAuth = $jwt->validateToken();

            if($respAuth !== 'Ok'){
                throw new \Exception("Usuario não autorizado!");
            }
            
            $sql = 'UPDATE Fornecedor 
            SET fornecedor = :for, endereco = :en, bairo = :ba, num = :nu, cep = :cep, 
            contato = :con, tel = :tel, 
            cnpj = :cnp, insc = :ins, Cidade_idCidade = :cid
            WHERE idFornecedor = :id';

            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':for', $data->{'fornecedor'});
            $stmt->bindValue(':en', $data->{'endereco'});
            $stmt->bindValue(':ba', $data->{'bairo'});
            $stmt->bindValue(':nu', $data->{'num'});
            $stmt->bindValue(':cep', $data->{'cep'});
            $stmt->bindValue(':con', $data->{'contato'});
            $stmt->bindValue(':tel', $data->{'tel'});
            $stmt->bindValue(':cnp', $data->{'cnpj'});
            $stmt->bindValue(':insc', $data->{'insc'});
            $stmt->bindValue(':cid', $data->{'Cidade_idCidade'});
            $stmt->bindValue(':id', $data->{'idFornecedor'});
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(\PDO::FETCH_ASSOC); 
                return $this->select($data->{'idFornecedor'}); 
            } else {
                $arr = $stmt->errorInfo();
                var_dump($arr);
                throw new \Exception("Falha ao atualizar fornecedor(a)!");
            }
        }
    }

        
