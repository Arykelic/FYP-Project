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

/* $pagescraper = "";
$pagescraper_err = "";
$urlregex = "/((([A-Za-z]{3,9}:(?:\/\/)?)(?:[-;:&=\+\$,\w]+@)?[A-Za-z0-9.-]+|(?:www.|[-;:&=\+\$,\w]+@)[A-Za-z0-9.-]+)((?:\/[\+~%\/.\w-_]*)?\??(?:[-\+=&;%@.\w_]*)#?(?:[\w]*))?)/";
 */

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>E-Commerce Insight (User)(Product Page Scraper)</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/54052f2f04.js" crossorigin="anonymous"></script>
    <script src="https://app.simplefileupload.com/buckets/4f2260bbeaf342ae7d7831862b11313c.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.search-box input[type="text"]').on("keyup input", function() {
                /* Get input value on change */
                var inputVal = $(this).val();
                var resultDropdown = $(this).siblings(".result");
                if (inputVal.length) {
                    $.get("backend-search-page.php", {
                        producturl: inputVal
                    }).done(function(data) {
                        // Display the returned data in browser
                        resultDropdown.html(data);
                    });
                } else {
                    resultDropdown.empty();
                }
            });

            // Set search input value on click of result item
            $(document).on("click", ".result p", function() {
                $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
                $(this).parent(".result").empty();
            });
        });
    </script>
</head>

<body>
    <input type="checkbox" id="nav-toggle"></input>

    <div class="sidebar">
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="userhome.php" class="active"><span class="fa-solid fa-house"></span>
                        <span style="padding: 5px;">Home</span></a>
                </li>
                <li>
                    <a href="usercataloguescraper.php" class="active"><span class="fa-solid fa-book-atlas"></span>
                        <span style="padding: 12px; font-size: 96%;">Product Catalogue Scraper</span></a>
                </li>
                <li>
                    <a href="userpagescraper.php" class="active"><span class="fa-solid fa-file"></span>
                        <span style="padding: 15px;">Product Page Scraper</span></a>
                </li>
                <li>
                    <a href="userreviewscraper.php" class="active"><span class="fa-solid fa-star"></span>
                        <span style="padding: 5px;">Product Review Scraper</span></a>
                </li>
                <li>
                    <a href="userviewrecords.php" class="active"><span class="fa-solid fa-database"></span>
                        <span style="padding: 10px;">View Records</span></a>
                </li>
                <li>
                    <a href="https://ratingrecommendersystem.herokuapp.com" class="active"><span class="fa-solid fa-computer"></span>
                        <span style="padding: 0px;">Recommender System</span></a>
                </li>
                <li>
                    <a href="userupdatepersonalinfo.php"><span class="fa-solid fa-circle-user"></span>
                        <span style="padding: 5px;">Edit Personal Information</span></a>
                </li>
                <li>
                    <a href="userresetpassword.php"><span class="fa-solid fa-key"></span>
                        <span style="padding: 5px;">Reset Password</span></a>
                </li>
                <li>
                    <a href="Logout.php"><span class="fa-solid fa-right-from-bracket"></span>
                        <span style="padding: 5px;">Logout</span></a>
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
                E-Commerce Insight (User)(Product Page Scraper)
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
                    <h2>Product Page Item Web Scraper</h2>
                </div>

                <div class="card-body">
                    <h3>example: https://www.amazon.sg/Teals-Radiance-Vitamin-Citrus-Essential/dp/B07PHFRJGW/</h3>
                    <form action="userpagescraper.php" method="POST">
                        <div class="search-box">
                            <input type="text" autocomplete="off" placeholder="Enter a item page url here" name="pagescraper" required>
                            <!-- <label class="error"><?php echo $pagescraper_err; ?></label> -->
                            <br>
                            <div class="result"></div><br>
                            <input type="submit" value="Scrape Item Page" name="pagescrapebutton">
                        </div>
                    </form>

                    <?php

                    if (isset($_POST['pagescrapebutton'])) {
                        /* shell_exec("app.py");
                    echo "success"; */

                        /* $app_link = "https://fyp-project-recommender-system.herokuapp.com/app.py";
                    $app_data = file_get_contents($app_link);
                    echo "<br><br>" . $app_data; */

                        /* if (!preg_match($urlregex, $_POST["pagescraper"])) {
                            $pagescraper_err = "Please enter a valid url.";
                        } else {
                            $pagescraper = $_POST["pagescraper"];
                        }
                        $command = system("python AmazonSGCatalogueScraper.py" . $_GET["cataloguescraper"]);
                        if (empty($pagescraper_err)) { */

                        $pagescraper = $_POST["pagescraper"];
                        $createdby = $_SESSION["username"];
                        $command =  escapeshellcmd("python AmazonSGWebScraper/AmazonSGPageScraper.py '$pagescraper' '$createdby'");
                        $result = shell_exec($command);
                        echo "<div>";
                        echo "<pre>$result</pre>";
                        echo "</div>";

                        /* echo shell_exec("python3 AmazonSGCatalogueScraper.py '$input' 2>&1"); */

                        /* Different methods of passing through commands through shell */
                        /* $command =  escapeshellcmd('python /AmazonSGWebScraper/AmazonSGCatalogueScraper.py'); */
                        /* $command = system("python AmazonSGCatalogueScraper.py 'smartphones'"); */
                        /* $command = exec("python AmazonSGCatalogueScraper.py 'smartphones' 2>&1"); */
                        /* $command = passthru("python AmazonSGCatalogueScraper.py 'smartphones'"); */
                        /* } else {
                            echo '<script>alert("Please enter a valid url")</script>';
                        } */
                    }
                    ?>
                </div>
            </div>

            <br>

            <div class="card">
                <div class="card-header">
                    <h2>Product Page Data (Most Recent 20 Records)</h2>
                    <a href="usersearchpage.php"><button>Search Product Page Records<span class="las la-arrow-right"></span></button></a>
                </div>

                <div class="card-body" width="100%">
                    <!-- <div class="table-responsive"> -->
                    <div class="table table-bordered table-striped" style="text-align:left;" width="100%" cellspacing="0">
                        <table width="100%">
                            <thead>
                                <tr>
                                    <td>Page Id</td>
                                    <td>Review Url</td>
                                    <td>Image</td>
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
                                $sql = "SELECT * FROM pagedata ORDER BY pageid DESC LIMIT 20";
                                if ($result = $mysqli->query($sql)) {
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_array()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['pageid'] . "</td>";
                                            echo "<td>" . $row['review_url'] . "</td>";
                                            echo "<td>";
                                            $image = $row['image_url'];
                                            $imageData = base64_encode(file_get_contents($image));
                                            echo '<img src="data:image/jpeg;base64,' . $imageData . '">';
                                            echo "</td>";
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

        </main>
    </div>
</body>

</html>