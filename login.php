<?php
$is_invalid = false;
session_start();
if (!empty($_SESSION["user_id"])) {
  header("Location: index.php");
  exit();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $mysqli = require __DIR__ . "/db.php";
  $sql = sprintf(
    "SELECT * FROM users WHERE email = '%s'",
    $mysqli->real_escape_string($_POST["email"])
  );
  $res = $mysqli->query($sql);
  $user = $res->fetch_assoc();
  if ($user) {
    if (password_verify($_POST["password"], $user["password_hash"])) {
      $_SESSION["user_id"] = $user["id"];
      header("Location: index.php");
      exit();
    }
  }
  $is_invalid = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="./bootstrap.min.css" />
</head>

<body style="background-color:#ECEFF1;">
  <div class="container-lg mx-auto my-auto pt-5" style="max-width: 500px;">
    <h1>Login to Workout Logger </h1>
    <?php if ($is_invalid) : ?>
      <div class="alert alert-warning mt-3" role="alert" style="max-width: 400px;">
        Invalid login credentials. Try again</div>
    <?php endif; ?>
    <form action="" method="post" class="my-3 py-3" style="max-width: 400px;">

      <label for="email" class="form-label">Email</label>
      <input type="email" name="email" id="email" class="form-control mb-2" />

      <label for="password" class="form-label">Password</label>
      <input type="password" name="password" id="password" class="form-control" />

      <input type="submit" value="Log in" class="btn btn-primary mt-3" />
    </form>
    <p>Don't have an account? <a href="signup.html">Sign up</a> now.</p>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>