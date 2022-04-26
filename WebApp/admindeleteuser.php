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

// Process delete operation after confirmation
if (isset($_POST["userid"]) && !empty($_POST["userid"])) {


    // Prepare a delete statement
    $sql = "DELETE FROM user WHERE userid = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_userid);

        // Set parameters
        $param_userid = trim($_POST["userid"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records deleted successfully. Redirect to landing page
            header("location: adminmanageuser.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    $stmt->close();

    // Close connection
    $mysqli->close();
} else {
    if (isset($_GET["userid"]) && !empty(trim($_GET["userid"]))) {
        // Prepare a select statement
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
                    $Username = $row["username"];
                    $Firstname = $row["firstname"];
                    $Lastname = $row["lastname"];
                    $phonenumber = $row["phonenumber"];
                    $emailaddress = $row["emailaddress"];
                    $BirthDate = $row["BirthDate"];
                    $Gender = $row["Gender"];
                    $Usertype = $row["usertype"];
                    $AccountStatus = $row["accountstatus"];
                    $createddatetime = $row["createddatetime"];
                    $updateddatetime = $row["updateddatetime"];
                    $updatedby = $row["updatedby"];
                } else {
                    // URL doesn't contain valid id parameter. Redirect to error page
                    echo '<script>alert("An error has occurred finding a valid id parameter.")</script>';
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
                    <a href="adminmanageuser.php"><span class="las la-users"></span>
                        <span>Manage Users</span></a>
                </li>
                <li>
                    <a href="adminadduser.php"><i class="fa-solid fa-user-plus"></i>
                        <span>Add Users</span></a>
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
                Delete User
                <p class="error">Are you sure you want to delete this user record?</p>
            </h2>
            <br><br>

            <form id="admindeleteuser" method="POST">
                <div class="form-box px-3">
                    <!-- create form wih post method to the same page -->
                    <label>User Id: </label>
                    <input type="text" value="<?php echo $row["userid"]; ?>" readonly>
                    <br><br>
                    <!-- create input text for Username for user to input username text -->
                    <label>User Name: </label>
                    <input type="text" value="<?php echo $row["username"]; ?>" readonly>
                    <br><br>
                    <label>First Name: </label>
                    <input type="text" value="<?php echo $row["firstname"]; ?>" readonly>
                    <br><br>
                    <label>Last Name: </label>
                    <input type="text" value="<?php echo $row["lastname"]; ?>" readonly>
                    <br><br>
                    <label>Phone Number: </label>
                    <input type="tel" value="<?php echo $row["phonenumber"]; ?>" readonly>
                    <br><br>
                    <label>Email Address: </label>
                    <input type="email" value="<?php echo $row["emailaddress"]; ?>" readonly>
                    <br><br>
                    <label>Birth Date: </label>
                    <input type="date" value="<?php echo $row["BirthDate"]; ?>" readonly>
                    <br><br>
                    <label>Gender: </label>
                    <input type="text" value="<?php echo $row["Gender"]; ?>" readonly>
                    <br><br>
                    <label>User Type: </label>
                    <input type="text" value="<?php echo $row["usertype"]; ?>" readonly>
                    <br><br>
                    <label>Account Status: </label>
                    <input type="text" value="<?php echo $row["accountstatus"]; ?>" readonly>
                    <br><br>
                    <label>Created Date Time: </label>
                    <input type="text" value="<?php echo $row["createddatetime"]; ?>" readonly>
                    <br><br>
                    <label>Updated Date Time: </label>
                    <input type="text" value="<?php echo $row["updateddatetime"]; ?>" readonly>
                    <br><br>
                    <label>Updated By: </label>
                    <input type="text" value="<?php echo $row["updatedby"]; ?>" readonly>
                    <br><br>
                    <input type="hidden" name="userid" value="<?php echo trim($_GET["userid"]); ?>" />
                    <input id="deletebutton" type="submit" value="Delete User"></input>
                </div>
            </form>
            <a href="adminmanageuser.php"><button class="backbutton" value="Back">Back</button></a>
        </main>
    </div>

</body>

</html>