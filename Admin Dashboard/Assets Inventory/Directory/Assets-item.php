<?php
    require_once "config.php";
    include "session-checker.php";
    //Get the group_id from the URL parameter
    $group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;

    // For Displaying the User
    $accountID = $_SESSION['account_id'];

    $userQuery = "
        SELECT e.employee_id, e.firstname, e.position
        FROM tblaccounts a
        JOIN tblemployee e ON a.account_id = e.account_id
        WHERE a.account_id = ?
    ";

    $employee_id = '';
    $loggedFirstname = '';
    $loggedUsertype = '';

    if ($stmt = mysqli_prepare($link, $userQuery)) {
        mysqli_stmt_bind_param($stmt, "i", $accountID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $employee_id, $loggedFirstname, $loggedUsertype);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }

    $_SESSION['loggedFirstname'] = $loggedFirstname;
    $_SESSION['loggedUsertype'] = $loggedUsertype;


    //For Fetching the Asset Inventory
$query = "
    SELECT 
        i.inventory_id,
        i.assetNumber,
        i.serialNumber,
        i.itemInfo,
        i.status,
        b.branch_id,
        b.branchName,
        e.employee_id,
        CONCAT(e.firstname, ' ', e.lastname) AS employeeName,
        g.group_id,
        g.groupName,
        a.assignment_id,
        a.transferDetails
    FROM tblassets_inventory i 
    LEFT JOIN tblassets_assignment a 
        ON i.assignment_id = a.assignment_id
    LEFT JOIN tblbranch b 
        ON i.branch_id = b.branch_id
    LEFT JOIN tblemployee e
        ON i.employee_id = e.employee_id
    JOIN tblassets_group g 
        ON i.group_id = g.group_id
    WHERE i.group_id = ?
    ORDER BY i.inventory_id ASC;
";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $group_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("SQL Error: " . mysqli_error($link));
}

?>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Storage Mart Assets Directory- Tables</title>

    <!-- Custom fonts for this template -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="icon" href="../../img/favicon.ico" type="image/x-icon">
    <!-- Custom styles for this page -->
    <link href="../../vendor/datatables/dataTables.min.css" rel="stylesheet">

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
            <li class="nav-item ">
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?= htmlspecialchars($loggedFirstname) . " (" . htmlspecialchars($loggedUsertype) . ")" ?>
                                </span>
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
                    <h1 class="h3 mb-2 text-gray-800">Tables</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                    <!-- Main conctent -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">List of Item Assets</h6>
                        </div>
                        <div class="d-flex flex-column align-items-end" style="gap: 10px; margin-right: 40px; margin-top: 40px;">
                            <a href="Add-Asset.php?group_id=<?= $group_id; ?>" class="btn btn-primary" style="width:160px;">
                                <i class="fas fa-plus"></i> Add Asset
                            </a>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="asset_inventory" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Item Info</th>
                                            <th>Asset Number</th>
                                            <th>Serial Number</th>
                                            <th>Branch</th>
                                            <th>Status</th>
											<th>Employee</th>
                                            <th>Transfer Info</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Item Info</th>
                                            <th>Asset Number</th>
                                            <th>Serial Number</th>
                                            <th>Branch</th>
                                            <th>Status</th>
											<th>Employee</th>
                                            <th>Transfer Info</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['itemInfo']);?> </td>
                                            <td><?= htmlspecialchars($row['assetNumber']);?> </td>
                                            <td><?= htmlspecialchars($row['serialNumber']);?> </td>
                                            <td><?= htmlspecialchars($row['branchName']); ?></td>
                                            <td><?= htmlspecialchars($row['status']);?> </td>
                                            <td><?= htmlspecialchars($row['employeeName']); ?></td>
                                            <td><?= htmlspecialchars($row['transferDetails']); ?></td>
                                            <td>
                                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="mr-2 d-none d-lg-inline text-gray-600 ">
                                                        Action</span>
                                                </a>
                                                    <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="userDropdown">
                                                        <!-- Update -->
                                                        <a class="dropdown-item" href="Update-Item.php?inventory_id=<?= $row['inventory_id']; ?>">
                                                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-black-400"></i>
                                                            Update
                                                        </a>
                                                        <!-- View -->
                                                        <a class="dropdown-item" href="Transfer-Asset.php?inventory_id=<?= $row['inventory_id']; ?>">
                                                            <i class="fa-solid fa-right-left  fa-sm fa-fw mr-2 text-black-400"></i>
                                                            Transfer
                                                        </a>
                                                        <a class="dropdown-item" href="Transfer-History.php?inventory_id=<?= $row['inventory_id']; ?>">
                                                            <i class="fas fa-history fa-sm fa-fw mr-2 text-black-400"></i>
                                                            Transfer History
                                                        </a>
                                                    </div>
                                            </td>

                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
    <script src="../../vendor/datatables/dataTables.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../js/demo/datatables-demo.js"></script>

</body>

</html>