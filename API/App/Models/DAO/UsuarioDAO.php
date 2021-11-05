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
    require_once (__ROOT__.'/App/Models/Entity/Usuario.php');
    include_once(__ROOT__.'/App/Utils/Encrypt.php');

    use \App\Utils\Encrypt;
    use \Database;
    use \App\Models\Entity\Usuario;
    
  
    class UsuarioDAO{
    
        function select(int $id) {
            
            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        

            // initialize object
            $usuario = new Usuario($db);
            $connPdo = $db;

            $sql = 'SELECT * FROM Usuario WHERE idUsuario = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum usuário encontrado!");
            }
        }

        function selectAll() {

            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        

            // initialize object
            $usuario = new Usuario($db);
            $connPdo = $db;

            $sql = 'SELECT * FROM Usuario';
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum usuário encontrado!");
            }
        }

        function findOne($email)
        {
            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        

            // initialize object
            $usuario = new Usuario($db);
            $connPdo = $db;

            $sql = 'SELECT * FROM Usuario WHERE email = :email';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } 
        }

        function insert($data)
        {
            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        

            // initialize object
            $usuario = new Usuario($db);
            $connPdo = $db;
            
            $user = $this->findOne($data->{'email'});

            if($user){
                throw new \Exception("Usuário já existe!");
            }

            $encr = new Encrypt();
            $newSenha = $encr->base64url_encode($data->{'senha'});
            
            $sql = 'INSERT INTO Usuario (nome, email, senha, cpf, endereco, bairo, num, cep, tel, 
            senharesettoken, senharesetexpire, sexo, datacriacao, Cidade_idCidade) 
            VALUES (:na, :em, :se, :cp, :en, :ba, :nu, :ce, :te, :srt, :sre, :sex, :dc, :cid)';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':na', $data->{'nome'});
            $stmt->bindValue(':em', $data->{'email'});
            $stmt->bindValue(':se', $newSenha);
            $stmt->bindValue(':cp', $data->{'cpf'});
            $stmt->bindValue(':en', $data->{'endereco'});
            $stmt->bindValue(':ba', $data->{'bairo'});
            $stmt->bindValue(':nu', $data->{'num'});
            $stmt->bindValue(':ce', $data->{'cep'});
            $stmt->bindValue(':te', $data->{'tel'});
            $stmt->bindValue(':srt', $data->{'senharesettoken'});
            $stmt->bindValue(':sre', $data->{'senharesetexpire'});
            $stmt->bindValue(':sex', $data->{'sexo'});
            $stmt->bindValue(':dc', $data->{'datacriacao'});
            $stmt->bindValue(':cid', $data->{'Cidade_idCidade'});
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(\PDO::FETCH_ASSOC); 
                return $connPdo->lastInsertId();; 
            } else {
                throw new \Exception("Falha ao inserir usuário(a)!");
            }
        }

        function login($email, $senha){
            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        

            // initialize object
            $usuario = new Usuario($db);
            $connPdo = $db;

            $user = $this->findOne($email);

            if(!$user){
                throw new \Exception("Usuário não existe!");
            }

            $encr = new Encrypt();
            $senhaPost = $encr->base64url_decode($user['senha']);

            if($senhaPost !== $senha){
                throw new \Exception("Senha inválida!");
            }
           
            if ($user) {
                return $user;
            } else {
                throw new \Exception("Nenhum usuário encontrado!");
            }
        }

        function forgotPass($data){

             // instantiate database and product object
             $database = new Database();
             $db = $database->getConnection();
         
             // initialize object
             $usuario = new Usuario($db);
             $connPdo = $db;
             
             $user = $this->findOne($data->{'email'});
 
             if(!$user){
                 throw new \Exception("Usuário não existe!");
             }

             $id = (int) $user['idUsuario'];

             $sql = 'UPDATE Usuario 
             SET senharesettoken = :srt, senharesetexpire = :sre WHERE idUsuario = '.$id;

             $stmt = $connPdo->prepare($sql);
             $stmt->bindValue(':srt', $data->{'senharesettoken'});
             $stmt->bindValue(':sre', $data->{'senharesetexpire'});

             try{
             
                if ($stmt->execute()) {
                    return $this->findOne($data->{'email'});
                } else {
                    $arr = $stmt->errorInfo();
                    throw new \Exception("Falha ao atualizar usuário(a)!");
                }
             }catch(\Exception $e){
                 var_dump($e);
             }
        }

        function update($data){

            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
        

            // initialize object
            $usuario = new Usuario($db);
            $connPdo = $db;
            
            $user = $this->findOne($data->{'email'});

            if(!$user){
                throw new \Exception("Usuário não existe!");
            }

            $encr = new Encrypt();
            $newSenha = $encr->base64url_encode($data->{'senha'});

            $sql = 'UPDATE Usuario 
            SET nome = :na, email = :em, senha = :se, cpf = :cp, 
            endereco = :en, bairo = :ba, num = :nu, cep = :ce, tel = :te, 
            senharesettoken = :srt, senharesetexpire = :sre, sexo = :sex, 
            datacriacao = :dc, Cidade_idCidade = :cid 
            WHERE idUsuario = :id';

            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':na', $data->{'nome'});
            $stmt->bindValue(':em', $data->{'email'});
            $stmt->bindValue(':se', $newSenha);
            $stmt->bindValue(':cp', $data->{'cpf'});
            $stmt->bindValue(':en', $data->{'endereco'});
            $stmt->bindValue(':ba', $data->{'bairo'});
            $stmt->bindValue(':nu', $data->{'num'});
            $stmt->bindValue(':ce', $data->{'cep'});
            $stmt->bindValue(':te', $data->{'tel'});
            $stmt->bindValue(':srt', $data->{'senharesettoken'});
            $stmt->bindValue(':sre', $data->{'senharesetexpire'});
            $stmt->bindValue(':sex', $data->{'sexo'});
            $stmt->bindValue(':dc', $data->{'datacriacao'});
            $stmt->bindValue(':cid', $data->{'Cidade_idCidade'});
            $stmt->bindValue(':id', $data->{'idUsuario'});
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(\PDO::FETCH_ASSOC); 
                return $this->select($data->{'idUsuario'}); 
            } else {
                $arr = $stmt->errorInfo();
                var_dump($arr);
                throw new \Exception("Falha ao atualizar usuário(a)!");
            }
        }

        function resetSenha($data){
            
            // instantiate database and product object
            $database = new Database();
            $db = $database->getConnection();
         
 
            // initialize object
            $usuario = new Usuario($db);
            $connPdo = $db;

            $email = $data->{'email'};
            $senharesettoken = $data->{'token'};
            $encr = new Encrypt();
            $senha = $encr->base64url_encode($data->{'password'});
 
            $user = $this->findOne($email);
 
            if(!$user){
                throw new \Exception("Usuário não existe!");
            }

            if($senharesettoken !== $user['senharesettoken']){
                throw new \Exception("Token inválido!");
            }

            $now = new \DateTime();
            $dateExpire = strtotime($user['senharesetexpire']);

            if($now > $dateExpire){
                throw new \Exception("Token expirado! Gere outro!");
            }

            $sql = 'UPDATE Usuario 
            SET senha = :se  
            WHERE idUsuario = :id';

            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':se', $senha);
            $stmt->bindValue(':id', $user['idUsuario']);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(\PDO::FETCH_ASSOC); 
                return $this->select($user['idUsuario']); 
            } else {
                $arr = $stmt->errorInfo();
                throw new \Exception("Falha ao resetar a senha!");
            }

        }
    }

        
