<?php
    include('includes/header.php');

    if ( isset($_SESSION['user_data']) && isset($_SESSION['user_data']->client_login)  ){
        header('Location: orders.php');
    }

    if ( !isset($_GET['unit_id']) ) header('Location: units.php');

    $id = $_GET['unit_id'];
    $instance = new Unit;
    $unit = $instance->findNoSoftDelete($id);

    // echo "<pre>".print_r($unit)."</pre>";
?>

<body>

<?php include('includes/sidebar.php'); ?>
  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Unit Edit</span>
      </div>
      
    </nav>

    <div class="home-content">
      <div class="form-container">
        <h2>Edit Unit Info</h2>
        <form method="post">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required value="<?=$unit->name ?>">

            <label for="short_name">Short Name</label>
            <input type="text" name="short_name" id="short_name" required value="<?=$unit->short_name ?>">

            <input type="submit" name="updateUnit" value="Update">
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
if( isset($_POST['updateUnit']) && $_POST['updateUnit'] ){
    $name = $_POST['name'];
    $short_name = $_POST['short_name'];

    try {
        $instance = new Unit;
        $stmt = $instance->pdo->prepare("UPDATE units SET name = :name, short_name = :short_name WHERE id = :id");
        // Bind parameters to your SQL statement
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':short_name', $short_name);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo "<script>alert('Unit has been updated!'); window.location='units.php';</script>";

    } catch (\PDOException  $e) {
        die('Database connection error: ' . $e->getMessage());
        echo "<script>alert('Something Went Wrong !'); window.location='../units.php';</script>";
    }
}

?>