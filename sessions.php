<?php
session_start();
if (empty($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
$mysqli = require __DIR__ . "/db.php";
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
    <style>
        a {
            text-decoration: none;
            color: black;
        }

        .card {
            transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
        }

        .card:hover {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important
        }
    </style>
</head>

<body style="background-color: var(--bs-gray-200);">
    <div class="container-lg m-20 px-auto" style="max-width:800px; margin:20px auto;">
        <h1>List of all added sessions (<?= $res->num_rows ?>)</h1>
        <a href="index.php"><button class="btn btn-secondary my-2 me-2">Home</button></a>
        <a href="create_session.php">
            <button class="btn btn-primary">Add Session +</button></a>
        <?php if ($res->num_rows === 0) : ?>
            No sessions are added yet. <br>
        <?php else : ?>
            <?php foreach ($arr as $i) : ?>

                <div class="card my-3 shadow-sm" style="width: 30rem;">
                    <div class="card-body">
                        <form action="delete_session.php" method="post">
                            <span>
                                <h5 class="card-title">Date: <?= $i[1] ?> </h5>
                                <p>No of workouts: <?= $i[5] ?> </p>
                                <p>Calories burned: <?= $i[2] ?> </p>
                                <p>Duration: <?= $i[3] ?> min</p>
                                <p class="card-text my-2">Notes: <?= $i[4] ?> </p>
                                <a href="workouts.php?session_id=<?= $i[0] ?>" class="btn btn-primary">See more...</a>
                                <input type="text" name="id" value="<?= $i[0] ?>" style="display:none">
                                <input type="submit" class="btn btn-danger" value="Delete session">
                            </span>
                        </form>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>