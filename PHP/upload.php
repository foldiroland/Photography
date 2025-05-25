<?php
// config.php
$host = 'localhost';
$db   = 'photography';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
     throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// upload.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => 'Hiba a fájl feltöltésénél.']);
        exit;
    }

    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = basename($_FILES['photo']['name']);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
        require 'config.php';

        $stmt = $pdo->prepare("INSERT INTO photos (title, description, filename) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $filename]);

        echo json_encode(['success' => true, 'message' => 'Kép sikeresen feltöltve.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Nem sikerült áthelyezni a feltöltött fájlt.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Nem támogatott kérésmódszer.']);
}