<?php
session_start();
if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/db.php";
    $sql = sprintf("SELECT * FROM users WHERE id = '%s'", $_SESSION["user_id"]);
    $res = $mysqli->query($sql);
    $user = $res->fetch_assoc();
} else {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="./bootstrap.min.css" />
    <link rel="stylesheet" href="./styles.css" />
</head>

<body>
    <div class="container px-0 mx-0" style="min-width:100%;" id="navcont">
        <nav class="navbar navbar-expand-lg bg-body-tertiary" style="padding:1rem 2rem;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">WorkoutLogger</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                    <ul class="navbar-nav ">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="./index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./exercises/">Exercises</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./sessions/">Sessions</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Account
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="container-lg m-20 px-auto" style="max-width:800px; margin:20px auto;">
        <h1>Welcome back, <?= $user["name"] ?></h1>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        <div class="row">
            <div class="col">
                <h2>Exercises</h2>
                <a href="exercises/create.php"><button class="btn btn-primary">Add Exercises +</button></a>
                <a href="exercises/"><button class="btn btn-secondary">See all...</button></a>
            </div>
            <div class="col">
                <h2>Workout sessions</h2>
                <a href="sessions/create.php"><button class="btn btn-primary">Add a session +</button></a>
                <a href="sessions/"><button class="btn btn-secondary">See all...</button></a>
            </div>
        </div>
    </div>
</body>

</html>