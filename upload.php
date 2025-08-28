<?php
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = uniqid() . '-' . basename($file['name']);
    $targetFile = $uploadDir . $fileName;

    // Basit uzantı kontrolü (jpg, png, gif, webp, jpeg)
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt)) {
        http_response_code(400);
        echo json_encode(['error' => 'Geçersiz dosya türü.']);
        exit;
    }

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        echo json_encode(['location' => $targetFile]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Yükleme başarısız.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Dosya alınamadı.']);
}
?>
