<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISBS Login - Supply Inventory System using Barcode Scanner</title>
    <link rel="stylesheet" type="text/css" href="login.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <style>
        body,
        html {
          margin: 0;
          padding: 0;
          height: 100%;
          background: #70e000 !important;
        }
        .user_card {
          height: 400px;
          width: 350px;
          margin-top: auto;
          margin-bottom: auto;
          background: #008000;
          position: relative;
          display: flex;
          justify-content: center;
          flex-direction: column;
          padding: 10px;
          box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
          -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
          -moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
          border-radius: 5px;

        }
        .brand_logo_container {
          position: absolute;
          height: 170px;
          width: 170px;
          top: -75px;
          border-radius: 50%;
          background: #008000;
          padding: 10px;
          text-align: center;
        }
        .brand_logo {
          height: 150px;
          width: 150px;
          border-radius: 50%;
          border: 2px solid white;
        }
        .form_container {
          margin-top: 100px;
        }
        .login_btn {
          width: 100%;
          background: #38b000 !important;
          color: white !important;
        }
        .login_btn:focus {
          box-shadow: none !important;
          outline: 0px !important;
        }
        .login_container {
          padding: 0 2rem;
        }
        .input-group-text {
          background: #38b000 !important;
          color: white !important;
          border: 0 !important;
          border-radius: 0.25rem 0 0 0.25rem !important;
        }
        .input_user,
        .input_pass:focus {
          box-shadow: none !important;
          outline: 0px !important;
        }
        .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
          background-color: #38b000 !important;
        }
    </style>
</head>
<body>
    <div class="container h-100">
      <div class="d-flex justify-content-center h-100">
        
        <div class="user_card tabcontent active" id="Form1" style="display: block;"> 
          <div class="d-flex justify-content-center">
            <div class="brand_logo_container">
              <img src="cvsu.png" class="brand_logo" alt="Logo">
            </div>
          </div>
          <div class="d-flex justify-content-center form_container">
            <form method="post">

                <div class="d-flex justify-content-center mt-3 login_container">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                  <input type="email" id="email" name="email" placeholder="Email" required>   

                </div>
              <div class="d-flex justify-content-center mt-3 login_container">
                <button type="submit" name="resetPassword" class="btn login_btn">Send email for reset password</button>
              </div>
            </form>
          </div>
      
          <div class="mt-4">
          
          </div>
        </div>
        
      
          <div class="mt-4">
  
          </div>
        </div>

      </div>


	  </div>



</body>
</html>


<?php

spl_autoload_register(function ($class) {
  include 'models/' . $class . '.php';
});

function generatePassword() {
  $length = 8;
  $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  $password = "";
  for ($i = 0; $i < $length; $i++) {
      $random_picked = mt_rand(0, strlen($charset) - 1);
      $password .= $charset[$random_picked];
  }
  return $password;
}


if( isset( $_POST['resetPassword'] ) ) {
  $email = $_POST['email'];
  include("send_email.php");

  try {
      $client = new Client;
      
      $stmt = $client->pdo->prepare('SELECT password FROM clients WHERE contact = :email AND deleted_at IS NULL');
      $stmt->execute(['email' => $email]); // replace with the provided email
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($row ) {
        $password = generatePassword();
        $hashed_password = hash('sha256', $password);
        $stmt = $client->pdo->prepare("UPDATE clients SET password = :hashed_password WHERE contact = :email");
        // Bind parameters to your SQL statement
        $stmt->bindParam(':hashed_password', $hashed_password);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        sendEmployeeEmail($email, $password, $row['name'], true);

        echo "<script>alert('Password has been reset, please check your email !'); window.location='index.php';</script>";
      } else {
          echo "<script>alert('Invalid email provided.'); window.location='forgotPassword.php';</script>";
      }

  } catch (\PDOException  $e) {
      die('Database connection error: ' . $e->getMessage());
      echo "<script>alert('Something Went Wrong !'); window.location='forgotPassword.php';</script>";
  }
}


