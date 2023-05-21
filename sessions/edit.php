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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css" />
</head>

<body>
    <h1>Edit Workout Session Details</h1>
    <form method="post">
        <input name="id" type="hidden" value="<?= $session[0] ?>">
        <label for="date">Date</label>
        <input type="datetime-local" name="date" id="date" value="<?= $session[2] ?>">
        <label for="duration">Duration (in minutes)</label>
        <input type="number" name="duration" id="duration" required value="<?= $session[3] ?>">
        <label for="calories">Calories burned</label>
        <input type="number" name="calories" id="calories" required value="<?= $session[4] ?>">
        <label for="notes">Notes</label>
        <textarea name="notes" id="notes" cols="60" rows="8"><?= $session[5] ?></textarea>
        <input type="submit" value="Update session">
    </form>
    <a href="index.php"><button><- View all sessions</button></a>
</body>

</html>