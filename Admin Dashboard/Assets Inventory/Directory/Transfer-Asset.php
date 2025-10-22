<?php
require_once "config.php";
include("session-checker.php");

$assets = [];
$username = '';
$accountID = $_SESSION['account_id'];

// 🔹 Fetch current user info
$sql = "SELECT username FROM tblaccounts WHERE account_id = ?";
if ($stmtuser = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmtuser, "i", $accountID);
    mysqli_stmt_execute($stmtuser);
    mysqli_stmt_bind_result($stmtuser, $dbUsername);
    mysqli_stmt_fetch($stmtuser);
    mysqli_stmt_close($stmtuser);
    $_SESSION['username'] = $dbUsername;
}

// 🔹 Get logged user display info
$userQuery = "SELECT e.firstname, a.usertype 
              FROM tblaccounts a 
              JOIN tblemployee e ON a.account_id = e.employee_id  
              WHERE a.account_id = ?";
if ($stmt = mysqli_prepare($link, $userQuery)) {
    mysqli_stmt_bind_param($stmt, "i", $accountID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $loggedFirstname, $loggedUsertype);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// 🔹 Get asset info based on inventory_id from URL
if (isset($_GET['inventory_id'])) {
    $inventory_id = intval($_GET['inventory_id']);

    $sqlAsset = "SELECT * FROM tblassets_inventory WHERE inventory_id = ?";
    $stmt = mysqli_prepare($link, $sqlAsset);
    mysqli_stmt_bind_param($stmt, "i", $inventory_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $assets = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
} else {
    $inventory_id = 0;
}

// 🔹 When form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $inventory_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $new_employee = $_POST['employee_id'];
    $transferDetails = $_POST['transferDetails'];
    $createdby = $_SESSION['username'];
    $dateIssued = date("Y-m-d");
    $datecreated = date("Y-m-d H:i:s");

    if ($inventory_id <= 0) {
        echo "<script>alert('Error: Missing inventory ID!'); window.history.back();</script>";
        exit;
    }

    if (empty($new_employee)) {
        echo "<script>alert('Please select an employee before submitting.'); window.history.back();</script>";
        exit;
    }

    // 1️⃣ Get current asset info
    $sqlOld = "SELECT assetNumber, group_id, status FROM tblassets_inventory WHERE inventory_id = ?";
    $stmt = mysqli_prepare($link, $sqlOld);
    mysqli_stmt_bind_param($stmt, "i", $inventory_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $oldAssetNumber, $group_id, $currentStatus);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // 2️⃣ Extract the base asset (e.g. OE-24001 from OE-24001-HOS001)
    $baseAsset = explode('-', $oldAssetNumber);
    if (count($baseAsset) >= 2) {
        $baseAssetNumber = $baseAsset[0] . "-" . $baseAsset[1];
    } else {
        $baseAssetNumber = $oldAssetNumber;
    }

    // 3️⃣ Get branchCode and branch_id from employee
    $sqlBranch = "
        SELECT b.branchCode, b.branch_id
        FROM tblemployee e
        JOIN tblbranch b ON e.branch_id = b.branch_id
        WHERE e.employee_id = ?
    ";
    $stmt = mysqli_prepare($link, $sqlBranch);
    mysqli_stmt_bind_param($stmt, "i", $new_employee);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $branchCode, $branch_id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // 4️⃣ Count how many times this assetNumber base has been assigned (for suffix)
    $sqlCount = "SELECT COUNT(*) FROM tblassets_inventory WHERE assetNumber LIKE CONCAT(?, '%')";
    $stmt = mysqli_prepare($link, $sqlCount);
    mysqli_stmt_bind_param($stmt, "s", $baseAssetNumber);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $totalCount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $suffix = str_pad($totalCount, 3, "0", STR_PAD_LEFT);

    // 5️⃣ Generate new asset number (base + branch code + suffix)
    $newAssetNumber = $baseAssetNumber . "-" . $branchCode . $suffix;

    // 6️⃣ Get employee’s full name for assignment log
    $sqlEmp = "SELECT CONCAT(lastname, ', ', firstname, ' ', IFNULL(middlename, '')) AS full_name 
               FROM tblemployee WHERE employee_id = ?";
    $stmt = mysqli_prepare($link, $sqlEmp);
    mysqli_stmt_bind_param($stmt, "i", $new_employee);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $assignedTo);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // 7️⃣ Record transfer in tblassets_assignment
    $insert = "INSERT INTO tblassets_assignment 
                (employee_id, assignedTo, dateIssued, transferDetails, datecreated, createdby)
                VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $insert);
    mysqli_stmt_bind_param($stmt, "isssss", 
        $new_employee, $assignedTo, $dateIssued, $transferDetails, $datecreated, $createdby
    );
    mysqli_stmt_execute($stmt);
    $assignment_id = mysqli_insert_id($link);
    mysqli_stmt_close($stmt);

    // 8️⃣ Update inventory record (mark as assigned)
    $newStatus = ($new_employee > 0) ? 'ASSIGNED' : 'UNASSIGNED';
    $update = "UPDATE tblassets_inventory 
               SET assignment_id = ?, employee_id = ?, branch_id = ?, assetNumber = ?, status = ?
               WHERE inventory_id = ?";
    $stmt = mysqli_prepare($link, $update);
    mysqli_stmt_bind_param($stmt, "iiissi", 
        $assignment_id, $new_employee, $branch_id, $newAssetNumber, $newStatus, $inventory_id
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // 9️⃣ Log transfer
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $action = "Transferred asset $oldAssetNumber to $assignedTo ($branchCode)";
    $module = "Asset Inventory";

    $log = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $log);
    mysqli_stmt_bind_param($stmt, "ssssss", 
        $date, $time, $action, $module, $inventory_id, $createdby
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo "<script>alert('Asset successfully transferred!\\nNew Asset Number: $newAssetNumber'); window.location.href='Assets.php';</script>";
}
?>



