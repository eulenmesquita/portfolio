<?php
    require_once('Mailer.php'); 
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
    
/*
    
else
    {
        header('HTTP/1.1 500 Internal Server Booboo');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
    }


    $message = (object) array(
        'name' => $name,
        'phoneNumber' => $phoneNumber,
        'email' => $email,
        'content' => $content
    );

    

    //echo json_encode($res);
    // Check for empty fields
    if(empty($_POST['name'])  		||
    empty($_POST['email']) 		||
    empty($_POST['phone']) 		||
    empty($_POST['message'])	||
    !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
    {
        echo "No arguments Provided!";
        return false;
    }
        
    $name = strip_tags(htmlspecialchars($_POST['name']));
    $email_address = strip_tags(htmlspecialchars($_POST['email']));
    $phone = strip_tags(htmlspecialchars($_POST['phone']));
    $message = strip_tags(htmlspecialchars($_POST['message']));
        
    // Create the email and send the message
    $to = 'yourname@yourdomain.com'; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
    $email_subject = "Website Contact Form:  $name";
    $email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email_address\n\nPhone: $phone\n\nMessage:\n$message";
    $headers = "From: noreply@yourdomain.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
    $headers .= "Reply-To: $email_address";	
    mail($to,$email_subject,$email_body,$headers);
    return true;			
    ?>
    */