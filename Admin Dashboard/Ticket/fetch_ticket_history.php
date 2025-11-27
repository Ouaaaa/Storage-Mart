<?php
require_once "config.php";

if (isset($_GET['ticket_id'])) {
    $ticketID = intval($_GET['ticket_id']);

    $sql = "
        SELECT 
            th.action_details,
            CONCAT(e2.lastname, ', ', e2.firstname) AS assigned_to, 
            th.old_status,
            th.new_status,
            th.date_logged
        FROM tblticket_history th
        LEFT JOIN tbltickets t ON th.ticket_id = t.ticket_id
        LEFT JOIN tblemployee e2 ON t.assigned_to = e2.employee_id
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
