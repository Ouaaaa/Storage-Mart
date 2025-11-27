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

        if (isset($account['status']) && strtolower($account['status']) === "inactive") {
                    $loginMessage = "<font color='red'><br>Your account is inactive. Please contact admin.</font>";
                } else {
                    // create session
                    session_start();
                    $_SESSION['account_id'] = $account['account_id'];
                    $_SESSION['username'] = $account['username']; 
                    $usertype = $account['usertype'];

                    // redirect based on user type
                    if (!empty($usertype)) {
                        if ($usertype == 'EMPLOYEE') {
                            header("Location: ../Admin Dashboard/Users/Employee/Dashboard/index.php");
                        } elseif ($usertype == 'HR') {
                            header("Location: ../../Employer/User/CareerSearch-Page.php");
                        } elseif ($usertype == 'ADMIN') {
                            header("Location: ../Admin Dashboard/index.php");
                        } elseif ($usertype == 'IT') {
                            header("Location: ../Admin Dashboard/Users/IT/Dashboard/index.php");
                        } else {
                            $loginMessage = "Error: Invalid user type.";
                        }
                    } else {
                        $loginMessage = "Error: No user type provided.";
                    }
                    exit();
                }
            } else {
                $loginMessage = "<font color='red'><br>Incorrect login details</font>";
            }
        }
    } else {
        echo "Error on the login statement";
    }
}
?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>StorageMart LMS Login</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap"
      rel="stylesheet"
    />
    <link rel="icon" type="image/png" href="Images/favicon.png" />
    <link rel="stylesheet" href="style.css" />
    
  </head>
  <body>
    <!-- Header -->
    <header class="storagemart-header">
      <img src="Images/storagemart-logo.png" alt="StorageMart Logo" />
    </header>

    <!-- Main Content -->
    <main class="index-main-content">
      <div class="login-box">
        <div class="logo-banner">
          <span class="logo-white">TMS</span
          ><span class="logo-orange">mart</span>
        </div>

        <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
        <?php if(isset($loginMessage)) : ?>
        		<div class = "message"><?php echo $loginMessage; ?></div>
    		<?php endif; ?>
          <label for="username">Username </label>
          <input
            type="text"
            id="idNumber"
            name = "txtUsername"
            placeholder="Enter your username"
            required
          />

          <label for="password">Password</label>
          <input
            type="password"
            id="txtPassword"
            name="txtPassword"
            placeholder="Enter your password"
            required
          />

          <button type="submit" name="btnLogin">LOG IN</button>

          <div class="forgot-password">
            <a href="#">Forgot password?</a>
          </div>
        </form>
      </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
      <p>
        &copy; 2025 StorageMart. All rights reserved. For Internal Use Only.
      </p>
    </footer>
  </body>
</html>
