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

    // Handle delete request
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'Decline') {
        $accountToDelete = $_POST['account_id'];

        // Protect against SQL injection
        $deleteSQL = "DELETE FROM tblaccounts WHERE account_id = ?";
        if ($stmt = mysqli_prepare($link, $deleteSQL)) {
            mysqli_stmt_bind_param($stmt, "i", $accountToDelete);
            if (mysqli_stmt_execute($stmt)) {
                // Optional: redirect to refresh the page
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "<script>alert('Error deleting account.');</script>";
            }
            mysqli_stmt_close($stmt);
        }
    }
    //All tickets query
$sqlQuery = "
SELECT 
    t.ticket_id, 
    t.ticket_number, 
    CONCAT(e.lastname, ', ', e.firstname) AS employee_name,
    t.category, 
    t.priority, 
    t.status, 
    t.date_filed, 
    b.branchName,
    t.assigned_to AS assigned_to_id,
    CONCAT(a2.firstname, ' ', a2.lastname) AS assigned_to_name
FROM tbltickets t
JOIN tblemployee e ON t.employee_id = e.employee_id
LEFT JOIN tblbranch b ON e.branch_id = b.branch_id
LEFT JOIN tblemployee a2 ON t.assigned_to = a2.employee_id
ORDER BY t.date_filed DESC
";

$result = mysqli_query($link, $sqlQuery);
if (!$result) {
    die("SQL Error (tickets): " . mysqli_error($link));
}


$result = mysqli_query($link, $sqlQuery);
// --- Prevent assigned_to changes if ticket is already resolved --- //
$checkStatusSQL = "SELECT status FROM tbltickets WHERE ticket_id = ?";
if ($stmtCheck = mysqli_prepare($link, $checkStatusSQL)) {
    mysqli_stmt_bind_param($stmtCheck, "i", $ticket_id);
    mysqli_stmt_execute($stmtCheck);
    mysqli_stmt_bind_result($stmtCheck, $currentStatus);
    mysqli_stmt_fetch($stmtCheck);
    mysqli_stmt_close($stmtCheck);
}

if ($currentStatus === 'Resolved') {
    // Block the change
    $_SESSION['error'] = "This ticket is already resolved and cannot be reassigned.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


?>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Storage Mart Tickets - Tables</title>
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
                    <img src="../img/logo.png" alt="Logo" style="width:100px; height:auto;">
                </div>
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
                               <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                   <?= htmlspecialchars($loggedfirstname) . " (" . htmlspecialchars($loggedPosition) . ")" ?>
                                </span>

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
                    <h1 class="h3 mb-2 text-gray-800">Tables</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p>

                    <!-- Main conctent -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">List of Tickets</h6>
                        </div>
                        <div class="d-flex flex-column align-items-end" style="gap: 10px; margin-right: 40px; margin-top: 40px;">
                            <a href="Add-Ticket.php" class="btn btn-primary" style="width:160px;"><i class="fas fa-plus"></i> Add Ticket</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="logsTicket" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Employee</th>
                                            <th>Branch</th>
                                            <th>Category</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Date Filed</th>
                                            <th>Assigned To</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['ticket_number']) ?></td>
                                                <td><?= htmlspecialchars($row['employee_name']) ?></td>
                                                <td><?= htmlspecialchars($row['branchName']) ?></td>
                                                <td><?= htmlspecialchars($row['category']) ?></td>
                                                <td><?= htmlspecialchars($row['priority']) ?></td>
                                                <td><?= htmlspecialchars($row['status']) ?></td>
                                                <td><?= htmlspecialchars($row['date_filed']) ?></td>
                                                <td><?= htmlspecialchars($row['assigned_to_name'] ?: 'Unassigned') ?></td>
                                                <td>
                                                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown<?= $row['ticket_id'] ?>" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="mr-2 d-none d-lg-inline text-gray-600">Action</span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="userDropdown<?= $row['ticket_id'] ?>">
                                                        <a href="#" class="dropdown-item viewBtn" data-action="View"
                                                        data-ticketid="<?= $row['ticket_id'] ?>"
                                                        data-ticketnum="<?= htmlspecialchars($row['ticket_number']) ?>"
                                                        data-employee="<?= htmlspecialchars($row['employee_name']) ?>"
                                                        data-branch="<?= htmlspecialchars($row['branchName']) ?>"
                                                        data-priority="<?= htmlspecialchars($row['priority']) ?>"
                                                        data-status="<?= htmlspecialchars($row['status']) ?>">
                                                            <i class="fas fa-eye fa-sm fa-fw mr-2 text-black-400"></i> View
                                                        </a>

                                                        <!-- Update assignment button: includes current assigned id -->
                                                        <a href="#" class="dropdown-item openUpdateAssignBtn" data-ticket-id="<?= $row['ticket_id']; ?>"
                                                        data-assignedid="<?= htmlspecialchars($row['assigned_to_id']) ?>">
                                                            <i class="fas fa-edit fa-sm fa-fw mr-2 text-black-400"></i> Update Assignment
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
                    <a class="btn btn-primary" href="../../public/login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
