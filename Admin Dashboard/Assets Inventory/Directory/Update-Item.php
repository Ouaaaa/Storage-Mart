<?php
require_once "config.php";
include("session-checker.php");

$inventory = [];
$username = '';
    $accountID = $_SESSION['account_id'];
    $sql = "SELECT username FROM tblaccounts WHERE account_id = ?";
    if($stmtuser = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmtuser, "i", $accountID);
        mysqli_stmt_execute($stmtuser);
        mysqli_stmt_bind_result($stmtuser, $dbUsername);
        mysqli_stmt_fetch($stmtuser);
        mysqli_stmt_close($stmtuser);   
    
        $_SESSION['username'] = $dbUsername; 
    }
// For displaying logged in user info
    $userQuery = "SELECT  e.firstname, a.usertype FROM tblaccounts a JOIN tblemployee e ON a.account_id = e.employee_id  WHERE a.account_id = ?";


    if ($stmt = mysqli_prepare($link, $userQuery)) {
        mysqli_stmt_bind_param($stmt, "i", $accountID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $loggedFirstname, $loggedUsertype);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btnSubmit'])) {
    $group_id = isset($_GET['inventory_id']) ? intval($_GET['inventory_id']) : 0;
    // === Update tblassets_inventory ===
    $sql = "UPDATE tblassets_inventory SET itemInfo = ?, serialNumber = ?, year_purchased = ? WHERE inventory_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssi", 
            $_POST['itemInfo'],
            $_POST['serialNumber'],
            $_POST['year_purchased'],
            $_POST['inventory'] 
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
                    // === Insert into tbllogs ===
                    $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) 
                            VALUES (?, ?, ?, ?, ?, ?)";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        $date = date("Y-m-d");
                        $time = date("h:i:sa");
                        $action = "Update Asset Group";
                        $module = "Item Asset Management";
                        $performedby = $_SESSION['username'];
                        mysqli_stmt_bind_param($stmt, "ssssss", 
                            $date, 
                            $time, 
                            $action, 
                            $module, 
                            $accountID, 
                            $_SESSION['username']
                        );

                        if (mysqli_stmt_execute($stmt)) {
                            $notificationMessage = "Item Asset successfully updated!";
                        } else {
                            echo "<font color='red'>Error inserting into tbllogs.</font>";
                        }
                        mysqli_stmt_close($stmt);
                    }
        } else {
           echo "<font color='red'>Error updating tblassets_directory: " . mysqli_error($link) . "</font>";
        }
        
    }

} else {
    // === Loading the data to the form ===
    if (isset($_GET['inventory_id']) && !empty(trim($_GET['inventory_id']))) {
    $sql = "SELECT * FROM tblassets_inventory WHERE inventory_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $_GET['inventory_id']);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $inventory  = $data;  // contains account fields
        }
        mysqli_stmt_close($stmt);
    }
}

}
?>


