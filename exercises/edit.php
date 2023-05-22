<?php
session_start();
$mysqli = require dirname(__FILE__, 2) . "/db.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["id"])) {
        die("Exercise id cannot be null");
    }
    $sql = sprintf(
        "UPDATE exercises SET name='%s', description='%s' WHERE id=%s",
        $mysqli->real_escape_string($_POST["name"]),
        $mysqli->real_escape_string($_POST["desc"]),
        $mysqli->real_escape_string($_POST["id"])

    );
    try {
        $res = $mysqli->query($sql);
        header("Location: ../exercises/history.php?exercise_id=" . $_POST["id"]);
        exit();
    } catch (Exception $e) {
        die($mysqli->error . " " . $mysqli->errno);
    }
}
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (empty($_GET["id"])) {
        die("Exercise id cannot be null");
    }

    $exsql = sprintf(
        "SELECT * FROM exercises WHERE user_id='%s' AND id='%s'",
        $_SESSION["user_id"],
        $mysqli->real_escape_string($_GET["id"])
    );

    try {
        $res = $mysqli->query($exsql);
        $exercise = $res->fetch_all()[0];
    } catch (Exception $e) {
        die($mysqli->error . " " . $mysqli->errno);
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a workout</title>
    <link rel="stylesheet" href="../bootstrap.min.css" />
    <link rel="stylesheet" href="../styles.css" />
</head>

<body style="background-color:#FFEBEE;">
    <div class="container px-0 mx-0" style="min-width:100%;" id="navcont">
        <nav class="navbar navbar-expand-lg bg-body-tertiary" style="padding:1rem 2rem;">
            <div class="container-fluid">
                <span class="navbar-brand">WorkoutLogger</span>
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

                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-lg m-20 px-auto" style="max-width:800px; margin:20px auto;">
        <h1>Create a exercise</h1>
        <?php if (false) : ?>
            <b>There is already an exercise with the same name. Try again with a different name</b>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="id" value="<?= $exercise[0] ?>">
            <label for="name" class="form-label">Exercise Name</label>
            <input type="text" name="name" id="name" required value="<?= $exercise[1] ?>" class="form-control">
            <label for="desc" class="form-label">Description</label>
            <textarea name="desc" id="desc" cols="60" rows="8" class="form-control"><?= $exercise[3] ?></textarea>
            <input type="submit" value="Update exercise" class="btn btn-primary my-3">
        </form>
        <a href="index.php"><button class="btn btn-secondary"><- View all exercises</button></a>
    </div>
</body>

</html>