<?php
session_start();
if (empty($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $mysqli = require __DIR__ . "/db.php";
    $sql = sprintf(
        "SELECT * FROM workouts WHERE user_id='%s' AND exercise_id='%s'",
        $_SESSION["user_id"],
        $_GET["exercise_id"]
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
    <h1>Logs of this exercise (<?= $res->num_rows ?>)</h1>
    <a href="index.php">Home</a>
    <a href="exercises.php">
        <button class="btn btn-primary">See all exercises</button></a>
    <?php if ($res->num_rows === 0) : ?>
        <h3>No workouts in this session.</h3>
    <?php else : ?>
        <table>
            <tr>
                <th>Sets</th>
                <th>Reps</th>
                <th>Weight (in kg)</th>
            </tr>

            <?php foreach ($arr as $i) : ?>
                <tr>
                    <td><?= $i[4] ?></td>
                    <td><?= $i[5] ?></td>
                    <td><?= $i[6] ?></td>

                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</body>

</html>