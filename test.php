<?php
$mysqli = new mysqli("localhost", "root", "", "test");

if ($mysqli->connect_error) {
    die("Eroare conexiune: " . $mysqli->connect_error);
}
echo "Conexiune reușită!";
?>