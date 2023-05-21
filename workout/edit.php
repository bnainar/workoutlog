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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css" />
</head>

<body>
    <h1>Edit workout</h1>
    <form method="post">
        <input name="session_id" type="hidden" value="<?= $_GET["session_id"] ?>">
        <input name="id" type="hidden" value="<?= $wkt[0] ?>">
        <label for="exercise_id">Exercise id</label>
        <select name="exercise_id" required>
            <option value="">--Select an exercise--</option>
            <?php foreach ($exercises as $i) : ?>
                <?php if ($wkt[2] == $i[0]) : ?>
                    <option value="<?= $i[0] ?>" selected="selected"><?= $i[1] ?></option>
                <?php else : ?>
                    <option value="<?= $i[0] ?>"><?= $i[1] ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <label for="sets">Sets</label>
        <input type="number" name="sets" required value="<?= $wkt[4] ?>">
        <label for="reps">Reps</label>
        <input type="number" name="reps" required value="<?= $wkt[5] ?>">
        <label for="weight">Weight</label>
        <input type="number" name="weight" required value="<?= $wkt[6] ?>">
        <input type="submit" value="Update workout">
    </form>
    <a href="../index.php"><button><- Go Home</button></a>
</body>

</html>