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
include "CatalogueConfig.php";
include "CombinedReviewConfig.php";
include "PageDataConfig.php"

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>E-Commerce Insight (Admin)(Home)</title>
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/54052f2f04.js" crossorigin="anonymous"></script>

  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"> -->
  <!-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> -->
  <!-- JavaScript Bundle with Popper -->
  <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->

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
        E-Commerce Insight (Admin)(Home)
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
      <div class="cards">
        <div class="card-single">
          <div>
            <?php
            $sql = "select count(*) as total from user";
            $result = mysqli_query($mysqli, $sql);
            $data = mysqli_fetch_assoc($result);
            echo "<h1>" . $data['total'] . "</h1>";
            ?>
            <span>Total Users</span>
          </div>
          <div>
            <span class="las la-users"></span>
          </div>
        </div>

        <div class="card-single">
          <div>
            <?php
            $sql = "select count(*) as total from user where usertype like 'Admin'";
            $result = mysqli_query($mysqli, $sql);
            $data = mysqli_fetch_assoc($result);
            echo "<h1>" . $data['total'] . "</h1>";
            ?>
            <span>Admin Accounts</span>
          </div>
          <div>
            <i class="las la-user-cog"></i>
          </div>
        </div>

        <div class="card-single">
          <div>
            <?php
            $sql = "select count(*) as total from user where usertype like 'User'";
            $result = mysqli_query($mysqli, $sql);
            $data = mysqli_fetch_assoc($result);
            echo "<h1>" . $data['total'] . "</h1>";
            ?>
            <span>User Accounts</span>
          </div>
          <div>
            <span class="las la-user"></span>
          </div>
        </div>

        <div class="card-single">
          <div>
            <?php
            $sql = "select count(*) as total from user where accountstatus like 'Disabled'";
            $result = mysqli_query($mysqli, $sql);
            $data = mysqli_fetch_assoc($result);
            echo "<h1>" . $data['total'] . "</h1>";
            ?>
            <span>Disabled Accounts</span>
          </div>

          <div>
            <?php
            $sql = "select count(*) as total from user where accountstatus like 'Disabled' and usertype like 'User'";
            $result = mysqli_query($mysqli, $sql);
            $data = mysqli_fetch_assoc($result);
            echo "<span>" . $data['total'] . "</span>";
            ?>
            <span>User</span>
          </div>

          <div>
            <?php
            $sql = "select count(*) as total from user where accountstatus like 'Disabled' and usertype like 'Admin'";
            $result = mysqli_query($mysqli, $sql);
            $data = mysqli_fetch_assoc($result);
            echo "<span>" . $data['total'] . "</span>";
            ?>
            <span>Admin</span>
          </div>

          <div>
            <span class="las la-user-slash"></span>
          </div>

        </div>

        <!-- 2nd row of cards -->

        <div class="card-single">
          <div>
            <?php
            $sql = "select count(*) as total from cataloguedata";
            $result = mysqli_query($mysqli, $sql);
            $data = mysqli_fetch_assoc($result);
            echo "<h1>" . $data['total'] . "</h1>";
            ?>
            <span>Total Product Catalogue Items</span>
          </div>
          <div>
            <span class="fa-solid fa-book-atlas"></span>
          </div>
        </div>

        <div class="card-single">
          <div>
            <?php
            $sql = "select count(*) as total from pagedata";
            $result = mysqli_query($mysqli, $sql);
            $data = mysqli_fetch_assoc($result);
            echo "<h1>" . $data['total'] . "</h1>";
            ?>
            <span>Total Product Item Data</span>
          </div>
          <div>
            <span class="las la-file"></span>
          </div>
        </div>

        <div class="card-single">
          <div>
            <?php
            $sql = "select count(*) as total from combinedreview";
            $result = mysqli_query($mysqli, $sql);
            $data = mysqli_fetch_assoc($result);
            echo "<h1>" . $data['total'] . "</h1>";
            ?>
            <span>Total Combined Product Reviews</span>
          </div>
          <div>
            <span class="las la-star"></span>
          </div>
        </div>

        <div class="card-single">
          <div>
            <?php
            /* $sql = "select count(*) as total from user where accountstatus like 'Disabled'";
            $result = mysqli_query($mysqli, $sql);
            $data = mysqli_fetch_assoc($result);
            echo "<h1>" . $data['total'] . "</h1>"; */
            ?>
            <h2>Data Based On AmazonSG</h2>
          </div>
          <div>
            <span class="fa-brands fa-amazon"></span>
          </div>
        </div>

      </div>

      <br>

      <div class="card">
        <div class="card-header">
          <h2>User Account Management (Disabled Accounts)</h2>
          <a href="adminmanageuser.php"><button>See All Users<span class="las la-arrow-right"></span></button></a>
        </div>


        <div class="card-body" width="100%">
          <!-- <div class="table-responsive"> -->
          <div class="table table-bordered table-striped" style="text-align:left;" width="100%" cellspacing="0">
            <table width="100%">
              <thead>
                <tr>
                  <td>User Id</td>
                  <td>Username</td>
                  <td>First Name</td>
                  <td>Last Name</td>
                  <td>Phone Number</td>
                  <td>Email Address</td>
                  <td>Birth Date</td>
                  <td>Gender</td>
                  <td>User Type</td>
                  <td>Account Status</td>
                </tr>
              </thead>
              <tbody>
                <?php
                // Attempt select query execution
                $mysqli = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT * FROM user WHERE accountstatus LIKE 'Disabled'";
                if ($result = $mysqli->query($sql)) {
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_array()) {
                      echo "<tr>";
                      echo "<td>" . $row['userid'] . "</td>";
                      echo "<td>" . $row['username'] . "</td>";
                      echo "<td>" . $row['firstname'] . "</td>";
                      echo "<td>" . $row['lastname'] . "</td>";
                      echo "<td>" . $row['phonenumber'] . "</td>";
                      echo "<td>" . $row['emailaddress'] . "</td>";
                      echo "<td> " . $row['BirthDate'] . "</td>";
                      echo "<td> " . $row['Gender'] . "</td>";
                      echo "<td> " . $row['usertype'] . "</td>";
                      echo "<td> " . $row['accountstatus'] . "</td>";
                      echo "</tr>";
                    }
                    // Free result set
                    $result->free();
                  } else {
                    echo "<label class='error'>No records were found.</label>";
                  }
                } else {
                  echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
                }

                // Close connection
                $mysqli->close();
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>



      <!--  <div class="customers">
            <div class="card">
              <div class="card-header">
                <h3>Python Test</h3>

                  <button>See all<span class="las la-arrow-right"></span></button>
              </div>

              <div class="card-body">
                
                <?php
                /* $app_link = "https://fyp-project-recommender-system.herokuapp.com/app.py";
                  $app_data = file_get_contents($app_link);
                  echo "<br><br>" . $app_data;

                  $command =  escapeshellcmd('app.py');
                  $result = shell_exec($command);
                  echo $result;  */
                ?>

              </div>
            </div>
          </div> -->

    </main>
  </div>
</body>

</html>