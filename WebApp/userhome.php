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
                <input type="submit" value="Enter Recommender System" name="recommendersystem">
              </form>

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

                $command =  escapeshellcmd("sh setup.sh && streamlit run app.py --server.enableXsrfProtection=false 2>&1");
                $result = shell_exec($command);
                echo "<pre>";
                print_r($result);
                echo "</pre>";
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

              <h3>Catalogue Scraper</h3>

                  <form action="userhome.php" method="POST">
                    <input type="text" placeholder="Enter a catalogue search term here" name="cataloguescraper"><br>
                    <input type="submit" value="Scrape Catalogue Page" name="scrape">
                  </form>

                  <?php

                  if (isset($_POST['scrape'])) {
                    /* shell_exec("app.py");
                echo "success"; */

                    /* $app_link = "https://fyp-project-recommender-system.herokuapp.com/app.py";
              $app_data = file_get_contents($app_link);
              echo "<br><br>" . $app_data; */
              
                    /* $command = system("python AmazonSGCatalogueScraper.py" . $_GET["cataloguescraper"]); */
                    $command =  escapeshellcmd('python WebApp/AmazonSGCatalogueScraper.py');
                    $result = shell_exec($command);
                    echo "<pre>";
                    print_r($result);
                    echo "</pre>";

                    $input = $_POST["cataloguescraper"];
                    /* echo "<pre>";
                    print_r($input);
                    echo "</pre>"; */
                    /* $command = system("python AmazonSGCatalogueScraper.py '$input' "); */
                    /* $result = shell_exec($command); */

                    /* echo shell_exec("python3 AmazonSGCatalogueScraper.py '$input' 2>&1"); */

                    /* $command =  escapeshellcmd('python /AmazonSGWebScraper/AmazonSGCatalogueScraper.py'); */
                    /* $command = system("python AmazonSGCatalogueScraper.py 'smartphones'"); */
                    /* $command = exec("python AmazonSGCatalogueScraper.py 'smartphones' 2>&1"); */
                    /* $command = passthru("python AmazonSGCatalogueScraper.py 'smartphones'");
                    $output = shell_exec($command);
                    echo "<pre>";
                    print_r($output);
                    echo "</pre>"; */

                    /* $result = shell_exec("python AmazonSGCatalogueScraper.py '$input' 2>&1");
                    echo "<pre>";
                    print_r($result);
                    echo "</pre>"; */
                  }
                  ?>


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

        <!-- <div class="customers">
          <div class="card">
            <div class="card-header">
              <h3>Python Test</h3>

            </div>

            <div class="card-body">

              

            </div>

          </div>
        </div> -->

      </div>
    </main>
  </div>
</body>

</html>