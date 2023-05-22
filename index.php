<?php
session_start();
if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/db.php";
    $sql = sprintf("SELECT * FROM users WHERE id = '%s'", $_SESSION["user_id"]);
    $userres = $mysqli->query($sql);
    $user = $userres->fetch_assoc();

    $sessionsql = sprintf(
        "SELECT session.id, session.date, session.calories_burned, session.duration_minutes, session.notes, COUNT(workouts.session_id) FROM `session` LEFT JOIN workouts ON session.id=workouts.session_id WHERE session.user_id=%s GROUP BY session.id;",
        $_SESSION["user_id"]
    );
    $sessionres = $mysqli->query($sessionsql);
    $sessions = $sessionres->fetch_all();


    $exsql = sprintf(
        "SELECT w.id, e.name, w.sets, w.reps, w.weight FROM workouts w INNER JOIN exercises e ON e.id=w.exercise_id WHERE w.user_id=%s GROUP BY w.id LIMIT 6;",
        $_SESSION["user_id"]
    );
    $exres = $mysqli->query($exsql);
    $exercises = $exres->fetch_all();
} else {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="./bootstrap.min.css" />
    <link rel="stylesheet" href="./styles.css" />
</head>

<body style="background-color:#ECEFF1;">
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
                            <a class="nav-link active" aria-current="page" href="./index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./exercises/">Exercises</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./sessions/">Sessions</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="container-lg m-20 px-auto" style="max-width:1200px; margin:20px auto;">
        <h1>Welcome back, <?= $user["name"] ?></h1>
        <p>Not <?= $user["name"] ?>? <a href="logout.php"><button type="button" class="btn btn-outline-danger ms-3">Logout</button></a></p>
        <div class="row mt-3">
            <div class="col">
                <h2>Recent Workouts</h2>
                <?php if ($exres->num_rows === 0) : ?>
                    No workouts are added. Add some! <br>
                <?php else : ?>
                    <table class="table table-success mt-3">
                        <thead>

                            <tr>
                                <th scope="col">Exercise Name</th>
                                <th scope="col">Sets</th>
                                <th scope="col">Reps</th>
                                <th scope="col">Weight (in kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exercises as $i) : ?>
                                <tr>
                                    <td><?= $i[1] ?></td>
                                    <td><?= $i[2] ?></td>
                                    <td><?= $i[3] ?></td>
                                    <td><?= $i[4] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <a href="exercises/create.php"><button class="btn btn-primary">Add Exercises +</button></a>
                <a href="exercises/"><button class="btn btn-secondary">See all...</button></a>
            </div>
            <div class="col">
                <h2>Workout sessions</h2>
                <?php if ($sessionres->num_rows === 0) : ?>
                    No sessions are logged. Add one! <br>
                <?php else : ?>
                    <table class="table table-primary mt-3">
                        <thead>
                            <tr>
                                <th scope="col">Time</th>
                                <th scope="col">Calories burned</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Notes</th>
                                <th scope="col">No. of workouts</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($sessions as $i) : ?>
                                <tr>
                                    <td><?= $i[1] ?></td>
                                    <td><?= $i[2] ?></td>
                                    <td><?= $i[3] ?></td>
                                    <td><?= $i[4] ?></td>
                                    <td><?= $i[5] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <a href="sessions/create.php"><button class="btn btn-primary">Add a session +</button></a>
                <a href="sessions/"><button class="btn btn-secondary">See all...</button></a>
            </div>
        </div>
    </div>
</body>

</html>