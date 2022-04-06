<?php
// Check existence of id parameter before processing further
if(isset($_GET["productid"]) && !empty(trim($_GET["productid"]))){
    // Include config file
    include "productconfig.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM producttable WHERE productid = ?";
    
    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_productid);
        
        // Set parameters
        $param_productid = trim($_GET["productid"]);
        
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
                header("location: error.php");
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
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
	<h1 class="header">Music-to-go (View Product)</h1>
	<div class= "block">
	<div class="form-style">
		<label class="naming">View Product</label>
                        <div class="question-text ">
                            <label>Category</label>
                            <p class="form-control-static"><?php echo $row["category"]; ?></p>
                        </div>
                        <div class="question-text ">
                            <label>Brand</label>
                            <p class="form-control-static"><?php echo $row["brand"]; ?></p>
                        </div>
						<div class="question-text ">
                            <label>Year Of Manufacture</label>
                            <p class="form-control-static"><?php echo $row["manufactureyear"]; ?></p>
                        </div>
						<div class="question-text ">
                            <label>Characteristics</label>
                            <p class="form-control-static"><?php echo $row["characteristics"]; ?></p>
                        </div>
						<div class="question-text ">
                            <label>Status</label>
                            <p class="form-control-static"><?php echo $row["status"]; ?></p>
                        </div>
						<div class="question-text ">
                            <label>Cost/Day</label>
                            <p class="form-control-static">$<?php echo $row["cost"]; ?></p>
                        </div>
						<div class="question-text ">
                            <label>Overdue Cost/Day</label>
                            <p class="form-control-static">$<?php echo $row["overduecost"]; ?></p>
                        </div>
						<div class="question-text">
							<button><a href="AdminHome.php" class="submit">Back</a></button>
						</div>
	</div>
	</div> 
</body>
</html>