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
  $query = "SELECT * FROM `combinedreview` WHERE CONCAT(`combinedreviewid`, `item_name`, `customername`, `rating_score`, `review_location`, `review_date` ,
     `createdby`) LIKE '%" . $searchValue . "%'";
  $search_result = filterTable($query);
  /* $count = "SELECT COUNT(*) from (SELECT * FROM `combinedreview` WHERE CONCAT(`combinedreviewid`, `item_name`, `customername`, `rating_score`, `review_location`, `review_date` ,
  `createdby`) LIKE '%" . $searchValue . "%') AS count ";
  $count_result = filterTableCount($count); */
  $sql = "select count(*) from (SELECT * FROM `combinedreview` WHERE CONCAT(`combinedreviewid`, `item_name`, `customername`, `rating_score`, `review_location`, `review_date` ,
  `createdby`) LIKE '%" . $searchValue . "%') as count";
  $result = mysqli_query($mysqli, $sql);
  $data = mysqli_fetch_assoc($result);
} else {
  if (empty(trim($_GET["searchValue"]))) {
    $query = "SELECT * FROM `combinedreview` ";
    $search_result = filterTable($query);
    /* $count = "SELECT COUNT(*) FROM `combinedreview`";
    $count_result = filterTableCount($count); */
    $sql = "select count(*) from combinedreview";
    $result = mysqli_query($mysqli, $sql);
    $data = mysqli_fetch_assoc($result);
  }
}

function filterTable($query)
{
  $connect = mysqli_connect("remotemysql.com", "y0vryqAKXK", "moMOpaacUP", "y0vryqAKXK");
  $filter_Result = mysqli_query($connect, $query);
  return $filter_Result;
}

function filterTableCount($count)
{
  $connect = mysqli_connect("remotemysql.com", "y0vryqAKXK", "moMOpaacUP", "y0vryqAKXK");
  $filter_Count = mysqli_query($connect, $count);
  return $filter_Count;
}


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>E-Commerce Insight (User)(Search Product Review Records)</title>
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
        E-Commerce Insight (User)(Search Product Review Records)
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
          <h2>Search Product Review Data</h2>
        </div>

        <form action="usersearchreview.php" method="GET">
          <div class="card-header">
            <div class="search-wrapper">
              <span class="las la-search"></span>
              <input type="search" name="searchValue" autocomplete="off" placeholder="Search here">
              <!-- <button type="submit" name="filterResults">Refresh</button> -->
            </div>
            <span>
              <button type="submit" name="search">Search</button>
              <button type="submit" name="refresh">Refresh</button>
            </span>
            <!-- <div class="search-wrapper">
                            <button type="submit" name="filterResults">Filter Empty Fields</button>
                            <button type="submit" name="search">Search</button>
                            <button type="submit" onclick="location.reload();">Refresh</button>
                        </div> -->
          </div>
        </form>

        <div class="card-body" width="100%">
          <!-- <div class="table-responsive"> -->
          <div>Number of Results: <?php echo $data['count'] ?></div>
          <br>
          <div class="table table-bordered table-striped" style="text-align:left;" width="100%" cellspacing="0">
            <table>
              <thead>
                <tr>
                  <td>Combined Review Id</td>
                  <td>Item Name</td>
                  <td>Customer Name</td>
                  <td>Rating Score</td>
                  <td>Review Location</td>
                  <td>Review Date</td>
                  <td>Created By</td>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = mysqli_fetch_array($search_result)) : ?>
                  <tr>
                    <td><?php echo $row['combinedreviewid']; ?></td>
                    <td><?php echo $row['item_name']; ?></td>
                    <td><?php echo $row['customername']; ?></td>
                    <td><?php echo $row['rating_score']; ?></td>
                    <td><?php echo $row['review_location']; ?></td>
                    <td><?php echo $row['review_date']; ?></td>
                    <td><?php echo $row['createdby']; ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>

</html>