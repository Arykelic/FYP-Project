<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$servername = "remotemysql.com";
$username = "y0vryqAKXK";
$password = "moMOpaacUP";
$dbname = "y0vryqAKXK";

$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli === false) {
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

if (isset($_REQUEST["term"])) {
    // Prepare a select statement
    $sql = "SELECT * FROM user WHERE username LIKE %$term% OR firstname LIKE %$term% OR lastname LIKE %$term%
    OR phonenumber LIKE %$term% OR emailaddress LIKE %$term% OR Gender LIKE %$term% OR usertype LIKE %$term% OR accountstatus LIKE %$term%";

    
        if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_term);

        // Set parameters
        $param_term = $_REQUEST["term"] . '%';

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            // Check number of rows in the result set
            if ($result->num_rows > 0) {
                // Fetch result rows as an associative array
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['userid'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['firstname'] . "</td>";
                    echo "<td>" . $row['lastname'] . "</td>";
                    echo "<td>" . $row['phonenumber'] . "</td>";
                    echo "<td>" . $row['emailaddress'] . "</td>";
                    echo "<td> " . $row['BirthDate'] . "</td>";
                    echo "<td> " . $row['Gender'] . "</td>";
                    echo "<td> " . $row['usertype'] . "</td>";
                    echo "<td> " . $row['accountstatus'] . "</td>";
                    echo "<td>";
                    echo "<a href='adminviewuser.php?userid=" . $row['userid'] . "' title='View User' data-toggle='tooltip'><i class='fa-solid fa-eye'></i></a>";
                    echo "<a href='adminupdateuser.php?userid=" . $row['userid'] . "' title='Update User' data-toggle='tooltip'><i class='fa-solid fa-pen-to-square'></i></a>";
                    echo "<a href='admindeleteuser.php?userid=" . $row['userid'] . "' title='Delete User' data-toggle='tooltip'><i class='fa-solid fa-trash'></i></a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<td>No matches found</td>";
            }
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }

    // Close statement
    $stmt->close();
}
// Close connection
$mysqli->close();
?>

