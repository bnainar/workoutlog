<?php
session_start();
if (empty($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $mysqli = require dirname(__FILE__, 2) . "/db.php";
    $wktsql = sprintf(
        "SELECT * FROM workouts WHERE user_id='%s' AND exercise_id='%s'",
        $_SESSION["user_id"],
        $_GET["exercise_id"]
    );
    $wktres = $mysqli->query($wktsql);
    $wkt = $wktres->fetch_all();

    $exsql = sprintf(
        "SELECT * FROM exercises WHERE user_id='%s' AND id='%s'",
        $_SESSION["user_id"],
        $_GET["exercise_id"]
    );
    $exres = $mysqli->query($exsql);
    $ex = $exres->fetch_all()[0];
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
    <link rel="stylesheet" href="../bootstrap.min.css" />
    <link rel="stylesheet" href="../styles.css" />
</head>

<body style="background-color:#FFEBEE;">
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
                            <a class="nav-link" aria-current="page" href="../">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./">Exercises</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../sessions/">Sessions</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-lg m-20 px-auto" style="max-width:800px; margin:20px auto;">
        <h1><?= $ex[1] ?></h1>
        <h3>Description: </h3>
        <p><?= $ex[3] ?></p>
        <a href="edit.php?id=<?= $ex[0] ?>"><button class="btn btn-primary">Edit</button></a>
        <h2 class="my-3">Logs of this exercise (<?= $wktres->num_rows ?>)</h2>

        <?php if ($wktres->num_rows === 0) : ?>
            <h3>No logs of this exercises has been added yet.</h3>
        <?php else : ?>
            <table class="table table-secondary">
                <thead>
                    <tr>
                        <th scope="col">Sets</th>
                        <th scope="col">Reps</th>
                        <th scope="col">Weight (in kg)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wkt as $i) : ?>
                        <tr>
                            <td><?= $i[4] ?></td>
                            <td><?= $i[5] ?></td>
                            <td><?= $i[6] ?></td>

                        </tr>
                    <?php endforeach; ?>
                <tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>