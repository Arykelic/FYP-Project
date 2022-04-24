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


$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

$firstname_err = $lastname_err = $phonenumber_err = $emailaddress_err = $BirthDate_err = $Gender_err = "";

$phoneregex = "/^(^[689]{1})(\d{7})$/";
$emailregex = "/^[^0-9][_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/";

// Check existence of id parameter before processing further
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
                $username = $row["username"];
                $firstname = $row["firstname"];
                $lastname = $row["lastname"];
                $phonenumber = $row["phonenumber"];
                $emailaddress = $row["emailaddress"];
                $BirthDate = $row["BirthDate"];
                $Gender = $row["Gender"];
                $usertype = $row["usertype"];
                $accountstatus = $row["accountstatus"];
                $createddatetime = $row["createddatetime"];
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
    header("location: adminmanageuser.php");
    exit();
}


if (isset($_POST["userid"]) && !empty($_POST["userid"])) {
    // Get hidden input value
    $userid = $_POST["userid"];

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

    // Check input errors before inserting in database
    if (empty($category_err) && empty($brand_err) && empty($manufactureyear_err) && empty($characteristics_err) && empty($status_err) && empty($cost_err) && empty($overduecost_err)) {
        // Prepare an update statement
        $sql = "UPDATE producttable SET category=?, brand=?, manufactureyear=?, characteristics=?, status=?, cost=?, overduecost=? WHERE productid=?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssissddi", $param_category, $param_brand, $param_manufactureyear, $param_characteristics, $param_status, $param_cost, $param_overduecost, $param_productid);

            // Set parameters
            $param_category = $category;
            $param_brand = $brand;
            $param_manufactureyear = $manufactureyear;
            $param_characteristics = $characteristics;
            $param_status = $status;
            $param_cost = $cost;
            $param_overduecost = $overduecost;
            $param_productid = $productid;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records updated successfully. Redirect to landing page
                header("location: AdminHome.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $mysqli->close();
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["productid"]) && !empty(trim($_GET["productid"]))) {
        // Get URL parameter
        $productid =  trim($_GET["productid"]);

        // Prepare a select statement
        $sql = "SELECT * FROM producttable WHERE productid = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_productid);

            // Set parameters
            $param_productid = $productid;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $category = $row["category"];
                    $brand = $row["brand"];
                    $manufactureyear = $row["manufactureyear"];
                    $characteristics = $row["characteristics"];
                    $status = $row["status"];
                    $cost = $row["cost"];
                    $overduecost = $row["overduecost"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();

        // Close connection
        $mysqli->close();
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>E-Commerce Insight (Admin)(View User)</title>
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
                E-Commerce Insight (Admin)(View User)
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

            <form id="adminviewuser" method="POST">

                <h2 class="title mt-4">
                    View User
                </h2>
                <br><br>
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