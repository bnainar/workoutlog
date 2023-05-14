<?php

session_start();
$duplicate = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["name"])) {
        die("Exercise name cannot be null");
    }

    $mysqli = require __DIR__ . "/db.php";
    var_dump($_POST);
    $sql = "";
    if ($_POST["desc"] === "") {
        $sql = sprintf(
            "INSERT INTO exercises (name, user_id) VALUES ('%s','%s')",
            $mysqli->real_escape_string($_POST["name"]),
            $mysqli->real_escape_string($_SESSION["user_id"])
        );
    } else {
        $sql = sprintf(
            "INSERT INTO exercises (name, user_id, description) VALUES ('%s','%s','%s')",
            $mysqli->real_escape_string($_POST["name"]),
            $mysqli->real_escape_string($_SESSION["user_id"]),
            $mysqli->real_escape_string($_POST["desc"])
        );
    }
    try {
        $res = $mysqli->query($sql);
        header("Location: exercises.php");
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css" />
</head>

<body>
    <h1>Create a exercise</h1>
    <?php if ($duplicate) : ?>
        <b>There is already an exercise with the same name. Try again with a different name</b>
    <?php endif; ?>
    <form method="post">
        <label for="name">Exercise Name</label>
        <input type="text" name="name" id="name" required>
        <label for="desc">Description (optional)</label>
        <textarea name="desc" id="desc" cols="60" rows="8"></textarea>
        <input type="submit" value="Create a exercise +">
    </form>
    <a href="exercises.php"><button><- View all exercises</button></a>
</body>

</html>