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
$username = $password = $confirm_password = $usertype = $accountstatus = "";
$username_err = $password_err = $confirm_password_err = $usertype_err = $accountstatus_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate username
  if (empty(test_input($_POST["username"]))) {
    $username_err = "Please enter a username.";
  } else {
    // Prepare a select statement
    $sql = "SELECT userid FROM user WHERE username = ?";

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

  $createdby = $_SESSION["username"];


  // Check input errors before inserting in database
  if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($usertype_err) && empty($accountstatus_err)) {

    // Prepare an insert statement
    $sql = "INSERT INTO user (username, password, usertype, accountstatus, createdby) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($sql)) {
      // Bind variables to the prepared statement as parameters
      $stmt->bind_param("sssss", $param_username, $param_password, $param_usertype, $param_accountstatus, $param_createdby);

      // Set parameters
      $param_username = $username;
      $param_password = password_hash($password, PASSWORD_DEFAULT); //Creates a password hash
      $param_usertype = $usertype;
      $param_accountstatus = $accountstatus;
      $param_createdby = $createdby;

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
          <a href="adminhome.php" class="active"><span class="fa-solid fa-house"></span>
            <span>Home</span></a>
        </li>
        <li>
          <a href="adminmanageuser.php"><span class="fa-solid fa-users"></span>
            <span>Manage Users</span></a>
        </li>
        <li>
          <a href="adminadduser.php"><span class="fa-solid fa-user-plus"></span>
            <span>Add Users</span></a>
        </li>
        <li>
          <a href="adminupdatepersonalinfo.php"><span class="fa-solid fa-circle-user"></span>
            <span>Edit Personal Information</span></a>
        </li>
        <li>
          <a href="adminresetpassword.php"><span class="fa-solid fa-key"></span>
            <span>Reset Password</span></a>
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
          <h4> <?php echo htmlspecialchars($_SESSION["username"]); ?> </h4>
          <small><?php echo htmlspecialchars($_SESSION["firstname"]); ?></small>
          <small><?php echo htmlspecialchars($_SESSION["lastname"]); ?></small>
        </div>
      </div>
    </header>

    <main>
      <h2 class="title mt-4">
        Create New User
      </h2>
      <br><br>

      <form id="adminadduser" method="POST">

        <div class="form-box px-3">
          <!-- create form wih post method to the same page -->
          <label>Username: </label>
          <input class="form-input" type="text" id="Username" name="username" placeholder="Enter a Username" value="<?php echo $username; ?>" required>
          <label class="error"><?php echo $username_err; ?></label>
          <br><br>
          <!-- create input text for Username for user to input username text -->
          <label>Password (At least 6 characters): </label>
          <input type="Password" class="form-input" id="Password" name="password" placeholder="Enter a Password (At least 6 Characters)" required>
          <label class="error"><?php echo $password_err; ?></label>
          <br><br>

          <label>Confirm Password: </label>
          <input type="Password" class="form-input" id="confirm_password" name="confirm_password" placeholder="Confirm the Password" required>
          <label class="error"><?php echo $confirm_password_err; ?></label>
          <br><br>
          <!-- create password text for Username for user to input username text -->
          <label>User Type</label>
          <select class="form-input" name="usertype" id="usertype" required>
            <option value="Admin">Admin</option>
            <option value="User">User</option>
          </select>
          <br><br>
          <label>Account Status</label>
          <select class="form-input form-select-sm" name="accountstatus" id="accountstatus" required>
            <option value="Active">Active</option>
            <option value="Disabled">Disabled</option>
          </select>
          <br><br>
          <!-- create option input for User Profile for user to select user profile -->
          <input class="btn btn-block text-uppercase" type="submit" value="Create User Account"></input>

        </div>

      </form>
    </main>
  </div>

</body>

</html>