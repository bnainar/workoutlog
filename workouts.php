<?php
session_start();
if (empty($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $mysqli = require __DIR__ . "/db.php";
    $sql = sprintf(
        "SELECT w.id, e.name, w.sets, w.reps, w.weight FROM workouts w INNER JOIN exercises e ON e.id=w.exercise_id WHERE w.user_id=%s AND w.session_id=%s GROUP BY w.id;",
        $_SESSION["user_id"],
        $_GET["session_id"]
    );
    $res = $mysqli->query($sql);
    $arr = $res->fetch_all();
}
// while ($exercises = $res->fetch_assoc()) {
//     echo ($exercises["name"]);
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css" />
    <style>
        div:hover {
            background-color: lightgreen;
        }
    </style>
</head>

<body>
    <h1>List of all workouts in this session (<?= $res->num_rows ?>)</h1>
    <a href="index.php">Home</a>
    <a href="sessions.php">
        <button>See all sessions</button></a>
    <?php if ($res->num_rows === 0) : ?>
        No workouts in this session. <br>
    <?php else : ?>
        <table>
            <tr>
                <th>Exercise Name</th>
                <th>Sets</th>
                <th>Reps</th>
                <th>Weight (in kg)</th>
            </tr>

            <?php foreach ($arr as $i) : ?>
                <tr>
                    <td><?= $i[1] ?></td>
                    <td><?= $i[2] ?></td>
                    <td><?= $i[3] ?></td>
                    <td><?= $i[4] ?></td>

                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</body>

</html>