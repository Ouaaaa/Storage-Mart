<?php
session_start();
if (!isset($_SESSION['account_id'])) {
	header("location: Public/login.php");
}
?>