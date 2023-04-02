<?php

$host = "localhost:3306";
$user = "root";
$password = "root";
$db = "AllWell-db";

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connessione fallita: " . mysqli_connect_error());
}
