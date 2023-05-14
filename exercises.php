<?php
session_start();
if (empty($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
$mysqli = require __DIR__ . "/db.php";
$sql = sprintf(
    "SELECT e.id, e.name, e.description, COUNT(w.exercise_id) FROM exercises e LEFT JOIN workouts w ON e.id = w.exercise_id WHERE e.user_id='%s' GROUP BY e.id;",
    $_SESSION["user_id"]
);
$res = $mysqli->query($sql);
$arr = $res->fetch_all();
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
    <h1>List of all added exercises (<?= $res->num_rows ?>)</h1>
    <a href="index.php">Home</a>
    <a href="create_exercise.php">
        <button>Add Exercise +</button></a>
    <?php if ($res->num_rows === 0) : ?>
        No exercises are added yet. <br>
    <?php else : ?>
        <?php foreach ($arr as $i) : ?>
            <div>
                <form action="delete_exercise.php" method="post">
                    <span>
                        <h3><?= $i[1] ?> </h3>
                        <input type="text" name="id" value="<?= $i[0] ?>" style="display:none">
                        <input type="submit" value="x">
                    </span>
                    <p>Description: <?= $i[2] ?></p>
                    <p>No of workouts logged: <?= $i[3] ?></p>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</body>

</html>