<?php
    header('Content-Type: application/json');
    define('__ROOT__', dirname(dirname(__FILE__))); 
    require_once(__ROOT__.'/App/Models/Services/UsuarioService.php'); 
    require_once(__ROOT__.'/App/Utils/Jwt.php');
    include_once(__ROOT__.'/App/Utils/Mail.php');
    include_once(__ROOT__.'/App/Utils/Encrypt.php');

    use \App\Utils\JwtAuth;
    use \App\Utils\Mail;
    use \App\Utils\Encrypt;
    
    // api/users/show/1
    if ($_GET['url']) {
        
        $url = explode('/', $_GET['url']);
        if ($url[0] === 'api') {
            array_shift($url);

            if($url[0] === 'user'){
                $service = 'App\Services\UserService';
                array_shift($url);
                
                
                //Pesquisar Usuarios
                if($url[0] === 'show'){
                    array_shift($url);
                    $method = strtolower($_SERVER['REQUEST_METHOD']);


                    try {
                        $response = call_user_func_array(array(new $service, $method), $url);

                        http_response_code(200);
                        echo json_encode(array('status' => 'sucess', 'data' => $response));
                        exit;
                    } catch (\Exception $e) {
                        http_response_code(404);
                        echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }

                //Cadastrar Usuarios
                if($url[0] === 'registro'){
                    $service = 'App\Services\UserService';
                    try{

                        $response = call_user_func_array(array(new $service, 'POST'), $url);
                        http_response_code(200);

                        $arId = array($response);
                        $jwt = new JWTAuth();

                        $usr = call_user_func_array(array(new $service, 'GET'), $arId);
                        $token = $jwt->gerarToken($usr['nome']);

                        $result = array(
                            'nome' => $usr['nome'],
                            'email' => $usr['email'],
                        );

                        echo json_encode(array('status' => 'sucess', 'data' => $result, 'token' => $token));
                        exit;

                    }catch (\Exception $e){

                        http_response_code(404);
                        echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                        exit;

                    }
                    
                }
                //Autenticar
                if($url[0] === 'autenticate'){
                    $service = 'App\Services\UserService';

                    try{

                        $response = call_user_func_array(array(new $service, 'login'), $url);
                        $jwt = new JWTAuth();
                        $usr = $response;
                        $token = $jwt->gerarToken($response['nome']);

                        $result = array(
                            'nome' => $usr['nome'],
                            'email' => $usr['email'],
                        );

                        http_response_code(200);
                        echo json_encode(array('status' => 'sucess', 'data' => $result, 'token' => $token));

                    }catch (\Exception $e){

                        http_response_code(404);
                        echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                    
                }

                //Esqueceu Senha
                if($url[0] === 'forgotpass'){

                    try{
                        $service = 'App\Services\UserService';
                    

                        $now = new \DateTime();
                        $localtime_assoc = localtime(time(), true);
                        $now->setTime(($localtime_assoc['tm_hour']+1),$localtime_assoc['tm_min']);
                        $datet = $now->format('Y-m-d H:i:s');;

                        $encrypt = new Encrypt();
                        $token = $encrypt->Salt();

                        $param = array($token, $datet);
                        $response = call_user_func_array(array(new $service, 'update'), $param);
                        $usr = $response;

                        $result = array(
                            'nome' => $usr['nome'],
                            'email' => $usr['email'],
                        );

                        $mail = new Mail();
                        $mail->sendEmail($result['email'], $result['email'], $token);

                        http_response_code(200);
                        echo json_encode(array('status' => 'sucess', 'data' => $result, 'token' => $token));
                    }catch (\Exception $e){

                        http_response_code(404);
                        echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                    
                }
                //resetpass
                if($url[0] === 'resetpass'){

                    try{
                        $service = 'App\Services\UserService';
                        $response = call_user_func_array(array(new $service, 'verifyReset'), $url);
                        $usr = $response;
                        http_response_code(200);
                        echo json_encode(array('status' => 'sucess', 'data' => $usr));
                    }catch (\Exception $e){
                        http_response_code(404);
                        echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                }
            }

            
        }
    }
    