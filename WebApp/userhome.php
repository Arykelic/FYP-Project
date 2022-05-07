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

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>E-Commerce Insight (User)(Home)</title>
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
        E-Commerce Insight (User)(Home)
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

      <div class="r-grid">
        <div class="projects">
          <div class="card">
            <div class="card-header">
              <h2>Recommender System</h2>

            </div>

            <div class="card-body">

              <!-- <form method="POST"> -->
              <!-- WORST CASE SCENARIO -->
              <!-- <input type="submit" value="Enter Recommender System" name="recommendersystem"></input>
              </form> -->

              <h2>Product Recommender System (User Based Collaborative Filtering)</h2>
              <img src="RecommenderSystemExample.jpg" alt="RecommenderSystemExample"></img>
              <h3>Our Product Recommender System serves as a recommender and dashboarding tool aimed at helping Small-Medium Enterprises (SMEs) analyse and
                  breakdown key trends within eCommerce Markets to help them make informed decisions about the current pool of users and products.
                  Receive key insights about the performance of various products and their key metrics which correlate to positive
                  ratings to enhance and improve your own products before launch</h3>
              <h4>Welcome to our system!! Click on the button below to enter our system</h4>
              <a href="https://ratingrecommendersystem.herokuapp.com"><input type="submit" value="Enter Recommender System" name="recommendersystem"></input></a>

              <?php

              /* if (isset($_POST['recommendersystem'])) { */
              /* shell_exec("app.py");
                echo "success"; */

              /* $app_link = "https://fyp-project-recommender-system.herokuapp.com/app.py";
              $app_data = file_get_contents($app_link);
              echo "<br><br>" . $app_data; */

              /* $command =  escapeshellcmd('sh setup.sh && streamlit run app.py --server.enableXsrfProtection=false');
                $result = shell_exec($command);
                echo $result; */

              /* $command =  escapeshellcmd('streamlit run app.py --server.enableXsrfProtection=false');
                $result = shell_exec($command);
                echo $result; */

              /* Testing the procfile variables */

              /*  
                web: python AmazonSGWebScraper/AmazonSGCatalogueScraper.py
                web: vendor/bin/heroku-php-apache2 WebApp/
                web: sh setup.sh && streamlit run WebApp/app.py
                myworker:  python dowork.py
                */

              /* $command = system("python AmazonSGCatalogueScraper.py" . $_POST["cataloguescraper"]); */
              /* $command =  escapeshellcmd('python /AmazonSGWebScraper/AmazonSGCatalogueScraper.py'); */
              /* $result = shell_exec($command);
                echo "<pre>";
                print_r($result);
                echo "</pre>"; */

              /* This command has a port is being used error which means sh setup.sh should work but is contested with the current apache port
                (find a way to close apache port and start streamlit port)
                $command =  passthru("sh setup.sh && streamlit run app.py --server.enableXsrfProtection=false"); */

              /* $command =  escapeshellcmd("sh setup.sh && streamlit run app.py --server.enableXsrfProtection=false"); */


              /* $command =  escapeshellcmd("python apprunner.py");
                $result = shell_exec($command);
                echo "<div>";
                print_r($result);
                echo "</div>";
                header("Location:appruner.py");
              } */

              ?>

            </div>

            <br>

            <div class="card-body">
              <h2>Product Catalogue Scraper</h2>
              <img src="ProductCatalogueScraperExample.jpg" alt="ProductCatalogueScraperExample"></img>
              <h3>Our Product Catalogue Scraper aims to scrape generalised information about the products that appear through a search term within AmazonSG aimed to help give
                our users a general idea of what products customers are seeing when they search for products. A key metric for the catalogue scraper is the search term where 
                finding through other analytical tools what customers are searching for can yield similar results and give our users a better understanding of what products
                eCommerce customers are presented. For example, the image above shows the result set from the search term samsung smartphones inside the AmazonSG marketplace and
                the various products that are presented to the user.
              </h3>
              <h4>Navigate the toolbar on the left or click the button below to see it in action!</h4>
              <a href="usercataloguescraper.php"><input type="submit" value="Enter Product Catalogue" name="productcatalogue"></input></a>
            </div>

            <br>

            <div class="card-body">
              <h2>Product Review Scraper</h2>
              <img src="ProductReviewScraperExample.jpg" alt="ProductReviewScraperExample"></img>
              <h3>Our Product Review Scraper aims to scrape information about the individual products review within each product page that has reviews attached to it.
                This is the bread and butter of our recommender system and the key information that will be used to train and build the user-based collaborative
                filtering algorithm to help accurately predict the rating score a user m will give to an item n.
                The image above shows the various types of reviews that will be scraped for the product, some key factors of the reviews is that it consists of reviews from
                other regions across the world as well as reviews from singapore. While there are some advantages and disadvantages of this, a key advantage of this 
                is that we are able to compare the reviews and opinions of a global reviewer base that takes into account a wider range of preferences and opinions.
              </h3>
              <h4>Navigate the toolbar on the left or click the button below to see it in action!</h4>
              <a href="userreviewscraper.php"><input type="submit" value="Enter Product Review" name="productreview"></input></a>
            </div>


          </div>
        </div>

        <div class="projects">
          <div class="card">
            <div class="card-header">
              <h2>Web Scraper</h2>
            </div>

            <div class="card-body">
              <h2>What is a Web Scraper??</h2>
              <img src="WebScraperExample.png" alt="WebScraperExample"></img>
              <h3>The job of the web scraper is to help retrieve and parse data from any online websites and material such as online storefronts and marketplaces.
                The web scraper parses the data from html based websites to convert into the desired output format that can be utilized by systems to process that data
                by Extracting-Transforming-Loading (ETL) the data into meaningful information to help make informed decisions.
              </h3>
              <img src="WebScraperExample2.png" alt="WebScraperExample2"></img>
              <h2>Future/Potential Improvements and Developments</h2>
              <h3>Future improvements that are being explored for the system is to potentially include scrapers for other eCommerce platforms and improving the consistency
                and features of the current scrapers to scrape pages such as the high fluctuation items inside the AmazonSG marketplace and additional features
                 for the recommender system to capitalize on available data. Proper error handling messages and improved usability is another field where improvements can
                 also be made.
              </h3>
            </div>

            <br>

            <div class="card-body">
              <h2>Product Page Scraper</h2>
              <img src="ProductPageScraperExample.jpg" alt="ProductPageScraperExample"></img>
              <h3>Our Product Page Scraper aims to scrape more detailed information about the products within the marketplace to give deeper insight about the products
                that can help be the basis of the analysis of why certain products do better than others. For example, the image above shows the product page of a samsung
                galaxy s22 ultra and the detailed information about the product.
              </h3>
              <h4>Navigate the toolbar on the left or click the button below to see it in action!</h4>
              <a href="userpagescraper.php"><input type="submit" value="Enter Product Page" name="productpage"></input></a>
            </div>

          

          </div>
        </div>
      </div>
    </main>
  </div>
</body>

</html>