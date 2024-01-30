<?php
session_start();
date_default_timezone_set('Asia/Manila');

spl_autoload_register(function ($class) {
    include '../models/' . $class . '.php';
});

$today = date('Y-m-d H:i:s');
$instance = new Client;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employee_id = filter_input(INPUT_POST, 'employee_id', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        // Check if employee_id already exists
        // $employee = $instance->setQuery("SELECT * FROM clients WHERE `employee_id` = '$employee_id' AND `password` = '$password' AND `deleted_at` IS NULL")->getFirst();

        $client = new Client;
        $stmt = $client->pdo->prepare('SELECT password FROM clients WHERE employee_id = :employee_id AND deleted_at IS NULL');
        $stmt->execute(['employee_id' => $employee_id]); // replace with the provided employee_id
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && hash('sha256', $password) === $row['password']) {
            $employee = $instance->setQuery("SELECT * FROM clients WHERE `employee_id` = '$employee_id' AND `deleted_at` IS NULL")->getFirst();

            $_SESSION['user_data'] = $employee;
            $_SESSION['user_data']->client_login = true;

            echo "<script>alert('Client Login Successfully !'); window.location='../clients.php';</script>";
        } else {
            echo "<script>alert('Login failed. Check your employee id and password.'); window.location='../index.php';</script>";

        }

}