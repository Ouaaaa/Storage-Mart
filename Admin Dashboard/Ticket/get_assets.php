<?php
require_once "config.php";
include("session-checker.php");

if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    $sql = "
        SELECT 
            i.inventory_id,  -- <--- Add this
            i.assetNumber,
            g.groupName,
            g.ic_code,
            i.itemInfo,
            i.serialNumber,
            i.year_purchased
        FROM tblassets_inventory i
        LEFT JOIN tblassets_group g ON i.group_id = g.group_id
        WHERE i.employee_id = ?
    ";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $employee_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $assets = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $assets[] = $row;
        }

        mysqli_stmt_close($stmt);
        echo json_encode($assets);
    } else {
        echo json_encode(['error' => 'Error executing query: ' . mysqli_error($link)]);
    }
    exit;
}
?>
