<?php

session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_SESSION["user_id"])) {
    if (empty($_POST["id"])) {
        die("Provide a valid session id to delete.");
    }
    $mysqli = require dirname(__FILE__, 2) . "/db.php";

    $sql = sprintf(
        "DELETE FROM session WHERE id = '%s'",
        $mysqli->real_escape_string($_POST["id"])
    );
    try {
        $res = $mysqli->query($sql);
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        die($mysqli->error . " " . $mysqli->errno);
    }
}
