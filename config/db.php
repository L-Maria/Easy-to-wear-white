<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // fără parolă, implicit în XAMPP
//$db = 'wedding_app';

$conn = new mysqli($host, $user, $pass, 'wedding_app');

if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}
?>
