<?php
session_start();
$mysqli = require dirname(__FILE__, 2) . "/db.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["exercise_id"])) {
        die("Exercise id cannot be null");
    }
    $sql = sprintf(
        "UPDATE workouts SET exercise_id='%s', sets='%s', reps='%s', weight='%s' WHERE id=%s",
        $mysqli->real_escape_string($_POST["exercise_id"]),
        $mysqli->real_escape_string($_POST["sets"]),
        $mysqli->real_escape_string($_POST["reps"]),
        $mysqli->real_escape_string($_POST["weight"]),
        $mysqli->real_escape_string($_POST["id"])

    );
    try {
        $res = $mysqli->query($sql);
        header("Location: ../sessions/session_details.php?id=" . $_POST["session_id"]);
        exit();
    } catch (Exception $e) {
        die($mysqli->error . " " . $mysqli->errno);
    }
}
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (empty($_GET["id"]) || empty($_GET["session_id"])) {
        die("Exercise id and session id cannot be null");
    }

    $selectsql = sprintf(
        "SELECT * FROM workouts WHERE id = '%s'",
        $mysqli->real_escape_string($_GET["id"])
    );

    $exsql = sprintf(
        "SELECT * FROM exercises WHERE user_id='%s'",
        $_SESSION["user_id"]
    );

    try {
        $res = $mysqli->query($selectsql);
        $wkt = $res->fetch_all()[0];

        $res = $mysqli->query($exsql);
        $exercises = $res->fetch_all();
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
                            <a class="nav-link" href="../exercises/">Exercises</a>
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
        <h1>Edit workout</h1>
        <form method="post">
            <input name="session_id" type="hidden" value="<?= $_GET["session_id"] ?>">
            <input name="id" type="hidden" value="<?= $wkt[0] ?>">

            <label for="exercise_id" class="form-label">Exercise Name</label>
            <select name="exercise_id" required class="form-select">
                <option value="">--Select an exercise--</option>
                <?php foreach ($exercises as $i) : ?>
                    <?php if ($wkt[2] == $i[0]) : ?>
                        <option value="<?= $i[0] ?>" selected="selected"><?= $i[1] ?></option>
                    <?php else : ?>
                        <option value="<?= $i[0] ?>"><?= $i[1] ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <label for="sets" class="form-label">Sets</label>
            <input type="number" name="sets" required value="<?= $wkt[4] ?>" class="form-control">

            <label for="reps" class="form-label">Reps</label>
            <input type="number" name="reps" required value="<?= $wkt[5] ?>" class="form-control">

            <label for="weight" class="form-label">Weight</label>
            <input type="number" name="weight" required value="<?= $wkt[6] ?>" class="form-control">

            <input type="submit" value="Update workout" class="btn btn-primary my-3">
        </form>
        <a href="../index.php"><button class="btn btn-secondary">Cancel</button></a>
    </div>
</body>

</html>