<?php
// edit_ticket_action.php
// Ready-to-paste endpoint to reassign ticket (Option B enforcement)

require_once "config.php";
include("session-checker.php");

session_start();

// Ensure user logged in
$accountID = $_SESSION['account_id'] ?? null;
if (!$accountID) {
    $_SESSION['error'] = "You must be logged in to perform this action.";
    header("Location: Tickets.php");
    exit();
}

// Map logged account to tblemployee.employee_id (performed_by)
$employee_id = null;
if ($stmt = mysqli_prepare($link, "SELECT employee_id FROM tblemployee WHERE account_id = ? LIMIT 1")) {
    mysqli_stmt_bind_param($stmt, "i", $accountID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $employee_id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Read POST data
$ticket_id = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : 0;
$new_assigned_to = isset($_POST['assigned_to']) && $_POST['assigned_to'] !== '' ? intval($_POST['assigned_to']) : null;
$remarks = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';

// Basic validation
if ($ticket_id <= 0) {
    $_SESSION['error'] = "Invalid ticket selected.";
    header("Location: Tickets.php");
    exit();
}
if (is_null($new_assigned_to) || $new_assigned_to <= 0) {
    $_SESSION['error'] = "Please select a valid assignee.";
    header("Location: Tickets.php");
    exit();
}

// 1) Fetch current ticket status and assigned_to
$currentStatus = null;
$currentAssigned = null;
$q = "SELECT status, assigned_to FROM tbltickets WHERE ticket_id = ? LIMIT 1";
if ($stmt = mysqli_prepare($link, $q)) {
    mysqli_stmt_bind_param($stmt, "i", $ticket_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $currentStatus, $currentAssigned);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['error'] = "Unable to validate ticket (SQL error).";
    header("Location: Tickets.php");
    exit();
}

if ($currentStatus === null) {
    $_SESSION['error'] = "Ticket not found.";
    header("Location: Tickets.php");
    exit();
}

// 2) Enforce Option B: block reassignment if ticket is already Resolved
if (strtolower($currentStatus) === 'resolved' || $currentStatus === 'Resolved') {
    $_SESSION['error'] = "This ticket is already resolved and cannot be reassigned.";
    header("Location: Tickets.php");
    exit();
}

// 3) If assigned_to unchanged -> nothing to do
if (intval($currentAssigned) === intval($new_assigned_to)) {
    $_SESSION['success'] = "No changes: ticket already assigned to selected user.";
    header("Location: Tickets.php");
    exit();
}

// 4) Ensure new assignee exists
$assigneeExists = false;
$assigneeName = '';
if ($stmt = mysqli_prepare($link, "SELECT firstname, lastname FROM tblemployee WHERE employee_id = ? LIMIT 1")) {
    mysqli_stmt_bind_param($stmt, "i", $new_assigned_to);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fn, $ln);
    if (mysqli_stmt_fetch($stmt)) {
        $assigneeExists = true;
        $assigneeName = trim($fn . ' ' . $ln);
    }
    mysqli_stmt_close($stmt);
}
if (!$assigneeExists) {
    $_SESSION['error'] = "Selected assignee does not exist.";
    header("Location: Tickets.php");
    exit();
}

// 5) Fetch old assignee name for history (if any)
$oldAssigneeName = '';
if (!is_null($currentAssigned) && intval($currentAssigned) > 0) {
    if ($stmt = mysqli_prepare($link, "SELECT firstname, lastname FROM tblemployee WHERE employee_id = ? LIMIT 1")) {
        mysqli_stmt_bind_param($stmt, "i", $currentAssigned);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $ofn, $oln);
        if (mysqli_stmt_fetch($stmt)) {
            $oldAssigneeName = trim($ofn . ' ' . $oln);
        }
        mysqli_stmt_close($stmt);
    }
}

// 6) Perform update on tbltickets (assigned_to, optionally remarks, last_updated)
$updateSql = "UPDATE tbltickets SET assigned_to = ?, last_updated = NOW()";
$bind_types = "i";
$bind_vals = [$new_assigned_to];

// If remarks provided (non-empty), update remarks
if ($remarks !== '') {
    $updateSql .= ", remarks = ?";
    $bind_types .= "s";
    $bind_vals[] = $remarks;
}

$updateSql .= " WHERE ticket_id = ?";
$bind_types .= "i";
$bind_vals[] = $ticket_id;

if ($stmt = mysqli_prepare($link, $updateSql)) {
    // dynamic binding
    mysqli_stmt_bind_param($stmt, $bind_types, ...$bind_vals);
    $ok = mysqli_stmt_execute($stmt);
    if (!$ok) {
        // update failed
        $_SESSION['error'] = "Failed to update ticket: " . mysqli_error($link);
        mysqli_stmt_close($stmt);
        header("Location: Tickets.php");
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['error'] = "SQL prepare failed (update ticket): " . mysqli_error($link);
    header("Location: Tickets.php");
    exit();
}

// 7) Insert into tblticket_history
$action_type = 'Reassigned';
$action_details = "Reassigned from " . ($oldAssigneeName ?: 'Unassigned') . " to " . $assigneeName;
$old_status = $currentStatus;
$new_status = $currentStatus; // unchanged
$performed_by = $employee_id ? intval($employee_id) : 0;
$performed_role = 'IT Staff';

$insertHist = "INSERT INTO tblticket_history (ticket_id, action_type, action_details, old_status, new_status, performed_by, performed_role, date_logged)
               VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

if ($stmt = mysqli_prepare($link, $insertHist)) {
    mysqli_stmt_bind_param($stmt, "issssis", $ticket_id, $action_type, $action_details, $old_status, $new_status, $performed_by, $performed_role);
    $execH = mysqli_stmt_execute($stmt);
    if (!$execH) {
        // not fatal, log and continue
        error_log("Failed to insert into tblticket_history: " . mysqli_error($link));
    }
    mysqli_stmt_close($stmt);
} else {
    error_log("Prepare failed (tblticket_history): " . mysqli_error($link));
}

// 8) Insert into tbllogs
$date = date('Y-m-d');
$time = date('h:i:sa');
$logAction = 'Reassigned Ticket';
$module = 'Ticket Management';
$performedby = $_SESSION['username'] ?? 'Unknown';

$insertLog = "INSERT INTO tbllogs (datelog, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
if ($stmt = mysqli_prepare($link, $insertLog)) {
    mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $logAction, $module, $ticket_id, $performedby);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} else {
    error_log("Prepare failed (tbllogs): " . mysqli_error($link));
}

// 9) Success
$_SESSION['success'] = "Ticket reassigned to {$assigneeName} successfully.";
header("Location: Tickets.php");
exit();
?>