<!-- View Ticket Modal -->
<div class="modal fade" id="viewTicketModal" tabindex="-1" aria-labelledby="viewTicketLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="viewTicketLabel">Ticket History</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Ticket Number</label>
                    <input type="text" id="ticket_number" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                    <label>Status</label>
                    <input type="text" id="status" class="form-control" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Employee</label>
                    <input type="text" id="employee" class="form-control" readonly>
                </div>
                <div class="col-md-6">
                    <label>Priority</label>
                    <input type="text" id="priority" class="form-control" readonly>
                </div>
            </div>

            <h6 class="mt-4">History Records</h6>
            <div class="table-responsive">
                <table class="table table-bordered" id="ticketHistoryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Action Taken</th>
                            <th>Technician</th>
                            <th>Old Status</th>
                            <th>New Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>
<!-- Update Assignment Modal -->
<div class="modal fade" id="updateAssignModal" tabindex="-1" role="dialog" aria-labelledby="updateAssignLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form method="POST" action="edit_ticket_action.php" id="updateAssignForm">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="updateAssignLabel">Update Ticket Assignment</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="ticket_id" id="update_ticket_id" value="">

          <div class="form-group">
            <label>Assign To (IT Staff)</label>
            <select class="form-control" name="assigned_to" id="assigned_to_select" required>
              <option value="">-- Select IT Staff --</option>
              <?php
              // Populate IT staff list
              $itQuery = mysqli_query($link, "SELECT employee_id, firstname, lastname FROM tblemployee WHERE department = 'IT' ORDER BY firstname, lastname");
              while ($it = mysqli_fetch_assoc($itQuery)) {
                  $eid = (int)$it['employee_id'];
                  $ename = htmlspecialchars($it['firstname'] . ' ' . $it['lastname']);
                  echo "<option value=\"{$eid}\">{$ename}</option>";
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label>Remarks (optional)</label>
            <textarea class="form-control" name="remarks" rows="3" placeholder="Add a short note (optional)"></textarea>
          </div>

          <div class="alert alert-info small">
            Note: Reassignment is <strong>not allowed</strong> if ticket status = <em>Resolved</em>.
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Assignment</button>
        </div>
      </div>
    </form>
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
    
<script>
  // --- Main list table (page) ---
  document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('#logsTicket')) {
      new DataTable('#logsTicket', {
        fixedHeader: { header: true },
        order: [],
        columnDefs: [
          {
            targets: [2,3, 4, 5], // Category, Priority, Date Filed
            columnControl: ["order", ["searchList","spacer","orderAsc","orderDesc","orderClear"]],
          },
          {
            targets: [0, 1, 6], // Employee, Branch, Action
            columnControl: ["order", ["search"]],
          },
        ],
        ordering: { indicators: false, handler: false },
      });
    }
  });

  // --- Modal history table (inside modal) ---
  // Replace your current viewBtn handler with this ready-to-paste block
