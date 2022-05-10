<?php
// Initialize the session
session_start();


// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["usertype"] !== "Admin" || $_SESSION["accountstatus"] !== "Active") {
    header("location: index.php");
    exit;
}
include "UserConfig.php";
include "GlobalClass.php";


if (isset($_GET["searchValue"]) && !empty(trim($_GET["searchValue"]))) {
    $searchValue = $_GET["searchValue"];
    // search in all table columns
    // using concat mysql function
    $query = "SELECT * FROM `user` WHERE CONCAT(`userid`, `username`, `firstname`, `lastname`, `phonenumber`, `emailaddress` ,
     `BirthDate`, `Gender` , `usertype`, `accountstatus`) LIKE '%" . $searchValue . "%'";
    $search_result = filterTable($query);
    $sql = "SELECT COUNT(*) from (SELECT * FROM `user` WHERE CONCAT(`userid`, `username`, `firstname`, `lastname`, `phonenumber`, `emailaddress` ,
    `BirthDate`, `Gender` , `usertype`, `accountstatus`) LIKE '%" . $searchValue . "%') as count";
    $result = mysqli_query($mysqli, $sql);
    $data = mysqli_fetch_assoc($result);
    $count = implode(",", $data);
} else {
    if (empty(trim($_GET["searchValue"]))) {
        $query = "SELECT * FROM `user` ";
        $search_result = filterTable($query);
        $sql = "SELECT COUNT(*) from user";
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

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>E-Commerce Insight (Admin)(Search User)</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/54052f2f04.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</head>


<body>
    <input type="checkbox" id="nav-toggle"></input>

    <div class="sidebar">
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="adminhome.php" class="active"><span class="fa-solid fa-house"></span>
                        <span>Home</span></a>
                </li>
                <li>
                    <a href="adminmanageuser.php"><span class="fa-solid fa-users"></span>
                        <span>Manage Users</span></a>
                </li>
                <li>
                    <a href="adminadduser.php"><span class="fa-solid fa-user-plus"></span>
                        <span>Add Users</span></a>
                </li>
                <li>
                    <a href="adminupdatepersonalinfo.php"><span class="fa-solid fa-circle-user"></span>
                        <span>Edit Personal Information</span></a>
                </li>
                <li>
                    <a href="adminresetpassword.php"><span class="fa-solid fa-key"></span>
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
                <img src="elogofinal.png" style="position:relative; top: 10px; height: 40px; width: 40px;"></img>
                E-Commerce Insight (Admin)(Search User)
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
                    <h2>Search User</h2>
                </div>

                <form action="adminsearchuser.php" method="GET">
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
                    <div>Number of Results: <?php echo $count ?></div>
                    <br>
                    <div class="table table-bordered table-striped" style="text-align:left;" width="100%" cellspacing="0">
                        <table width="100%">
                            <thead>
                                <tr>
                                    <td>User Id</td>
                                    <td>Username</td>
                                    <td>First Name</td>
                                    <td>Last Name</td>
                                    <td>Phone Number</td>
                                    <td>Email Address</td>
                                    <td>Birth Date</td>
                                    <td>Gender</td>
                                    <td>User Type</td>
                                    <td>Account Status</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_array($search_result)) : ?>
                                    <tr>
                                        <td><?php echo $row['userid']; ?></td>
                                        <td><?php echo $row['username']; ?></td>
                                        <td><?php echo $row['firstname']; ?></td>
                                        <td><?php echo $row['lastname']; ?></td>
                                        <td><?php echo $row['phonenumber']; ?></td>
                                        <td><?php echo $row['emailaddress']; ?></td>
                                        <td><?php echo $row['BirthDate']; ?></td>
                                        <td><?php echo $row['Gender']; ?></td>
                                        <td><?php echo $row['usertype']; ?></td>
                                        <td><?php echo $row['accountstatus']; ?></td>
                                        <?php
                                        echo "<td>";
                                        echo "<a href='adminviewuser.php?userid=" . $row['userid'] . "' title='View User' data-toggle='tooltip'><i class='fa-solid fa-eye'></i></a>";
                                        echo "<a href='adminupdateuser.php?userid=" . $row['userid'] . "' title='Update User' data-toggle='tooltip'><i class='fa-solid fa-pen-to-square'></i></a>";
                                        echo "<a href='admindeleteuser.php?userid=" . $row['userid'] . "' title='Delete User' data-toggle='tooltip'><i class='fa-solid fa-trash'></i></a>";
                                        echo "</td>";
                                        ?>
                                    </tr>
                                <?php endwhile; ?>
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