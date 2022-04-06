<?php
// Initialize the session
 // Initialize the session
session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page
 
// Include config file
include "GlobalClass.php";
include "UserConfig.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(test_input($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = test_input($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(test_input($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = test_input($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT userid, username, password, usertype FROM usertable WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();
                
                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1){                    
                    // Bind result variables
                    $stmt->bind_result($userid, $username, $passwordtest, $usertype);
                    if($stmt->fetch()){
                        if(password_verify($password, $passwordtest)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["userid"] = $userid;
                            $_SESSION["username"] = $username;                            
                            $_SESSION["usertype"] = $usertype;
							
							
							// Redirect user to welcome page based on role
							if($usertype == "admin"){
								header("location: AdminHome.php");
								}
							if($usertype == "user"){
								header("location: UserHome.php");
							}
                            
                            
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection

}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Music-to-go</title>
    <link rel="stylesheet" href="stylesheet.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
</head>
<body>
	<h1 class="header">Welcome To Music-to-go</h1>
    <div class="block">
	<div class="form-style">
        <label class="naming">Login to Music-to-go</label>
        <label class="question-text">Enter in your username and password</label>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="question-text <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
			<input type="text" name="username" class="input-field" value="<?php echo $username; ?>">
                <label class="error"><?php echo $username_err; ?></label>
            </div>    
            <div class="question-text <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="input-field">
                <label class="error"><?php echo $password_err; ?></label>
            </div>
            <div class="question-text">
                <input type="submit" class="submit" value="Login">
            </div>
			<br>
            <label class="question-text">Don't have an account? <a href="Register.php">Sign up now</a></label>
        </form>
	</div>
    </div>  
		
</body>
</html>