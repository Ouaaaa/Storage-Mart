<?php
require_once "config.php";
include("session-checker.php");
$accountID = $_SESSION['account_id'];
$fetchUser = "
    SELECT e.firstname,e.position 
    FROM tblaccounts a
    JOIN tblemployee e ON a.account_id = e.account_id
    WHERE a.account_id = ?
";
$loggedfirstname = '';
$loggedPosition = '';
if($stmt = mysqli_prepare($link, $fetchUser)){
    mysqli_stmt_bind_param($stmt, 'i',$accountID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$loggedfirstname,$loggedPosition);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}
$_SESSION['loggedfirstname'] = $loggedfirstname;
$_SESSION['loggedPosition'] = $loggedPosition;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$inventory = [];
$notificationMessage = "";

// === FETCH INVENTORY INFO ===
if (isset($_GET['inventory_id']) && !empty(trim($_GET['inventory_id']))) {
    $inventory_id = trim($_GET['inventory_id']);

    $sqlDisplay = "
        SELECT 
            e.employee_id,
            CONCAT(e.lastname, ', ', e.firstname, ' ', e.middlename) AS fullname,
            e.department,
            b.branch_id,
            b.branchName,
            i.inventory_id,
            i.assetNumber,
            g.group_id,
            CONCAT(g.groupName,' - ',g.description) AS groupName
        FROM tblemployee e
        JOIN tblbranch b ON e.branch_id = b.branch_id
        JOIN tblassets_inventory i ON e.employee_id = i.employee_id
        LEFT JOIN tblassets_group g ON g.group_id = i.group_id
        WHERE i.inventory_id = ?
    ";

    if ($stmt = mysqli_prepare($link, $sqlDisplay)) {
        mysqli_stmt_bind_param($stmt, "i", $inventory_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $inventory = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}
$notification = "";
// Handle form submission
if (isset($_POST['btnSubmit'])) {
    // Generate unique ticket number
    $ticket_number = 'TCK-' . date('YmdHis') . '-' . rand(100, 999);

    // Gather form data
    $employee_id = $_POST['employee_id'];      
    $inventory_id = $_POST['inventory_id'];    
    $branch_id = $_POST['branch_id'] ?? null;  
    $department = $_POST['department'] ?? '';
    $category = $_POST['category'] ?? '';
    $concern_details = $_POST['concern_details'] ?? '';
    $priority = $_POST['priority'] ?? 'Low';
    $accountID = $_SESSION['account_id'];      

    // Insert into tbltickets
    $sql = "INSERT INTO tbltickets (
        ticket_number, employee_id, inventory_id, branch_id, department,
        category, concern_details, priority, status, created_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param(
            $stmt,
            "siisssssi",
            $ticket_number,
            $employee_id,
            $inventory_id,
            $branch_id,
            $department,
            $category,
            $concern_details,
            $priority,
            $accountID
        );

        if (mysqli_stmt_execute($stmt)) {
            $ticket_id = mysqli_insert_id($link);

            // ✅ Log into tblticket_history
            $logHistory = "INSERT INTO tblticket_history 
                (ticket_id, action_type, action_details, new_status, performed_by, performed_role)
                VALUES (?, 'Created', 'Ticket filed by employee', 'Pending', ?, 'Employee')";
            if ($stmtLog = mysqli_prepare($link, $logHistory)) {
                mysqli_stmt_bind_param($stmtLog, "ii", $ticket_id, $accountID);
                mysqli_stmt_execute($stmtLog);
                mysqli_stmt_close($stmtLog);
            }

            // ✅ Log into tbllogs (system audit)
            $date = date("Y-m-d");
            $time = date("h:i:sa");
            $logAction = "Create";
            $module = "Ticket Management";
            $performedby = $_SESSION['username'];

            $sqlLog = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) 
                       VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmtLog2 = mysqli_prepare($link, $sqlLog)) {
                mysqli_stmt_bind_param(
                    $stmtLog2,
                    "ssssss",
                    $date,
                    $time,
                    $logAction,
                    $module,
                    $ticket_id,
                    $performedby
                );
                mysqli_stmt_execute($stmtLog2);
                mysqli_stmt_close($stmtLog2);
            }

            // ✅ Success message
            $notificationMessage = "Ticket successfully filed! Ticket No: $ticket_number";

        } else {
            echo "<font color='red'>Error inserting into tbltickets: " . mysqli_error($link) . "</font>";
        }
    } else {
        echo "<font color='red'>Error preparing statement for tbltickets: " . mysqli_error($link) . "</font>";
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

    <title>StorageMart | File Ticket Employee</title>

    <!-- Custom fonts for this template -->
    <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../css/input.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../Dashboard/index.php">
                <div class="sidebar-brand-icon ">
                    <img src="../../../img/logo.png" alt="Logo" style="width:40px; height:auto;">
                </div>
                <div class="sidebar-brand-text mx-3">Storage Mart</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="../Dashboard/index.php">
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
			<li class="nav-item active">
                <a class="nav-link" href="../Tickets/IT-Tickets.php">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Ticket</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Assets.php">
                    <i class="fas fa-archive"></i>
                    <span>Assets Directory </span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Actions
            </div>

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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?= htmlspecialchars($loggedfirstname) . " (" . htmlspecialchars($loggedPosition) . ")" ?>
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="../../../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../../public/login.php" data-toggle="modal" data-target="#logoutModal">
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
                            <h6 class="m-0 font-weight-bold text-primary">Add Ticket</h6>
                        </div>
                        <div class="card-body">
                            <div class="container mt-4">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <input type="hidden" name="branch_id" value="<?= htmlspecialchars($inventory['branch_id'] ?? '') ?>">
                                    <input type="hidden" name="inventory_id" value="<?= htmlspecialchars($inventory['inventory_id'] ?? '') ?>">
                            <h1>Employee Details</h1>
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label for="employee_id" class="form-label">Employee ID</label>
                                        <input type="text" class="form-control" id="employee_id" name="employee_id" placeholder="Employee ID" value="<?= htmlspecialchars($inventory['employee_id'] ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="fullname" class="form-label">Fullname</label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" value="<?= htmlspecialchars($inventory['fullname'] ?? '')  ?>" readonly>
                                    </div>
                                    </div>
                                    <div class ="row mb-5">
                                        <div class="col-md-6">
                                            <label for="department" class="form-label">Department</label>
                                            <input type="text" class="form-control" id="department" name="department" placeholder="Department" value="<?= htmlspecialchars($inventory['department'] ?? '') ?>" readonly>
                                        </div>
                                    <div class="col-md-6">
                                        <label for="branchName" class="form-label">Branch</label>
                                        <input type="text" class="form-control" id="branchName" name="branchName" placeholder="Branch" value="<?= htmlspecialchars($inventory['branchName'] ?? '') ?>" readonly>
                                    </div>
                                    </div>
                                <hr></hr>
                                <h1>Asset Details</h1>
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label for="assetNumber" class="form-label">Asset Number</label>
                                        <input type="text" class="form-control" id="assetNumber" name="assetNumber" placeholder="Asset Number" value="<?= htmlspecialchars($inventory['assetNumber'] ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="groupName" class="form-label">Model Name</label>
                                        <input type="text" class="form-control" id="groupName" name="groupName" placeholder="Model" value="<?= htmlspecialchars($inventory['groupName'] ?? '') ?>"  readonly>
                                    </div>
                                    </div>
                                <h1>Ticket Concerns</h1>
                                    <div class ="row mb-5">
                                            <div class ="col-md-6">
                                                <label for="category" class="form-label">Technical Category</label>
                                                <select id="category" name="category" class="form-control" required>
                                                <option value="">-- Select Category --</option>
                                                <option value="Hardware">Hardware</option>
                                                <option value="Software">Software</option>
                                                <option value="Network">Network</option>
                                                </select>
                                            </div>
                                            <div class ="col-md-6">
                                                <label for ="priority" class ="form-label">Priority</label>
                                                    <select id="priority" name="priority" class="form-control" required>
                                                    <option value="">-- Select Priority level --</option>
                                                    <option value="low">Low</option>
                                                    <option value="medium">Medium</option>
                                                    <option value="high">High</option>
                                                    </select>
                                            </div>
                                    </div>

                                    <div class="row mb-5">
                                            <div class="col-md-6">
                                                <label for = "concern_details" class ="form-label">Concern Summary</label>
                                                <textarea id ="concern_details" name="concern_details" class="form-control" rows="6" maxlength="1000" required></textarea>
                                                <small class="form-text text-muted">Maximum 1000 characters.</small>
                                            </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary" name="btnSubmit">Submit</button>
                                    <a href="Tickets.php" class="btn btn-danger">Cancel</a>
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
                    <a class="btn btn-primary" href="../../../../../public/Login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../../../vendor/jquery/jquery.min.js"></script>
    <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../../js/demo/datatables-demo.js"></script>
    <script>
    function togglePasswordVisibility() {
        var passwordField = document.getElementById("txtPassword");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            document.getElementById("showPassword").textContent = "Hide";
        }
        else {
            passwordField.type = "password";
            document.getElementById("showPassword").textContent = "Show";
        }
    }
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
    if (selectedOption.value !== "") {
        document.getElementById("lastname").value = selectedOption.getAttribute("data-lastname");
        document.getElementById("firstname").value = selectedOption.getAttribute("data-firstname");
        document.getElementById("middlename").value = selectedOption.getAttribute("data-middlename");
        document.getElementById("branch").value = selectedOption.getAttribute("data-branch");
        document.getElementById("department").value = selectedOption.getAttribute("data-department");
    } else {
        document.getElementById("lastname").value = "";
        document.getElementById("firstname").value = "";
        document.getElementById("middlename").value = "";
        document.getElementById("branch").value = "";
        document.getElementById("department").value = "";
    }
});
</script>

</body>

</html>