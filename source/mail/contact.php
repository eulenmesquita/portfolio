<?php
    require_once('Mailer.php'); 
    
    /* AJAX check  */
    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header('Location: /');
    }
    
    define('DIR_VENDOR', dirname(__DIR__) . DIRECTORY_SEPARATOR   . 'vendor'. DIRECTORY_SEPARATOR );
    
    if (file_exists(DIR_VENDOR . 'autoload.php')) {
        require_once(DIR_VENDOR . 'autoload.php');
    }

    $dotenv = new Dotenv\Dotenv($_SERVER["DOCUMENT_ROOT"]);
    $dotenv->load();
    $input = file_get_contents('php://input');
    $data = json_decode($input, TRUE);
    
    $message = (object) array(
        'name' => $data['name'],
        'phoneNumber' => $data['phone'],
        'email' => $data['email'],
        'content' => $data['message']
    );

    $env = (object)$_ENV;
    $mailer = new Mailer($env, $message);
    $res = $mailer->send();

    if ($res['status'] == "success") {
        header('Content-Type: application/json');
    } else {
        header('HTTP/1.1 500 Internal Server');
        header('Content-Type: application/json; charset=UTF-8');
    }
    die(json_encode($res));