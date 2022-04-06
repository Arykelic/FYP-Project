<?php
// Initialize the session
session_start();
 
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["usertype"] !== "admin") {
    header("location: login.php");
    exit;
}

include "GlobalClass.php";
include "UserConfig.php";
include "RentConfig.php";
include "ProductConfig.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Music-to-go</title>
    <link rel="stylesheet" href="stylesheet.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
</head>
<body>
    <h1 class="header">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to Music-to-go (Admin)</h1>
	<div class="form-style">
	<div id="logout" class="question-text">
		<button><a href="logout.php" class="submit">Logout</a></button>
    </div>
	</div>
	
	
	<div class="form-style">
	<label class="naming">Products</label>
	</div>
	<div class="block">
	<div class="form-style">
	<div class="question-text-add">
		<button><a href="CreateProduct.php" class="submit">Add New Product</a></button>
		<button><a href="SearchProduct.php" class="submit">Search For A Product</a></button>
		<button><a href="ViewAvailableProduct.php" class="submit">View Available Products</a></button>
		<button><a href="ViewRentedProduct.php" class="submit">View Rented Products</a></button>
		<button><a href="ViewOverdueProduct.php" class="submit">View Overdue Products</a></button>
    </div>
	
	<br>
	<table class="table table-bordered table-striped" style="text-align:left;" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th width="5%">Product Id</th>
				<th>Category</th>
				<th>Brand</th>
				<th width="10%">Year Of Manufacture</th>
				<th>Characteristics</th>
				<th>Status</th>
				<th width="10%">Cost/Day</th>
				<th width="10%">Overdue Cost/Day</th>
				<th width="5%">Action</th>
			</tr>
		</thead>
		<tbody>
		<?php
                    // Attempt select query execution
					$mysqli = new mysqli($servername, $username, $password, $dbname);
                    $sql = "SELECT * FROM producttable";
                    if($result = $mysqli->query($sql)){
                        if($result->num_rows > 0){
								while($row = $result->fetch_array()){
                                    echo "<tr>";
                                        echo "<td>" . $row['productid'] . "</td>";
                                        echo "<td>" . $row['category'] . "</td>";
                                        echo "<td>" . $row['brand'] . "</td>";
                                        echo "<td>" . $row['manufactureyear'] . "</td>";
										echo "<td>" . $row['characteristics'] . "</td>";
										echo "<td>" . $row['status'] . "</td>";
										echo "<td> $" . $row['cost'] . "</td>";
										echo "<td> $" . $row['overduecost'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='ViewProduct.php?productid=". $row['productid'] ."' title='View Product' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            echo "<a href='EditProduct.php?productid=". $row['productid'] ."' title='Update Product' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            // Free result set
                            $result->free();
                        } else{
                            echo "<label class='question-text'>No records were found.</label>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
                    }
                    
                    // Close connection
                    $mysqli->close();
                    ?>
		</tbody>
	</table>
	</div>
	</div>

	<div class="form-style">
	<label class="naming">Rentals</label>
	</div>
	<div class="block">
	<div class="form-style">

	<br>
	<table class="table table-bordered table-striped" style="text-align:left;" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th>Rent Id</th>
				<th>User Id</th>
				<th>Product Id</th>
				<th>Rent Start Date</th>
				<th>Rent End Date</th>
				<th>Returned Date</th>
				<th>Rent Status</th>
				<th>Payment Status</th>
				<th>Payment Amount</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
		<?php
                    // Attempt select query execution
					$mysqli = new mysqli($servername, $username, $password, $dbname);
                    $sql = "SELECT * FROM renttable";
                    if($result = $mysqli->query($sql)){
                        if($result->num_rows > 0){
								while($row = $result->fetch_array()){
                                    echo "<tr>";
                                        echo "<td>" . $row['rentid'] . "</td>";
                                        echo "<td>" . $row['userid'] . "</td>";
                                        echo "<td>" . $row['productid'] . "</td>";
                                        echo "<td>" . $row['rentfromdate'] . "</td>";
										echo "<td>" . $row['renttodate'] . "</td>";
										echo "<td>" . $row['rentreturndate'] . "</td>";
										echo "<td>" . $row['rentstatus'] . "</td>";
										echo "<td>" . $row['paymentstatus'] . "</td>";
										echo "<td> $" . $row['paymentamount'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='EditPaymentStatus.php?rentid=". $row['rentid'] ."' title='Update Payment Status' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            // Free result set
                            $result->free();
                        } else{
                            echo "<label class='question-text'>No records were found.</label>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
                    }
                    
                    // Close connection
                    $mysqli->close();
                    ?>
		</tbody>
	</table>
	</div>
	</div>
	
</body>
</html>