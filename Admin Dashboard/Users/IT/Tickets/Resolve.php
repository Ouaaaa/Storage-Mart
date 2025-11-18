<?php
require_once "config.php";
include("session-checker.php");

$accountID = $_SESSION['account_id'];

// ==========================
// Fetch user info for display
// ==========================
$fetchUser = "
    SELECT e.firstname, e.position 
    FROM tblaccounts a
    JOIN tblemployee e ON a.account_id = e.account_id
    WHERE a.account_id = ?
";
$loggedfirstname = '';
$loggedPosition = '';
if ($stmt = mysqli_prepare($link, $fetchUser)) {
    mysqli_stmt_bind_param($stmt, 'i', $accountID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $loggedfirstname, $loggedPosition);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}
$_SESSION['loggedfirstname'] = $loggedfirstname;
$_SESSION['loggedPosition'] = $loggedPosition;
$fetchTechnical = "
  SELECT 
    t.ticket_id,
    t.ticket_number,
    CONCAT(e.lastname, ', ', e.firstname, ' ', LEFT(e.middlename, 1), '.') AS employee_name,
    CONCAT(g.groupName, ' - ' , i.itemInfo) AS asset,
    b.branchName,
    tt.technical_purpose,
    tt.action_taken,
    tt.result,
    tt.remarks,
    tt.date_performed
  FROM tbltickets t 
  JOIN tblticket_technical tt ON t.ticket_id = tt.ticket_id
  JOIN tblemployee e ON t.employee_id = e.employee_id
  JOIN tblassets_inventory i ON  t.inventory_id = i.inventory_id
  LEFT JOIN tblassets_group g ON i.group_id = g.group_id  
  JOIN tblbranch b ON e.branch_id = b.branch_id
  WHERE t.status = 'Resolved'
";
$result = mysqli_query($link, $fetchTechnical);
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

    <title>Storage Mart | IT Resolve Tickets - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
        <link rel="icon" href="../../../img/favicon.ico" type="image/x-icon">

    <!-- Custom styles for this page -->
    <link href="../../../vendor/datatables/dataTables.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../Dashboard/index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                </div>
                <img src="../../../img/logo.png" alt="Logo" style="width:100px; height:auto;">
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
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Ticket</span>	
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Ticket:</h6>
                        <a class="collapse-item" href="IT-Tickets.php">In Progress</a>
                        <a class="collapse-item" href="#">Resolve</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../Asset/Assets.php">
                    <i class="fas fa-archive"></i>
                    <span>My Assets</span>
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
                                    <?= htmlspecialchars($loggedfirstname) . " (" . htmlspecialchars($loggedPosition) . ")" ?></span>
                                <img class="img-profile rounded-circle"
                                    src="../../../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../../../../public/login.php" data-toggle="modal" data-target="#logoutModal">
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

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">List of Resolve Tickets</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="ticketTables" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Employee Name</th>
                                            <th>Asset</th>
                                            <th>Branch</th>
											                      <th>Technical Purpose</th>
                                            <th>Action Taken</th>
                                            <th>Result</th>
											                      <th>Remarks</th>
                                            <th>Date Performed</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Employee Name</th>
                                            <th>Asset</th>
                                            <th>Branch</th>
											                      <th>Technical Purpose</th>
                                            <th>Action Taken</th>
                                            <th>Result</th>
											                      <th>Remarks</th>
                                            <th>Date Performed</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <tr>  
                                                <td><?= htmlspecialchars($row['ticket_number']) ?></td>
                                                <td><?= htmlspecialchars($row['employee_name']) ?></td>
                                                <td><?= htmlspecialchars($row['asset']) ?></td>
                                                <td><?= htmlspecialchars($row['branchName']) ?></td>
                                                <td><?= htmlspecialchars($row['technical_purpose']) ?></td>
                                                <td><?= htmlspecialchars($row['action_taken']) ?></td>
                                                <td><?= htmlspecialchars($row['result']) ?></td>
                                                <td><?= htmlspecialchars($row['remarks']) ?></td>
                                                <td><?= htmlspecialchars($row['date_performed']) ?></td>
                                                <td>
                                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="mr-2 d-none d-lg-inline text-gray-600 ">
                                                        Action</span>
                                                    </a>
                                                    <!-- Dropdown - User Information -->
                                                    <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="userDropdown">
                                                    <a href="../../../generatePDF/generate_technical.php?ticket_id=<?= $row['ticket_id'] ?>" class="dropdown-item">
                                                        <i class="fas fa-file-word a-fw mr-2 text-black-400"></i> Generate Technical Report
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
                    <a class="btn btn-primary" href="../../../../public/login.php">Logout</a>
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
        <script src="../../../vendor/datatables/dataTables.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="../../../js/demo/datatables-demo.js"></script>
</body>

</html>