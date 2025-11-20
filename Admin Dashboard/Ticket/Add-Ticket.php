<?php
require_once "config.php";
include("session-checker.php");
    $accountID = $_SESSION['account_id'];
    $username = ''; // Initialize to avoid Errors

    $userQuery = "SELECT  e.firstname, a.usertype FROM tblaccounts a JOIN tblemployee e ON a.account_id = e.employee_id  WHERE a.account_id = ?";


    if ($stmt = mysqli_prepare($link, $userQuery)) {
        mysqli_stmt_bind_param($stmt, "i", $accountID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $loggedFirstname, $loggedUsertype);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
    $_SESSION['username'] = $username;

// Handle employee selection
if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    // Query to fetch the assets related to the selected employee
    $sql = "
    SELECT 
        i.assetNumber,
        g.groupName,
        g.ic_code,
        i.itemInfo,
        i.serialNumber,
        i.year_purchased
    FROM 
        tblassets_inventory i
    LEFT JOIN tblassets_group g ON i.group_id = g.group_id
    WHERE i.employee_id = ?
    ";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $employee_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $assets = [];

        // Fetch assets and add them to the assets array
        while ($row = mysqli_fetch_assoc($result)) {
            $assets[] = $row;
        }

        mysqli_stmt_close($stmt);

        // Return assets as JSON
        echo json_encode($assets);
    } else {
        echo json_encode(['error' => 'Error executing query']);
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
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../index.php">
                <div class="sidebar-brand-icon ">
                    <img src="../img/logo.png" alt="Logo" style="width:40px; height:auto;">
                </div>
                <div class="sidebar-brand-text mx-3">Storage Mart</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="../index.php">
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
                        <a class="collapse-item" href="../Account/Accounts.php">Accounts</a>
                        <a class="collapse-item" href="../Account/Employee.php">Employee</a>
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
                <a class="nav-link" href="../Assets Inventory/Directory/Assets.php">
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
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Head-office.php">Head Office</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Iran.php">Iran</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Don-roces.php">Don Roces</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Sucat.php">Sucat</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Banawe.php">Sucat</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Santolan.php">Santolan</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Pasig.php">Pasig</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Bangkal.php">Bangkal</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Delta.php">Delta</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Binondo.php">Binondo</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Katipunan.php">Katipunan</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Fairview.php">Fairview</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Jabad.php">Jabad</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Yakal.php">Yakal</a>
                        <a class="collapse-item" href="../Assets Inventory/Inventory/Caloocan.php">Caloocan</a>

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
                <a class="nav-link" href="../Pendings.php">
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
                                    src="../img/undraw_profile.svg">
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
                                    
                                <h1>Employee Details</h1>
                                <div class="row mb-5">
                                    <div class="col-md-6">
                                        <label for="employee_search" class="form-label">Search Employee</label>
                                        <div class="input-group mb-3">
                                            <input type="text" id="employee_search" class="form-control" placeholder="Type employee name or ID">
                                            <button type="button" class="btn btn-primary" id="btnSearchEmployee">Search</button>
                                        </div>
                                        <input type="hidden" id="employee_id" name="employee_id">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="fullname" class="form-label">Fullname</label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" required>
                                    </div>
                                    </div>
                                    <div class ="row mb-5">
                                        <div class="col-md-6">
                                            <label for="department" class="form-label">Department</label>
                                            <input type="text" class="form-control" id="department" name="department" placeholder="Department" required>
                                        </div>
                                    <div class="col-md-6">
                                        <label for="branch" class="form-label">Branch</label>
                                        <input type="text" class="form-control" id="branch" name="branch" placeholder="Branch" required>
                                    </div>
                                    </div>

                                    <hr></hr>
                            <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="asset-ticket" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Asset Number</th>
                                            <th>Name</th>
                                            <th>IC CODE</th>
                                            <th>Description</th>
											<th>Serial Number</th>
                                            <th>Year Purchased</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Asset Number</th>
                                            <th>Name</th>
                                            <th>IC CODE</th>
                                            <th>Description</th>
											<th>Serial Number</th>
                                            <th>Year Purchased</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="assetsTable">
                                    </tbody>
                                    </tbody>
                                </table>
                            </div>
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
                    <a class="btn btn-primary" href="../../public/login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    var notificationMessage = "<?php echo isset($notificationMessage) ? $notificationMessage : ''; ?>";
    if (notificationMessage !== "") {
        alert(notificationMessage);
        window.location.href = "Tickets.php";
    }
</script>
 <script>
$('#btnSearchEmployee').on('click', function() {
    var query = $('#employee_search').val().trim();

    if(query === '') {
        alert("Please enter employee name or ID.");
        return;
    }

    $.ajax({
        url: 'search-employee.php',  // create this new file
        type: 'GET',
        data: { q: query },
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                $('#employee_id').val(response.employee_id);
                $('#employee_search').val(response.full_name);
                $('#fullname').val(response.full_name);
                $('#branch').val(response.branchName);
                $('#department').val(response.department);

                // Trigger asset fetch
                fetchAssets(response.employee_id);
            } else {
                alert(response.message);
                $('#employee_id').val('');
                $('#fullname').val('');
                $('#branch').val('');
                $('#department').val('');
                $('#assetsTable').html('');
            }
        },
        error: function() {
            alert('Error fetching employee data.');
        }
    });
});

// Function to fetch assets
function fetchAssets(employee_id) {
    if(employee_id) {
        $.ajax({
            type: 'GET',
            url: 'get_assets.php',
            data: { employee_id: employee_id },
            dataType: 'json',
            success: function(response) {
                if(response && response.length > 0) {
                    var assetsHTML = '';
                    $.each(response, function(index, asset) {
                        assetsHTML += '<tr>';
                        assetsHTML += '<td>' + asset.assetNumber + '</td>';
                        assetsHTML += '<td>' + asset.groupName + '</td>';
                        assetsHTML += '<td>' + asset.ic_code + '</td>';
                        assetsHTML += '<td>' + asset.itemInfo + '</td>';
                        assetsHTML += '<td>' + asset.serialNumber + '</td>';
                        assetsHTML += '<td>' + asset.year_purchased + '</td>';
                        assetsHTML += '<td><a href="File-ticket.php?inventory_id=' + asset.inventory_id + '&employee_id=' + employee_id + '"><button type="button" class="btn btn-outline-success">File Ticket</button></a></td>';
                        assetsHTML += '</tr>';
                    });
                    $('#assetsTable').html(assetsHTML);
                } else {
                    $('#assetsTable').html('<tr><td colspan="7">No assets found for this employee.</td></tr>');
                }
            },
            error: function() {
                alert('Error fetching asset data.');
            }
        });
    } else {
        $('#assetsTable').html('');
    }
}

    </script>


</body>
</html>