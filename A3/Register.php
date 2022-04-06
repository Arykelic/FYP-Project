<?php

// Include config file
include "GlobalClass.php";
include "UserConfig.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $firstname = $lastname = $phonenumber = $emailaddress = $usertype = "";
$username_err = $password_err = $confirm_password_err = $firstname_err = $lastname_err = $phonenumber_err = $emailaddress_err = "";
$phoneregex = "/^(^[689]{1})(\d{7})$/";
$emailregex = "/^[^0-9][_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(test_input($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT userid FROM usertable WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = test_input($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = test_input($_POST["username"]);
                }
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Validate password
    if(empty(test_input($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(test_input($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = test_input($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(test_input($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = test_input($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
	
	if(empty(test_input($_POST["firstname"]))){
        $firstname_err = "Please enter your first name.";     
    } else{
        $firstname = test_input($_POST["firstname"]);
        }
		
	if(empty(test_input($_POST["lastname"]))){
        $lastname_err = "Please enter your last name.";     
    } else{
        $lastname = test_input($_POST["lastname"]);
        }
		
	if(empty(test_input($_POST["phonenumber"]))){
        $phonenumber_err = "Please enter your phone number.";     
    }	elseif (!preg_match($phoneregex, $_POST["phonenumber"])) {
		$phonenumber_err="Please enter a valid phone number."; 
	}	else{
        $phonenumber = test_input($_POST["phonenumber"]);
        }	
		
	if(empty(test_input($_POST["emailaddress"]))){
        $emailaddress_err = "Please enter your email address.";     
    }	elseif (!preg_match($emailregex, $_POST["emailaddress"])) {
		$emailaddress_err="Please enter a valid email address.";
	}	else{
        $emailaddress = test_input($_POST["emailaddress"]);
        }
		
	$usertype = test_input($_POST["usertype"]);
	
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($firstname_err) && empty($lastname_err) && empty($phonenumber_err) && empty($emailaddress_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO usertable (username, password, firstname, lastname, phonenumber, emailaddress, usertype) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssiss", $param_username, $param_password, $param_firstname, $param_lastname, $param_phonenumber, $param_emailaddress, $param_usertype);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); //Creates a password hash
			$param_firstname = $firstname;
			$param_lastname = $lastname;
			$param_phonenumber = $phonenumber;
			$param_emailaddress = $emailaddress;
			$param_usertype = $usertype;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: Login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $mysqli->close();
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
	<div class= "block">
	<div class="form-style">
		<label class="naming">Sign Up with Music-to-go</label>
		<label class="question-text">Fill out the form to create an account and get started!</label>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
			<div class="question-text <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="input-field" value="<?php echo $username; ?>">
                <label class="error"><?php echo $username_err; ?></label>
            </div>    
            <div class="question-text <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="input-field" value="<?php echo $password; ?>">
                <label class="error"><?php echo $password_err; ?></label>
            </div>
            <div class="question-text <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="input-field" value="<?php echo $confirm_password; ?>">
                <label class="error"><?php echo $confirm_password_err; ?></label>
            </div>
			<div class="question-text <?php echo (!empty($firstname_err)) ? 'has-error' : ''; ?>">
                <label>First Name</label>
                <input type="text" name="firstname" class="input-field" value="<?php echo $firstname; ?>">
                <label class="error"><?php echo $firstname_err; ?></label>
            </div>   
			<div class="question-text <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>">
                <label>Last Name</label>
                <input type="text" name="lastname" class="input-field" value="<?php echo $lastname; ?>">
                <label class="error"><?php echo $lastname_err; ?></label>
            </div>   
			<div class="question-text <?php echo (!empty($phonenumber_err)) ? 'has-error' : ''; ?>">
                <label>Phone Number</label>
                <input type="tel" name="phonenumber" class="input-field" value="<?php echo $phonenumber; ?>">
                <label class="error"><?php echo $phonenumber_err; ?></label>
            </div>   
			<div class="question-text <?php echo (!empty($emailaddress_err)) ? 'has-error' : ''; ?>">
                <label>Email Address</label>
                <input type="email" name="emailaddress" class="input-field" value="<?php echo $emailaddress; ?>">
                <label class="error"><?php echo $emailaddress_err; ?></label>
            </div>
			<div class="question-text">
                <label>User Type</label>
				<select id="userType" name="usertype" class="input=field">
							<option value="user">User</option>
							<option value="admin">Admin</option>
				</select>
            </div>
			<br>
            <div class="question-text">
                <input type="submit" class="submit" value="Register">
                <input type="reset" class="submit" value="Reset">
            </div>
			<br>
            <label class="question-text">Already have an account? <a href="Login.php">Login here</a></label>
			
		</form>
	</div>
	</div> 
</body>
</html>
