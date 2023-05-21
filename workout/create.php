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
    // var_dump($_POST);
    die();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a workout</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css" />
</head>

<body>
    <h1>Add a workout</h1>
    <?php if (false) : ?>
        <b>There is already an exercise with the same name. Try again with a different name</b>
    <?php endif; ?>
    <form method="post">
        <input name="session_id" type="hidden" value="<?= $_GET["session_id"] ?>">
        <label for="exercise_id">Exercise Name</label>
        <select name="exercise_id" required>
            <option value="" disabled selected>--Select an exercise--</option>
            <?php foreach ($exercises as $i) : ?>
                <option value="<?= $i[0] ?>"><?= $i[1] ?></option>
            <?php endforeach; ?>
        </select>
        <label for="sets">Sets</label>
        <input type="number" name="sets" required>
        <label for="reps">Reps</label>
        <input type="number" name="reps" required>
        <label for="weight">Weight</label>
        <input type="number" name="weight" required>
        <input type="submit" value="Add workout">
    </form>
    <a href="../index.php"><button><- Go Home</button></a>
</body>

</html>