<?php

session_start();
if ($_SERVER["REQUEST_METHOD"] === "GET" && !empty($_SESSION["user_id"])) {
    if (empty($_GET["id"])) {
        die("Provide a valid exercise id to delete.");
    }
    $mysqli = require dirname(__FILE__, 2) . "/db.php";

    $sql = sprintf(
        "DELETE FROM exercises WHERE id = '%s'",
        $mysqli->real_escape_string($_GET["id"])
    );
    try {
        $res = $mysqli->query($sql);
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        die($mysqli->error . " " . $mysqli->errno);
    }
}
