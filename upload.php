<?php
session_start();
$userId = $_SESSION['user_id'];

$targetDir = "uploads/";
$imageFileType = strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($imageFileType, $allowed)) {
    die("Format invalid. Doar JPG, JPEG, PNG, GIF sunt permise.");
}

$newFileName = uniqid() . "." . $imageFileType;
$targetFile = $targetDir . $newFileName;

if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
    $conn = new mysqli("localhost", "root", "", "wedding_app");
    $stmt = $conn->prepare("UPDATE furnizor SET profile_pic = ? WHERE id = ?");
    $stmt->bind_param("si", $newFileName, $userId);
    $stmt->execute();
    header("Location: home_f.php"); // întoarcere la profil
} else {
    echo "Eroare la upload.";
}



$uploadDir = "uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
    $fileName = basename($_FILES['images']['name'][$key]);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($tmpName, $targetFile)) {
        echo "Imaginea $fileName a fost încărcată cu succes.<br>";
    } else {
        echo "Eroare la încărcarea imaginii $fileName.<br>";
    }
}
?>



