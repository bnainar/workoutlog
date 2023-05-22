<?php
$mysqli = require dirname(__FILE__, 2) . "/db.php";
session_start();
$duplicate = false;
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (empty($_GET["session_id"])) {
        die("Session id cannot be null");
    }
    $sql = sprintf(
        "SELECT * FROM exercises WHERE user_id='%s'",
        $_SESSION["user_id"]
    );
    $res = $mysqli->query($sql);
    $exercises = $res->fetch_all();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["exercise_id"])) {
        die("Exercise id cannot be null");
    }
    $sql = sprintf(
        "INSERT INTO workouts (session_id, exercise_id, user_id, sets, reps, weight) VALUES ('%s','%s','%s','%s','%s','%s')",
        $mysqli->real_escape_string($_POST["session_id"]),
        $mysqli->real_escape_string($_POST["exercise_id"]),
        $mysqli->real_escape_string($_SESSION["user_id"]),
        $mysqli->real_escape_string($_POST["sets"]),
        $mysqli->real_escape_string($_POST["reps"]),
        $mysqli->real_escape_string($_POST["weight"])
    );
    try {
        $res = $mysqli->query($sql);
        header("Location: ../sessions/session_details.php?id=" . $_POST["session_id"]);
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
    <title>Add a workout</title>
    <link rel="stylesheet" href="../bootstrap.min.css" />
    <link rel="stylesheet" href="../styles.css" />
</head>

<body style="background-color:#E8EAF6;">
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
                            <a class="nav-link" href="../sessions/">Sessions</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-lg m-20 px-auto" style="max-width:800px; margin:20px auto;">
        <h1>Add a workout</h1>
        <?php if (false) : ?>
            <b>There is already an exercise with the same name. Try again with a different name</b>
        <?php endif; ?>
        <form method="post">
            <input name="session_id" type="hidden" value="<?= $_GET["session_id"] ?>">
            <label for="exercise_id" class="form-label">Exercise Name</label>
            <select name="exercise_id" required class="form-select">
                <option value="" disabled selected>--Select an exercise--</option>
                <?php foreach ($exercises as $i) : ?>
                    <option value="<?= $i[0] ?>"><?= $i[1] ?></option>
                <?php endforeach; ?>
            </select>

            <label for="sets" class="form-label">Sets</label>
            <input type="number" name="sets" required class="form-control">

            <label for="reps" class="form-label">Reps</label>
            <input type="number" name="reps" required class="form-control">

            <label for="weight" class="form-label">Weight</label>
            <input type="number" name="weight" required class="form-control">

            <input type="submit" value="Add workout" class="btn btn-primary my-3">
        </form>
        <a href="../index.php"><button class="btn btn-secondary">Cancel</button></a>
    </div>
</body>

</html>