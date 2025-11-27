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
// Get the logged-in IT employee_id
// ========================
$employee_id = null;
$getEmp = mysqli_prepare($link, "SELECT employee_id FROM tblemployee WHERE account_id = ?");
mysqli_stmt_bind_param($getEmp, "i", $accountID);
mysqli_stmt_execute($getEmp);
mysqli_stmt_bind_result($getEmp, $employee_id);
mysqli_stmt_fetch($getEmp);
mysqli_stmt_close($getEmp);

// ========================
// Handle Approve / Decline (Resolve / On Hold / Unresolved)
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $ticket_id = intval($_POST['ticket_id'] ?? 0);
    $action = trim($_POST['action'] ?? '');
    $technical_purpose = trim($_POST['technical_purpose'] ?? '');
    $action_taken = trim($_POST['action_taken'] ?? '');
    $result = trim($_POST['result'] ?? '');
    $remarks = trim($_POST['remarks'] ?? '');
    $status = trim($_POST['status'] ?? '');

    // --- SERVER-SIDE OWNERSHIP CHECK ---
    $assigned_to_db = null;
    $qry = "SELECT assigned_to FROM tbltickets WHERE ticket_id = ?";
    if ($stmtCheck = mysqli_prepare($link, $qry)) {
        mysqli_stmt_bind_param($stmtCheck, 'i', $ticket_id);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_bind_result($stmtCheck, $assigned_to_db);
        mysqli_stmt_fetch($stmtCheck);
        mysqli_stmt_close($stmtCheck);
    } else {
        die('SQL Error: ' . mysqli_error($link));
    }

    if ($assigned_to_db === null || intval($assigned_to_db) !== intval($employee_id)) {
        // Not allowed — set an error message and redirect back
        $_SESSION['error'] = 'You are not allowed to modify this ticket.';
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Determine new status from action
    if ($action === 'Resolve') {
        $newStatus = 'Resolved';
    } elseif ($action === 'On Hold') {
        $newStatus = 'On Hold';
    } elseif ($action === 'Unresolved') {
        $newStatus = 'Unresolved';
    } else {
        $newStatus = 'In Progress';
    }

    $action_type = $newStatus;
    $action_details = "Ticket $newStatus by IT Staff (Account ID: $accountID)";

    // =============================
    // 1️⃣ Update tbltickets
    // =============================
    $update = "
        UPDATE tbltickets
        SET 
            status = ?, 
            remarks = ?, 
            last_updated = NOW()
        WHERE ticket_id = ?
    ";
    if ($stmt = mysqli_prepare($link, $update)) {
        mysqli_stmt_bind_param($stmt, 'ssi', $newStatus, $remarks, $ticket_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        die('❌ SQL Error (tbltickets): ' . mysqli_error($link));
    }

    // =============================
    // 2️⃣ Insert into tblticket_technical
    // =============================
    $insertTech = "
        INSERT INTO tblticket_technical
        (ticket_id, performed_by, technical_purpose, action_taken, result, remarks, date_performed)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ";
    if ($stmtTech = mysqli_prepare($link, $insertTech)) {
        mysqli_stmt_bind_param($stmtTech, 'iissss', $ticket_id, $employee_id, $technical_purpose, $action_taken, $result, $remarks);
        mysqli_stmt_execute($stmtTech);
        mysqli_stmt_close($stmtTech);
    } else {
        die('❌ SQL Error (tblticket_technical): ' . mysqli_error($link));
    }

    // =============================
    // 3️⃣ Log into tblticket_history
    // =============================
    $insertHistory = "
        INSERT INTO tblticket_history
        (ticket_id, action_type, action_details, old_status, new_status, performed_by, performed_role)
        VALUES (?, ?, ?, ?, ?, ?, 'IT Staff')
    ";
    $oldStatus = 'In Progress';
    if ($stmtHist = mysqli_prepare($link, $insertHistory)) {
        mysqli_stmt_bind_param($stmtHist, 'issssi', $ticket_id, $action_type, $action_details, $oldStatus, $newStatus, $accountID);
        mysqli_stmt_execute($stmtHist);
        mysqli_stmt_close($stmtHist);
    } else {
        die('❌ SQL Error (tblticket_history): ' . mysqli_error($link));
    }

    // =============================
    // 4️⃣ Log into tbllogs
    // =============================
    $date = date('Y-m-d');
    $time = date('h:i:sa');
    $logAction = ucfirst($newStatus) . ' Ticket';
    $module = 'Ticket Management';
    $performedby = $_SESSION['username'] ?? 'Unknown';

    $insertLog = "
        INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby)
        VALUES (?, ?, ?, ?, ?, ?)
    ";
    if ($stmtLog = mysqli_prepare($link, $insertLog)) {
        mysqli_stmt_bind_param($stmtLog, 'ssssss', $date, $time, $logAction, $module, $ticket_id, $performedby);
        mysqli_stmt_execute($stmtLog);
        mysqli_stmt_close($stmtLog);
    } else {
        die('❌ SQL Error (tbllogs): ' . mysqli_error($link));
    }

    // ✅ Redirect back
    $_SESSION['success'] = 'Ticket updated successfully.';
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// ========================
// Fetch Assigned tickets (include assigned_to id + name)
// ========================
$fetchQuery = "
    SELECT 
        t.ticket_id,
        t.ticket_number,
        t.category,
        t.priority,
        t.concern_details,
        t.status,
        t.date_filed,
        t.remarks,
        CONCAT(e.firstname, ' ', e.lastname) AS employee_name,
        b.branchName,
        CONCAT(i.assetNumber, ' - ', g.groupName) AS asset_info,
        t.assigned_to,
        CONCAT(a2.firstname, ' ', a2.lastname) AS assigned_to_name
    FROM tbltickets t
    JOIN tblemployee e ON t.employee_id = e.employee_id
    JOIN tblbranch b ON e.branch_id = b.branch_id
    JOIN tblassets_inventory i ON t.inventory_id = i.inventory_id
    LEFT JOIN tblassets_group g ON i.group_id = g.group_id
    LEFT JOIN tblemployee a2 ON t.assigned_to = a2.employee_id
    WHERE t.status = 'In Progress'
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

    <title>Storage Mart | IT  Tickets - Tables</title>

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

                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">Tables</h1>
                    <p class="mb-4">DataTables used below. For more: <a target="_blank" href="https://datatables.net">DataTables docs</a>.</p>

                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">List of Pending Tickets</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <!-- expose current employee id to JS via data attribute -->
                                <table class="table table-bordered" id="IT-TicketDatables" data-employee-id="<?= intval($employee_id) ?>" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Category</th>
                                            <th>Priority</th>
                                            <th>Concern Details</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                            <th>Employee Name</th>
                                            <th>Assigned To</th>
                                            <th>Branch</th>
                                            <th>Date Filed</th>
                                            <th>Asset Info</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) {
                                            $isAssignedToMe = ($row['assigned_to'] !== null && intval($row['assigned_to']) === intval($employee_id));
                                        ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['ticket_number']) ?></td>
                                                <td><?= htmlspecialchars($row['category']) ?></td>
                                                <td><?= htmlspecialchars($row['priority']) ?></td>
                                                <td><?= htmlspecialchars($row['concern_details']) ?></td>
                                                <td><?= htmlspecialchars($row['status']) ?></td>
                                                <td><?= htmlspecialchars($row['remarks']) ?></td>
                                                <td><?= htmlspecialchars($row['employee_name']) ?></td>
                                                <td>
                                                    <?= htmlspecialchars($row['assigned_to_name'] ?: 'Unassigned') ?>
                                                    <?php if ($isAssignedToMe): ?>
                                                        <span class="badge badge-success ml-1">Mine</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($row['branchName']) ?></td>
                                                <td><?= htmlspecialchars($row['date_filed']) ?></td>
                                                <td><?= htmlspecialchars($row['asset_info']) ?></td>
                                                <td>
                                                    <?php if ($isAssignedToMe): ?>
                                                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown<?= $row['ticket_id'] ?>" role="button"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="mr-2 d-none d-lg-inline text-gray-600">Action</span>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="userDropdown<?= $row['ticket_id'] ?>">
                                                            <a href="#" class="dropdown-item openModalBtn" data-action="Resolve" data-ticket-id="<?= $row['ticket_id']; ?>" data-assigned="<?= $row['assigned_to']; ?>">
                                                                <i class="fas fa-check fa-sm fa-fw mr-2 text-black-400"></i> Resolved
                                                            </a>
                                                            <a href="#" class="dropdown-item openModalBtn" data-action="On Hold" data-ticket-id="<?= $row['ticket_id']; ?>" data-assigned="<?= $row['assigned_to']; ?>">
                                                                <i class="fas fa-pause fa-sm fa-fw mr-2 text-black-400"></i> On Hold
                                                            </a>
                                                            <a href="#" class="dropdown-item openModalBtn" data-action="Unresolved" data-ticket-id="<?= $row['ticket_id']; ?>" data-assigned="<?= $row['assigned_to']; ?>">
                                                                <i class="fas fa-times fa-sm fa-fw mr-2 text-black-400"></i> Unresolved
                                                            </a>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">Not assigned to you</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div> <!-- /.container-fluid -->
            </div> <!-- End of Main Content -->
        </div> <!-- End of Content Wrapper -->
    </div> <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document"><div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="../../../../public/login.php">Logout</a>
            </div>
        </div></div>
    </div>

    <!-- ✅ Ticket Modal (unchanged fields) -->
    <div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" style="margin-top: 100px;" role="document">
        <form method="POST" action="">
          <div class="modal-content shadow-lg border-0" style="margin:auto; max-width:850px;">
            <div class="modal-header bg-primary text-white text-center">
              <h5 class="modal-title w-100" id="ticketModalLabel">Update Ticket</h5>
              <button type="button" class="close text-white position-absolute" style="right:15px;" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body px-4 py-3">
              <input type="hidden" name="ticket_id" id="ticket_id">
              <input type="hidden" name="action" id="ticket_action">

              <h5 class="text-primary text-center mb-3">Technical Details</h5>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label>Technical Purpose</label>
                  <select class="form-control" name="technical_purpose" required>
                    <option value="">-- Select Technical Purpose --</option>
                    <option>Desktop / Laptop Issue</option>
                    <option>Network Issue</option>
                    <option>Software Installation / Activation</option>
                    <option>Application Issue</option>
                    <option>Phone Issue</option>
                    <option>Others</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Status</label>
                  <select class="form-control" name="status" required>
                    <option value="Resolved">Resolved</option>
                    <option value="On Hold">On Hold</option>
                    <option value="Unresolved">Unresolved</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label>Action Taken</label>
                  <textarea class="form-control" name="action_taken" rows="3" required></textarea>
                </div>
                <div class="col-md-6">
                  <label>After Service Note</label>
                  <textarea class="form-control" name="result" rows="3" required></textarea>
                </div>
              </div>

              <div class="form-group">
                <label>Remarks</label>
                <textarea class="form-control" name="remarks" rows="3"></textarea>
              </div>
            </div>

            <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success px-4" id="modalSubmitBtn">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Scripts -->
    <script src="../../../vendor/jquery/jquery.min.js"></script>
    <script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../../js/sb-admin-2.min.js"></script>
    <script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../../../vendor/datatables/dataTables.min.js"></script>

