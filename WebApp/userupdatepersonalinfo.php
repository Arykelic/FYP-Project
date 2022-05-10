<?php
// Initialize the session
session_start();


// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["usertype"] !== "User" || $_SESSION["accountstatus"] !== "Active") {
  header("location: index.php");
  exit;
}

include "GlobalClass.php";
include "UserConfig.php";


// Define variables and initialize with empty values and assign userid to session userid
$userid = $_SESSION["userid"];
$updatedby = $_SESSION["username"];
$firstname = $_SESSION["firstname"];
$lastname = $_SESSION["lastname"];
$phonenumber = $_SESSION["phonenumber"];
$emailaddress = $_SESSION["emailaddress"];
$BirthDate = $_SESSION["BirthDate"];
$Gender = $_SESSION["Gender"];

$firstname_err = $lastname_err = $phonenumber_err = $emailaddress_err = $BirthDate_err = $Gender_err = "";
$phoneregex = "/^(^[689]{1})(\d{7})$/";
$emailregex = "/^[^0-9][_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

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


  // Check input errors before inserting in database
  if (empty($firstname_err) && empty($lastname_err) && empty($phonenumber_err) && empty($emailaddress_err) && empty($BirthDate_err) && empty($Gender_err)) {

    // Prepare an UPDATE statement
    $sql = "UPDATE user SET firstname=?, lastname=?, phonenumber=?, emailaddress=?, BirthDate=?, Gender=?, updateddatetime = CURRENT_TIMESTAMP, updatedby =? WHERE userid=? ";

    if ($stmt = $mysqli->prepare($sql)) {
      // Bind variables to the prepared statement as parameters
      $stmt->bind_param("ssissssi", $param_firstname, $param_lastname, $param_phonenumber, $param_emailaddress, $param_BirthDate, $param_Gender, $param_updatedby, $param_userid);

      // Set parameters
      $param_firstname = $firstname;
      $param_lastname = $lastname;
      $param_phonenumber = $phonenumber;
      $param_emailaddress = $emailaddress;
      $param_BirthDate = $BirthDate;
      $param_Gender = $Gender;
      $param_updatedby = $updatedby;
      $param_userid = $userid;

      //UPDATE Session Variables
      $_SESSION["firstname"] = $firstname;
      $_SESSION["lastname"] = $lastname;
      $_SESSION["phonenumber"] = $phonenumber;
      $_SESSION["emailaddress"] = $emailaddress;
      $_SESSION["BirthDate"] = $BirthDate;
      $_SESSION["Gender"] = $Gender;

      // Attempt to execute the prepared statement
      if ($stmt->execute()) {
        // Redirect to login page
        echo '<script>alert("Personal Information Updated Succesfully")</script>';
      } else {
        echo '<script>alert("Something went wrong. Please try again later")</script>';
      }

      // Close statement
      $stmt->close();
    }
  }
}

// Close connection
$mysqli->close();

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>E-Commerce Insight (User)(Update Personal Information)</title>
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
          <a href="userhome.php" class="active"><span class="fa-solid fa-house"></span>
            <span>Home</span></a>
        </li>
        <li>
          <a href="userviewrecords.php" class="active"><span class="fa-solid fa-database"></span>
            <span>View Records</span></a>
        </li>
        <li>
          <a href="usercataloguescraper.php" class="active"><span class="fa-solid fa-book-atlas"></span>
            <span>Product Catalogue Scraper</span></a>
        </li>
        <li>
          <a href="userpagescraper.php" class="active"><span class="fa-solid fa-file"></span>
            <span>Product Page Scraper</span></a>
        </li>
        <li>
          <a href="userreviewscraper.php" class="active"><span class="fa-solid fa-star"></span>
            <span>Product Review Scraper</span></a>
        </li>
        <li>
          <a href="https://ratingrecommendersystem.herokuapp.com" class="active"><span class="fa-solid fa-computer"></span>
            <span>Recommender System</span></a>
        </li>
        <li>
          <a href="userupdatepersonalinfo.php"><span class="fa-solid fa-circle-user"></span>
            <span>Edit Personal Information</span></a>
        </li>
        <li>
          <a href="userresetpassword.php"><span class="fa-solid fa-key"></span>
            <span>Reset Password</span></a>
        </li>
        <li>
          <a href="Logout.php"><span class="fa-solid fa-right-from-bracket"></span>
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
        <img src="elogofinal.png" style="position:relative; top: 10px; height: 40px; width: 40px;"></img>
        E-Commerce Insight (User)(Update Personal Information)
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
        Update Personal Information
      </h2>
      <br><br>
      <form id="userupdatepersonalinfo" method="POST">
        <div class="form-box px-3">
          <!-- create form wih post method to the same page -->
          <label>First Name: </label>
          <input type="text" class="form-input" id="firstname" name="firstname" placeholder="Enter your First Name" value="<?php echo $firstname; ?>" required>
          <br><br>
          <!-- create input text for Username for user to input username text -->
          <label>Last Name: </label>
          <input type="text" class="form-input" id="lastname" name="lastname" placeholder="Enter your Last Name" value="<?php echo $lastname; ?>" required>
          <br><br>
          <label>Phone Number: </label>
          <input type="tel" class="form-input" id="phonenumber" name="phonenumber" placeholder="Enter your Phone Number" value="<?php echo $phonenumber; ?>" required>
          <label class="error"><?php echo $phonenumber_err; ?></label>
          <br><br>
          <!-- create password text for Username for user to input username text -->
          <label>Email Address</label>
          <input type="email" id="emailaddress" name="emailaddress" class="form-input" placeholder="Enter your Email Address" value="<?php echo $emailaddress; ?>" required>
          <label class="error"><?php echo $emailaddress_err; ?></label>
          <br><br>
          <label>Birth Date</label>
          <input type="date" id="BirthDate" name="BirthDate" class="form-input" value="<?php echo $BirthDate; ?>" required>
          <label class="error"><?php echo $BirthDate_err; ?></label>
          <br><br>
          <label>Gender</label>
          <select class="form-input" name="Gender" id="Gender" value="<?php echo $Gender; ?>" required>
            <option> <?php echo $Gender; ?> </option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
          <br><br>
          <!-- create option input for User Profile for user to select user profile -->
          <input class="btn btn-block text-uppercase" type="submit" value="Update User Information"></input>
          <input type="reset" class="btn btn-block text-uppercase" value="Reset Fields"></input>
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