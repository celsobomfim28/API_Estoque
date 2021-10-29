<?php
declare(strict_types=1);
namespace App\Utils;
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

}
