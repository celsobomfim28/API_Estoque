<?php
error_reporting(E_ALL & ~E_NOTICE);
    $url = 'http://localhost:80/APU/public_html/api';

    $class = '/user';
    $param = '';

    $response = file_get_contents($url.$class.$param);

    //echo $response;

    //
    //$response = json_decode($response, 1);
    //var_dump($response['data'][1]['email']);