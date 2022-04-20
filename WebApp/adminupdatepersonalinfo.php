<?php
// Initialize the session
session_start();
 
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["usertype"] !== "Admin" || $_SESSION["accountstatus"] !== "Active"){
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
            E-Commerce Insight (Admin)(Update Personal Information)
          </h2>
          <div class="search-wrapper">
            <span class="las la-search"></span>
            <input type="search" placeholder="Search here"/>
          </div>

          <div class="user-wrapper">
            <span class="las la-user-circle fa-3x"></span>
            <div>
            <h4> <?php echo htmlspecialchars($_SESSION["username"]); ?> </h4>
            <small><?php echo htmlspecialchars($_SESSION["firstname"]); ?></small>
            </div>
          </div>
        </header>

      <main>
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

      </main>
      
    </div>
      
  </body>
</html>