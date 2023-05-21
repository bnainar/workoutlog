<?php
session_start();
if (empty($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
$mysqli = require dirname(__FILE__, 2) . "/db.php";
$sql = sprintf(
    "SELECT session.id, session.date, session.calories_burned, session.duration_minutes, session.notes, COUNT(workouts.session_id) FROM `session` LEFT JOIN workouts ON session.id=workouts.session_id WHERE session.user_id=%s GROUP BY session.id;",
    $_SESSION["user_id"]
);
$res = $mysqli->query($sql);
$arr = $res->fetch_all();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap.min.css" />
    <link rel="stylesheet" href="../styles.css" />
    <link rel="stylesheet" href="../feathericon.min.css" />
</head>

<body>
    <div class="container px-0 mx-0" style="min-width:100%;" id="navcont">
        <nav class="navbar navbar-expand-lg bg-body-tertiary" style="padding:1rem 2rem;">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">WorkoutLogger</a>
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Account
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Logout</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div class="container-lg m-20 px-auto" style="max-width:800px; margin:20px auto;">
        <h1>List of all added sessions (<?= $res->num_rows ?>)</h1>

        <div class="row">
            <div class="col">
                <?php if ($res->num_rows === 0) : ?>
                    No sessions are added yet. <br>
                <?php else : ?>
                    <?php foreach ($arr as $i) : ?>

                        <div class="card my-3 shadow-sm" style="width: 30rem;">
                            <div class="card-body">
                                <form action="delete.php" method="post">
                                    <span>
                                        <h5 class="card-title">Date: <?= $i[1] ?> </h5>
                                        <hr>
                                        <div class="row mt-3">
                                            <div class="col">
                                                <i data-feather="list"></i>
                                                <h6 class="my-1">No of workouts: </h6>
                                                <p><?= $i[2] ?></p>
                                                <i data-feather="zap"></i>
                                                <h6 class="my-1">Calories burned: </h6>
                                                <p><?= $i[2] ?></p>
                                            </div>
                                            <div class="col">
                                                <i data-feather="clock"></i>
                                                <h6 class="my-1">Duration: </h6>
                                                <p><?= $i[3] ?> min</p>
                                                </p>
                                                <i data-feather="bookmark"></i>
                                                <h6 class="my-1">Notes: </h6>
                                                <p><?= $i[4] ?></p>
                                                </p>
                                            </div>
                                        </div>
                                        <a href="session_details.php?id=<?= $i[0] ?>" class="btn btn-primary">See more...</a>
                                        <input type="text" name="id" value="<?= $i[0] ?>" style="display:none">
                                        <input type="submit" class="btn btn-danger ms-2" value="Delete session">
                                    </span>
                                </form>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="col"><a href="create.php">
                    <button class="btn btn-primary btn-lg my-3 shadow">Add Session +</button></a></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace()
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>