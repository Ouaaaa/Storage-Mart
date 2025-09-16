<?php
    require_once "config.php";
    include "session-checker.php";

    $accountID = $_SESSION['account_id'];
    $sql = "SELECT employee_id FROM tbltickets WHERE ticket_id = ?";
    $username = ''; // Initialize the variable to avoid undefined variable errors


$userQuery = "SELECT username, usertype FROM tblaccounts WHERE account_id = ?";
if ($stmt = mysqli_prepare($link, $userQuery)) {
    mysqli_stmt_bind_param($stmt, "i", $accountID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $loggedUsername, $loggedUsertype);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}



    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $accountID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $username);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }

    $_SESSION['username'] = $username;

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

$fetchQuery = "
    SELECT 
        t.ticket_id,
        t.employee_id,
        CONCAT(e.lastname, ', ', e.firstname, ' ', e.middlename) AS fullname,
        t.branch,
        t.department,
        t.ticket_assign,
        CONCAT(assign.lastname, ', ', assign.firstname, ' ', assign.middlename) AS assign_name,
        t.technical_purpose,
        t.concern_details,
        t.action,
        t.result,
        t.status,
        t.priority,
        t.category,
        t.created_by,
        a.username AS created_by_name,
        a.usertype AS created_by_usertype,
        t.datecreated,
        t.dateupdated,
        t.attachments,
        t.remarks
    FROM tbltickets t
    JOIN tblemployee e 
        ON t.employee_id = e.employee_id
    LEFT JOIN tblemployee assign 
        ON t.ticket_assign = assign.employee_id
    LEFT JOIN tblaccounts a 
        ON t.created_by = a.account_id
";

