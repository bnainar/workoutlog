<?php

session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_SESSION["user_id"])) {
    if (empty($_POST["id"])) {
        die("Provide a valid exercise id to delete.");
    }
    $mysqli = require __DIR__ . "/db.php";

    $sql = sprintf(
        "DELETE FROM exercises WHERE id = '%s'",
        $mysqli->real_escape_string($_POST["id"])
    );
    try {
        $res = $mysqli->query($sql);
        header("Location: exercises.php");
        exit();
    } catch (Exception $e) {
        die($mysqli->error . " " . $mysqli->errno);
    }
}