<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>StorageMart | Admin Transfer Details</title>

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
                            <h6 class="m-0 font-weight-bold text-primary">Transfer Asset</h6>
                        </div>
                        <div class="card-body">
                            <div class="container mt-4">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($assets['inventory_id']); ?>">
                                    <h1>Transfer Details</h1>
                                    <div class ="row mb-5">
                                        <div class = "col-md-6">
                                            <label for="assignedTo" class="form-label">Transfer to</label>
                                                <select id="employee_id" name="employee_id" class="form-control" required>
                                                <option value="">-- Select Employee --</option>
                                                    <?php 
                                                        $sql = "
                                                            SELECT e.employee_id,
                                                                CONCAT(e.lastname, ', ', e.firstname, ' ', IFNULL(e.middlename, '')) AS full_name,
                                                                b.branchName,
                                                                b.branchCode
                                                            FROM tblemployee e
                                                            LEFT JOIN tblbranch b ON e.branch_id = b.branch_id
                                                            ORDER BY e.lastname ASC
                                                        ";

                                                        $result = mysqli_query($link, $sql);
                                                        
                                                        if($result && mysqli_num_rows($result) > 0){
                                                            while ($row = mysqli_fetch_assoc($result)) {
                                                                $displaytext = $row['employee_id'] . " - " . $row['full_name'];
                                                                echo '<option value="'.$row['employee_id'].'"
                                                                            data-branchName="'.$row['branchName'].'"
                                                                            data-branchCode="'.$row['branchCode'].'">'
                                                                            .$displaytext.
                                                                    '</option>';
                                                            }

                                                            mysqli_free_result($result);
                                                        } else {
                                                            echo "<option value=''>No categories available</option>";
                                                        }
                                                    ?>
                                                </select>
                                        </div>
                                        <div class = "col-md-6">
                                            <label for="branchName" class="form-label">Employee Branch</label>
                                            <input type="text" class ="form-control" id ="branchName" name="branchName" placeholder="Employee Branch" value="<?php echo htmlspecialchars($branch['branchName'] ?? ''); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class ="row mb-5">
                                            <div class="col-md-6">
                                                <label for = "transferDetails" class ="form-label">Transfer Details</label>
                                                <textarea id ="transferDetails" name="transferDetails" class="form-control" rows="6" maxlength="1000" required></textarea>
                                                <small class="form-text text-muted">Maximum 1000 characters.</small>
                                            </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary" name="btnSubmit">Submit</button>
                                    <a href="Assets.php" class="btn btn-danger">Cancel</a>
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
    function togglePasswordVisibility() {
    var passwordField = document.getElementById("password");
    var icon = document.querySelector("#showPassword i");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

document.getElementById("showPassword").addEventListener("click", togglePasswordVisibility);

</script>

<script>
    var notificationMessage = "<?php echo isset($notificationMessage) ? $notificationMessage : ''; ?>";
    if (notificationMessage !== "") {
        alert(notificationMessage);
        window.location.href = "Assets.php";
    }
</script>
<script>
document.getElementById("employee_id").addEventListener("change", function() {
    var selectedOption = this.options[this.selectedIndex];
    var branchName = selectedOption.getAttribute("data-branchName") || "";
    document.getElementById("branchName").value = branchName;
});

</script>
</body>

</html>