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


$username = $firstname = $lastname = $phonenumber = $emailaddress = $BirthDate = $Gender = $usertype = $accountstatus = $createddatetime = $updateddatetime = $updatedby = "";
$username_err = $phonenumber_err = $emailaddress_err = $usertype_err = $accountstatus_err = "";

$phoneregex = "/^(^[689]{1})(\d{7})$/";
$emailregex = "/^[^0-9][_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/";

if (isset($_POST["userid"]) && !empty($_POST["userid"])) {
  // Get hidden/disabled input value
  $userid = $_POST["userid"];
  /* $username = $_POST["username"]; */

  /* // Validate username and code to change username
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
    } */

  $firstname = test_input($_POST["firstname"]);
  $lastname = test_input($_POST["lastname"]);

  if (!preg_match($phoneregex, $_POST["phonenumber"])) {
    $phonenumber_err = "Please enter a valid phone number.";
  } else {
    $phonenumber = test_input($_POST["phonenumber"]);
  }

  if (!preg_match($emailregex, $_POST["emailaddress"])) {
    $emailaddress_err = "Please enter a valid email address.";
  } else {
    $emailaddress = test_input($_POST["emailaddress"]);
  }

  $BirthDate = test_input($_POST["BirthDate"]);
  $Gender = test_input($_POST["Gender"]);

  if (empty(test_input($_POST["usertype"]))) {
    $usertype_err = "Please enter the user type.";
  } else {
    $usertype = test_input($_POST["usertype"]);
  }

  if (empty(test_input($_POST["accountstatus"]))) {
    $accountstatus_err = "Please enter the Account Status.";
  } else {
    $accountstatus = test_input($_POST["accountstatus"]);
  }

  $createddatetime = test_input($_POST["createddatetime"]);
  $updatedby = $_SESSION["username"];

  // Check input errors before inserting in database
  if (empty($phonenumber_err) && empty($emailaddress_err) && empty($usertype_err) && empty($accountstatus_err)) {
    // Prepare an update statement
    $sql = "UPDATE user SET firstname=?, lastname=?, phonenumber=?, emailaddress=?, BirthDate=?,
        Gender=?, usertype=?, accountstatus=?, updateddatetime = CURRENT_TIMESTAMP, updatedby=? WHERE userid=?";

    if ($stmt = $mysqli->prepare($sql)) {
      // Bind variables to the prepared statement as parameters
      $stmt->bind_param(
        "ssissssssi",
        $param_firstname,
        $param_lastname,
        $param_phonenumber,
        $param_emailaddress,
        $param_BirthDate,
        $param_Gender,
        $param_usertype,
        $param_accountstatus,
        $param_updatedby,
        $param_userid

      );

      // Set parameters

      /* $param_username = $username; */
      $param_firstname = $firstname;
      $param_lastname = $lastname;
      $param_phonenumber = $phonenumber;
      $param_emailaddress = $emailaddress;
      $param_BirthDate = $BirthDate;
      $param_Gender = $Gender;
      $param_usertype = $usertype;
      $param_accountstatus = $accountstatus;
      $param_updatedby = $updatedby;
      $param_userid = $userid;

      //UPDATE Session Variables if updating self
      if ($_SESSION["userid"] == $userid) {
        $_SESSION["firstname"] = $firstname;
        $_SESSION["lastname"] = $lastname;
        $_SESSION["phonenumber"] = $phonenumber;
        $_SESSION["emailaddress"] = $emailaddress;
        $_SESSION["BirthDate"] = $BirthDate;
        $_SESSION["Gender"] = $Gender;
      }

      // Attempt to execute the prepared statement
      if ($stmt->execute()) {
        // Records updated successfully. Redirect to landing page
        echo '<script>alert("User Updated Succesfully")</script>';
        header("location: adminmanageuser.php");
        exit();
      } else {
        echo '<script>alert("Something went wrong. Please try again later")</script>';
      }
    }

    // Close statement
    $stmt->close();
  }

  // Close connection
  $mysqli->close();
} else {
  if (isset($_GET["userid"]) && !empty(trim($_GET["userid"]))) {
    // Prepare a select statement
    $userid = trim($_GET["userid"]);

    $sql = "SELECT * FROM user WHERE userid = ?";
    if ($stmt = $mysqli->prepare($sql)) {
      // Bind variables to the prepared statement as parameters
      $stmt->bind_param("i", $param_userid);

      // Set parameters
      $param_userid = trim($_GET["userid"]);

      // Attempt to execute the prepared statement
      if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
          /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
          $row = $result->fetch_array(MYSQLI_ASSOC);

          // Retrieve individual field value
          $firstname = $row["firstname"];
          $username = $row["username"];
          $lastname = $row["lastname"];
          $phonenumber = $row["phonenumber"];
          $emailaddress = $row["emailaddress"];
          $BirthDate = $row["BirthDate"];
          $Gender = $row["Gender"];
          $usertype = $row["usertype"];
          $accountstatus = $row["accountstatus"];
          $createddatetime = $row["createddatetime"];
          $createdby = $row["createdby"];
          $updateddatetime = $row["updateddatetime"];
          $updatedby = $row["updatedby"];
        } else {
          // URL doesn't contain valid id parameter. Redirect to error page
          echo '<script>alert("An error has occurred finding a valid id parameter")</script>';
        }
      } else {
        echo '<script>alert("Something went wrong. Please try again later")</script>';
      }
    }

    // Close connection
    //$mysqli->close();
  } else {
    // URL doesn't contain id parameter. Redirect to error page
    echo '<script>alert("An error has occurred finding a valid id parameter. Redirecting you back to E-Commerce Insight (Admin)(Manage User)")</script>';
  }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>E-Commerce Insight (Admin)(Update User)</title>
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
            <span style="padding: 7px;">Home</span></a>
        </li>
        <li>
          <a href="adminmanageuser.php"><span class="fa-solid fa-users"></span>
            <span style="padding: 5px;">Manage Users</span></a>
        </li>
        <li>
          <a href="adminadduser.php"><span class="fa-solid fa-user-plus"></span>
            <span style="padding: 5px;">Add Users</span></a>
        </li>
        <li>
          <a href="adminupdatepersonalinfo.php"><span class="fa-solid fa-circle-user"></span>
            <span style="padding: 10px;">Edit Personal Information</span></a>
        </li>
        <li>
          <a href="adminresetpassword.php"><span class="fa-solid fa-key"></span>
            <span style="padding: 10px;">Reset Password</span></a>
        </li>
        <li>
          <a href="Logout.php"><i class="fa-solid fa-right-from-bracket"></i>
            <span style="padding: 15px;">Logout</span></a>
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
        <img src="elogofinal.png" style="position:relative; top: 10px; height: 40px; width: 40px;"></img>
        E-Commerce Insight (Admin)(Update User)
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
        Update User
      </h2>
      <br><br>

      <form id="adminviewuser" method="post" action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>">

        <div class="form-box px-3">
          <!-- create form wih post method to the same page -->
          <label>User Id: </label>
          <input type="text" value="<?php echo $userid; ?>" disabled>
          <br><br>
          <!-- create input text for Username for user to input username text -->
          <label>User Name: </label>
          <input type="text" class="form-input" value="<?php echo $username; ?>" disabled>
          <br><br>
          <label>First Name: </label>
          <input type="text" class="form-input" id="firstname" name="firstname" placeholder="Enter your First Name" value="<?php echo $firstname; ?>" required>
          <br><br>
          <label>Last Name: </label>
          <input type="text" class="form-input" id="lastname" name="lastname" placeholder="Enter your Last Name" value="<?php echo $lastname; ?>" required>
          <br><br>
          <label>Phone Number: </label>
          <input type="tel" class="form-input" id="phonenumber" name="phonenumber" placeholder="Enter your Phone Number" value="<?php echo $phonenumber; ?>" required>
          <label class="error"><?php echo $phonenumber_err; ?></label>
          <br><br>
          <label>Email Address: </label>
          <input type="email" id="emailaddress" name="emailaddress" class="form-input" placeholder="Enter your Email Address" value="<?php echo $emailaddress; ?>" required>
          <label class="error"><?php echo $emailaddress_err; ?></label>
          <br><br>
          <label>Birth Date: </label>
          <input type="date" id="BirthDate" name="BirthDate" class="form-input" value="<?php echo $BirthDate; ?>" required>
          <br><br>
          <label>Gender: </label>
          <select class="form-input" name="Gender" id="Gender" value="<?php echo $Gender; ?>" required>
            <option> <?php echo $Gender; ?> </option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
          <br><br>
          <label>User Type</label>
          <select class="form-input" name="usertype" id="usertype" value="<?php echo $usertype; ?>" required>
            <option> <?php echo $usertype; ?> </option>
            <option value="Admin">Admin</option>
            <option value="User">User</option>
          </select>
          <br><br>
          <label>Account Status</label>
          <select class="form-input" name="accountstatus" id="accountstatus" value="<?php echo $accountstatus; ?>" required>
            <option> <?php echo $accountstatus; ?> </option>
            <option value="Active">Active</option>
            <option value="Disabled">Disabled</option>
          </select>
          <br><br>
          <label>Created Date Time: </label>
          <input class="form-input" type="text" value="<?php echo $createddatetime; ?>" disabled>
          <br><br>
          <label>Created By: </label>
          <input class="form-input" type="text" value="<?php echo $createdby; ?>" disabled>
          <br><br>
          <label>Updated Date Time: </label>
          <input class="form-input" type="text" value="<?php echo $updateddatetime; ?>" disabled>
          <br><br>
          <label>Updated By: </label>
          <input class="form-input" type="text" value="<?php echo $updatedby; ?>" disabled>
          <br><br>
          <input type="hidden" name="userid" value="<?php echo $userid; ?>" />

          <input class="btn btn-block text-uppercase" type="submit" value="Update User"></input>
        </div>
      </form>
      <!-- <a href="adminmanageuser.php"><button class="backbutton" value="Back">Back</button></a> -->
      <button class="backbutton" value="Back" onclick="history.back()">Back</button>
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