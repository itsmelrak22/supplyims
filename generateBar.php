<?php
    include('includes/header.php');
    if ( isset($_SESSION['user_data']) && isset($_SESSION['user_data']->client_login)  ){
        header('Location: orders.php');
    }
?>

<body>

<?php include('includes/sidebar.php'); ?>
    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="dashboard">Generate Barcode</span>
            </div>
        </nav>
        <div class="container">
            <div class="form-container left">
                <h2>Create Barcode </h2>
                <label for="product-name">Product Name:</label>
                <input type="text" id="product-name" placeholder="Enter Product Name">

                <label for="barcode-input">Enter Barcode data:</label>
                <input type="text" id="barcode-input" placeholder="Enter Barcode data">

                <button onclick="generateBarcode()">Generate Barcode</button>

                <br>

                <div id="barcode-display">
                    <div id="product-name-display"></div>
                    <canvas id="barcode"></canvas>
                </div>

                <button id="downloadButton" style="display: none;">Download File</button>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
        <script>
            function generateBarcode() {
                var productName = document.getElementById('product-name').value;
                var barcodeData = document.getElementById('barcode-input').value;

                if (productName.trim() === "" || barcodeData.trim() === "") {
                    alert("Please enter Product Name and Barcode data");
                    return;
                }

                // Display Product Name above the barcode
               

                // Concatenate Product Name with Barcode data
                var combinedData =  barcodeData;
                document.getElementById('product-name-display').innerText = `Product Name: ${productName}`;

                // Generate Barcode
                JsBarcode("#barcode", combinedData, {
                    format: "CODE128",
                    displayValue: true
                });

                // Display the "Download File" button after generating the barcode
                document.getElementById('downloadButton').style.display = 'block';
            }

            document.getElementById('downloadButton').addEventListener('click', function () {
                var canvas = document.getElementById("barcode");
                var img = canvas.toDataURL("image/png");
                var timestamp = Date.now();
                var link = document.createElement('a');
                link.download = `${document.getElementById('product-name').value}-${timestamp}.png`;
                link.href = img;
                link.click();
            });
        </script>
    </section>

</body>
</html>