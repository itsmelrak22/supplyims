<?php
    include('includes/header.php');
    if ( isset($_SESSION['user_data']) && isset($_SESSION['user_data']->client_login)  ){
        header('Location: orders.php');
    }
    $instance = new Department;
    $departments = $instance->all();
?>
<style>
    .container {
        display: flex;
        flex-wrap: wrap;
    }
    .form-container, .product-container {
        flex: 1 0 100%;
    }
    @media (min-width: 600px) {
        .form-container, .product-container {
            flex: 1;
        }
    }
</style>

<body>

<?php include('includes/sidebar.php'); ?>

<section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Departments</span>
      </div>
    </nav>

<div class="container">
    <div class="form-container left">
          <h2> Add Department Info </h2>
          <form method="post">

            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>

            <label for="short_name">Short Name</label>
            <input type="text" name="short_name" id="short_name" required>

            <input type="submit" name="createDepartment" value="Submit">
        </form>
    </div>
    <div class="product-container" style="margin-left: 10px">
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Short Name</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($departments as $value): ?>
            <tr>
                <td><?= $value['name']; ?></td>
                <td><?= $value['short_name']; ?></td>
                <td>
                    <a href="department-edit.php?department_id=<?=$value['id'] ?>"> <button type="button" style="background-color: #38b000; width: 100%;">Edit</button> </a>
                    <form method="post" style="margin-top: 2px;">
                        <input type="hidden" name="id" value="<?= $value['id']; ?>">
                        <input style="background-color: #004b23;" type="submit" name="deleteDepartment" value="Delete">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>
</section>

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

if( isset($_POST['createDepartment']) && $_POST['createDepartment'] ){
    $name = $_POST['name'];
    $short_name = $_POST['short_name'];

    try {
        $instance = new Department;
        $stmt = $instance->pdo->prepare("INSERT INTO departments (`name`, `short_name`) VALUES (:name, :short_name)");

        // Bind parameters to your SQL statement
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':short_name', $short_name);
        $stmt->execute();

        echo "<script>alert('Department has been created!'); window.location='departments.php';</script>";

    } catch (\PDOException  $e) {
        die('Database connection error: ' . $e->getMessage());
        echo "<script>alert('Something Went Wrong !'); window.location='../departments.php';</script>";
    }
}

if( isset($_POST['deleteDepartment']) && $_POST['deleteDepartment'] ){
    $id = $_POST['id'];

    try {
        $instance = new Department;
        $stmt = $instance->pdo->prepare("DELETE FROM departments WHERE id = :id");
        // Bind parameters to your SQL statement
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        echo "<script>alert('Department has been deleted!'); window.location='departments.php';</script>";

    } catch (\PDOException  $e) {
        die('Database connection error: ' . $e->getMessage());
        echo "<script>alert('Something Went Wrong !'); window.location='../departments.php';</script>";
    }
}