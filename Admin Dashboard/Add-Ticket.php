<?php
require_once "config.php";
include("session-checker.php");

    $accountID = $_SESSION['account_id'];
    $sql = "SELECT employee_id FROM tbltickets WHERE ticket_id = ?";
    $username = '';
if (isset($_POST['btnSubmit'])) {
    // Insert into tbltickets
   $sql = "INSERT INTO tbltickets (
                employee_id, lastname, firstname, middlename, branch, department,
                ticket_assign, technical_purpose, concern_details, action, result,
                status, priority, category, created_by, datecreated, attachments, remarks
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Collect form values
        $employee_id = $_POST['employee_id'];
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $branch = $_POST['branch'];
        $department = $_POST['department'];
        $ticket_assign = $_POST['ticket_assign'];
        $technical_purpose = $_POST['technical_purpose'];
        $concern_details = $_POST['concern_details'];
        $actionTaken = $_POST['action'];
        $resultDetails = $_POST['result'];
        $priority = $_POST['priority'];
        $category = $_POST['category'];
        $created_by = $_SESSION['account_id']; 
        $datecreated = date('Y-m-d H:i:s');
        $status = "PENDING";   
        $attachments = "";
        $remarks = $_POST['remarks'];

       
       mysqli_stmt_bind_param(
            $stmt,
            "isssssssssssssssss",   // 1 int + 17 strings = 18 total
            $employee_id, $lastname, $firstname, $middlename, $branch, $department,
            $ticket_assign, $technical_purpose, $concern_details, $actionTaken, $resultDetails,
            $status, $priority, $category, $created_by, $datecreated, $attachments, $remarks
        );



        if (mysqli_stmt_execute($stmt)) {
            // Get the auto incremented ticket_id
            $ticket_id = mysqli_insert_id($link);

            // Insert into tbllogs
            $sqlLog = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) 
                       VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmtLog = mysqli_prepare($link, $sqlLog)) {
                $date       = date("Y-m-d");
                $time       = date("h:i:sa");
                $logAction  = "Create";
                $module     = "Ticket Management";
                $performedby = $_SESSION['username'];

                mysqli_stmt_bind_param(
                    $stmtLog,
                    "ssssss",
                    $date, $time, $logAction, $module, $ticket_id, $performedby
                );
                mysqli_stmt_execute($stmtLog);
            }

            $notificationMessage = "New Ticket successfully created!";
        } else {
            echo "<font color='red'>Error inserting into tbltickets: " . mysqli_error($link) . "</font>";
        }
    } else {
        echo "<font color='red'>Error preparing statement for tbltickets.</font>";
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
            <li class="nav-item ">
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
			
			<li class="nav-item active">
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
                            <h6 class="m-0 font-weight-bold text-primary">Add Ticket</h6>
                        </div>
                        <div class="card-body">
                            <div class="container mt-4">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    
                                <h1>Employee Details</h1>
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label for="lastname" class="form-label">Employee ID</label>
                                        <select id="employee_id" name="employee_id" class="form-control" required> 
                                            <option value="">-- Select Employee --</option>
                                            <?php
                                            $sql = "SELECT employee_id, lastname, firstname, middlename, branch, department 
                                                    FROM tblemployee ";
                                            $result = mysqli_query($link, $sql);

                                            if ($result && mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $displayText = $row['employee_id'] . " - " . $row['lastname'] . ", " . $row['firstname'];
                                                    echo '<option value="'.$row['employee_id'].'" 
                                                                data-lastname="'.$row['lastname'].'" 
                                                                data-middlename="'.$row['middlename'].'" 
                                                                data-firstname="'.$row['firstname'].'" 
                                                                data-branch="'.$row['branch'].'" 
                                                                data-department="'.$row['department'].'">'
                                                            .$displayText.'</option>';
                                                }
                                            } else {
                                                echo '<option value="">No employees found</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lastname" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last name" required>
                                    </div>
                                    </div>

                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <label for="firstname" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First name" required>
                                        </div>
                                    <div class="col-md-6">
                                        <label for="middlename" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle name" required>
                                    </div>
                                    </div>

                                    <div class ="row mb-5">
                                        <div class="col-md-6">
                                            <label for="department" class="form-label">Department</label>
                                            <select id="department" name="department" class="form-control" required>
                                            <option value="">-- Select Department --</option>
                                            <option value="IT">Information Technology</option>
                                            <option value=""></option>
                                            <option value=""></option>
                                            </select>
                                        </div>
                                            <div class="col-md-6">
                                                <label for="branch" class="form-label">Branch</label>
                                                <select id="branch" name="branch" class="form-control" required>
                                                <option value="">-- Select Branch --</option>
                                                <option value="ERAN">Eran</option>
                                                <option value=""></option>
                                                <option value=""></option>
                                                </select>
                                            </div>
                                    </div>
                                <hr></hr>
                                <h1>Ticket Details</h1>
                                    <div class="row mb-5">
                                        <div class="col-md-6">
                                            <label for="department" class="form-label">Assign to</label>
                                                <select id="ticket_assign" name="ticket_assign" class="form-control" required> 
                                                    <option value="">-- Select Assignee --</option>
                                                    <?php
                                                    // include DB config
                                                    require_once "config.php"; 

                                                    // query employees
                                                    $sql = "SELECT
                                                    employee_id, 
                                                    CONCAT(lastname ,',',' ', firstname) AS fullname
                                                    FROM tblemployee WHERE department = 'IT'";
                                                    $result = mysqli_query($link, $sql);

                                                    if ($result && mysqli_num_rows($result) > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            echo '<option value="'.$row['employee_id'].'">'.$row['fullname'].'</option>';
                                                        }
                                                    } else {
                                                        echo '<option value="">No employees found</option>';
                                                    }
                                                    ?>
                                                </select>
                                        </div>
                                    </div>
                                    <div class ="row mb-5">
                                            <div class ="col-md-6">
                                                <label for="technical_purpose" class="form-label">Technical Purpose</label>
                                                <select id="technical_purpose" name="technical_purpose" class="form-control" required>
                                                <option value="">-- Select Purpose --</option>
                                                <option value="CCTV & MAINTAINANCE">CCTV & MAINTAINANCE</option>
                                                <option value=""></option>
                                                <option value=""></option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for = "concern-details" class ="form-label">Concern Details</label>
                                                <textarea id ="concern_details" name="concern_details"class="form-control" rows="6" maxlength="1000" required>

                                                </textarea>
                                                <small class="form-text text-muted">Maximum 1000 characters.</small>
                                            </div>
                                    </div>

                                    <div class ="row mb-5">
                                            <div class="col-md-6">
                                                <label for = "action" class ="form-label">Action Taken</label>
                                                <textarea id ="action" name="action"class="form-control" rows="6" maxlength="1000" required>

                                                </textarea>
                                                <small class="form-text text-muted">Maximum 1000 characters.</small>
                                            </div>

                                            <div class="col-md-6">
                                                <label for = "result" class ="form-label">Result Details</label>
                                                <textarea id ="result" name="result" class="form-control" rows="6" maxlength="1000" required>

                                                </textarea>
                                                <small class="form-text text-muted">Maximum 1000 characters.</small>
                                            </div>
                                    </div>

                                    <div class ="row mb-5">
                                        <div class ="col-md-6">
                                            <label for ="priority" class ="form-label">Priority</label>
                                                <select id="priority" name="priority" class="form-control" required>
                                                <option value="">-- Select Priority level --</option>
                                                <option value="low">Low</option>
                                                <option value="medium">Medium</option>
                                                <option value="high">High</option>
                                                </select>
                                        </div>

                                        <div class ="col-md-6">
                                            <label for ="category" class ="form-label">Category</label>
                                                <select id="category" name="category" class="form-control" required>
                                                <option value="">-- Select Category --</option>
                                                <option value="Software,Hardware">Software & Hardware</option>
                                                <option value=""></option>
                                                <option value=""></option>
                                                </select>
                                        </div>

                                    </div>

                                    <div class="row mb-5">
                                            <div class="col-md-6">
                                                <label for = "remarks" class ="form-label">Remarks</label>
                                                <textarea id ="remarks" name="remarks" class="form-control" rows="6" maxlength="1000" required>

                                                </textarea>
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
        window.location.href = "Tickets.php";
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