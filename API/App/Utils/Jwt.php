<?php

declare(strict_types=1);

namespace App\Utils;

error_reporting(E_ALL & ~E_NOTICE);
use Firebase\JWT\JWT;
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/vendor/autoload.php');

class JwtAuth{

    public static function gerarToken($name){

        $secretKey  = '3a00b474f75baebe7cd13bd17e78d3b1';
        $issuedAt   = new \DateTimeImmutable();
        $expire     = $issuedAt->modify('+60 minutes')->getTimestamp();      // Add 60 seconds
        $serverName = "localhost";
        $username   = $name;                                           // Retrieved from filtered POST data
        
        $data = [
            'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'iss'  => $serverName,                       // Issuer
            'nbf'  => $issuedAt->getTimestamp(),         // Not before
            'exp'  => $expire,                           // Expire
            'userName' => $username,                     // User name
        ];

        return JWT::encode(
            $data,
            $secretKey,
            'HS512'
        );
    }

    public static function validateToken(){

        $matches = array();

        if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            header('HTTP/1.0 400 Bad Request');
            throw new \Exception("Token not found in request!");
        }
        $jwt = $matches[1];
        if (! $jwt) {
            // No token was able to be extracted from the authorization header
            header('HTTP/1.0 400 Bad Request');
            throw new \Exception("No token was able to be extracted from the authorization header!");
        }
        $secretKey  = '3a00b474f75baebe7cd13bd17e78d3b1';
        $token = JWT::decode($jwt, $secretKey, ['HS512']);
        $now = new \DateTimeImmutable();
        $serverName = "localhost";

        if ($token->iss !== $serverName ||
            $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp())
        {
            header('HTTP/1.1 401 Unauthorized');
            throw new \Exception("Erro Desconhecido!");
        }   
        return "Ok";
    }

}
