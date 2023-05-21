<?php

session_start();
$duplicate = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["name"])) {
        die("Exercise name cannot be null");
    }

    $mysqli = require dirname(__FILE__, 2) . "/db.php";
    var_dump($_POST);

    $sql = sprintf(
        "INSERT INTO exercises (name, user_id, description) VALUES ('%s','%s','%s')",
        $mysqli->real_escape_string($_POST["name"]),
        $mysqli->real_escape_string($_SESSION["user_id"]),
        $mysqli->real_escape_string($_POST["desc"])
    );
    try {
        $res = $mysqli->query($sql);
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        if ($mysqli->errno === 1062) {
            $duplicate = true;
        } else {
            die($mysqli->error . " " . $mysqli->errno);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a exercise</title>

    <link rel="stylesheet" href="../bootstrap.min.css" />
    <link rel="stylesheet" href="../styles.css" />
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
                            <a class="nav-link" aria-current="page" href="../index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./index.php">Exercises</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../sessions/">Sessions</a>
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
        <h1>Create a exercise</h1>
        <?php if ($duplicate) : ?>
            <b>There is already an exercise with the same name. Try again with a different name</b>
        <?php endif; ?>
        <form method="post">
            <label for="name" class="form-label">Exercise Name</label>
            <input type="text" name="name" id="name" required class="form-control shadow-sm">
            <label for="desc" class="form-label">Description</label>
            <textarea name="desc" id="desc" cols="60" rows="8" class="form-control shadow-sm"></textarea>
            <input type="submit" class="btn btn-primary my-3 shadow-sm" value="Create a exercise +">
        </form>
        <a href="index.php"><button class="btn btn-secondary shadow-sm"><- View all exercises</button></a>
    </div>
</body>

</html>