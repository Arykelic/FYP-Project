<?php
// Initialize the session
session_start();
 
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["usertype"] !== "Admin" || $_SESSION["accountstatus"] !== "Active"){
  header("location: index.php");
  exit;
}

include "GlobalClass.php";
include "UserConfig.php";

// Define variables and initialize with empty values
$username = $password = $manufactureyear = $characteristics = $status = $cost = $overduecost = "";
$category_err = $brand_err = $manufactureyear_err = $characteristics_err = $status_err = $cost_err = $overduecost_err = "";

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
          E-Commerce Insight (Admin)(Add User)
        </h2>
        <div class="search-wrapper">
          <span class="las la-search"></span>
          <input type="search" placeholder="Search here"/>
        </div>

        <div class="user-wrapper">
          <span class="las la-user-circle fa-3x"></span>
          <div>
            <h4>Admin1</h4>
            <small>Super admin</small>
          </div>
        </div>
      </header>

      <main>
        <form class="row g-3">
          <div class="col-md-4">
            <label for="validationDefault01" class="form-label">First name</label>
            <input type="text" class="form-control" id="validationDefault01" required>
          </div>
          <div class="col-md-4">
            <label for="validationDefault02" class="form-label">Last name</label>
            <input type="text" class="form-control" id="validationDefault02" required>
          </div>
          <div class="col-md-4">
            <label for="validationDefaultUsername" class="form-label">Username</label>
            <div class="input-group">
              <span class="input-group-text" id="inputGroupPrepend2">@</span>
              <input type="text" class="form-control" id="validationDefaultUsername"  aria-describedby="inputGroupPrepend2" required>
            </div>
          </div>
          <div class="col-md-6">
            <label for="validationDefault03" class="form-label">Password</label>
            <input type="password" class="form-control" id="validationDefault03" required>
          </div>
          <div class="col-md-3">
            <label for="validationDefault04" class="form-label">State</label>
            <select class="form-select" id="validationDefault04" required>
              <option selected disabled value="">Choose...</option>
              <option>Admin</option>
              <option>User</option>
            </select>
          </div>


          <div class="col-12">
            <button class="btn btn-primary" type="submit">Add</button>
          </div>
        </form>

      </main>
    </div>
    
  </body>
</html>
