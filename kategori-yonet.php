<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-giris.php");
    exit;
}

$vt = new PDO("mysql:host=localhost;dbname=toya_db;charset=utf8", "root", "");

// Silme
if (isset($_GET['sil'])) {
    $vt->prepare("DELETE FROM kategoriler WHERE id = ?")->execute([$_GET['sil']]);
    header("Location: kategori-yonet.php");
    exit;
}

// Güncelleme
if (isset($_POST['guncelle'])) {
    $vt->prepare("UPDATE kategoriler SET ad = ? WHERE id = ?")->execute([$_POST['ad'], $_POST['id']]);
    header("Location: kategori-yonet.php");
    exit;
}

// Ekleme
if (isset($_POST['ekle'])) {
    $vt->prepare("INSERT INTO kategoriler (ad) VALUES (?)")->execute([$_POST['ad']]);
    header("Location: kategori-yonet.php");
    exit;
}

$kategoriler = $vt->query("SELECT * FROM kategoriler ORDER BY ad ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kategori Yönetimi</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white p-8">
  <div class="max-w-2xl mx-auto space-y-8">
    <h1 class="text-2xl font-bold">Kategori Yönetimi</h1>

    <form method="post" class="space-y-4">
      <input type="text" name="ad" placeholder="Yeni kategori adı" required class="w-full px-3 py-2 rounded bg-slate-800 text-white">
      <button type="submit" name="ekle" class="w-full bg-green-600 py-2 rounded hover:bg-green-700">Kategori Ekle</button>
    </form>

    <div class="bg-slate-800 rounded p-4 mt-6">
      <h2 class="text-xl font-semibold mb-4">Mevcut Kategoriler</h2>
      <?php foreach($kategoriler as $kat): ?>
        <form method="post" class="flex items-center gap-4 mb-2">
          <input type="hidden" name="id" value="<?= $kat['id'] ?>">
          <input name="ad" value="<?= htmlspecialchars($kat['ad']) ?>" class="flex-1 px-3 py-2 rounded bg-slate-700 text-white">
          <button type="submit" name="guncelle" class="bg-yellow-500 px-4 py-2 rounded hover:bg-yellow-600">Güncelle</button>
          <a href="?sil=<?= $kat['id'] ?>" onclick="return confirm('Silmek istediğinize emin misiniz?')" class="bg-red-600 px-4 py-2 rounded hover:bg-red-700">Sil</a>
        </form>
      <?php endforeach; ?>
    </div>

  </div>
</body>
</html>
