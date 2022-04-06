<?php
// Include config file
// Initialize the session
session_start();
include "productconfig.php";
include "globalclass.php";
 
 
// Define variables and initialize with empty values
$category = $brand = $manufactureyear = $characteristics = $status = $cost = $overduecost = "";
$category_err = $brand_err = $manufactureyear_err = $characteristics_err = $status_err = $cost_err = $overduecost_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate category
    $input_category = test_input($_POST["category"]);
    if(empty($input_category)){
        $category_err = "Please enter a category.";
    } else{
        $category = $input_category;
    }
    
    // Validate brand
    $input_brand = test_input($_POST["brand"]);
    if(empty($input_brand)){
        $brand_err = "Please enter a brand.";     
    } else{
        $brand = $input_brand;
    }
    
    // Validate manufactureyear
    $input_manufactureyear = test_input($_POST["manufactureyear"]);
    if(empty($input_manufactureyear)){
        $manufactureyear_err = "Please enter the Year of manufacture.";     
    } elseif(!ctype_digit($input_manufactureyear)){
        $manufactureyear_err = "Please enter a valid year.";
    } else{
        $manufactureyear = $input_manufactureyear;
    }
	
	//Validate characteristics
	$input_characteristics = test_input($_POST["characteristics"]);
    if(empty($input_characteristics)){
        $characteristics_err = "Please enter the characteristics.";     
    } else{
        $characteristics = $input_characteristics;
    }
	
	//Validate status
	$input_status = test_input($_POST["status"]);
    if(empty($input_status)){
        $status_err = "Please enter a status.";     
    } else{
        $status = $input_status;
    }
	
	//Validate cost
	$input_cost = test_input($_POST["cost"]);
    if(empty($input_cost)){
        $cost_err = "Please enter the cost per day.";     
    } elseif(!ctype_digit($input_cost)){
        $cost_err = "Please enter a valid cost per day.";
    } else{
        $cost = $input_cost;
    }
	
	//Validate overduecost
	$input_overduecost = test_input($_POST["overduecost"]);
    if(empty($input_overduecost)){
        $overduecost_err = "Please enter the overdue cost per day.";     
    } elseif(!ctype_digit($input_overduecost)){
        $overduecost_err = "Please enter a valid overdue cost per day.";
    } else{
        $overduecost = $input_overduecost;
    }
	
    
    // Check input errors before inserting in database
    if(empty($category_err) && empty($brand_err) && empty($manufactureyear_err) && empty($characteristics_err) && empty($status_err) && empty($cost_err) && empty($overduecost_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO producttable (category, brand, manufactureyear, characteristics, status, cost, overduecost) VALUES (?, ?, ?, ?, ?, ?, ?)";
 
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssissdd", $param_category, $param_brand, $param_manufactureyear, $param_characteristics, $param_status, $param_cost, $param_overduecost);
            
            // Set parameters
            $param_category = $category;
            $param_brand = $brand;
            $param_manufactureyear = $manufactureyear;
			$param_characteristics = $characteristics;
			$param_status = $status;
			$param_cost = $cost;
			$param_overduecost = $overduecost;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
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
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>

	<h1 class="header">Music-to-go (Create Product)</h1>
	<div class= "block">
	<div class="form-style">
		<label class="naming">Create Product</label>
		<label class="question-text">Fill out the form to add a product into the database.</label>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="question-text <?php echo (!empty($category_err)) ? 'has-error' : ''; ?>">
                            <label>Category</label>
                            <input type="text" name="category" class="input-field" value="<?php echo $category; ?>">
                            <label class="error"><?php echo $category_err;?></label>
                        </div>
                        <div class="question-text <?php echo (!empty($brand_err)) ? 'has-error' : ''; ?>">
                            <label>Brand</label>
                            <input type="text" name="brand" class="input-field" value="<?php echo $brand; ?>">
                            <label class="error"><?php echo $brand_err;?></label>
                        </div>
                        <div class="question-text <?php echo (!empty($manufactureyear_err)) ? 'has-error' : ''; ?>">
                            <label>Year Of Manufacture</label>
                            <input type="number" name="manufactureyear" class="input-field" min="1901" max="2021" value="<?php echo $manufactureyear; ?>">
                            <label class="error"><?php echo $manufactureyear_err;?></label>
                        </div>
						<div class="question-text <?php echo (!empty($characteristics_err)) ? 'has-error' : ''; ?>">
                            <label>Characteristics</label>
                            <input type="text" name="characteristics" class="input-field" value="<?php echo $characteristics; ?>">
                            <label class="error"><?php echo $characteristics_err;?></label>
                        </div>
						<div class="question-text <?php echo (!empty($status_err)) ? 'has-error' : ''; ?>">
                            <label>Status</label>
                            <input type="text" name="status" class="input-field" value="<?php echo $status; ?>">
                            <label class="error"><?php echo $status_err;?></label>
                        </div>
						<div class="question-text <?php echo (!empty($cost_err)) ? 'has-error' : ''; ?>">
                            <label>Cost Per Day</label>
                            <input type="number" name="cost" class="input-field" value="<?php echo $cost; ?>">
                            <label class="error"><?php echo $cost_err;?></label>
                        </div>
						<div class="question-text <?php echo (!empty($overduecost_err)) ? 'has-error' : ''; ?>">
                            <label>Overdue Cost Per Day</label>
                            <input type="number" name="overduecost" class="input-field" value="<?php echo $overduecost; ?>">
                            <label class="error"><?php echo $overduecost_err;?></label>
                        </div>
						<div class="question-text">
							<input type="submit" class="submit" value="Create">
							<button><a href="AdminHome.php" class="submit">Back</a></button>
						</div>

		</form>
	</div>
	</div> 
</body>
</html>