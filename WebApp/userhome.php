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
include "PageDataConfig.php"

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
          <a href="userhome.php" class="active"><i class="fa-solid fa-house"></i>
            <span>Home</span></a>
        </li>
        <li>
          <a href="userupdatepersonalinfo.php"><span class="las la-user-circle"></span>
            <span>Edit Personal Information</span></a>
        </li>
        <li>
          <a href="userresetpassword.php"><span class="las la-key"></span>
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
            <span>Total Catalogue Items</span>
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
            <span>Total Item Pages</span>
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
            <span>Total Combined Reviews</span>
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
              <h2> Recommender System</h2>

            </div>

            <div class="card-body">

              <form method="POST">
              <!-- WORST CASE SCENARIO -->
              <input type="submit" value="Enter Recommender System" name="recommendersystem">
              </form>
              <button class="backbutton" value="Forward"><a href="https://ratingrecommendersystem.herokuapp.com">Forward</a></button>

              <?php



              if (isset($_POST['recommendersystem'])) {
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


                $command =  escapeshellcmd("python apprunner.py");
                $result = shell_exec($command);
                /* echo "<div>";
                print_r($result);
                echo "</div>"; */
                header("Location:appruner.py");
              }

              ?>

            </div>


          </div>
        </div>

        <div class="projects">
          <div class="card">
            <div class="card-header">
              <h2>Web Scraper</h2>

            </div>

            <div class="card-body">
              <h3>Catalogue Scraper (delimit search terms with a "+")(First 50 Records)</h3>
              <form action="userhome.php" method="POST">
                <input type="text" placeholder="Enter a catalogue search term here" name="cataloguescraper"><br>
                <input type="submit" value="Scrape Catalogue Page" name="cataloguescrapebutton">
              </form>

              <?php

              if (isset($_POST['cataloguescrapebutton'])) {
                /* shell_exec("app.py");
                    echo "success"; */

                /* $app_link = "https://fyp-project-recommender-system.herokuapp.com/app.py";
                    $app_data = file_get_contents($app_link);
                    echo "<br><br>" . $app_data; */

                /* $command = system("python AmazonSGCatalogueScraper.py" . $_GET["cataloguescraper"]); */
                $catalogueinput = $_POST["cataloguescraper"];
                $command =  escapeshellcmd("python AmazonSGWebScraper/AmazonSGCatalogueScraper.py '$catalogueinput'");
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
              }
              ?>
            </div>

            <div class="card-body">
              <h3>Page Scraper (enter a item page url)</h3>
              <form action="userhome.php" method="POST">
                <input type="text" placeholder="Enter a item page url here" name="pagescraper"><br>
                <input type="submit" value="Scrape Item Page" name="pagescrapebutton">
              </form>

              <?php

              if (isset($_POST['pagescrapebutton'])) {
                /* shell_exec("app.py");
                    echo "success"; */

                /* $app_link = "https://fyp-project-recommender-system.herokuapp.com/app.py";
                    $app_data = file_get_contents($app_link);
                    echo "<br><br>" . $app_data; */

                /* $command = system("python AmazonSGCatalogueScraper.py" . $_GET["cataloguescraper"]); */
                $pageinput = $_POST["pagescraper"];
                $command =  escapeshellcmd("python AmazonSGWebScraper/AmazonSGPageScraper.py '$pageinput'");
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
              }
              ?>
            </div>

            <div class="card-body">
              <h3>Review Scraper (enter a review page url)(First 50 Reviews)</h3>
              <form action="userhome.php" method="POST">
                <input type="text" placeholder="Enter a review page url here" name="reviewscraper"><br>
                <input type="submit" value="Scrape Review Page" name="reviewscrapebutton">
              </form>

              <?php

              if (isset($_POST['reviewscrapebutton'])) {
                /* shell_exec("app.py");
                    echo "success"; */

                /* $app_link = "https://fyp-project-recommender-system.herokuapp.com/app.py";
                    $app_data = file_get_contents($app_link);
                    echo "<br><br>" . $app_data; */

                /* $command = system("python AmazonSGCatalogueScraper.py" . $_GET["cataloguescraper"]); */
                $reviewinput = $_POST["reviewscraper"];
                $command =  escapeshellcmd("python AmazonSGWebScraper/AmazonSGReviewScraper.py '$reviewinput'");
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
              }
              ?>
            </div>

          </div>
        </div>

      </div>
    </main>
  </div>
</body>

</html>