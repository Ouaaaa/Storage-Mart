<?php
require_once "config.php";

$q = trim($_GET['q'] ?? '');
if ($q === '') {
    echo json_encode(['success' => false, 'message' => 'Empty query']);
    exit;
}

if($q === '') {
    echo json_encode(['success' => false, 'message' => 'Empty search query']);
    exit;
}

$sql = "
    SELECT e.employee_id,
           CONCAT(e.lastname, ', ', e.firstname, ' ', IFNULL(e.middlename, '')) AS full_name,
           b.branchName,
           e.department
    FROM tblemployee e
    LEFT JOIN tblbranch b ON e.branch_id = b.branch_id
    WHERE e.firstname LIKE ? OR e.lastname LIKE ? OR e.employee_id LIKE ?
    LIMIT 1
";

$stmt = mysqli_prepare($link, $sql);
$likeQuery = "%$q%";
mysqli_stmt_bind_param($stmt, "sss", $likeQuery, $likeQuery, $likeQuery);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if($row = mysqli_fetch_assoc($result)) {
    echo json_encode([
        'success' => true,
        'employee_id' => $row['employee_id'],
        'full_name' => $row['full_name'],
        'branchName' => $row['branchName'],
        'department' => $row['department']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'No employee found']);
}
?>
