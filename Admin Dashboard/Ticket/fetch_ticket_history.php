<?php
require_once "config.php";

if (isset($_GET['ticket_id'])) {
    $ticketID = intval($_GET['ticket_id']);

    $sql = "
        SELECT 
            th.action_details,
            CONCAT(e.lastname, ', ', e.firstname) AS performed_by,
            th.old_status,
            th.new_status,
            th.date_logged
        FROM tblticket_history th
        LEFT JOIN tblemployee e ON th.performed_by = e.employee_id
        WHERE th.ticket_id = ?
        ORDER BY th.date_logged DESC
    ";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $ticketID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        echo json_encode($rows);
    }
}
?>
