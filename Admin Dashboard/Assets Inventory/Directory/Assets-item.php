<?php
    require_once "config.php";
    include "session-checker.php";
    //For Displaying the User
    $accountID = $_SESSION['account_id'];
    $sql = "SELECT employee_id FROM tbltickets WHERE ticket_id = ?";
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

    // for fetching the Group of an Item
$fetchModel = "
    SELECT 
        g.group_id,
        g.groupName,
        g.description,
        c.categoryName,
        COUNT(i.group_id) AS totalItems,
        SUM(CASE WHEN i.status = 'ASSIGNED' THEN 1 ELSE 0 END) AS assigned,
        SUM(CASE WHEN i.status = 'UNASSIGNED' THEN 1 ELSE 0 END) AS unassigned
    FROM tblassets_group g
    JOIN tblassets_category c 
        ON g.category_id = c.category_id
    LEFT JOIN tblassets_inventory i 
        ON g.group_id = i.group_id 
        AND i.status NOT IN ('DISPOSE','LOST') 
    GROUP BY g.group_id, g.groupName, g.description, c.categoryName
    ORDER BY g.group_id ASC;
";

$result = mysqli_query($link, $fetchModel);
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
                            <a href="Add-Asset.php" class="btn btn-primary" style="width:160px;">Add Asset</a>
                            <a href="Add-Category.php" class="btn btn-primary" style="width:160px;">Add Category</a>
                            <a href="Add-Group.php" class="btn btn-primary" style="width:160px;">Add Group</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="asset" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Model</th>
                                            <th>Description</th>
                                            <th>Category</th>
                                            <th>Quantity</th>
                                            <th>Assigned</th>
											<th>Unassigned</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Model</th>
                                            <th>Description</th>
                                            <th>Category</th>
                                            <th>Quantity</th>
                                            <th>Assigned</th>
											<th>Unassigned</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['groupName']);?> </td>
                                            <td><?= htmlspecialchars($row['description']);?> </td>
                                            <td><?= htmlspecialchars($row['categoryName']);?> </td>
                                            <td><?= htmlspecialchars($row['totalItems']); ?></td>
                                            <td><?= htmlspecialchars($row['assigned']);?> </td>
                                            <td><?= htmlspecialchars($row['unassigned']); ?></td>
                                            <td>
                                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="mr-2 d-none d-lg-inline text-gray-600 ">
                                                        Action</span>
                                                </a>
                                                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                                        <!-- Update -->
                                                        <a class="dropdown-item" href="Update-Group.php?group_id=<?= $row['group_id']; ?>">
                                                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-black-400"></i>
                                                            Update
                                                        </a>
                                                        <!-- View -->
                                                        <a class="dropdown-item" href="Inventory-Assets.php?group_id=<?= $row['group_id']; ?>">
                                                            <i class="fas fa-eye fa-sm fa-fw mr-2 text-black-400"></i>
                                                            View
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