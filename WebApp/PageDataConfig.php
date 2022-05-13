<?php

$servername = "remotemysql.com";
$username = "y0vryqAKXK";
$password = "kwdY0yQrV0";
$dbname = "y0vryqAKXK";

// Create connection
//$mysqli = new mysqli($servername, $username, $password);
// Check connection
//if ($mysqli->connect_error) 
//{ die("Connection failed: " . $mysqli->connect_error); }

// Create database
//$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
//if ($mysqli->query($sql) === TRUE)
{ /* echo "Database exists or created successfully","<br>"; */ }
//else
{ /* echo "Error creating database: " . $mysqli->error; */ }

//$mysqli->close();

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($mysqli->connect_error)
{ die("Connection failed: " . $mysqli->connect_error); }

$dbtable = "cataloguedata";

//$checktable = mysql_query("SHOW TABLES LIKE '$dbtable'");
$checktable = $mysqli->query("SHOW TABLES LIKE '$dbtable'");
$table_exists = $checktable->num_rows >= 1; //mysql_num_rows($checktable) > 0;
//echo $table_exists,"<br>";
//var_dump($checktable);

If (!$table_exists) {
	// sql to create table
	$sql = "CREATE TABLE $dbtable (
		pageid INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        review_url VARCHAR(255),
		image_url VARCHAR(255),
		item_name VARCHAR(255),
		item_price DECIMAL(10,2),
        item_brand VARCHAR(255),
		average_rating DECIMAL(5,2),
		number_of_ratings INT(10)
		)";

	if ($mysqli->query($sql) === TRUE)
	{ /* echo "Table $dbtable created successfully","<br>"; */ }
	else
	{ /* echo "Error creating table: " . $mysqli->error; */ }
}
else
{ /* echo "Table exist.\n"; */ }

?>