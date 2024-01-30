

<?php
session_start();
date_default_timezone_set('Asia/Manila');

spl_autoload_register(function ($class) {
    include 'models/' . $class . '.php';
});


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $instance = new Model;
    $stmt = $instance->pdo->prepare('SELECT password FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]); // replace with the provided username
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && hash('sha256', $password) === $row['password']) {
        $employee = $instance->setQuery("SELECT * FROM users WHERE `username` = '$username' ")->getFirst();

        $_SESSION['user_data'] = (array)$employee;
        echo "<script>alert('Login successful!'); window.location='product.php';</script>";
        exit(); // Ensure that no other code is executed after the redirect
    } else {
        echo "<script>alert('Login failed. Check your username and password.'); window.location='index.php';</script>";
        exit(); // Ensure that no other code is executed after the redirect
    }

}
?>
