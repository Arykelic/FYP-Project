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
            $sql="select count(*) as total from user";
            $result=mysqli_query($mysqli,$sql);
            $data=mysqli_fetch_assoc($result);
            echo $data['total'];
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
            // Attempt select query execution
            /* $mysqli = new mysqli($servername, $username, $password, $dbname);
            $sql = "SELECT COUNT(userid) FROM user WHERE usertype LIKE 'Admin'";
            if ($result = $mysqli->query($sql)) {
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                  echo "<h1>" . $result . "</h1>";
                }
                // Free result set
                $result->free();
              } else {
                echo "<label class='question-text'>No records were found.</label>";
              }
            } else {
              echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
            }

            // Close connection
            $mysqli->close(); */
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
            // Attempt select query execution
            /* $mysqli = new mysqli($servername, $username, $password, $dbname);
            $sql = "SELECT COUNT(userid) FROM user WHERE usertype LIKE 'User'";
            if ($result = $mysqli->query($sql)) {
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                  echo "<h1>" . $sql . "</h1>";
                }
                // Free result set
                $result->free();
              } else {
                echo "<label class='question-text'>No records were found.</label>";
              }
            } else {
              echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
            }

            // Close connection
            $mysqli->close(); */
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
            // Attempt select query execution
            /* $mysqli = new mysqli($servername, $username, $password, $dbname);
            $sql = "SELECT COUNT(userid) FROM user WHERE accountstatus LIKE 'Disabled'";
            if ($result = $mysqli->query($sql)) {
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                  echo "<h1>" . $result . "</h1>";
                }
                // Free result set
                $result->free();
              } else {
                echo "<label class='question-text'>No records were found.</label>";
              }
            } else {
              echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
            }

            // Close connection
            $mysqli->close(); */
            ?>
            <span>Disabled Accounts</span>
          </div>
          <div>
            <span class="las la-user-slash"></span>
          </div>
        </div>
      </div>

      <div class="recent-grid">
        <div class="projects">
          <div class="card">
            <div class="card-header">
              <h3>Recommender System</h3>

              <button>See all<span class="las la-arrow-right"></span></button>
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
                        <span class="status green"></span>
                        review
                      </td>
                    </tr>
                    <tr>
                      <td>Web Development</td>
                      <td>Frontend</td>
                      <td>
                        <span class="status orange"></span>
                        in progress
                      </td>
                    </tr>
                    <tr>
                      <td>Ushop app</td>
                      <td>Mobile team</td>
                      <td>
                        <span class="status red"></span>
                        pending
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="customers">
          <div class="card">
            <div class="card-header">
              <h3>Web Scraper</h3>

              <button>See all<span class="las la-arrow-right"></span></button>
            </div>

            <div class="card-body">
              <div class="customer">
                <div class="info">
                  <img src="user1.jpg" width="40px" height="40px" alt="">
                  <div>
                    <h4>Dominic</h4>
                    <small>User</small>
                  </div>
                </div>
                <div class="contact">
                  <span class="las la-user-circle"></span>
                  <span class="las la-comment"></span>
                  <span class="las la-phone"></span>
                </div>
              </div>

              <div class="customer">
                <div class="info">
                  <img src="user1.jpg" width="40px" height="40px" alt="">
                  <div>
                    <h4>Dominic</h4>
                    <small>User</small>
                  </div>
                </div>
                <div class="contact">
                  <span class="las la-user-circle"></span>
                  <span class="las la-comment"></span>
                  <span class="las la-phone"></span>
                </div>
              </div>

              <div class="customer">
                <div class="info">
                  <img src="user1.jpg" width="40px" height="40px" alt="">
                  <div>
                    <h4>Dominic</h4>
                    <small>User</small>
                  </div>
                </div>
                <div class="contact">
                  <span class="las la-user-circle"></span>
                  <span class="las la-comment"></span>
                  <span class="las la-phone"></span>
                </div>
              </div>
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


      </div>
    </main>
  </div>
</body>

</html>