<script>
$(document).ready(function() {
  // Initialize datatable
  $('#IT-TicketDatables').DataTable();

  // Current employee id from data attribute
  const currentEmployeeId = parseInt($('#IT-TicketDatables').data('employee-id'), 10);

  // Handle ticket action modal with guard
  $('.openModalBtn').click(function(e) {
    e.preventDefault();

    const ticketId = $(this).data('ticket-id');
    const action = $(this).data('action'); // Resolve / On Hold / Unresolved
    const assignedTo = $(this).data('assigned'); // may exist

    // Client-side guard: if assigned exists and doesn't match, block
    if (typeof assignedTo !== 'undefined' && parseInt(assignedTo, 10) !== currentEmployeeId) {
      alert('You cannot modify tickets not assigned to you.');
      return;
    }

    const btnColor = action === 'Resolve' ? 'btn-success' :
                     action === 'On Hold' ? 'btn-warning' :
                     'btn-danger';

    $('#ticket_id').val(ticketId);
    $('#ticket_action').val(action);
    $('#ticketModalLabel').text(action + ' Ticket');
    $('#modalSubmitBtn')
      .removeClass('btn-success btn-warning btn-danger')
      .addClass(btnColor)
      .text(action);

    $('#ticketModal').modal('show');
  });
});
</script>
</body>
</html>
