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

// ========================
// Handle Approve / Decline
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $action = $_POST['action'];

    // Fetch ticket number for log
    $getTicketNo = mysqli_prepare($link, "SELECT ticket_number FROM tbltickets WHERE ticket_id = ?");
    mysqli_stmt_bind_param($getTicketNo, "i", $ticket_id);
    mysqli_stmt_execute($getTicketNo);
    mysqli_stmt_bind_result($getTicketNo, $ticket_number);
    mysqli_stmt_fetch($getTicketNo);
    mysqli_stmt_close($getTicketNo);

    if ($action === 'ApproveAssign') {
        $oldStatus = 'Pending';
        $newStatus = 'In Progress';
        $assigned_to = $_POST['assigned_to'];
        $remarks = trim($_POST['remarks'] ?? '');
        $action_type = 'Approved';
        $action_details = "Ticket approved and assigned to IT staff ID: $assigned_to";

        // ✅ Update tbltickets
        $query = "
            UPDATE tbltickets 
            SET status = ?, assigned_to = ?, approved_by = ?, remarks = ?, date_approved = NOW(), last_updated = NOW()
            WHERE ticket_id = ?
        ";
        if ($stmt = mysqli_prepare($link, $query)) {
            mysqli_stmt_bind_param($stmt, 'siisi', $newStatus, $assigned_to, $accountID, $remarks, $ticket_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // ✅ Insert into tblticket_history
        $logHistory = "
            INSERT INTO tblticket_history (ticket_id, action_type, action_details, old_status, new_status, performed_by, performed_role)
            VALUES (?, ?, ?, ?, ?, ?, 'Admin')
        ";
        if ($stmtLog = mysqli_prepare($link, $logHistory)) {
            mysqli_stmt_bind_param($stmtLog, "issssi", $ticket_id, $action_type, $action_details, $oldStatus, $newStatus, $accountID);
            mysqli_stmt_execute($stmtLog);
            mysqli_stmt_close($stmtLog);
        }

        // ✅ Log into tbllogs
        $date = date("Y-m-d");
        $time = date("h:i:sa");
        $logAction = "Approve & Assign";
        $module = "Ticket Management";
        $performedby = $_SESSION['username'];
        $sqlLog = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby)
                VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmtLog2 = mysqli_prepare($link, $sqlLog)) {
            mysqli_stmt_bind_param($stmtLog2, "ssssss", $date, $time, $logAction, $module, $ticket_id, $performedby);
            mysqli_stmt_execute($stmtLog2);
            mysqli_stmt_close($stmtLog2);
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    }elseif ($action === 'Decline') {
    $oldStatus = 'Pending';
    $newStatus = 'Declined';
    $decline_reason = trim($_POST['decline_reason'] ?? '');
    $remarks = trim($_POST['remarks'] ?? '');
    $ticket_id = intval($_POST['ticket_id']);

    if ($ticket_id <= 0) {
        die("Invalid Ticket ID received.");
    }

    // ✅ Update tbltickets
    $query = "
        UPDATE tbltickets 
        SET status = 'Closed', 
            decline_reason = ?, 
            remarks = ?, 
            declined_by = ?, 
            date_declined = NOW(),
            last_updated = NOW()
        WHERE ticket_id = ?
    ";
    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, 'ssii', $decline_reason, $remarks, $accountID, $ticket_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            // ✅ Only log if ticket updated successfully
            $logHistory = "INSERT INTO tblticket_history 
                (ticket_id, action_type, action_details, old_status, new_status, performed_by, performed_role)
                VALUES (?, 'Closed', 'Ticket Declined by Admin', 'Pending', 'Closed', ?, 'Admin')";
            if ($stmtLog = mysqli_prepare($link, $logHistory)) {
                mysqli_stmt_bind_param($stmtLog, "ii", $ticket_id, $accountID);
                mysqli_stmt_execute($stmtLog);
                mysqli_stmt_close($stmtLog);
            }

            // ✅ Log to tbllogs
            $date = date("Y-m-d");
            $time = date("h:i:sa");
            $logAction = "Decline";
            $module = "Ticket Management";
            $performedby = $_SESSION['username'];
            $sqlLog = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmtLog2 = mysqli_prepare($link, $sqlLog)) {
                mysqli_stmt_bind_param($stmtLog2, "ssssss", $date, $time, $logAction, $module, $ticket_id, $performedby);
                mysqli_stmt_execute($stmtLog2);
                mysqli_stmt_close($stmtLog2);
            }
        } else {
            die("❌ Ticket update failed. No rows affected. Check ticket_id or DB data.");
        }

        mysqli_stmt_close($stmt);
    } else {
        die("❌ SQL error: " . mysqli_error($link));
    }

    $notificationMessage = "Ticket successfully Declined! Ticket No: $ticket_number";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
    }
}

// ========================
// Fetch pending tickets
// ========================
$fetchQuery = "
    SELECT 
        t.ticket_id,
        t.ticket_number,
        CONCAT(e.lastname, ', ', e.firstname, ' ', e.middlename) AS fullname,
        b.branchName,
        e.department,
        CONCAT(i.assetNumber, ' - ', g.groupName) AS asset_info,
        t.category,
        t.priority,
        t.concern_details,
        t.date_filed,
        t.status
    FROM tbltickets t
    JOIN tblemployee e ON t.employee_id = e.employee_id
    JOIN tblbranch b ON e.branch_id = b.branch_id
    JOIN tblassets_inventory i ON t.inventory_id = i.inventory_id
    LEFT JOIN tblassets_group g ON i.group_id = g.group_id
    WHERE t.status = 'Pending'
    ORDER BY t.date_filed ASC
