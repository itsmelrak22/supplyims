<?php
    include('includes/header.php');
    if ( isset($_SESSION['user_data']) && isset($_SESSION['user_data']->client_login)  ){
      header('Location: orders.php');
  }

  $unit = new Unit;
  $units = $unit->all();

?>

<body>

<?php include('includes/sidebar.php'); ?>

  <section class="home-section">
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Add Supply</span>
      </div>
      
    </nav>
    <div class="container">
      <div class="form-container left">
          <h2>Add Supply </h2>
          <form action="addProduct.php" method="post"  onsubmit="return confirmSubmit()">
            <label for="barcodeId">Barcode:</label>
            <input type="text" name="barcodeId" id="barcodeId" required>

            <label for="productName">Supply Name:</label>
            <input type="text" name="productName" id="productName" required>

            <label for="productGroup">Category:</label>
            <input type="text" name="productGroup" id="productGroup" required>

            <label for="qty">Quantity:</label>
            <input type="number" name="qty" id="qty" required>

            <label for="unit_id">Unit:</label>
            <select name="unit_id" id="unit_id" >
              <option selected readonly disabled required> Select Unit </option>
              <?php
                foreach ($units as $key => $value) {
                  echo '<option value="'.$value['id'] .'">' .$value['name'] . ' (' .$value['short_name'] .')'. '</option>';
                }
              ?>
            </select>

            <input type="submit" value="Add Product">
        </form>
      </div>

      <script>
      function confirmSubmit() {
          var r = confirm("Are you sure you want to submit?");
          if (r == true) {
              return true;
          } else {
              return false;
          }
    }
    </script>


      <div class="product-container">
        <h2>Supply Information</h2>
<?php

// Create connection
    include("connection.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
// Fetch update history
$sql = "SELECT 
        A.barcodeId, 
        A.productName, 
        A.productGroup, 
        A.qty,
        A.created_at, 
        B.name as unit_name, 
        B.short_name as unit_short_name 
        FROM product AS A 
        LEFT JOIN units AS B ON 
        A.unit_id = B.id 
        ORDER BY A.created_at DESC";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch data and store in an associative array
    while ($row = $result->fetch_assoc()) {
        $updateProduct[] = $row;
    }
}
?><?php if (!empty($updateProduct)): ?>
        <table>
            <tr>
                <th>Product ID</th>
                <th>Supply Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Date Created</th>
            </tr>
            <?php foreach ($updateProduct as $update): ?>
                <tr>
                    <td><?= $update['barcodeId']; ?></td>
                    <td><?= $update['productName']; ?></td>
                    <td><?= $update['productGroup']; ?></td>
                    <td><?= $update['qty']; ?></td>
                    <td><?= $update['unit_name']; ?> (<?= $update['unit_short_name']; ?>)</td>
                    <td><?= $update['created_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No product list available.</p>
    <?php endif; ?>
    
        <br>
    
    </div> 
     
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