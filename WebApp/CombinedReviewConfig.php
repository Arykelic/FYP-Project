<?php

$servername = "remotemysql.com";
$username = "y0vryqAKXK";
$password = "ovYvXY4sFJ";
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

$dbtable = "combinedreview";

//$checktable = mysql_query("SHOW TABLES LIKE '$dbtable'");
$checktable = $mysqli->query("SHOW TABLES LIKE '$dbtable'");
$table_exists = $checktable->num_rows >= 1; //mysql_num_rows($checktable) > 0;
//echo $table_exists,"<br>";
//var_dump($checktable);

If (!$table_exists) {
	// sql to create table
	$sql = "CREATE TABLE $dbtable (
		combinedreviewid INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		image_url VARCHAR(255),
		item_name VARCHAR(255),
        customername VARCHAR(255),
        rating_score INT(10),
		review_location VARCHAR(255),
		review_date VARCHAR(255)
		)";

	if ($mysqli->query($sql) === TRUE)
	{ /* echo "Table $dbtable created successfully","<br>"; */ }
	else
	{ /* echo "Error creating table: " . $mysqli->error; */ }
}
else
{ /* echo "Table exist.\n"; */ }

?>