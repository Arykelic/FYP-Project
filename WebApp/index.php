<?php
// Initialize the session
session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["usertype"] === "Admin" && $_SESSION["accountstatus"] === "Active"){
  header("location: adminhome.php");
  exit;
}

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["usertype"] === "User" && $_SESSION["accountstatus"] === "Active"){
  header("location: userhome.php");
  exit;
}

// Include config file
include "GlobalClass.php";
include "UserConfig.php";
 
// Define variables and initialize with empty values
$username = $password = $accountstatus = "";
$username_err = $password_err = $accountstatus_err = "";

 
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
        $sql = "SELECT userid, username, password, firstname, lastname, usertype, accountstatus FROM user WHERE username = ?";
        
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
                    $stmt->bind_result($userid, $username, $passwordtest, $firstname, $lastname, $usertype, $accountstatus);
                    if($stmt->fetch()){
                        #if(password_verify($password, $passwordtest))
                        if($password == $passwordtest)
                        {
                          // Password is correct, so check account status
                          if ($_SESSION['accountstatus']=="Active"){
                            
                            // Account status is active, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["userid"] = $userid;
                            $_SESSION["username"] = $username;
                            $_SESSION["firstname"] = $firstname;
                            $_SESSION["lastname"] = $lastname;                          
                            $_SESSION["usertype"] = $usertype;
                            $_SESSION["accountstatus"] = $accountstatus;
							
                            if ($_SESSION['usertype']!=null){
                              switch($_SESSION['usertype'])
                              {
                                  case 'Admin':
                                      header("Location:adminhome.php");
                                      break;
              
                                  case 'User':
                                      header("Location:userhome.php");
                                      break;
                              }
                          }                            
                        }
                        else{
                          // Display an error message if account status is not valid
                          $accountstatus_err = "Your account is locked, please contact an admin to unlock it.";
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
<html lang="en" dir="ltr">
  <head>
    <title>Welcome to E-Commerce Insight</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <!-- CSS only -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="row px-3">
        <div class="col-lg-10 col-xl-9 card flex-row mx-auto px-0">
          <div class="img-left d-none d-md-flex"></div>
          <div class="card-body">
            <h4 class="title text-center mt-4">
              Login into E-Commerce Insight
            </h4>
            <form class="form-box px-3" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">

            <div class="form-input">
                <span><i class="fa fa-envelope-o"></i></span>
                <input type="username" name="username" placeholder="username" tabindex="10" value="<?php echo $username; ?>" required>
                <label class="error"><?php echo $username_err; ?></label>
              </div>

              <div class="form-input">
                <span><i class="fa fa-key"></i></span>
                <input type="password" name="password" placeholder="password" required>
                <label class="error"><?php echo $password_err; ?></label>
                <label class="error"><?php echo $accountstatus_err; ?></label>
              </div>
        
              <!-- <div class="mb-3">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="cb1" name="">
                  <label class="custom-control-label" for="cb1">
                    Remember me</label>
                </div>
              </div> -->

              <div class="mb-3">
                <button type="submit" class="btn btn-block text-uppercase">
                  Login
                </button>
              </div>
  
              <hr class="my-4">
              <div class="text-right">
                <a href="#" class="forget-link">
                  Forget Password?
                </a>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
