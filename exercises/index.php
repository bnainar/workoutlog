<?php
session_start();
if (empty($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}
$mysqli = require dirname(__FILE__, 2) . "/db.php";
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
    <link rel="stylesheet" href="../bootstrap.min.css" />
    <link rel="stylesheet" href="../styles.css" />
    <link rel="stylesheet" href="../feathericon.min.css" />
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
                            <a class="nav-link" aria-current="page" href="../index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="./index.php">Exercises</a>
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
        <h1>List of all added exercises (<?= $res->num_rows ?>)</h1>
        <div class="row">
            <div class="col">
                <?php if ($res->num_rows === 0) : ?>
                    No exercises are added yet. <br>
                <?php else : ?>
                    <?php foreach ($arr as $i) : ?>
                        <div class="card my-3 shadow-sm" style="width: 30rem;">
                            <div class="card-body">
                                <form action="" method="">
                                    <span>
                                        <h3 class="card-title"><?= $i[1] ?> </h3>
                                        <hr>
                                        <div class="row mt-3">
                                            <div class="col w-75">
                                                <i data-feather="bookmark"></i>
                                                <h6 class="my-1">Description:</h6>
                                                <p><?= $i[2] ?></p>
                                            </div>
                                            <div class="col w-25">
                                                <i data-feather="list"></i>
                                                <h6 class="my-1">No of workouts logged:</h6>
                                                <p><?= $i[3] ?></p>
                                            </div>
                                        </div>

                                        <input type="text" name="id" value="<?= $i[0] ?>" style="display:none">
                                        <a href="history.php?exercise_id=<?= $i[0] ?>" class="btn btn-primary">See history</a>
                                        <a href="delete.php?id=<?= $i[0] ?>" class="btn btn-outline-danger ms-2"> Delete exercise</a>
                                    </span>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="col">
                <a href="create.php">
                    <button class="btn btn-primary btn-lg mt-3 shadow">Add Exercise +</button></a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace()
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>