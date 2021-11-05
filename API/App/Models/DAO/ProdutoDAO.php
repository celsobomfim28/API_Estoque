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
    require_once (__ROOT__.'/App/Models/Entity/Produto.php');
    include_once(__ROOT__.'/App/Utils/Encrypt.php');
    include_once(__ROOT__.'/App/Utils/Jwt.php');

    use \App\Utils\Encrypt;
    use \App\Utils\JwtAuth;
    use \Database;
    use \App\Models\Entity\Produto;
    

    class ProdutoDAO{

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
            $produto = new Produto($db);
            $connPdo = $db;

            $sql = 'SELECT * FROM Produto WHERE idProduto = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum produto encontrado!");
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
            $produto = new Produto($db);
            $connPdo = $db;

            $sql = 'SELECT * FROM Produto';
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum produto encontrado!");
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
            $produto = new Produto($db);
            $connPdo = $db;

            if($user){
                throw new \Exception("Produto já existe!");
            }
            
            $sql = 'INSERT INTO Produto (descricao, peso, controlado, qtdemin, 
            Categoria_idCategoria, Fornecedor_idFornecedor) 
            VALUES (:de, :pe, :co, :qt, :idcat, :idfor)';

            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':de', $data->{'descricao'});
            $stmt->bindValue(':pe', $data->{'peso'});
            $stmt->bindValue(':co', $data->{'controlado'});
            $stmt->bindValue(':qt', $data->{'qtdemin'});
            $stmt->bindValue(':idcat', $data->{'Categoria_idCategoria'});
            $stmt->bindValue(':idfor', $data->{'Fornecedor_idFornecedor'});
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(\PDO::FETCH_ASSOC); 
                return $connPdo->lastInsertId();; 
            } else {
                throw new \Exception("Falha ao inserir produto!");
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
             $produto = new Produto($db);
             $connPdo = $db;
             
             $sql = 'UPDATE Produto
             SET descricao = :de, peso = :pe, controlado = :co, 
             qtdemin = :qt, Categoria_idCategoria = :idcat, 
             Fornecedor_idFornecedor = idfor
             WHERE idProduto = :id';

             $stmt = $connPdo->prepare($sql);
             $stmt->bindValue(':de', $data->{'descricao'});
             $stmt->bindValue(':pe', $data->{'peso'});
             $stmt->bindValue(':co', $data->{'controlado'});
             $stmt->bindValue(':qt', $data->{'qtdemin'});
             $stmt->bindValue(':idcat', $data->{'Categoria_idCategoria'});
             $stmt->bindValue(':idfor', $data->{'Fornecedor_idFornecedor'});
             $stmt->bindValue(':id', $data->{'idProduto'});
             $stmt->execute();
 
             if ($stmt->rowCount() > 0) {
                 $result = $stmt->fetch(\PDO::FETCH_ASSOC); 
                 return $connPdo->lastInsertId();; 
             } else {
                 throw new \Exception("Falha ao atualizar produto!");
             }
        }
}