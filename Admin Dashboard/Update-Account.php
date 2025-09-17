<?php
require_once "config.php";
include("session-checker.php");

$account = [];
$employee = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btnSubmit'])) {
    // === Update tblaccounts ===
    $sql = "UPDATE tblaccounts SET username = ?, password = ?, usertype = ?,status = ? WHERE account_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssi", 
            $_POST['username'], 
            $_POST['password'], 
            $_POST['usertype'],
            $_POST['status'], 
            $_POST['account_id']
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            // === Update tblemployee ===
            $empsql = "UPDATE tblemployee 
                       SET lastname = ?, firstname = ?, middlename = ?, department = ?, branch = ?, email = ? 
                       WHERE employee_id = ?";
            if ($stmt = mysqli_prepare($link, $empsql)) {
                mysqli_stmt_bind_param($stmt, "ssssssi", 
                    $_POST['last-name'], 
                    $_POST['first-name'], 
                    $_POST['middle-name'], 
                    $_POST['department'], 
                    $_POST['branch'],
                    $_POST['email'], 
                    $_POST['employee_id']
                );

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);

                    // === Insert into tbllogs ===
                    $sql = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) 
                            VALUES (?, ?, ?, ?, ?, ?)";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        $date = date("Y-m-d");
                        $time = date("h:i:sa");
                        $action = "Updated an Account";
                        $module = "Employee Management";
                        $employee_id = $_POST['employee_id'];
                        mysqli_stmt_bind_param($stmt, "ssssss", 
                            $date, 
                            $time, 
                            $action, 
                            $module, 
                            $employee_id, 
                            $_SESSION['username']
                        );

                        if (mysqli_stmt_execute($stmt)) {
                            $notificationMessage = "Account successfully updated!";
                        } else {
                            echo "<font color='red'>Error inserting into tbllogs.</font>";
                        }
                        mysqli_stmt_close($stmt);
                    }
                } else {
                    echo "<font color='red'>Error updating tblemployee: " . mysqli_error($link) . "</font>";
                }
            }
        } else {
           echo "<font color='red'>Error updating tblaccounts: " . mysqli_error($link) . "</font>";
        }
    }

} else {
    // === Loading the data to the form ===
    if (isset($_GET['account_id']) && !empty(trim($_GET['account_id']))) {
    $sql = "SELECT a.*, e.* 
            FROM tblaccounts a
            JOIN tblemployee e ON a.account_id = e.account_id
            WHERE a.account_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $_GET['account_id']);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $account  = $data;  // contains account fields
            $employee = $data;  // contains employee fields
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

    <title>Unipath Admin Accounts - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/input.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon ">
                    <img src="img/logo.png" alt="Logo" style="width:40px; height:auto;">
                </div>
                <div class="sidebar-brand-text mx-3">Storage Mart</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
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
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Users</span>	
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">User:</h6>
                        <a class="collapse-item" href="Accounts.php">Accounts</a>
                        <a class="collapse-item" href="Employee.php">Employee</a>
                    </div>
                </div>
            </li>
			
			<li class="nav-item">
                <a class="nav-link" href="Tickets.php">
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
            <li class="nav-item ">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsethree"
                    aria-expanded="true" aria-controls="collapsethree">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Asset Inventory</span>	
                </a>
                <div id="collapsethree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Branch:</h6>
                        <a class="collapse-item" href="Head-office.php">Head Office</a>
                        <a class="collapse-item" href="Iran.php">Iran</a>
                        <a class="collapse-item" href="Don-roces.php">Don Roces</a>
                        <a class="collapse-item" href="Sucat.php">Sucat</a>
                        <a class="collapse-item" href="Banawe.php">Sucat</a>
                        <a class="collapse-item" href="Santolan.php">Santolan</a>
                        <a class="collapse-item" href="Pasig.php">Pasig</a>
                        <a class="collapse-item" href="Bangkal.php">Bangkal</a>
                        <a class="collapse-item" href="Delta.php">Delta</a>
                        <a class="collapse-item" href="Binondo.php">Binondo</a>
                        <a class="collapse-item" href="Katipunan.php">Katipunan</a>
                        <a class="collapse-item" href="Fairview.php">Fairview</a>
                        <a class="collapse-item" href="Jabad.php">Jabad</a>
                        <a class="collapse-item" href="Yakal.php">Yakal</a>
                        <a class="collapse-item" href="Caloocan.php">Caloocan</a>

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
                <a class="nav-link" href="Pendings.php">
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../public/login.php" data-toggle="modal" data-target="#logoutModal">
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
                            <h6 class="m-0 font-weight-bold text-primary">Update Account</h6>
                        </div>
                        <div class="card-body">
                            <div class="container mt-4">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <input type="hidden" name="account_id" value="<?php echo htmlspecialchars($account['account_id']); ?>">
                                    <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee['employee_id']); ?>">
                                    <h1>Account Details</h1>
                                    <div class ="row mb-5">
                                        <div class = "col-md-6">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class ="form-control" id ="username" name="username" placeholder="Username" value="<?php echo htmlspecialchars($account['username'] ?? ''); ?>" required>
                                        </div>
                                    <div class="col-md-6 position-relative">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Password" value="<?php echo htmlspecialchars($account['password'] ?? ''); ?>" required>
                                            <span class="input-group-text" id="showPassword" style="cursor: pointer;">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>


                                    </div>

                                    <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label for="usertype" class="form-label">User Type</label>
                                        <select id="usertype" name="usertype" class="form-control" required>
                                            <option value="">-- Select User Type --</option>
                                            <option value="ADMIN" <?= (isset($account['usertype']) && $account['usertype'] === 'ADMIN') ? 'selected' : '' ?>>Admin</option>
                                            <option value="HR" <?= (isset($account['usertype']) && $account['usertype'] === 'HR') ? 'selected' : '' ?>>Human Resources</option>
                                            <option value="ACCTNG" <?= (isset($account['usertype']) && $account['usertype'] === 'ACCTNG') ? 'selected' : '' ?>>ACCOUNTING</option>
                                        </select>
                                    </div>
                                        <div class="col-md-6">
                                            <label for="status" class="form-label">Status</label>
                                            <select id="status" name="status" class="form-control" required>
                                            <option value="">-- Select Status --</option>
                                            <option value="ACTIVE" <?= (isset($account['status']) && $account['status'] === 'ACTIVE') ? 'selected' : '' ?>>Active</option>
                                            <option value="INACTIVE" <?= (isset($account['status']) && $account['status'] === 'INACTIVE') ? 'selected' : '' ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>


                                    <h1>Employee Details </h1>
                                    <div class ="row mb-5">
                                            <div class= "col-md-6">
                                                <label for="employee_id" class="form-label">Employee ID</label>
                                                <input type="text" class="form-control" id="employee_id" name="employee_id" placeholder="Employee ID" value="<?php echo htmlspecialchars($employee['employee_id'] ?? ''); ?>" readonly> 
                                            </div>
                                            <div class="col-md-6">
                                                <label for="branch" class="form-label">Branch</label>
                                                <select id="branch" name="branch" class="form-control" required>
                                                <option value="">-- Select Branch --</option>
                                                <option value="ERAN" <?= (isset($employee['branch']) && $employee['branch'] === 'ERAN') ? 'selected' : '' ?>>Eran</option>
                                                <option value=""></option>
                                                <option value=""></option>
                                                </select>
                                            </div>
                                    </div>
                                    <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label for="last-name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="last-name" name="last-name" placeholder="Last name" value="<?php echo htmlspecialchars($employee['lastname'])?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="first-name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="first-name" name="first-name" placeholder="First name" value="<?php echo htmlspecialchars($employee['firstname'])?>" required>
                                    </div>
                                    </div>

                                    <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label for="middle-name" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" id="middle-name" name="middle-name" placeholder="Middle name" value="<?php echo htmlspecialchars($employee['middlename'])?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="department" class="form-label">Department</label>
                                        <select id="department" name="department" class="form-control" required>
                                        <option value="">-- Select Department --</option>
                                        <option value="IT"<?= (isset($employee['department']) && $employee['department'] === 'IT') ? 'selected' : '' ?>>Information Technology</option>
                                        <option value=""></option>
                                        <option value=""></option>
                                        </select>
                                    </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($employee['email'])?>" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="btnSubmit">Submit</button>
                                    <a href="Accounts.php" class="btn btn-danger">Cancel</a>
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
                    <a class="btn btn-primary" href="../public/login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
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
        window.location.href = "Accounts.php";
    }
</script>
</body>

</html>