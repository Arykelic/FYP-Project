<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Music-to-go</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container-fluid p-5 my-5 bg-dark text-white text-center ">
        <h1>Hello, <b></b>. Welcome to eCommerce Insight (Admin)</h1>

    </div>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="AdminHomePage.php">Home</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Add User Accounts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Search User Accounts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Manage User Accounts</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Dropdown</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Link</a></li>
                            <li><a class="dropdown-item" href="#">Another link</a></li>
                            <li><a class="dropdown-item" href="#">A third link</a></li>
                        </ul>
                    </li>
                    <li class="d-flex">
                        <button class="btn btn-primary  " type="button" href="#" >Logout</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4 text-white">Conditions of Use</div>
                <div class="col-sm-4 text-white">Privacy Statement</div>
                <div class="col-sm-4 text-white">2022, eCommerce Insight</div>
            </div>
        </div>
    </nav>

    <div class="form-style">
        <label class="naming">User Account Management</label>
    </div>

    <div class="block">
        <div class="form-style">

            <br>
            <table class="table table-bordered table-striped" style="text-align:left;" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Rent Id</th>
                        <th>User Id</th>
                        <th>Product Id</th>
                        <th>Rent Start Date</th>
                        <th>Rent End Date</th>
                        <th>Returned Date</th>
                        <th>Rent Status</th>
                        <th>Payment Status</th>
                        <th>Payment Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>

</body>

</html>