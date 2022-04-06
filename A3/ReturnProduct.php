<?php
// Include config file
// Initialize the session
session_start();
include "productconfig.php";
include "rentconfig.php";
include "globalclass.php";
// Define variables and initialize with empty values
$rentreturndate = "";
$rentreturndate_err = "";
 

 
// Processing form data when form is submitted
if(isset($_POST["rentid"]) && !empty($_POST["rentid"])){
    // Get hidden input value
    $rentid = $_POST["rentid"];
    
	$userid = test_input($_POST["userid"]);
	$productid = test_input($_POST["productid"]);
	$rentfromdate = test_input($_POST["rentfromdate"]);
	$renttodate = test_input($_POST["renttodate"]);
	$rentstatus = test_input($_POST["rentstatus"]);
	$paymentstatus = test_input($_POST["paymentstatus"]);
	$paymentamount = test_input($_POST["paymentamount"]);
	
	
	//Validate status
	$input_rentreturndate = test_input($_POST["rentreturndate"]);
    if(empty($input_rentreturndate)){
        $rentreturndate_err = "Please enter a return date status.";     
    } else{
        $rentreturndate = $input_rentreturndate;
    }
	
    
    // Check input errors before inserting in database
    if(empty($rentreturndate_err)){
        // Prepare an update statement
        $sql = "UPDATE renttable SET rentreturndate =?, rentstatus ='Returned'  WHERE rentid=?";
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_rentreturndate, $param_rentid);
            
            // Set parameters

			$param_rentreturndate = $rentreturndate;
			$param_rentid = $rentid;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                echo "<script>alert('Product Returned sucessfully, The product is returned $renttodate - $rentreturndate late, and it will cost $$paymentamount')</script>";
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
} 
	else{
    // Check existence of id parameter before processing further
    if(isset($_GET["rentid"]) && !empty(trim($_GET["rentid"]))){
        // Get URL parameter
        $rentid =  trim($_GET["rentid"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM renttable WHERE rentid = ?";
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_rentid);
            
            // Set parameters
            $param_rentid = $rentid;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $result = $stmt->get_result();
                
                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
					$userid = $row["userid"];
                    $productid = $row["productid"];
					$rentfromdate = $row["rentfromdate"];
					$renttodate = $row["renttodate"];
					$rentreturndate = $row["rentreturndate"];
					$rentstatus = $row["rentstatus"];
					$paymentstatus = $row["paymentstatus"];
					$paymentamount = $row["paymentamount"];
					
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $stmt->close();
        
        // Close connection
        $mysqli->close();
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
	<h1 class="header">Music-to-go (Update Payment Status)</h1>
	<div class= "block">
	<div class="form-style">
		<label class="naming">Update Payment Status</label>
		
		<form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post">
                        <div class="question-text">
                            <label>User Id</label>
                            <input type="text" name="userid" class="input-field" readonly value="<?php echo $userid; ?>">
                        </div>
                        <div class="question-text">
                            <label>Product Id</label>
                            <input type="text" name="productid" class="input-field" readonly value="<?php echo $productid; ?>">
                        </div>
                        <div class="question-text">
                            <label>Rent Start Date</label>
                            <input type="date" name="rentfromdate" class="input-field" readonly value="<?php echo $rentfromdate; ?>">
                        </div>
						<div class="question-text">
                            <label>Rent End Date</label>
							<input type="date" name="renttodate" class="input-field" readonly value="<?php echo $renttodate; ?>">
                        </div>
						<div class="question-text <?php echo (!empty($rentreturndate_err)) ? 'has-error' : ''; ?>">
                            <label>Rent Return Date</label>
							<input type="date" name="rentreturndate" class="input-field" value="<?php echo $rentreturndate; ?>">
							<label class="error"><?php echo $rentreturndate_err;?></label>
                        </div>
						<div class="question-text">
                            <label>Rent Status</label>
							<input type="text" name="rentstatus" class="input-field" readonly value="<?php echo $rentstatus; ?>">
                        </div>
						<div class="question-text">
                            <label>Payment Status</label>
                            <input type="text" name="paymentstatus" class="input-field" value="<?php echo $paymentstatus; ?>">
                        </div>
						<div class="question-text">
                            <label>Payment Amount</label>
                            <input type="number" name="paymentamount" class="input-field" readonly value="<?php echo $paymentamount; ?>">
                        </div>
						<input type="hidden" name="rentid" value="<?php echo $rentid; ?>"/>
						<br>
						<div class="question-text">
							<input type="submit" class="submit" value="Update">
							<button><a href="UserHome.php" class="submit">Back</a></button>
						</div>

		</form>
	</div>
	</div> 
</body>
</html>