<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>StorageMart | Update Item</title>

    <!-- Custom fonts for this template -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/input.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../../index.php">
                <div class="sidebar-brand-icon ">
                    <img src="../../img/logo.png" alt="Logo" style="width:40px; height:auto;">
                </div>
                <div class="sidebar-brand-text mx-3">Storage Mart</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="../../index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Users</span>	
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">User:</h6>
                        <a class="collapse-item" href="../../Account/Accounts.php">Accounts</a>
                        <a class="collapse-item" href="../../Account/Employee.php">Employee</a>
                    </div>
                </div>
            </li>
			
			<li class="nav-item">
                <a class="nav-link" href="../../Ticket/Tickets.php">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Ticket</span>
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="Assets.php">
                    <i class="fas fa-archive"></i>
                    <span>Assets Directory </span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsethree"
                    aria-expanded="true" aria-controls="collapsethree">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Asset Inventory</span>	
                </a>
                <div id="collapsethree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Branch:</h6>
                        <a class="collapse-item" href="../Inventory/Head-office.php">Head Office</a>
                        <a class="collapse-item" href="../Inventory/Iran.php">Iran</a>
                        <a class="collapse-item" href="../Inventory/Don-roces.php">Don Roces</a>
                        <a class="collapse-item" href="../Inventory/Sucat.php">Sucat</a>
                        <a class="collapse-item" href="../Inventory/Banawe.php">Sucat</a>
                        <a class="collapse-item" href="../Inventory/Santolan.php">Santolan</a>
                        <a class="collapse-item" href="../Inventory/Pasig.php">Pasig</a>
                        <a class="collapse-item" href="../Inventory/Bangkal.php">Bangkal</a>
                        <a class="collapse-item" href="../Inventory/Delta.php">Delta</a>
                        <a class="collapse-item" href="../Inventory/Binondo.php">Binondo</a>
                        <a class="collapse-item" href="../Inventory/Katipunan.php">Katipunan</a>
                        <a class="collapse-item" href="../Inventory/Fairview.php">Fairview</a>
                        <a class="collapse-item" href="../Inventory/Jabad.php">Jabad</a>
                        <a class="collapse-item" href="../Inventory/Yakal.php">Yakal</a>
                        <a class="collapse-item" href="../Inventory/Caloocan.php">Caloocan</a>

                    </div>
                </div>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Actions
            </div>
			
            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="../../Pendings.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Pendings</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Search -->

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= htmlspecialchars($loggedFirstname) . " (" . htmlspecialchars($loggedUsertype) . ")" ?></span>
                                <img class="img-profile rounded-circle"
                                    src="../../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../../../public/login.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800"></h1>
                    <p class="mb-4">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Update Item Asset</h6>
                        </div>
                                                <?php
                        if (empty($inventory)) {
                            echo "<div class='alert alert-danger'>⚠️ No asset record found. Please open this page with a valid inventory_id in the URL.</div>";
                        }
                        ?>
                        <div class="card-body">
                            <div class="container mt-4">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <input type="hidden" name="inventory" value="<?php echo htmlspecialchars($inventory['inventory_id']); ?>">
                                    <h1>Update Item Asset Details</h1>
                                        <div class ="row mb-5">
                                            <div class = "col-md-6">
                                                <label for="assetNumber" class="form-label">Asset Number</label>
                                                <input type="text" class ="form-control" id ="assetNumber" name="assetNumber" placeholder="Asset Number" value="<?php echo htmlspecialchars($inventory['assetNumber'] ?? ''); ?>" readonly>
                                            </div>
                                            <div class = "col-md-6">
                                                <label for="status" class="form-label">Status</label>
                                                <input type="text" class ="form-control" id ="status" name="status" placeholder="Status" value="<?php echo htmlspecialchars($inventory['status'] ?? ''); ?>" readonly>
                                            </div>
                                        </div>
                                     <div class ="row mb-5">
                                            <div class="col-md-6">
                                                <label for = "itemInfo" class ="form-label">Item general info</label>
                                                <textarea id ="itemInfo" name="itemInfo" class="form-control" rows="6" maxlength="1000" required><?php echo htmlspecialchars($inventory['itemInfo'] ?? ''); ?></textarea>
                                                <small class="form-text text-muted">Maximum 1000 characters.</small>
                                            </div>
                                            <div class="col-md-6">
                                            <label for = "serialNumber" class ="form-label">Serial Number</label>
                                                <input type="text" name="serialNumber" class="form-control" id="serialNumber" placeholder="Serial Number" value="<?php echo htmlspecialchars($inventory['serialNumber'] ?? '');?>"required>
                                            </div>
                                    </div>

                                    <div class ="row mb-5">
                                        <div class="col-md-6">
                                            <label for = "year_purchased" class ="form-label">Year purchased</label>
                                                <input type="text" name="year_purchased" class="form-control" id="year_purchased" placeholder="Year purchased" value="<?php echo htmlspecialchars(($inventory['year_purchased'] ?? ''));?>" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="btnSubmit">Submit</button>
                                    <a href="Assets-Item.php" class="btn btn-danger">Cancel</a>
                                    </form>
                            </div>

                        </div>
                    </div>
                    
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../../../public/login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../js/demo/datatables-demo.js"></script>

<script>
    var notificationMessage = "<?php echo isset($notificationMessage) ? $notificationMessage : ''; ?>";
    if (notificationMessage !== "") {
        alert(notificationMessage);
        window.location.href = "Assets-item.php";
    }
</script>
</body>

</html>