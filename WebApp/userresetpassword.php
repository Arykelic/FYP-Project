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

// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter the new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password must have atleast 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before updating the database
    if (empty($new_password_err) && empty($confirm_password_err)) {
        // Prepare an update statement
        $sql = "UPDATE user SET password = ?, updateddatetime = CURRENT_TIMESTAMP, updatedby=? WHERE userid = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssi", $param_password, $param_updatedby, $param_userid);

            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_updatedby = $_SESSION["username"];
            $param_userid = $_SESSION["userid"];

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page
                session_destroy();
                header("location: index.php");
                exit();
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
    <title>E-Commerce Insight (User)(Reset Password)</title>
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
          <a href="userhome.php" class="active"><span class="fa-solid fa-house "></span>
            <span> Home</span></a>
        </li>
        <li>
          <a href="usercataloguescraper.php" class="active"><span class="fa-solid fa-book-atlas "></span>
            <span> Product Catalogue Scraper</span></a>
        </li>
        <li>
          <a href="userpagescraper.php" class="active"><span class="fa-solid fa-file "></span>
            <span> Product Page Scraper</span></a>
        </li>
        <li>
          <a href="userreviewscraper.php" class="active"><span class="fa-solid fa-star fa-sm"></span>
            <span> Product Review Scraper</span></a>
        </li>
        <li>
          <a href="userviewrecords.php" class="active"><span class="fa-solid fa-database "></span>
            <span>View Records</span></a>
        </li>
        <li>
          <a href="https://ratingrecommendersystem.herokuapp.com" class="active"><span class="fa-solid fa-computer fa-sm"></span>
            <span>Recommender System</span></a>
        </li>
        <li>
          <a href="userupdatepersonalinfo.php"><span class="fa-solid fa-circle-user fa-sm"></span>
            <span>Edit Personal Information</span></a>
        </li>
        <li>
          <a href="userresetpassword.php"><span class="fa-solid fa-key fa-sm"></span>
            <span>Reset Password</span></a>
        </li>
        <li>
          <a href="Logout.php"><span class="fa-solid fa-right-from-bracket fa-sm"></span>
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
                E-Commerce Insight (User)(Reset Password)
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
                Reset Password
            </h2>
            <br><br>

            <form id="userresetpassword" method="POST">

                <div class="form-box px-3">
                    <!-- create form wih post method to the same page -->
                    <!-- create input text for Username for user to input username text -->
                    <label>New Password (At least 6 characters): </label>
                    <input type="Password" class="form-input" id="new_password" name="new_password" placeholder="Enter your new password (At least 6 Characters)" required>
                    <label class="error"><?php echo $new_password_err; ?></label>
                    <br><br>

                    <label>Confirm Password: </label>
                    <input type="Password" class="form-input" id="confirm_password" name="confirm_password" placeholder="Confirm the Password" required>
                    <label class="error"><?php echo $confirm_password_err; ?></label>
                    <br><br>

                    <input class="btn btn-block text-uppercase" type="submit" value="Reset Password"></input>

                </div>

            </form>
        </main>
    </div>

</body>

</html>