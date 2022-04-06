<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "musictogodb";

// Create connection
$mysqli = new mysqli($servername, $username, $password);
// Check connection
if ($mysqli->connect_error) 
{ die("Connection failed: " . $mysqli->connect_error); }

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";

if ($mysqli->query($sql) === TRUE)
{ /* echo "Database exists or created successfully","<br>"; */ }
else
{ /* echo "Error creating database: " . $mysqli->error; */ }

$mysqli->close();

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($mysqli->connect_error)
{ die("Connection failed: " . $mysqli->connect_error); }

$dbtable = "producttable";

//$checktable = mysql_query("SHOW TABLES LIKE '$dbtable'");
$checktable = $mysqli->query("SHOW TABLES LIKE '$dbtable'");
$table_exists = $checktable->num_rows >= 1; //mysql_num_rows($checktable) > 0;
//echo $table_exists,"<br>";
//var_dump($checktable);

If (!$table_exists) {
	// sql to create table
	$sql = 
		"CREATE TABLE $dbtable (
		productid INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		category VARCHAR(255) NOT NULL,
		brand VARCHAR(255) NOT NULL,
		manufactureyear YEAR NOT NULL,
		characteristics VARCHAR (255) NOT NULL,
		status VARCHAR(100) NOT NULL,
		cost DOUBLE(7,2) NOT NULL,
		overduecost DOUBLE(7,2) NOT NULL)";

	if ($mysqli->query($sql) === TRUE)
	{ /* echo "Table $dbtable created successfully","<br>"; */ }
	else 
	{ /* echo "Error creating table: " . $mysqli->error; */ }
}
else
{ /* echo "Table exist.\n"; */ }

?>