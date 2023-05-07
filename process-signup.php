<?php
// validation
if (empty($_POST["name"])) {
    die("Name is empty");
}
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Email is not valid");
}
if (strlen($_POST["password"]) < 8) {
    die("Password length should be atleast 8");
}
if (!preg_match("/\d+/", $_POST["password"]) || !preg_match("/[a-z]+/i", $_POST["password"])) {
    die("Passwords must contain atleast one number and one letter");
}
if ($_POST["password"] != $_POST["passwordrepeat"]) {
    die("Passwords don't match");
}
$pass_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
$mysqli = require __DIR__ . "/db.php";

$sql = "INSERT INTO users (name, email, password_hash) VALUES (?,?,?)";
$stmt = $mysqli->stmt_init();

if (!$stmt->prepare($sql)) {
    die("SQL stmt prep error: " . $mysqli->error);
}
$stmt->bind_param("sss", $_POST["name"], $_POST["email"], $pass_hash);

try {
    $stmt->execute();
    echo ("\nSignUp Succesful");
    header("Location: signup-success.html");
    exit();
} catch (Exception $e) {
    if ($mysqli->errno === 1062) {
        die("Email already in use");
    } else {
        die($mysqli->error . "" . $mysqli->errno);
    }
}
