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

$dbtable = "user";

//$checktable = mysql_query("SHOW TABLES LIKE '$dbtable'");
$checktable = $mysqli->query("SHOW TABLES LIKE '$dbtable'");
$table_exists = $checktable->num_rows >= 1; //mysql_num_rows($checktable) > 0;
//echo $table_exists,"<br>";
//var_dump($checktable);

If (!$table_exists) {
	// sql to create table
	$sql = "CREATE TABLE $dbtable (
		userid INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		username VARCHAR(255) NOT NULL UNIQUE,
		password VARCHAR(255) NOT NULL,
		firstname VARCHAR(50),
		lastname VARCHAR (50),
		phonenumber INT(10),
		emailaddress VARCHAR(255),
		BirthDate DATE,
		Gender VARCHAR(10),
		usertype VARCHAR(100) NOT NULL,
		accountstatus VARCHAR(100) NOT NULL,
		createddatetime DATETIME,
		createdby VARCHAR(255),
		updateddatetime DATETIME,
		updatedby VARCHAR(255)
		)";

	if ($mysqli->query($sql) === TRUE)
	{ /* echo "Table $dbtable created successfully","<br>"; */ }
	else
	{ /* echo "Error creating table: " . $mysqli->error; */ }
}
else
{ /* echo "Table exist.\n"; */ }

?>