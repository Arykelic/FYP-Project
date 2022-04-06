<?php
session_start();
// Include config file
include "productconfig.php";
include "RentConfig.php";
include "UserConfig.php";
include "globalclass.php";
 
$category = $brand = $manufactureyear = $characteristics = $status = $cost = $overduecost = $rentfromdate = $renttodate = "";
$rentfromdate_err = $renttodate_err = "";

			
			
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Get hidden input value
			$productid = $_POST["productid"];
			$userid = $_SESSION["userid"];
			$rentstatus = "Ongoing";
			$rentreturndate = "";
			$paymentstatus = "Not Paid";
			$paymentamount = "";
			
	$category = test_input($_POST["category"]);
	$brand = test_input($_POST["brand"]);
	$manufactureyear = test_input($_POST["manufactureyear"]);
	$characteristics = test_input($_POST["characteristics"]);
	$status = test_input($_POST["status"]);
	$cost = test_input($_POST["cost"]);
	$overduecost = test_input($_POST["overduecost"]);
	
	
	
	
	$input_rentfromdate = test_input($_POST["rentfromdate"]);
    if(empty($input_rentfromdate)){
        $rentfromdate_err = "Please enter a date.";
    } else{
        $rentfromdate = $input_rentfromdate;
    }
	
	$input_renttodate = test_input($_POST["renttodate"]);
    if(empty($input_renttodate)){
        $renttodate_err = "Please enter a date.";
    } else{
        $renttodate = $input_renttodate;
    }
	
	
	if(empty($rentfromdate_err) && empty($renttodate_err)){
 
        $sql = "INSERT INTO renttable (userid, productid, rentfromdate, renttodate, rentstatus, paymentstatus, paymentamount) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if($stmt = $mysqli->prepare($sql)){
			
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("iissssd", $param_userid, $param_productid, $param_rentfromdate, $param_renttodate, $param_rentstatus, $param_paymentstatus, $param_paymentamount);
            
            // Set parameters
			
            $param_userid = $userid;
            $param_productid = $productid;
            $param_rentfromdate = $rentfromdate;
			$param_renttodate = $renttodate;
			$param_rentstatus = $rentstatus;
			$param_paymentstatus = $paymentstatus;
			$param_paymentamount = $paymentamount;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
				echo "<script>alert(Rent created sucessfully')</script>";
            } else{
                echo "Something went wrong. Please try again later.";
            }
			$stmt->close();
			
        }
		
		$mysqlli = new mysqli("localhost", "root", "", "musictogodb");
		$sqll = "UPDATE producttable SET status='Rented' WHERE productid=?";
		if($stmt = $mysqlli->prepare($sqll)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_productid);
            
            // Set parameters
            $param_productid = $productid;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                echo "<script>alert('Rent created sucessfully, The date to return the product is $renttodate and it will cost $$cost per day and $$overduecost for every late day returned')</script>";
            } else{
                echo "Something went wrong. Please try again later.";
            }
			$stmt->close();
			
        }
        // Close statement
        
        
    }
	// Close connection
    $mysqli->close();
}

	else{
	// Check existence of id parameter before processing further
	if(isset($_GET["productid"]) && !empty(trim($_GET["productid"]))){
    // Include config file
    $productid =  trim($_GET["productid"]);
    // Prepare a select statement
    $sql = "SELECT * FROM producttable WHERE productid = ?";
    
    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_productid);
        
        // Set parameters
        $param_productid = $productid;
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $result = $stmt->get_result();
            
            if($result->num_rows == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $result->fetch_array(MYSQLI_ASSOC);
                
                // Retrieve individual field value
				$category = $row["category"];
				$brand = $row["brand"];
				$manufactureyear = $row["manufactureyear"];
				$characteristics = $row["characteristics"];
				$status = $row["status"];
				$cost = $row["cost"];
				$overduecost = $row["overduecost"];
				
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: usererror.php");
                exit();
            }
            
        } else{
            echo "Something went wrong. Please try again later.";
        }
    }
    
    // Close connection
    //$mysqli->close();
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: usererror.php");
    exit();
}
	}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rent Product</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="stylesheet.css">
	
</head>
<body>

	<h1 class="header">Music-to-go (Rent Product)</h1>
	<div class= "block">
	<div class="form-style">
		<label class="naming">Rent Product</label>
		
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="question-text">
                            <label>Category</label>
                            <input type="text" name="category" class="input-field" readonly value="<?php echo $category; ?>">
                            
                        </div>
                        <div class="question-text">
                            <label>Brand</label>
                            <input type="text" name="brand" class="input-field" readonly value="<?php echo $brand; ?>">
                           
                        </div>
                        <div class="question-text ">
                            <label>Year Of Manufacture</label>
                            <input type="number" name="manufactureyear" class="input-field" min="1901" max="2021" readonly value="<?php echo $manufactureyear; ?>">
                            
                        </div>
						<div class="question-text ">
                            <label>Characteristics</label>
                            <input type="text" name="characteristics" class="input-field" readonly value="<?php echo $characteristics; ?>">
                           
                        </div>
						<div class="question-text ">
                            <label>Status</label>
                            <input type="text" name="status" class="input-field" readonly value="<?php echo $status; ?>">
                           
                        </div>
						<div class="question-text ">
                            <label>Cost Per Day</label>
                            <input type="number" name="cost" class="input-field" readonly value="<?php echo $cost; ?>">
                            
                        </div>
						<div class="question-text ">
                            <label>Overdue Cost Per Day</label>
                            <input type="number" name="overduecost" class="input-field" readonly value="<?php echo $overduecost; ?>">
                        </div>
						<div class="question-text ">
                            <label>Start Date</label>
                            <input type="date" name="rentfromdate" class="input-field">
							<label class="error"><?php echo $rentfromdate_err;?></label>
                        </div>
						<div class="question-text ">
                            <label>End Date</label>
                            <input type="date"  name="renttodate" class="input-field">
							<label class="error"><?php echo $renttodate_err;?></label>
                        </div>
						<input type="hidden" name="productid" value="<?php echo $productid; ?>"/>
						<br>
						<div class="question-text">
							<input type="submit" class="submit" value="Rent">
						</div>
		</form>
						<button><a href="Userhome.php" class="submit">Back</a></button>
						
	</div>
	</div> 
</body>
</html>