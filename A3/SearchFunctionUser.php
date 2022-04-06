<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
// Initialize the session
session_start();
include "GlobalClass.php";
include "ProductConfig.php";

$mysqli = new mysqli("localhost", "root", "", "musictogodb");
 
// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Results</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
<link rel="stylesheet" href="stylesheet.css">
</head>
<body>
	<h1 class="header">Music-to-go (Search Results)</h1>
	<div class="form-style">
	<label class="naming">Products</label>
	</div>
	<div class="block">
	<div class="form-style">
	
	<table class="table table-bordered table-striped" style="text-align:left;" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th>Product Id</th>
				<th>Category</th>
				<th>Brand</th>
				<th>Year Of Manufacture</th>
				<th>Characteristics</th>
				<th>Status</th>
				<th>Cost/Day</th>
				<th>Overdue Cost/Day</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$category = $brand  = $characteristics = $status = "";

			// Processing form data when form is submitted
			if($_SERVER["REQUEST_METHOD"] == "POST"){
				// Validate category
					$category = test_input($_POST['category']);
					$brand = test_input($_POST['brand']);
					$characteristics = test_input($_POST['characteristics']);
					$status = test_input($_POST['status']);
					
					//if all fields are empty
					if(empty($category) && empty($brand) && empty($characteristics) && empty($status)){
						// Prepare an insert statement
						echo "<label>No matches found</label>";
						// Close statement
						
					}
					
					//category
					 if(!empty($category) && empty($brand) && empty($characteristics) && empty($status)){
						// Prepare an insert statement
						$sql = "SELECT * FROM producttable WHERE category =?";
						if($stmt = $mysqli->prepare($sql)){
							// Bind variables to the prepared statement as parameters
							$stmt->bind_param("s", $param_category);
							// Set parameters
							$param_category = $category;
							
							// Attempt to execute the prepared statement
							if($stmt->execute()){
						$result = $stmt->get_result();
						// Check number of rows in the result set
						if($result->num_rows > 0){
							// Fetch result rows as an associative array
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
												echo "</tr>";
												}
											} else{
													echo "<label>No matches found</label>";
												}
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
							}
						}
						 
						// Close statement
						$stmt->close();
					}
					
					//category + brand
					 if(!empty($category) && !empty($brand) && empty($characteristics) && empty($status)){
						// Prepare an insert statement
						$sql = "SELECT * FROM producttable WHERE category =? AND brand =?";
						if($stmt = $mysqli->prepare($sql)){
							// Bind variables to the prepared statement as parameters
							$stmt->bind_param("ss", $param_category, $param_brand);
							// Set parameters
							$param_category = $category;
							$param_brand = $brand;
							
							// Attempt to execute the prepared statement
							if($stmt->execute()){
						$result = $stmt->get_result();
						// Check number of rows in the result set
						if($result->num_rows > 0){
							// Fetch result rows as an associative array
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
												echo "</tr>";
												}
											} else{
													echo "<label>No matches found</label>";
												}
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
							}
						}
						 
						// Close statement
						$stmt->close();
					}
					
					//category + brand + characteristics
				 if(!empty($category) && !empty($brand) && !empty($characteristics) && empty($status)){
						// Prepare an insert statement
						$sql = "SELECT * FROM producttable WHERE category =? AND brand =? AND characteristics =?";
						if($stmt = $mysqli->prepare($sql)){
							// Bind variables to the prepared statement as parameters
							$stmt->bind_param("sss", $param_category, $param_brand, $param_characteristics);
							// Set parameters
							$param_category = $category;
							$param_brand = $brand;
							$param_characteristics = $characteristics;
							
							// Attempt to execute the prepared statement
							if($stmt->execute()){
						$result = $stmt->get_result();
						// Check number of rows in the result set
						if($result->num_rows > 0){
							// Fetch result rows as an associative array
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
												echo "</tr>";
												}
											} else{
													echo "<label>No matches found</label>";
												}
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
							}
						}
						 
						// Close statement
						$stmt->close();
					}
					
					
			
			//brand
				 if(!empty($brand) && empty($category) && empty($characteristics) && empty($status)){
					$sql = "SELECT * FROM producttable WHERE brand =?";
					if($stmt = $mysqli->prepare($sql)){
						// Bind variables to the prepared statement as parameters
						$stmt->bind_param("s", $param_brand);
						// Set parameters
						$param_brand = $brand;
						
					// Attempt to execute the prepared statement
						if($stmt->execute()){
						$result = $stmt->get_result();
						// Check number of rows in the result set
						if($result->num_rows > 0){
							// Fetch result rows as an associative array
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
												echo "</tr>";
												}
											} else{
													echo "<label>No matches found</label>";
												}
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
								}
				}
				$stmt->close();
			}
			
			//brand + characteristics
				 if(!empty($brand) && !empty($characteristics) && empty($category) && empty($status)){
					$sql = "SELECT * FROM producttable WHERE brand =? AND characteristics=?";
					if($stmt = $mysqli->prepare($sql)){
						// Bind variables to the prepared statement as parameters
						$stmt->bind_param("ss", $param_brand, $param_characteristics);
						// Set parameters
						$param_brand = $brand;
						$param_characteristics = $characteristics;
						
					// Attempt to execute the prepared statement
						if($stmt->execute()){
						$result = $stmt->get_result();
						// Check number of rows in the result set
						if($result->num_rows > 0){
							// Fetch result rows as an associative array
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
												echo "</tr>";
												}
											} else{
													echo "<label>No matches found</label>";
												}
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
								}
				}
				$stmt->close();
			}
			
			//brand + characteristics + status
				 if(!empty($status) && !empty($brand) && !empty($characteristics) && empty($category)){
					$sql = "SELECT * FROM producttable WHERE brand =? AND characteristics=? AND status=?";
					if($stmt = $mysqli->prepare($sql)){
						// Bind variables to the prepared statement as parameters
						$stmt->bind_param("sss", $param_brand, $param_characteristics, $param_status);
						// Set parameters
						$param_brand = $brand;
						$param_characteristics = $characteristics;
						$param_status = $status;
					// Attempt to execute the prepared statement
						if($stmt->execute()){
						$result = $stmt->get_result();
						// Check number of rows in the result set
						if($result->num_rows > 0){
							// Fetch result rows as an associative array
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
												echo "</tr>";
												}
											} else{
													echo "<label>No matches found</label>";
												}
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
								}
				}
				$stmt->close();
			}
			
			//characteristics
				 if(!empty($characteristics) && empty($category) && empty($brand) && empty($status)){
					$sql = "SELECT * FROM producttable WHERE characteristics =?";
					if($stmt = $mysqli->prepare($sql)){
						// Bind variables to the prepared statement as parameters
						$stmt->bind_param("s", $param_characteristics);
						// Set parameters
						$param_characteristics = $characteristics;
						
					// Attempt to execute the prepared statement
						if($stmt->execute()){
						$result = $stmt->get_result();
						// Check number of rows in the result set
						if($result->num_rows > 0){
							// Fetch result rows as an associative array
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
												echo "</tr>";
												}
											} else{
													echo "<label>No matches found</label>";
												}
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
								}
				}
				$stmt->close();
			}
			
			//characteristics + status
				 if(!empty($characteristics) && !empty($status) && empty($category) && empty($brand)){
					$sql = "SELECT * FROM producttable WHERE characteristics =? AND status=?";
					if($stmt = $mysqli->prepare($sql)){
						// Bind variables to the prepared statement as parameters
						$stmt->bind_param("ss", $param_characteristics, $param_status );
						// Set parameters
						$param_characteristics = $characteristics;
						$param_status = $status;
						
					// Attempt to execute the prepared statement
						if($stmt->execute()){
						$result = $stmt->get_result();
						// Check number of rows in the result set
						if($result->num_rows > 0){
							// Fetch result rows as an associative array
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
												echo "</tr>";
												}
											} else{
													echo "<label>No matches found</label>";
												}
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
								}
				}
				$stmt->close();
			}
			
			//status
				 if(!empty($status) && empty($category) && empty($brand) && empty($characteristics)){
					$sql = "SELECT * FROM producttable WHERE status =?";
					if($stmt = $mysqli->prepare($sql)){
						// Bind variables to the prepared statement as parameters
						$stmt->bind_param("s", $param_status);
						// Set parameters
						$param_status = $status;
						
					// Attempt to execute the prepared statement
						if($stmt->execute()){
						$result = $stmt->get_result();
						// Check number of rows in the result set
						if($result->num_rows > 0){
							// Fetch result rows as an associative array
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
												echo "</tr>";
												}
											} else{
													echo "<label>No matches found</label>";
												}
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
								}
				}
				$stmt->close();
			}
			
			//all present
				 if(!empty($category) && !empty($brand) && !empty($characteristics) && !empty($status)){
					$sql = "SELECT * FROM producttable WHERE category =? AND brand =? AND characteristics =? AND status = ?";
					if($stmt = $mysqli->prepare($sql)){
						// Bind variables to the prepared statement as parameters
						$stmt->bind_param("ssss", $param_category, $param_brand, $param_characteristics, $param_status);
						// Set parameters
						$param_category = $category;
						$param_brand = $brand;
						$param_characteristics = $characteristics;
						$param_status = $status;
						
					// Attempt to execute the prepared statement
						if($stmt->execute()){
						$result = $stmt->get_result();
						// Check number of rows in the result set
						if($result->num_rows > 0){
							// Fetch result rows as an associative array
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
												echo "</tr>";
												}
											} else{
													echo "<label>No matches found</label>";
												}
							} else{
								echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
								}
				}
				$stmt->close();
			}
			
			// Close connection
			$mysqli->close();
		}

			
			?>
		</tbody>
	</table>
	<br>
		<div class="question-text-add">
			<button><a href="searchproductuser.php" class="submit">Back</a></button>
		</div>
	
	
	
	<br>
	
	
	</div>
	</div>

</body>
</html>