$result = mysqli_query($link, $fetchQuery);


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
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
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
                    <span>Asset</span>
                </a>
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
                                   <?= htmlspecialchars($loggedUsername) . " (" . htmlspecialchars($loggedUsertype) . ")" ?>
                                </span>

                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../JobSeeker/User/Login.php" data-toggle="modal" data-target="#logoutModal">
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
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Ticket ID</th>
                                            <th>Employee ID</th>
                                            <th>Customer Name</th>
                                            <th>Branch</th>
											<th>Department</th>
                                            <th>Concern Details</th>
											<th>Priority</th>
                                            <th>Category</th>
                                            <th>Attachments</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Ticket ID</th>
                                            <th>Employee ID</th>
                                            <th>Customer Name</th>
                                            <th>Branch</th>
											<th>Department</th>
                                            <th>Concern Details</th>
											<th>Priority</th>
                                            <th>Category</th>
                                            <th>Attachments</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['ticket_id']) ?></td>
                                            <td><?= htmlspecialchars($row['employee_id']) ?></td>
                                            <td><?= htmlspecialchars($row['fullname']) ?></td>
                                            <td><?= htmlspecialchars($row['branch']) ?></td>
                                            <td><?= htmlspecialchars($row['department']) ?></td>
                                            <td><?= htmlspecialchars($row['concern_details']) ?></td>
                                            <td><?= htmlspecialchars($row['priority']) ?></td>
                                            <td><?= htmlspecialchars($row['category']) ?></td>
                                            <td><?= htmlspecialchars($row['attachments']) ?></td>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <button type="button" 
                                                        class="btn btn-success btn-sm viewBtn" 
                                                        style="width: 80px; margin-bottom: 20px;"
                                                        data-toggle="modal" 
                                                        data-target="#viewTicketModal"
                                                        data-ticketid="<?= $row['ticket_id'] ?>"
                                                        data-employeeid="<?= $row['employee_id'] ?>"
                                                        data-employee="<?= htmlspecialchars($row['fullname']) ?>"
                                                        data-branch="<?= htmlspecialchars($row['branch']) ?>"
                                                        data-department="<?= htmlspecialchars($row['department']) ?>"
                                                        data-assign="<?= htmlspecialchars($row['assign_name']) ?>"
                                                        data-tech="<?= htmlspecialchars($row['technical_purpose']) ?>"
                                                        data-concern="<?= htmlspecialchars($row['concern_details']) ?>"
                                                        data-action="<?= htmlspecialchars($row['action']) ?>"
                                                        data-result="<?= htmlspecialchars($row['result']) ?>"
                                                        data-status="<?= htmlspecialchars($row['status']) ?>"
                                                        data-priority="<?= htmlspecialchars($row['priority']) ?>"
                                                        data-category="<?= htmlspecialchars($row['category']) ?>"
                                                        data-createdby="<?= htmlspecialchars($row['created_by_usertype']) ?>"
                                                        data-datecreated="<?= htmlspecialchars($row['datecreated']) ?>"
                                                        data-dateupdated="<?= htmlspecialchars($row['dateupdated']) ?>"
                                                        data-attachments="<?= htmlspecialchars($row['attachments']) ?>"
                                                        data-remarks="<?= htmlspecialchars($row['remarks']) ?>">
                                                    View
                                                </button>
                                                    <input type="hidden" name="ticket_id" value="<?= $row['ticket_id'] ?>">
                                                    <button onclick="return confirm('Are you sure you want to delete this account?')" type="submit" name="action" value="Decline" class="btn btn-danger btn-sm" style="width: 80px;" >Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-3" style="margin-bottom:20px; margin-left:40px;">
                            <a href="Add-Ticket.php" class="btn btn-primary">Add Ticket</a>
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
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
<!-- View Ticket Modal -->
<div class="modal fade" id="viewTicketModal" tabindex="-1" aria-labelledby="viewTicketLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
        <h5 class="modal-title" id="viewTicketLabel">Ticket Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>


      <div class="container mt-4">
        <div class="row mb-5" style="margin-left:10px">
                <div class="col-md-6">
                    <label for="ticket_id" class="form-label">Ticket ID</label>
                    <input type="text" class="form-control" id="ticket_id" name="ticket_id" placeholder="Ticket ID" readonly>
                </div>
                <div class="col-md-6">
                    <label for="employee_id" class="form-label">Employee ID</label>
                    <input type="text" class="form-control" id="employee_id" name="employee_id" placeholder="Employee ID" readonly>
                </div>
        </div>

        <div class="row mb-5" style="margin-left:10px">
                <div class="col-md-6">
                    <label for="fullname" class="form-label">Employee name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full name" readonly>
                </div>
                <div class="col-md-6">
                    <label for="branch" class="form-label">Branch</label>
                    <input type="text" class="form-control" id="branch" name="branch" placeholder="Branch" readonly>
                </div>
        </div>

        <div class="row mb-5" style="margin-left:10px">
                <div class="col-md-6">
                    <label for="department" class="form-label">Department</label>
                    <input type="text" class="form-control" id="department" name="department" placeholder="Department" readonly>
                </div>
                <div class="col-md-6">
                    <label for="assign_name" class="form-label">Ticket Assign to</label>
                    <input type="text" class="form-control" id="assign_name" name="assign_name" placeholder="Ticket Assign" readonly>
                </div>
        </div>

        <div class="row mb-5" style="margin-left:10px">
                <div class="col-md-6">
                    <label for="technical_purpose" class="form-label">Technical Purpose</label>
                    <input type="text" class="form-control" id="technical_purpose" name="technical_purpose" placeholder="Technical Purpose" readonly>
                </div>
                <div class="col-md-6">
                    <label for = "concern-details" class ="form-label">Concern Details</label>
                    <textarea id ="concern_details" name="concern_details"class="form-control" rows="6" maxlength="1000" readonly>

                    </textarea>
                </div>
        </div>

        <div class="row mb-5" style="margin-left:10px">
                <div class="col-md-6">
                    <label for = "action" class ="form-label">Action Taken</label>
                    <textarea id ="action" name="action"class="form-control" rows="6" maxlength="1000" readonly>

                    </textarea>
                </div>
                <div class="col-md-6">
                    <label for = "result" class ="form-label">Result Details</label>
                    <textarea id ="result" name="result"class="form-control" rows="6" maxlength="1000" readonly>

                    </textarea>
                </div>
        </div>

        <div class="row mb-5" style="margin-left:10px">
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <input type="text" class="form-control" id="status" name="status" placeholder="Status" readonly>
                </div>
                <div class="col-md-6">
                    <label for="priority" class="form-label">Priority</label>
                    <input type="text" class="form-control" id="priority" name="priority" placeholder="priority" readonly>
                </div>
        </div>

        <div class="row mb-5" style="margin-left:10px">
                <div class="col-md-6">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" class="form-control" id="category" name="category" placeholder="Category" readonly>
                </div>
                <div class="col-md-6">
                    <label for="created_by" class="form-label">Performed By</label>
                    <input type="text" class="form-control" id="created_by" name="created_by" placeholder="Performed By" readonly>
                </div>
        </div>

        <div class="row mb-5" style="margin-left:10px">
                <div class="col-md-6">
                    <label for="datecreated" class="form-label">Date created</label>
                    <input type="text" class="form-control" id="datecreated" name="datecreated" placeholder="Date Created" readonly>
                </div>
                <div class="col-md-6">
                    <label for="dateupdated" class="form-label">Date Updated</label>
                    <input type="text" class="form-control" id="dateupdated" name="dateupdated" placeholder="Date Updated" readonly>
                </div>
        </div>

        <div class="row mb-5" style="margin-left:10px">
                <div class="col-md-6">
                    <label for = "remarks" class ="form-label">Remarks</label>
                    <textarea id ="remarks" name="remarks"class="form-control" rows="6" maxlength="1000" readonly>

                    </textarea>
                </div>
        </div>
      </div>
      
      <!-- Modal Footer -->
      <div class="modal-footer">
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
        </a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"s>Close</button>
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
    $(document).ready(function() {
        $('.viewBtn').on('click', function() {
            $('#ticket_id').val($(this).data('ticketid'));
            $('#employee_id').val($(this).data('employeeid'));
            $('#fullname').val($(this).data('employee'));
            $('#branch').val($(this).data('branch'));
            $('#department').val($(this).data('department'));
            $('#assign_name').val($(this).data('assign'));
            $('#technical_purpose').val($(this).data('tech'));
            $('#concern_details').val($(this).data('concern'));
            $('#action').val($(this).data('action'));
            $('#result').val($(this).data('result'));
            $('#status').val($(this).data('status'));
            $('#priority').val($(this).data('priority'));
            $('#category').val($(this).data('category'));
            $('#created_by').val($(this).data('createdby'));
            $('#datecreated').val($(this).data('datecreated'));
            $('#dateupdated').val($(this).data('dateupdated'));
            $('#remarks').val($(this).data('remarks'));

            $('#viewTicketModal').modal('show');
        });
    });
    </script>


                                       
</body>

</html>