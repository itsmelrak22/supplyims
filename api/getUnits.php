<?php
session_start();
date_default_timezone_set('Asia/Manila');

spl_autoload_register(function ($class) {
    include '../models/' . $class . '.php';
});

header('Content-Type: application/json');



// Assuming you have a Department model with a method getDepartments
$unit = new Unit();
$units = $unit->all();

// Convert the units array into JSON format

http_response_code(200);
echo json_encode(array('message' => 'Success', 'data' => $units ));
?>