let historyDT = null;

$(document).on('click', '.viewBtn', function () {
  const id = $(this).data('ticketid');
  console.log('View clicked — ticket id =', id);

  if (!id || parseInt(id, 10) <= 0) {
    alert('Invalid ticket id. Please refresh and try again.');
    return;
  }

  // Fill header fields (optional)
  $('#ticket_number').val($(this).data('ticketnum') || '');
  $('#employee').val($(this).data('employee') || '');
  $('#priority').val($(this).data('priority') || '');
  $('#status').val($(this).data('status') || '');

  // 1) Clear previous rows and safely destroy previous DataTable
  try {
    if (historyDT && typeof historyDT.destroy === 'function') {
      historyDT.destroy();
    }
  } catch (err) {
    console.warn('Error destroying previous DataTable instance:', err);
  }
  historyDT = null;
  $('#ticketHistoryTable tbody').empty();

  // 2) Show loading row and open modal (so user sees immediate feedback)
  $('#ticketHistoryTable tbody').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
  $('#viewTicketModal').modal('show');

  // 3) Fetch history
  $.ajax({
    url: 'fetch_ticket_history.php',
    method: 'GET',
    data: { ticket_id: id },
    dataType: 'json',
    cache: false,
    timeout: 10000,
    success: function (history) {
      console.log('fetch_ticket_history response:', history);

      // Ensure the modal is still open (optional)
      if (!$('#viewTicketModal').hasClass('show')) {
        // modal closed by user; do nothing
      }

      // Validate response
      if (!Array.isArray(history) || history.length === 0) {
        $('#ticketHistoryTable tbody').html('<tr><td colspan="5" class="text-center">No history found.</td></tr>');
      } else {
        const rowsHtml = history.map(row => {
          const action = row.action_details || '';
          const tech = row.assigned_to || '';
          const oldS = row.old_status || '';
          const newS = row.new_status || '';
          const date = row.date_logged || '';
          return `<tr>
                    <td>${action}</td>
                    <td>${tech}</td>
                    <td>${oldS}</td>
                    <td>${newS}</td>
                    <td>${date}</td>
                  </tr>`;
        }).join('');
        $('#ticketHistoryTable tbody').html(rowsHtml);
      }

      // 4) Initialize DataTable AFTER the rows are in the DOM
      // Use a tiny timeout to ensure layout settled (helps with some bootstrap modal timing)
      setTimeout(function () {
        try {
          historyDT = new DataTable('#ticketHistoryTable', {
            fixedHeader: { header: true },
            order: [],
            destroy: true // allow re-init safely (redundant with manual destroy above)
          });
        } catch (err) {
          console.warn('Failed to init DataTable:', err);
        }
      }, 50);
    },
    error: function (xhr, status, err) {
      console.error('fetch_ticket_history error:', status, err, xhr);
      $('#ticketHistoryTable tbody').html('<tr><td colspan="5" class="text-center text-danger">Failed to load history.</td></tr>');
    }
  });
});

</script>


<script>
    // Open Update Assignment modal and populate fields
$(document).on('click', '.openUpdateAssignBtn', function (e) {
  e.preventDefault();

  const ticketId = $(this).data('ticket-id');
  const assignedId = $(this).data('assignedid') || '';

  if (!ticketId) {
    alert('Invalid ticket id.');
    return;
  }

  // Set hidden ticket id
  $('#update_ticket_id').val(ticketId);

  // Preselect assigned_to (if any)
  if (assignedId) {
    $('#assigned_to_select').val(assignedId);
  } else {
    $('#assigned_to_select').val('');
  }

  // Show modal
  $('#updateAssignModal').modal('show');
});

</script>
                                       
</body>

</html>