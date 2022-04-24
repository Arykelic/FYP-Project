<?php
// Initialize the session
session_start();


// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["usertype"] !== "Admin" || $_SESSION["accountstatus"] !== "Active") {
  header("location: index.php");
  exit;
}

include "GlobalClass.php";


// Define variables and initialize with empty values
$username = $password = $confirm_password = $firstname = $lastname = $phonenumber = $emailaddress = $BirthDate = $Gender = $usertype = $accountstatus = "";
$username_err = $password_err = $confirm_password_err = $firstname_err = $lastname_err = $phonenumber_err = $emailaddress_err = $BirthDate_err = $Gender_err = $usertype_err = $accountstatus_err = "";
$phoneregex = "/^(^[689]{1})(\d{7})$/";
$emailregex = "/^[^0-9][_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(test_input($_POST["firstname"]))) {
    $firstname_err = "Please enter your first name.";
  } else {
    $firstname = test_input($_POST["firstname"]);
  }

  if (empty(test_input($_POST["lastname"]))) {
    $lastname_err = "Please enter your last name.";
  } else {
    $lastname = test_input($_POST["lastname"]);
  }

  if (empty(test_input($_POST["phonenumber"]))) {
    $phonenumber_err = "Please enter your phone number.";
  } elseif (!preg_match($phoneregex, $_POST["phonenumber"])) {
    $phonenumber_err = "Please enter a valid phone number.";
  } else {
    $phonenumber = test_input($_POST["phonenumber"]);
  }

  if (empty(test_input($_POST["emailaddress"]))) {
    $emailaddress_err = "Please enter your email address.";
  } elseif (!preg_match($emailregex, $_POST["emailaddress"])) {
    $emailaddress_err = "Please enter a valid email address.";
  } else {
    $emailaddress = test_input($_POST["emailaddress"]);
  }

  if (empty(test_input($_POST["BirthDate"]))) {
    $emailaddress_err = "Please enter your email address.";
  } elseif (!preg_match($emailregex, $_POST["emailaddress"])) {
    $emailaddress_err = "Please enter a valid email address.";
  } else {
    $emailaddress = test_input($_POST["emailaddress"]);
  }

  if (empty(test_input($_POST["Gender"]))) {
    $emailaddress_err = "Please enter your email address.";
  } elseif (!preg_match($emailregex, $_POST["emailaddress"])) {
    $emailaddress_err = "Please enter a valid email address.";
  } else {
    $emailaddress = test_input($_POST["emailaddress"]);
  }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>E-Commerce Insight (Admin)(Update Personal Information)</title>
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
            <span>Edit Personal Information</span></a>
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
        E-Commerce Insight (Admin)(Update Personal Information)
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

      <form id="adminupdatepersonalinfo" method="POST">

        <h2 class="title mt-4">
          Update Personal Information
        </h2>
        <br><br>
        <div class="form-box px-3">
          <!-- create form wih post method to the same page -->
          <label>First Name: </label>
          <input type="text" id="Username" name="username" placeholder="Username" required>
          <label class="error"><?php echo $username_err; ?></label>
          <br><br>
          <!-- create input text for Username for user to input username text -->
          <label>Last Name: </label>
          <input type="Password" class="form-input" id="Password" name="password" placeholder="Password" required>
          <label class="error"><?php echo $password_err; ?></label>
          <br><br>

          <label>Phone Number: </label>
          <input type="Password" class="form-input" id="confirm_password" name="confirm_password" placeholder="Confirm the Password" required>
          <label class="error"><?php echo $confirm_password_err; ?></label>
          <br><br>
          <!-- create password text for Username for user to input username text -->
          <label>Email Address</label>
          <select class="form-input form-select-sm" name="usertype" id="usertype">
            <option value="Admin">Admin</option>
            <option value="User">User</option>
          </select>
          <br><br>
          <label>Birth Date</label>
          <select class="form-input form-select-sm" name="BirthDate" id="BirthDate">
            <option value="Active">Active</option>
            <option value="Disabled">Disabled</option>
          </select>
          <br><br>
          <label>Gender</label>
          <select class="form-input form-select-sm" name="Gender" id="Gender">
            <option value="M">Male</option>
            <option value="F">Female</option>
          </select>
          <br><br>
          <!-- create option input for User Profile for user to select user profile -->
          <input class="btn btn-block text-uppercase" type="submit" value="Create User Account"></input>

        </div>

      </form>
      <!-- <div class="recent-grid">
        <div class="projects">
          <div class="card">
            <div class="card-header">
              <h2>User Account Management</h2>

            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table width="100%">
                  <thead>
                    <tr>
                      <td>Project Title</td>
                      <td>Department</td>
                      <td>Status</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>UI/UX Design</td>
                      <td>UI team</td>
                      <td>
                        <span class="status purple"></span>
                        review
                      </td>
                    </tr>
                    <tr>
                      <td>Web Development</td>
                      <td>Frontend</td>
                      <td>
                        <span class="status pink"></span>
                        in progress
                      </td>
                    </tr>
                    <tr>
                      <td>Ushop app</td>
                      <td>Mobile team</td>
                      <td>
                        <span class="status orange"></span>
                        pending
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div> -->

    </main>

  </div>

</body>

</html>