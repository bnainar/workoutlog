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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css" />
</head>

<body>
  <h1>Login to Workout Logger ▶️</h1>
  <?php if ($is_invalid) : ?>
    <p>Invalid login credentials. Try again</p>
  <?php endif; ?>
  <form action="" method="post">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" />

    <label for="password">Password</label>
    <input type="password" name="password" id="password" />

    <input type="submit" value="Log in" />
  </form>
  <p>Don't have an account? <a href="signup.html">Sign up</a> now.</p>
</body>

</html>