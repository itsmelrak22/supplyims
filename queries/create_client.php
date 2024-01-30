<?php

require("../PHPMailer/src/PHPMailer.php");
require("../PHPMailer/src/SMTP.php");

session_start();
date_default_timezone_set('Asia/Manila');

spl_autoload_register(function ($class) {
    include '../models/' . $class . '.php';
});

$today = date('Y-m-d H:i:s');


$instance = new Client;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize POST data
    $employee_id = filter_input(INPUT_POST, 'employee_id', FILTER_SANITIZE_STRING);
    $employee_name = filter_input(INPUT_POST, 'employee_name', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm-password', FILTER_SANITIZE_STRING);
    $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);
    $hashed_password = hash('sha256', $password);

    if ( strlen($employee_id) < 4 ) {
        echo "<script>alert('Employee ID must be at least 4 characters long'); window.location='../clients.php';</script>";
        exit();
    }


    if ($password != $confirm_password) {
        echo "<script>alert('Passwords do not match'); window.location='../clients.php';</script>";
        exit();
    }

    // Check if employee_id already exists
    $employee = $instance->setQuery("SELECT * FROM clients WHERE employee_id = '$employee_id'")->getFirst();

    if($employee->id){
        echo "<script>alert('Client Already Exist !'); window.location='../clients.php';</script>";
        exit();
    }

    $employee = $instance->setQuery("SELECT * FROM clients WHERE contact = '$contact'")->getFirst();

    if($employee->id){
        echo "<script>alert('Client Already Exist !'); window.location='../clients.php';</script>";
        exit();
    }



    try {
        $instance->setQuery(" INSERT INTO clients ( `employee_id`, `password`, `name`, `contact`, `created_at`, `updated_at`)
                            VALUES ( '$employee_id', '$hashed_password', '$employee_name', '$contact', '$today', '$today'); ");
        echo "<script>alert('Successfully Created Client !'); window.location='../clients.php';</script>";

    } catch (\PDOException  $e) {
        die('Database connection error: ' . $e->getMessage());
        echo "<script>alert('Something Went Wrong !'); window.location='../clients.php';</script>";
        exit();

    }
    sendEmployeeEmail($contact, $password, $employee_name);

    exit();

}


function sendEmployeeEmail($MAIL_TO, $PASSWORD, $RECEIVER_NAME){
    $mailTo = $MAIL_TO;

    $body = "   <h1>Welcome! $MAIL_TO</h1>
    <p>You have been successfully registered in our system. We're excited to have you on board!</p>
    <br> <hr>
    <p>This is you password: $PASSWORD</p>
    <br> <hr>
    <p>Best Regards,</p>
    <p>CVSU GENERAL TRIAS SUPPLY DEPARTMENT </p>";


    $mail = new PHPMailer\PHPMailer\PHPMailer();
    // $mail->SMTPDebug = 3;
    $mail->isSMTP();
    $mail->Host = "mail.smtp2go.com";
    $mail->SMTPAuth = true;

    $mail->Username = "supplyims.online";
    $mail->Password = "password";
    $mail->SMTPSecure = "tls";

    $mail->Port = "2525";
    $mail->From = "admin@supplyims.Online";
    $mail->FromName = "Cvsu Supply Department";
    $mail->addAddress($mailTo, $RECEIVER_NAME );

    $mail->isHTML('true');
    $mail->Subject = "Account Creation in SupplyIMS";
    $mail->Body = $body;
    $mail->AltBody = "Alt Body";

    if(!$mail->send()){
        return 500;
        // echo "Mailer Error :". $mail->ErrorInfo;
    }else{
        return 200;
        // echo "Message Sent";
    }
}

