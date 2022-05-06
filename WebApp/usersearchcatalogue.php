<?php
// Initialize the session
session_start();


// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["usertype"] !== "User" || $_SESSION["accountstatus"] !== "Active") {
  header("location: index.php");
  exit;
}

include "GlobalClass.php";
include "CatalogueConfig.php";
include "CombinedReviewConfig.php";
include "PageDataConfig.php";


if (isset($_GET["searchValue"]) && !empty(trim($_GET["searchValue"]))) {
    $searchValue = $_GET["searchValue"];
    // search in all table columns
    // using concat mysql function
    $query = "SELECT * FROM `user` WHERE CONCAT(`userid`, `username`, `firstname`, `lastname`, `phonenumber`, `emailaddress` ,
     `BirthDate`, `Gender` , `usertype`, `accountstatus`) LIKE '%" . $searchValue . "%'";
    $search_result = filterTable($query);
} else {
    if (empty(trim($_GET["searchValue"]))) {
        $query = "SELECT * FROM `user` ";
        $search_result = filterTable($query);
    }
}

function filterTable($query)
{
    $connect = mysqli_connect("remotemysql.com", "y0vryqAKXK", "moMOpaacUP", "y0vryqAKXK");
    $filter_Result = mysqli_query($connect, $query);
    return $filter_Result;
}


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>E-Commerce Insight (User)(View Records)</title>
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/54052f2f04.js" crossorigin="anonymous"></script>
  <script src="https://app.simplefileupload.com/buckets/4f2260bbeaf342ae7d7831862b11313c.js"></script>
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
        E-Commerce Insight (User)(View Records)
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

      <div class="card">
        <div class="card-header">
          <h2>Product Catalogue Data (Most Recent 10 Records)</h2>
          <a href="usersearchrecords.php"><button>Search Records<span class="las la-arrow-right"></span></button></a>
        </div>

        <div class="card-body" width="100%">
          <!-- <div class="table-responsive"> -->
          <div class="table table-bordered table-striped" style="text-align:left;" width="100%" cellspacing="0">
            <table>
              <thead>
                <tr>
                  <td>Catalogue Id</td>
                  <td>Product Url</td>
                  <td>Item Name</td>
                  <td>Item Price</td>
                  <td>Average Rating</td>
                  <td>No. Of Ratings</td>
                </tr>
              </thead>
              <tbody>
                <?php
                // Attempt select query execution
                $mysqli = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT * FROM cataloguedata ORDER BY catalogueid DESC LIMIT 10";
                if ($result = $mysqli->query($sql)) {
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_array()) {
                      echo "<tr>";
                      echo "<td>" . $row['catalogueid'] . "</td>";
                      echo "<td>" . $row['product_url'] . "</td>";
                      echo "<td>" . $row['item_name'] . "</td>";
                      echo "<td>" . $row['item_price'] . "</td>";
                      echo "<td>" . $row['average_rating'] . "</td>";
                      echo "<td>" . $row['number_of_ratings'] . "</td>";
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

      <br>

      <div class="card">
        <div class="card-header">
          <h2>Product Page Data (Most Recent 10 Records)</h2>
          <a href="usersearchrecords.php"><button>Search Records<span class="las la-arrow-right"></span></button></a>
        </div>

        <div class="card-body" width="100%">
          <!-- <div class="table-responsive"> -->
          <div class="table table-bordered table-striped" style="text-align:left;" width="100%" cellspacing="0">
            <table width="100%">
              <thead>
                <tr>
                  <td>Page Id</td>
                  <td>Review Url</td>
                  <td>Item Name</td>
                  <td>Average Rating</td>
                  <td>No. Of Ratings</td>
                  <td>Similar Items</td>
                  <td>Item Brand</td>
                </tr>
              </thead>
              <tbody>
                <?php
                // Attempt select query execution
                $mysqli = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT * FROM pagedata ORDER BY pageid DESC LIMIT 10";
                if ($result = $mysqli->query($sql)) {
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_array()) {
                      echo "<tr>";
                      echo "<td>" . $row['pageid'] . "</td>";
                      echo "<td>" . $row['review_url'] . "</td>";
                      echo "<td>" . $row['item_name'] . "</td>";
                      echo "<td>" . $row['average_rating'] . "</td>";
                      echo "<td>" . $row['number_of_ratings'] . "</td>";
                      echo "<td>" . $row['similar_items'] . "</td>";
                      echo "<td>" . $row['item_brand'] . "</td>";
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

      <br>

      <div class="card">
        <div class="card-header">
          <h2>Product Review Data (Most Recent 10 Records)</h2>
          <a href="usersearchrecords.php"><button>Search Records<span class="las la-arrow-right"></span></button></a>
        </div>

        <div class="card-body" width="100%">
          <!-- <div class="table-responsive"> -->
          <div class="table table-bordered table-striped" style="text-align:left;" width="100%" cellspacing="0">
            <table>
              <thead>
                <tr>
                  <td>Combined Id</td>
                  <td>Item Name</td>
                  <td>Customer Name</td>
                  <td>Rating Score</td>
                  <td>Review Location</td>
                  <td>Review Date</td>
                </tr>
              </thead>
              <tbody>
                <?php
                // Attempt select query execution
                $mysqli = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT * FROM combinedreview ORDER BY combinedreviewid DESC LIMIT 10";
                if ($result = $mysqli->query($sql)) {
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_array()) {
                      echo "<tr>";
                      echo "<td>" . $row['combinedreviewid'] . "</td>";
                      echo "<td>" . $row['item_name'] . "</td>";
                      echo "<td>" . $row['customername'] . "</td>";
                      echo "<td>" . $row['rating_score'] . "</td>";
                      echo "<td>" . $row['review_location'] . "</td>";
                      echo "<td>" . $row['review_date'] . "</td>";
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