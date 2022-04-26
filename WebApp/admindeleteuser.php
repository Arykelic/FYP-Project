<?php
// Process delete operation after confirmation
if (isset($_POST["userid"]) && !empty($_POST["userid"])) {
    // Include config file
    require_once "UserConfig.php";

    // Prepare a delete statement
    $sql = "DELETE FROM user WHERE userid = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_userid);

        // Set parameters
        $param_userid = trim($_POST["userid"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records deleted successfully. Redirect to landing page
            header("location: index.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    $stmt->close();

    // Close connection
    $mysqli->close();
} else {
    // Check existence of userid parameter
    if (empty(trim($_GET["userid"]))) {
        // URL doesn't contain id parameter. Redirect to error page
        echo '<script>alert("An error has occurred finding a valid id parameter")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="userid" value="<?php echo trim($_GET["userid"]); ?>" />
                            <p>Are you sure you want to delete this employee record?</p>
                            <p>
                                <input class="btn btn-block text-uppercase" type="submit" value="Delete User"></input>
                            </p>
                        </div>
                    </form>
                    <a href="adminmanageuser.php"><button class="backbutton" value="Back">Back</button></a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>