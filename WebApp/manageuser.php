<?php
// Initialize the session
session_start();


// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["usertype"] !== "Admin" || $_SESSION["accountstatus"] !== "Active") {
  header("location: index.php");
  exit;
}

include "GlobalClass.php";
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>E-Commerce Insight (Admin)(Manage User)</title>
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
        E-Commerce Insight (Admin)(Manage User)
      </h2>
      
      <div class="search-wrapper">
        <span class="las la-search"></span>
        <input type="search" placeholder="Search here" />
      </div>

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

      <div class="card">
        <div class="card-header">
          <h2>User Account Management</h2>
        </div>
        <button><a href ="searchusers.php" >Search Users<span class="las la-arrow-right"></span></a></button>
        
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
                $sql = "SELECT * FROM user";
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
  </div>


  </main>
  </div>

</body>

</html>