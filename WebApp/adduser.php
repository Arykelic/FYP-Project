<?php
// Initialize the session
session_start();


// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["usertype"] !== "Admin" || $_SESSION["accountstatus"] !== "Active") {
  header("location: index.php");
  exit;
}

include "GlobalClass.php";
include "UserConfig.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = $firstname = $lastname = $phonenumber = $emailaddress = $BirthDate = $Gender = $usertype = $accountstatus = "";
$username_err = $password_err = $confirm_password_err = $firstname_err = $lastname_err = $phonenumber_err = $emailaddress_err = $BirthDate_err = $Gender_err = $usertype_err = $accountstatus_err = "";
$phoneregex = "/^(^[689]{1})(\d{7})$/";
$emailregex = "/^[^0-9][_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate username
  if (empty(test_input($_POST["username"]))) {
    $username_err = "Please enter a username.";
  } else {
    // Prepare a select statement
    $sql = "SELECT userid FROM usertable WHERE username = ?";

    if ($stmt = $mysqli->prepare($sql)) {
      // Bind variables to the prepared statement as parameters
      $stmt->bind_param("s", $param_username);

      // Set parameters
      $param_username = test_input($_POST["username"]);

      // Attempt to execute the prepared statement
      if ($stmt->execute()) {
        // store result
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
          $username_err = "This username is already taken.";
        } else {
          $username = test_input($_POST["username"]);
        }
      } else {
        echo "Something went wrong. Please try again later.";
      }

      // Close statement
      $stmt->close();
    }
  }

  // Validate password
  if (empty(test_input($_POST["password"]))) {
    $password_err = "Please enter a password.";
  } elseif (strlen(test_input($_POST["password"])) < 6) {
    $password_err = "Password must have at least 6 characters.";
  } else {
    $password = test_input($_POST["password"]);
  }

  // Validate confirm password
  if (empty(test_input($_POST["confirm_password"]))) {
    $confirm_password_err = "Please confirm password.";
  } else {
    $confirm_password = test_input($_POST["confirm_password"]);
    if (empty($password_err) && ($password != $confirm_password)) {
      $confirm_password_err = "Password did not match.";
    }
  }

  $input_usertype = test_input($_POST["usertype"]);
  if (empty($input_usertype)) {
    $usertype_err = "Please enter a user type.";
  } else {
    $usertype = $input_usertype;
  }

  $input_accountstatus = test_input($_POST["accountstatus"]);
  if (empty($input_accountstatus)) {
    $accountstatus_err = "Please enter a account status.";
  } else {
    $accountstatus = $input_accountstatus;
  }


  // Check input errors before inserting in database
  if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($usertype_err) && empty($accountstatus_err)) {

    // Prepare an insert statement
    $sql = "INSERT INTO user (username, password, usertype, accountstatus, createddatetime) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($sql)) {
      // Bind variables to the prepared statement as parameters
      $stmt->bind_param("sssss", $param_username, $param_password, $param_usertype, $param_accountstatus);

      // Set parameters
      $param_username = $username;
      $param_password = password_hash($password, PASSWORD_DEFAULT); //Creates a password hash
      $param_usertype = $usertype;
      $param_accountstatus = $accountstatus;

      // Attempt to execute the prepared statement
      if ($stmt->execute()) {
        // Redirect to login page
        echo '<script>alert("User added succesfully")</script>';
      } else {
        echo '<script>alert("Something went wrong. Please try again later")</script>';
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
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>E-Commerce Insight (Admin)(Add User)</title>
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/54052f2f04.js" crossorigin="anonymous"></script>

</head>

<body>
  <input type="checkbox" id="nav-toggle"></input>

  <div class="sidebar">
    <div class="sidebar-menu">
      <ul>
        <li>
          <a href="adminhome.php" class="active"><i class="fa-solid fa-house"></i>
            <span>Home</span></a>
        </li>
        <li>
          <a href="adduser.php"><i class="fa-solid fa-user-plus"></i>
            <span>Add Users</span></a>
        </li>
        <li>
          <a href="manageuser.php"><span class="las la-users"></span>
            <span>Manage Users</span></a>
        </li>
        <li>
          <a href="adminupdatepersonalinfo.php"><span class="las la-user-circle"></span>
            <span>Edit Personal Account</span></a>
        </li>
        <li>
          <a href="Logout.php"><i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span></a>
        </li>
      </ul>
    </div>
  </div>

  <div class="main-content">
    <header>
      <h2>
        <label for="nav-toggle">
          <span class="las la-bars"></span>
        </label>
        E-Commerce Insight (Admin)(Add User)
      </h2>

      <div class="user-wrapper">
        <span class="las la-user-circle fa-3x"></span>
        <div>
          <h4>Admin1</h4>
          <small>Super admin</small>
        </div>
      </div>
    </header>

    <main>
        <form id="AdminAddUserPage" method="POST" action="adduser.php">
        
        <div class="form-group">
        <!-- create form wih post method to the same page -->
        <label>Username: </label>
        <input type="text" class="form-control" id="Username" name="username" placeholder="Username" required><br><br>
        <!-- create input text for Username for user to input username text -->
        <label>Password: </label>
        <input type="Password" class="form-control" id="Password" name="password" placeholder="Password" required><br><br>
        <!-- create password text for Username for user to input username text -->
        <label for="sel1">User Type</label>&ensp;
        <select class="form-control form-select-sm" name = "usertype" id="usertype">
                  <option value = "Admin" >Admin</option>
                  <option value = "User" >User</option>
        </select>
        <label for="sel1">Account Status</label>&ensp;
        <select class="form-control form-select-sm" name = "accountstatus" id="accountstatus">
                  <option value = "Active" >Active</option>
                  <option value = "Disabled" >Disabled</option>
        </select>
        <br>
        <!-- create option input for User Profile for user to select user profile -->
        <button class="btn btn-primary mb-3" type="submit" value="Submit">Submit</button>

        </div>
      </form> 
    </main>
  </div>

</body>

</html>