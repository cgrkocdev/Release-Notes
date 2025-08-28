<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-giris.php");
    exit;
}

$vt = new PDO("mysql:host=localhost;dbname=toya_db;charset=utf8", "root", "");

if ($_POST) {
    $ad = $_POST['ad'];
    $vt->prepare("INSERT INTO kategoriler (ad) VALUES (?)")->execute([$ad]);
    header("Location: admin-panel.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kategori Ekle</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white p-8">
  <div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Yeni Kategori Ekle</h1>
    <form method="post" class="space-y-4">
      <input name="ad" placeholder="Kategori Adı" required class="w-full px-3 py-2 rounded bg-slate-800 text-white">
      <button type="submit" class="w-full bg-green-600 py-2 rounded hover:bg-green-700">Kategori Ekle</button>
    </form>
  </div>
</body>
</html>
