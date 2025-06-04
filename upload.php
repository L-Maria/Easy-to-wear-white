<?php
session_start();
$userId = $_SESSION['user_id'];
$targetDir = "uploads/";
$allowed = ['jpg', 'jpeg', 'png', 'gif'];

$conn = new mysqli("localhost", "root", "", "wedding_app");

if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    // ⬅️ Upload pentru poza de profil
    $imageFileType = strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));
    if (!in_array($imageFileType, $allowed)) {
        die("Format invalid. Doar JPG, JPEG, PNG, GIF sunt permise.");
    }

    $newFileName = uniqid() . "." . $imageFileType;
    $targetFile = $targetDir . $newFileName;

    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
        $stmt = $conn->prepare("UPDATE furnizor SET profile_pic = ? WHERE id = ?");
        $stmt->bind_param("si", $newFileName, $userId);
        $stmt->execute();
        header("Location: home_f.php");
        exit;
    } else {
        echo "Eroare la upload poza de profil.";
    }

} elseif (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
    // ⬅️ Upload pentru galerie (multiple)
    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
        $fileName = $_FILES['images']['name'][$index];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($fileType, $allowed)) {
            $newFileName = uniqid() . "." . $fileType;
            $targetFile = $targetDir . $newFileName;

            if (move_uploaded_file($tmpName, $targetFile)) {
                $stmt = $conn->prepare("INSERT INTO images (furnizor_id, path) VALUES (?, ?)");
                $stmt->bind_param("is", $userId, $targetFile);
                $stmt->execute();
            }
        }
    }

    header("Location: home_f.php");
    exit;

} else {
    echo "Nicio imagine nu a fost trimisă.";
}
?>
