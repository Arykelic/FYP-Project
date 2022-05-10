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



if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
  $page_no = $_GET['page_no'];
} else {
  $page_no = 1;
}
$total_records_per_page = 20;
$offset = ($page_no - 1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";

$result_count = mysqli_query(
  $mysqli,
  "SELECT COUNT(*) As total_records FROM `cataloguedata`"
);
$total_records = mysqli_fetch_array($result_count);
$total_records = $total_records['total_records'];
$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1; // total pages minus 1


if (isset($_GET["searchValue"]) && !empty(trim($_GET["searchValue"]))) {
  $searchValue = $_GET["searchValue"];
  // search in all table columns
  // using concat mysql function
  $query = "SELECT * FROM `cataloguedata` WHERE CONCAT(`catalogueid`, `product_url`, `item_name`, `item_price`, `average_rating` ,
     `number_of_ratings`, `createdby`, `search_term`) LIKE '%" . $searchValue . "%' LIMIT $offset,$total_records_per_page ";
  $search_result = filterTable($query);
  /* $count = "SELECT COUNT(*) from (SELECT * FROM `cataloguedata` WHERE CONCAT(`catalogueid`, `product_url`, `item_name`, `item_price`, `average_rating` ,
  `number_of_ratings`, `createdby`, `search_term`) LIKE '%" . $searchValue . "%') AS count";
  $count_result = filterTableCount($count); */

  $sql = "SELECT COUNT(*) from (SELECT * FROM `cataloguedata` WHERE CONCAT(`catalogueid`, `product_url`, `item_name`, `item_price`, `average_rating` ,
  `number_of_ratings`, `createdby`, `search_term`) LIKE '%" . $searchValue . "%') as count";
  $result = mysqli_query($mysqli, $sql);
  $data = mysqli_fetch_assoc($result);
  $count = implode(",", $data);
} else {
  if (empty(trim($_GET["searchValue"]))) {
    $query = "SELECT * FROM `cataloguedata` ";
    $search_result = filterTable($query);
    /* $count = "SELECT COUNT(*) FROM `cataloguedata`";
    $count_result = filterTableCount($count);  */
    $sql = "SELECT COUNT(*) from cataloguedata";
    $result = mysqli_query($mysqli, $sql);
    $data = mysqli_fetch_assoc($result);
    $count = implode(",", $data);
  }
}

function filterTable($query)
{
  $connect = mysqli_connect("remotemysql.com", "y0vryqAKXK", "moMOpaacUP", "y0vryqAKXK");
  $filter_Result = mysqli_query($connect, $query);
  return $filter_Result;
}

/* function filterTableCount($count)
{
  $connect = mysqli_connect("remotemysql.com", "y0vryqAKXK", "moMOpaacUP", "y0vryqAKXK");
  $filter_Count = mysqli_query($connect, $count);
  return $filter_Count;
} */


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>E-Commerce Insight (User)(Search Product Catalogue Records)</title>
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/54052f2f04.js" crossorigin="anonymous"></script>
  <script src="https://app.simplefileupload.com/buckets/4f2260bbeaf342ae7d7831862b11313c.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
          <a href="https://ratingrecommendersystem.herokuapp.com" class="active"><span class="fa-solid fa-computer"></span>
            <span>Recommender System</span></a>
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
        <img src="elogofinal.png" style="position:relative; top: 10px; height: 40px; width: 40px;"></img>
        E-Commerce Insight (User)(Search Product Catalogue Records)
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
          <h2>Search Product Catalogue Data</h2>
        </div>

        <form action="usersearchcatalogue.php" method="GET">
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
          <div>Number of Results: <?php echo $count ?></div>
          <br>

          <div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
            <strong>Page <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
            <ul class="pagination">
              <?php if ($page_no > 1) {
                echo "<li><a href='?page_no=1'>First Page</a></li>";
              } ?>

              <li <?php if ($page_no <= 1) {
                    echo "class='disabled'";
                  } ?>>
                <a <?php if ($page_no > 1) {
                      echo "href='?page_no=$previous_page'";
                    } ?>>Previous</a>
              </li>

              <li <?php if ($page_no >= $total_no_of_pages) {
                    echo "class='disabled'";
                  } ?>>
                <a <?php if ($page_no < $total_no_of_pages) {
                      echo "href='?page_no=$next_page'";
                    } ?>>Next</a>
              </li>

              <?php if ($page_no < $total_no_of_pages) {
                echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
              }

              if ($total_no_of_pages <= 10) {
                for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                  if ($counter == $page_no) {
                    echo "<li class='active'><a>$counter</a></li>";
                  } else {
                    echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                  }
                }
              } elseif ($total_no_of_pages > 10) {
                if ($page_no <= 4) {
                  for ($counter = 1; $counter < 8; $counter++) {
                    if ($counter == $page_no) {
                      echo "<li class='active'><a>$counter</a></li>";
                    } else {
                      echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                    }
                  }
                  echo "<li><a>...</a></li>";
                  echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                  echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                  echo "<li><a href='?page_no=1'>1</a></li>";
                  echo "<li><a href='?page_no=2'>2</a></li>";
                  echo "<li><a>...</a></li>";
                  for (
                    $counter = $page_no - $adjacents;
                    $counter <= $page_no + $adjacents;
                    $counter++
                  ) {
                    if ($counter == $page_no) {
                      echo "<li class='active'><a>$counter</a></li>";
                    } else {
                      echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                    }
                  }
                  echo "<li><a>...</a></li>";
                  echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
                  echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                } else {
                  echo "<li><a href='?page_no=1'>1</a></li>";
                  echo "<li><a href='?page_no=2'>2</a></li>";
                  echo "<li><a>...</a></li>";
                  for (
                    $counter = $total_no_of_pages - 6;
                    $counter <= $total_no_of_pages;
                    $counter++
                  ) {
                    if ($counter == $page_no) {
                      echo "<li class='active'><a>$counter</a></li>";
                    } else {
                      echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                    }
                  }
                }
              }
              ?>
            </ul>
          </div>

          <div class="table table-bordered table-striped" style="text-align:left; table-layout: fixed; word-break: break-all;" width="100%" cellspacing="0">
            <table>
              <thead>
                <tr>
                  <td width="7%">Catalogue Id</td>
                  <td>Product Url</td>
                  <td>Image</td>
                  <td>Item Name</td>
                  <td>Item Price</td>
                  <td width="11%">Average Rating</td>
                  <td>No. Of Ratings</td>
                  <td>Created By</td>
                  <td>Search Term</td>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = mysqli_fetch_array($search_result)) : ?>
                  <tr>
                    <td width="10%"><?php echo $row['catalogueid']; ?></td>
                    <td width="15%"><?php echo $row['product_url']; ?></td>
                    <td width="10%">
                      <?php
                      $image = $row['image_url'];
                      $imageData = base64_encode(file_get_contents($image));
                      echo '<img src="data:image/jpeg;base64,' . $imageData . '">';
                      ?>
                    </td>
                    <td width="15%"><?php echo $row['item_name']; ?></td>
                    <td width="10%"><?php echo $row['item_price']; ?></td>
                    <td width="10%"><?php echo $row['average_rating']; ?></td>
                    <td width="10%"><?php echo $row['number_of_ratings']; ?></td>
                    <td width="10%"><?php echo $row['createdby']; ?></td>
                    <td width="10%"><?php echo $row['search_term']; ?></td>
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