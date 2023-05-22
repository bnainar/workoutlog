<?php
session_start();
$mysqli = require dirname(__FILE__, 2) . "/db.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["id"])) {
        die("Exercise id cannot be null");
    }
    $sql = sprintf(
        "UPDATE session SET date='%s', duration_minutes='%s', calories_burned='%s', notes='%s' WHERE id=%s",
        $mysqli->real_escape_string($_POST["date"]),
        $mysqli->real_escape_string($_POST["duration"]),
        $mysqli->real_escape_string($_POST["calories"]),
        $mysqli->real_escape_string($_POST["notes"]),
        $mysqli->real_escape_string($_POST["id"])
    );
    try {
        $res = $mysqli->query($sql);
        header("Location: ../sessions/session_details.php?id=" . $_POST["id"]);
        exit();
    } catch (Exception $e) {
        die($mysqli->error . " " . $mysqli->errno);
    }
}
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (empty($_GET["id"])) {
        die("session id cannot be null");
    }

    $exsql = sprintf(
        "SELECT * FROM session WHERE user_id='%s' AND id='%s'",
        $_SESSION["user_id"],
        $mysqli->real_escape_string($_GET["id"])
    );

    try {
        $res = $mysqli->query($exsql);
        $session = $res->fetch_all()[0];
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
    <title>Edit Session Details</title>
    <link rel="stylesheet" href="../bootstrap.min.css" />
    <link rel="stylesheet" href="../styles.css" />
</head>

<body style="background-color:#EFEBE9;">
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
                            <a class="nav-link" href="../exercises/">Exercises</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./index.php">Sessions</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-lg m-20 px-auto" style="max-width:800px; margin:20px auto;">
        <h1>Edit Workout Session Details</h1>
        <form method="post">
            <input name="id" type="hidden" value="<?= $session[0] ?>">

            <label for="date" class="form-label">Date</label>
            <input type="datetime-local" name="date" id="date" value="<?= $session[2] ?>" class="form-control">

            <label for="duration" class="form-label">Duration (in minutes)</label>
            <input type="number" name="duration" id="duration" required value="<?= $session[3] ?>" class="form-control">

            <label for="calories" class="form-label">Calories burned</label>
            <input type="number" name="calories" id="calories" required value="<?= $session[4] ?>" class="form-control">

            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" cols="60" rows="5" class="form-control"><?= $session[5] ?></textarea>

            <input type="submit" value="Update session" class="btn btn-primary my-3">
        </form>
        <a href="index.php"><button class="btn btn-secondary">Cancel</button></a>
    </div>
</body>

</html>