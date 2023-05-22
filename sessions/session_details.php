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
    <link rel="stylesheet" href="../bootstrap.min.css" />
    <link rel="stylesheet" href="../styles.css" />
</head>

<body style="background-color:#EFEBE9;">
    <div class="container px-0 mx-0" style="min-width:100%;" id="navcont">
        <nav class="navbar navbar-expand-lg bg-body-tertiary" style="padding:1rem 2rem;">
            <div class="container-fluid">
                <span class="navbar-brand">WorkoutLogger</span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                    <ul class="navbar-nav ">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="../index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../exercises/">Exercises</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./index.php">Sessions</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-lg m-20 px-auto" style="max-width:800px; margin:20px auto;">
        <h1>Session Details</h1>
        <div class="row my-3">
            <div class="col">
                <h3>Date: </h3>
                <p><?= $session[2] ?></p>
                <h3>Duration: </h3>
                <p><?= $session[3] ?></p>
            </div>
            <div class="col">
                <h3>Calories Burned: </h3>
                <p><?= $session[4] ?></p>
                <h3>Notes: </h3>
                <p><?= $session[5] ?></p>
            </div>
            <div class="col"><a href="edit.php?id=<?= $session[0] ?>">
                    <button class="btn btn-primary btn-lg">Edit Session Details</button></a></div>
        </div>

        <h3>List of all workouts in this session (<?= $res->num_rows ?>)</h3>
        <a href="../workout/create.php?session_id=<?= $_GET["id"] ?>">
            <button class="btn btn-primary">Add a workout</button></a>
        <?php if ($res->num_rows === 0) : ?>
            <p class="mt-3">No workouts in this session.</p>
        <?php else : ?>

            <table class="table table-secondary mt-3">
                <thead>

                    <tr>
                        <th scope="col">Exercise Name</th>
                        <th scope="col">Sets</th>
                        <th scope="col">Reps</th>
                        <th scope="col">Weight (in kg)</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arr as $i) : ?>
                        <tr>
                            <td><?= $i[1] ?></td>
                            <td><?= $i[2] ?></td>
                            <td><?= $i[3] ?></td>
                            <td><?= $i[4] ?></td>
                            <td>
                                <a href="../workout/edit.php?id=<?= $i[0] ?>&session_id=<?= $_GET["id"] ?>">
                                    <button class="btn btn-secondary">Edit</button>
                                </a>
                            </td>
                            <td>
                                <a href="../workout/delete.php?id=<?= $i[0] ?>&session_id=<?= $_GET["id"] ?>">
                                    <button class="btn btn-danger">Delete</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="index.php">
            <button class="btn btn-outline-secondary mt-3">See all sessions</button></a>
    </div>
</body>

</html>