";
$result = mysqli_query($link, $fetchQuery);
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

    <title>Storage Mart | Admin Pending Tickets - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <img src="img/logo.png" alt="Logo" style="width:100px; height:auto;">
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
                        <a class="collapse-item" href="Account/Accounts.php">Accounts</a>
                        <a class="collapse-item" href="Account/Employee.php">Employee</a>
                    </div>
                </div>
            </li>
			
			<li class="nav-item">
                <a class="nav-link" href="Ticket/Tickets.php">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Ticket</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Assets Inventory/Directory/Assets.php">
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
            <li class="nav-item active">
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
                                    <?= htmlspecialchars($loggedfirstname) . " (" . htmlspecialchars($loggedPosition) . ")" ?></span>
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
                    <h1 class="h3 mb-2 text-gray-800">Tables</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">List of Pending Tickets</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="pendings" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Ticket ID</th>
                                            <th>Employee Name</th>
                                            <th>Department</th>
                                            <th>Branch</th>
											<th>Asset Info</th>
											<th>Category</th>
                                            <th>Priority</th>
                                            <th>Concern Details</th>
                                            <th>Date Filed</th>
                                            <th>Status</th>
											<th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Ticket ID</th>
                                            <th>Employee Name</th>
                                            <th>Department</th>
                                            <th>Branch</th>
											<th>Asset Info</th>
											<th>Category</th>
                                            <th>Priority</th>
                                            <th>Concern Details</th>
                                            <th>Date Filed</th>
                                            <th>Status</th>
											<th>Actions</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['ticket_number']); ?></td>
                                            <td><?= htmlspecialchars($row['fullname']); ?></td>
                                            <td><?= htmlspecialchars($row['department']); ?></td>
                                            <td><?= htmlspecialchars($row['branchName']); ?></td>
                                            <td><?= htmlspecialchars($row['asset_info']); ?></td>
                                            <td><?= htmlspecialchars($row['category']); ?></td>
                                            <td><?= htmlspecialchars($row['priority']); ?></td>
                                            <td><?= htmlspecialchars($row['concern_details']); ?></td>
                                            <td><?= htmlspecialchars($row['date_filed']); ?></td>
                                            <td><?= htmlspecialchars($row['status']); ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="actionDropdown<?= $row['ticket_id'] ?>" data-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="actionDropdown<?= $row['ticket_id'] ?>">
                                                        <!-- Approve -->
                                                        <!-- Approve (opens Assign modal) -->
                                                        <button type="button" 
                                                                class="dropdown-item text-success"
                                                                data-toggle="modal" 
                                                                data-target="#approveAssignModal" 
                                                                data-ticket-id="<?= $row['ticket_id']; ?>">
                                                            <i class="fas fa-check fa-sm fa-fw mr-2"></i>Approve & Assign
                                                        </button>


                                                        <!-- Decline (modal trigger) -->
                                                        <button type="button" class="dropdown-item text-danger" 
                                                                data-toggle="modal" 
                                                                data-target="#declineModal" 
                                                                data-ticket-id="<?= $row['ticket_id']; ?>">
                                                            <i class="fas fa-times fa-sm fa-fw mr-2"></i>Decline
                                                        </button>
                                                    </div>
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
                    <a class="btn btn-primary" href="../public/login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Decline Modal -->
    <div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="declineModalLabel">Decline Ticket</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="ticket_id" id="decline_ticket_id">
            <input type="hidden" name="action" value="Decline">
            <div class="form-group">
                <label for="decline_reason">Reason for Decline:</label>
                <textarea class="form-control" id="decline_reason" name="decline_reason" rows="4" required></textarea>
                <label for="remarks">Remarks:</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="4"></textarea>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Confirm Decline</button>
            </div>
        </div>
        </form>
    </div>
    </div>


        <!-- Approve & Assign Modal -->
    <div class="modal fade" id="approveAssignModal" tabindex="-1" role="dialog" aria-labelledby="approveAssignModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="approveAssignModalLabel">Approve Ticket & Assign IT Staff</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">

            <input type="hidden" name="ticket_id" id="approve_ticket_id">
            <input type="hidden" name="action" value="ApproveAssign">

            <div class="form-group">
                <label for="assigned_to">Select IT Staff:</label>
                <select class="form-control" id="assigned_to" name="assigned_to" required>
                <option value="">-- Select IT Staff --</option>
                <?php
                $it_query = "SELECT employee_id, firstname, lastname FROM tblemployee WHERE department = 'IT'";
                $it_result = mysqli_query($link, $it_query);
                while ($it = mysqli_fetch_assoc($it_result)) {
                    echo "<option value='{$it['employee_id']}'>{$it['firstname']} {$it['lastname']}</option>";
                }
                ?>
                </select>
            </div>

            <div class="form-group">
                <label for="remarks">Remarks (optional):</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
            </div>

            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Approve & Assign</button>
            </div>
        </div>
        </form>
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
    <script src="vendor/datatables/dataTables.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    <script>
        $('#approveAssignModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var ticketId = button.data('ticket-id');
  $(this).find('#approve_ticket_id').val(ticketId);
});

    </script>
<script>
$(document).ready(function() {
  $('#dataTable').DataTable();

  $('#declineModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var ticketId = button.data('ticket-id');
    $(this).find('#decline_ticket_id').val(ticketId);
  });
});
</script>
</body>

</html>