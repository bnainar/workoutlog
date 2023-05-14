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
        <h1>List of all added exercises (<?= $res->num_rows ?>)</h1>
        <a href="index.php"><button class="btn btn-secondary my-2 me-2">Home</button></a>
        <a href="create_exercise.php">
            <button class="btn btn-primary">Add Exercise +</button></a>
        <?php if ($res->num_rows === 0) : ?>
            No exercises are added yet. <br>
        <?php else : ?>
            <?php foreach ($arr as $i) : ?>
                <div class="card my-3 shadow-sm" style="width: 30rem;">
                    <div class="card-body">
                        <form action="" method="">
                            <span>
                                <h3 class="card-title"><?= $i[1] ?> </h3>
                                <p class="card-text my-2">Description: <?= $i[2] ?></p>
                                <p class="card-text my-2">No of workouts logged: <?= $i[3] ?></p>
                                <input type="text" name="id" value="<?= $i[0] ?>" style="display:none">
                                <a href="history.php?exercise_id=<?= $i[0] ?>" class="btn btn-primary">See history</a>
                                <input type="submit" class="btn btn-outline-danger" value="Delete exercise">
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