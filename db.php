<?php
$mysqli = new mysqli("localhost", "root", "", "workoutdbv1");
if ($mysqli->connect_errno) {
    die("Connection Error" . $mysqli->connect_error);
}
return $mysqli;
