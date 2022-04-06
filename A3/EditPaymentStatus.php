<?php
// Include config file
// Initialize the session
session_start();
include "productconfig.php";
include "rentconfig.php";
include "globalclass.php";
// Define variables and initialize with empty values
$paymentstatus = "";
$paymentstatus_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["rentid"]) && !empty($_POST["rentid"])){
    // Get hidden input value
    $rentid = $_POST["rentid"];
    
	//Validate status
	$input_paymentstatus = test_input($_POST["paymentstatus"]);
    if(empty($input_paymentstatus)){
        $paymentstatus_err = "Please enter a payment status.";     
    } else{
        $paymentstatus = $input_paymentstatus;
    }
	
    
    // Check input errors before inserting in database
    if(empty($paymentstatus_err)){
        // Prepare an update statement
        $sql = "UPDATE renttable SET paymentstatus=? WHERE rentid=?";
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_paymentstatus, $param_rentid);
            
            // Set parameters

			$param_paymentstatus = $paymentstatus;
			$param_rentid = $rentid;
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: AdminHome.php");
                exit();
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
						<div class="question-text">
                            <label>Rent Return Date</label>
							<input type="date" name="rentreturndate" class="input-field" readonly value="<?php echo $rentreturndate; ?>">
                        </div>
						<div class="question-text">
                            <label>Rent Status</label>
							<input type="text" name="rentstatus" class="input-field" readonly value="<?php echo $rentstatus; ?>">
                        </div>
						<div class="question-text <?php echo (!empty($paymentstatus_err)) ? 'has-error' : ''; ?>">
                            <label>Payment Status</label>
                            <input type="text" name="paymentstatus" class="input-field" value="<?php echo $paymentstatus; ?>">
							<label class="error"><?php echo $paymentstatus_err;?></label>
                        </div>
						<div class="question-text">
                            <label>Payment Amount</label>
                            <input type="number" name="paymentamount" class="input-field" readonly value="<?php echo $paymentamount; ?>">
                        </div>
						<input type="hidden" name="rentid" value="<?php echo $rentid; ?>"/>
						<br>
						<div class="question-text">
							<input type="submit" class="submit" value="Update">
							<button><a href="AdminHome.php" class="submit">Back</a></button>
						</div>

		</form>
	</div>
	</div> 
</body>
</html>