<div class="sidebar">
        <div class="details"></div>
        <ul class="nav-links">

            <li>
                <a href="orders.php">
                    <i class='bx bx-cart'></i>
                    <span class="links_name">Request</span>
                </a>
            </li>

            <?php if( !isset($_SESSION['user_data']->client_login) ) {?>   
                
                <li>
                    <a href="product.php">
                        <i class='bx bx-box'></i>
                        <span class="links_name">Supply</span>
                    </a>
                </li>
                <li>
                    <a href="barcode.php">
                    <i class='bx bxs-package' ></i>                    
                    <span class="links_name">Add Supply</span>
                    </a>
                </li>
                <li>
                    <a href="approved_products.php">
                        <i class='bx bx-user-check'></i>
                        <span class="links_name">Approved Supply</span>
                    </a>
                </li>
                <li>
                    <a href="generateBar.php">
                        <i class='bx bx-barcode' ></i>
                        <span class="links_name">Generate Barcode</span>
                    </a>
                </li>
                <li>
                    <a href="stocks.php">
                        <i class='bx bx-package'></i>
                        <span class="links_name">Stocks</span>
                    </a>
                </li>
                <li>
                    <a href="report.php">
                    <i class='bx bxs-report' ></i>
                        <span class="links_name">Report</span>
                    </a>
                </li>

                <li>
                    <a href="clients.php">
                    <i class='bx bxs-group'></i>
                        <span class="links_name">Clients</span>
                    </a>
                </li>
                
                <li>
                    <a href="setting.php">
                            <i class='bx bx-cog'></i>
                        <span class="links_name">Settings</span>
                    </a>
                </li>
                
                <li>
                    <a href="csvImporter.php">
                        <i class='bx bx-import' ></i>
                        <span class="links_name">CSV Importer</span>
                    </a>
                </li>

                <li>
                    <a href="departments.php">
                    <i class='bx bx-building' ></i>
                        <span class="links_name">Departments</span>
                    </a>
                </li>

                <li>
                    <a href="units.php">
                    <i class='bx bxs-layer'></i>
                        <span class="links_name">Units</span>
                    </a>
                </li>
            
            
            <?php } else {?>
                <li>
                    <a href="changePassword.php">
                    <i class='bx bx-cog'></i>
                        <span class="links_name">Change Password</span>
                    </a>
                </li>
            <?php } ?>



            
            <li class="log_out">
                <a href="logout.php">
                    <i class='bx bx-log-out'></i>
                    <span class="links_name">Log out</span>
                </a>
            </li>
        </ul>
    </div>