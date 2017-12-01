<?php
    define('DIR_VENDOR', dirname(__DIR__) . DIRECTORY_SEPARATOR   . 'vendor'. DIRECTORY_SEPARATOR );

    /* AJAX check  */
    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        echo 'ok';
        header('Location: /');
    }
    
    
    if (file_exists(DIR_VENDOR . 'autoload.php')) {
        require_once(DIR_VENDOR . 'autoload.php');
    }

    $dotenv = new Dotenv\Dotenv($_SERVER["DOCUMENT_ROOT"]);
    $dotenv->load();
    $input = file_get_contents('php://input');
    $data = json_decode($input, TRUE);

    $url = 'https://www.google.com/recaptcha/api/siteverify';

    $secret = $_ENV['CAPTCHA_SECRET'];

    $response = json_decode(file_get_contents($url . '?secret=' .$secret . '&response=' . $data['response'] . '&remoteip=' . $_SERVER['REMOTE_ADDR']), true);
    
    header('Content-Type: application/json');
    echo json_encode($response);
    
    