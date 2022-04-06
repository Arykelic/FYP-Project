<?php

// Include config file
include "productconfig.php";
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Available Products Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
	<h1 class="header">Music-to-go (View Available Products)</h1>
	<div class= "block">
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
                    // Attempt select query execution
                    $sql = "SELECT * FROM producttable WHERE status ='Available'";
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
		<div class="question-text">
			<button><a href="AdminHome.php" class="submit">Back</a></button>
		</div>
	</div>
	</div> 
</body>
</html>