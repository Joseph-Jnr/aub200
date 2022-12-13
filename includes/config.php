<?php

$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "exam_db";

/* Attempt to connect to MySQL database */
$link = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($link === false) {
    die("Could not connect. Contact Developer");
}