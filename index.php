<?php
session_start();
if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/db.php";
    $sql = sprintf("SELECT * FROM users WHERE id = '%s'", $_SESSION["user_id"]);
    $res = $mysqli->query($sql);
    $user = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css" />
</head>

<body>
    <h1>Welcome to WorkoutLog</h1>
    <?php if (isset($_SESSION["user_id"])) : ?>
        <p>Hi <?= $user["name"] ?></p>
        <a href="logout.php">Logout</a>
        <h2>Exercises</h2>
        <a href="create_exercise.php"><button>Add Exercises +</button></a>
        <a href="exercises.php"><button>See all...</button></a>
        <h2>Workout sessions</h2>
        <a href="create_session.php"><button>Add a session +</button></a>
        <a href="sessions.php"><button>See all...</button></a>
    <?php else : ?>
        <p><a href="login.php">Log in</a> or <a href="signup.html">Sign up</a> to use the app.</p>
    <?php endif; ?>
</body>

</html>