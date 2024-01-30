<?php
    include('includes/header.php');
    $adminLogin = isset($_SESSION['user_data']) && !isset($_SESSION['user_data']->client_login);
    $id = $adminLogin ? $_SESSION['user_data']['id'] : $_SESSION['user_data']->id;


    if ( $adminLogin  ){
        header('Location: orders.php');
    }
  
?>

<body>

<?php include('includes/sidebar.php'); ?>
  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Setting</span>
      </div>
      
    </nav>

    <div class="home-content">
      <div class="form-container">
      <h2>CHANGE PASSWORD</h2>

      <form  method="post" id="client_form">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>

            <label for="confirm_new_password">New Confirm Password:</label>
            <input type="password" name="confirm_new_password" id="confirm_new_password" required>

            <input type="submit" value="Submit" name="changePassword">
        </form>
    </div><br>
    </div>

  <script>
   let sidebar = document.querySelector(".sidebar");
  let sidebarBtn = document.querySelector(".sidebarBtn");
  sidebarBtn.onclick = function() {
    sidebar.classList.toggle("active");
    if(sidebar.classList.contains("active")){
    sidebarBtn.classList.replace("bx-menu" ,"bx-menu-alt-right");
  }else
    sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
  }

 </script>


</body>
</html>

<?php

if( isset($_POST['changePassword']) ){
    // $old_password = filter_input(INPUT_POST, 'old_password', FILTER_SANITIZE_STRING);
    $confirm_new_password = filter_input(INPUT_POST, 'confirm_new_password', FILTER_SANITIZE_STRING);
    $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
    $hashed_password = hash('sha256', $new_password);

    if ($new_password != $confirm_new_password) {
        echo "<script>alert('New Passwords do not match'); window.location='changePassword.php';</script>";
        exit();
    }


    try {
        $instance = new Model;
        $stmt = $instance->pdo->prepare("UPDATE clients SET password = :hashed_password WHERE id = :id");
        // Bind parameters to your SQL statement
        $stmt->bindParam(':hashed_password', $hashed_password);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo "<script>alert('User has been updated!'); window.location='orders.php';</script>";

    } catch (\PDOException  $e) {
        die('Database connection error: ' . $e->getMessage());
        echo "<script>alert('Something Went Wrong !'); window.location='orders.php';</script>";
    }


}

?>
