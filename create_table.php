<?php

require_once 'db.php';

$sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255),
    password_hash VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS exercises (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    userId INT(11),
    description VARCHAR(255),
    FOREIGN KEY (userId) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS session (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11),
    date DATETIME,
    duration_minutes INT(11),
    calories_burned INT(11),
    notes VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS workouts (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    session_id INT(11),
    exercise_id INT(11),
    user_id INT(11),
    sets INT(11),
    reps INT(11),
    weight INT(11),
    FOREIGN KEY (session_id) REFERENCES session(id),
    FOREIGN KEY (exercise_id) REFERENCES exercises(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
";

if ($mysqli->multi_query($sql)) {
    echo "Tables created successfully.";
} else {
    echo "Error creating tables: " . $mysqli->error;
}

$mysqli->close();
