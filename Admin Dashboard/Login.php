<?php
if (isset($_POST['btnLogin'])) {
	// require the config file
	require_once "config.php";
	// build the template f or the login sql statement
	$sql = "SELECT * FROM tblaccounts WHERE username = ? AND password = ? ";
	// check if the sql statement will run on the link by preparing the statement
	if ($stmt = mysqli_prepare($link, $sql)) {
		// bind the data from the login form to the sql statement
		mysqli_stmt_bind_param($stmt, "ss", $_POST['txtUsername'], $_POST['txtPassword']);
		// check if the statement will execute
		if (mysqli_stmt_execute($stmt)) {
			// get the result of executing the statement
			$result = mysqli_stmt_get_result($stmt);			
			// check if there is/are row/rows in the result
			if (mysqli_num_rows($result) > 0) {
				// fetch the result into an array
				$account = mysqli_fetch_array($result, MYSQLI_ASSOC);
				// create session();
				session_start();
                $_SESSION['account_id'] = $account['account_id'];
				$_SESSION['username'] = $_POST['txtUsername'];
				$usertype = $account['usertype'];
				// redirect to the accounts page
				if (isset($usertype) && !empty($usertype)) {
					if ($usertype == 'Jobseeker') {
						header("Location: CareerSearch-Page.php");
					} 
					else if ($usertype == 'Employer') {
						header("Location: ../../Employer/User/CareerSearch-Page.php");
					} 
					else if ($usertype == 'ADMIN') {
						header("Location:index.php");
					} 
					else {
						echo "Error: Invalid user type.";
					}
				} else {
					echo "Error: No user type provided.";
				}
				exit();
			}
			else {
				$loginMessage = "<font color = 'red'><br>Incorrect login details or account is disabled</font>";
			}
		}
	}
	else {
		echo "Error on the login statement";
	}
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/x-icon" href="../assets/img/logo.ico">
    <title>Login - Storage Mart</title>
</head>
<body>
    <div class="container">
            <?php if(isset($loginMessage)) : ?>
        		<div class = "message"><?php echo $loginMessage; ?></div>
    		<?php endif; ?>
        <div class="form-container">
            <h2>Sign In</h2>
            <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
            <input type = "text" name = "txtUsername" placeholder = "Username" required><br>    
            <input type = "password" name = "txtPassword" id = "txtPassword" placeholder = "Password" required>
                <center><button type="submit" name = "btnLogin">Login</button></center>
            </form>
            <div class="signin">
                <p>Don`t have an account? <a href="Register.php">Sign Up</a></p>
            </div>
        </div>
    </div>
</body>
</html>