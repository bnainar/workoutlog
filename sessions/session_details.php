<?php
session_start();
if (empty($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $mysqli = require dirname(__FILE__, 2) . "/db.php";

    try {
        $sql = sprintf(
            "SELECT w.id, e.name, w.sets, w.reps, w.weight FROM workouts w INNER JOIN exercises e ON e.id=w.exercise_id WHERE w.user_id=%s AND w.session_id=%s GROUP BY w.id;",
            $_SESSION["user_id"],
            $_GET["id"]
        );
        $res = $mysqli->query($sql);
        $arr = $res->fetch_all();
        $sessionsql = sprintf(
            "SELECT * FROM session WHERE user_id=%s AND id=%s",
            $_SESSION["user_id"],
            $_GET["id"]
        );
        $sessionres = $mysqli->query($sessionsql);
        $session = $sessionres->fetch_all()[0];
    } catch (Exception $e) {
        if ($mysqli->errno === 1064) {
            header("Location: index.php");
        } else {
            die($mysqli->error . "" . $mysqli->errno);
        }
    }
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
    <title>All workouts in this session</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css" />
    <style>
        div:hover {
            background-color: lightgreen;
        }
    </style>
</head>

<body>
    <h1>Session Details</h1>
    <span>
        <h2>Date: </h2>
        <p><?= $session[2] ?></p>
        <h2>Duration: </h2>
        <p><?= $session[3] ?></p>
    </span>
    <span>
        <h2>Calories Burned: </h2>
        <p><?= $session[4] ?></p>
        <h2>Notes: </h2>
        <p><?= $session[5] ?></p>
    </span>
    <a href="edit.php?id=<?= $session[0] ?>"><button>Edit</button></a>
    <h3>List of all workouts in this session (<?= $res->num_rows ?>)</h3>

    <?php if ($res->num_rows === 0) : ?>
        No workouts in this session. <br>
    <?php else : ?>
        <a href="../workout/create.php?session_id=<?= $_GET["id"] ?>"><button>Add a workout</button></a>
        <table>
            <tr>
                <th>Exercise Name</th>
                <th>Sets</th>
                <th>Reps</th>
                <th>Weight (in kg)</th>
                <th></th>
                <th></th>
            </tr>

            <?php foreach ($arr as $i) : ?>
                <tr>
                    <td><?= $i[1] ?></td>
                    <td><?= $i[2] ?></td>
                    <td><?= $i[3] ?></td>
                    <td><?= $i[4] ?></td>
                    <td>
                        <a href="../workout/edit.php?id=<?= $i[0] ?>&session_id=<?= $_GET["id"] ?>">
                            <button>Edit</button>
                        </a>
                    </td>
                    <td>
                        <a href="../workout/delete.php?id=<?= $i[0] ?>&session_id=<?= $_GET["id"] ?>">
                            <button>Delete</button>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <a href="../index.php">Home</a>
    <a href="index.php">
        <button>See all sessions</button></a>

</body>

</html>