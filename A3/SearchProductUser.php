<?php
// Initialize the session
session_start();
include "GlobalClass.php";
include "ProductConfig.php";


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Search</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
<link rel="stylesheet" href="stylesheet.css">

</head>
<body>
	<h1 class="header">Music-to-go (Search Products)</h1>
	<div class="form-style">
	<label class="naming">Products</label>
	</div>
	<div class="block">
	<div class="form-style">
	<label class="naming">Search Product</label>
	<form action="SearchFunctionUser.php" method="post">
			<div class="question-text">
				<label class="question-text">Search Category</label>
				<input class="input-field" name="category" type="text" autocomplete="off" placeholder="Enter Category" />
				<label></label>
				<label class="question-text">Search Brand</label>
				<input class="input-field" name="brand" type="text" autocomplete="off" placeholder="Enter Brand" />
				<label></label>
				<label class="question-text">Search Characteristics</label>
				<input class="input-field" name="characteristics" type="text" autocomplete="off" placeholder="Enter Characteristics" />
				<label></label>
				<label class="question-text">Search Status</label>
				<input class="input-field" name="status" type="statustext" autocomplete="off" placeholder="Enter Status" />
				<label></label>
				
				<input href="searchfunctionuser.php" name= "submit" type="submit" class="submit" value="Search">
				<button><a href="userhome.php" class="submit">Back</a></button>
			</div>
	</form>
	<br>
					
	
	
	
	<br>
	
	
	</div>
	</div>

</body>
</html>

