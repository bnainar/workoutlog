<?php

session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["date"]) || empty($_POST["duration"]) || empty($_POST["calories"]) || empty($_POST["notes"])) {
        die("Please fill in required session details");
    }

    $mysqli = require __DIR__ . "/db.php";
    var_dump($_POST);
    $sql = sprintf(
        "INSERT INTO session (user_id, date, duration_minutes, calories_burned, notes) VALUES ('%s','%s','%s','%s','%s')",
        $mysqli->real_escape_string($_SESSION["user_id"]),
        $mysqli->real_escape_string($_POST["date"]),
        $mysqli->real_escape_string($_POST["duration"]),
        $mysqli->real_escape_string($_POST["calories"]),
        $mysqli->real_escape_string($_POST["notes"])
    );
    try {
        $res = $mysqli->query($sql);
        header("Location: sessions.php");
        exit();
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
    <title>Add a session</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css" />
</head>

<body>
    <h1>Create a Workout Session</h1>
    <form method="post">
        <label for="date">Date</label>
        <input type="datetime-local" name="date" id="date">
        <label for="duration">Duration (in minutes)</label>
        <input type="number" name="duration" id="duration" required>
        <label for="calories">Calories burned</label>
        <input type="number" name="calories" id="calories" required>
        <label for="notes">Notes</label>
        <textarea name="notes" id="notes" cols="60" rows="8"></textarea>
        <input type="submit" value="Create a session +">
    </form>
    <a href="sessions.php"><button><- View all sessions</button></a>
</body>

</html>