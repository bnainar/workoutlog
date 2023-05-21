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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css" />
</head>

<body>
    <h1>Create a exercise</h1>
    <?php if (false) : ?>
        <b>There is already an exercise with the same name. Try again with a different name</b>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="id" value="<?= $exercise[0] ?>">
        <label for="name">Exercise Name</label>
        <input type="text" name="name" id="name" required value="<?= $exercise[1] ?>">
        <label for="desc">Description (optional)</label>
        <textarea name="desc" id="desc" cols="60" rows="8"><?= $exercise[3] ?></textarea>
        <input type="submit" value="Update exercise">
    </form>
    <a href="index.php"><button><- View all exercises</button></a>
</body>

</html>