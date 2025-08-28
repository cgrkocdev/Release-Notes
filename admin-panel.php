<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-giris.php");
    exit;
}

$vt = new PDO("mysql:host=localhost;dbname=toya_db;charset=utf8", "root", "");

// Sürüm notlarını ve kategorileri çek
$surumler = $vt->query("SELECT s.*, k.ad AS kategori FROM surumler s JOIN kategoriler k ON s.kategori_id = k.id ORDER BY tarih DESC")->fetchAll(PDO::FETCH_ASSOC);

// Kategori ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kategori_adi'])) {
    $kategoriAdi = trim($_POST['kategori_adi']);
    if ($kategoriAdi !== '') {
        $stmt = $vt->prepare("INSERT INTO kategoriler (ad) VALUES (?)");
        $stmt->execute([$kategoriAdi]);
        header("Location: admin-panel.php");
        exit;
    }
}

// Kategori silme işlemi
if (isset($_GET['kategorisil'])) {
    $silID = intval($_GET['kategorisil']);
    $vt->prepare("DELETE FROM kategoriler WHERE id = ?")->execute([$silID]);
    header("Location: admin-panel.php");
    exit;
}

// Tüm kategorileri çek
$kategoriler = $vt->query("SELECT * FROM kategoriler")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Admin Paneli</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white p-8">

  <div class="max-w-5xl mx-auto">
    <!-- Başlık -->
    <h1 class="text-2xl font-bold mb-4">Sürüm Notları Yönetimi</h1>

    <!-- Yeni Ekle Butonu -->
    <a href="admin-ekle.php" class="inline-block mb-4 px-4 py-2 bg-green-600 rounded hover:bg-green-700">+ Yeni Ekle</a>

    <!-- Sürüm Notları Tablosu -->
    <table class="w-full text-sm mb-10">
      <thead class="bg-slate-800">
        <tr>
          <th class="p-2 text-left">Başlık</th>
          <th class="p-2 text-center">Tarih</th>
          <th class="p-2 text-center">Kategori</th>
          <th class="p-2 text-center">İşlemler</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($surumler as $s): ?>
          <tr class="border-b border-white/10 hover:bg-white/5">
            <td class="p-2"><?= htmlspecialchars($s['baslik']) ?></td>
            <td class="p-2 text-center"><?= date('d.m.Y', strtotime($s['tarih'])) ?></td>
            <td class="p-2 text-center"><?= htmlspecialchars($s['kategori']) ?></td>
            <td class="p-2 text-center">
              <a href="admin-duzenle.php?id=<?= $s['id'] ?>" class="text-blue-400 hover:underline">Düzenle</a>
              <a href="admin-sil.php?id=<?= $s['id'] ?>" onclick="return confirm('Silmek istediğinize emin misiniz?');" class="text-red-400 ml-2 hover:underline">Sil</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Kategori Yönetimi -->
    <h2 class="text-xl font-semibold mb-2">Kategoriler</h2>

    <!-- Kategori Ekleme Formu -->
    <form method="POST" class="flex items-center gap-4 mb-4">
      <input type="text" name="kategori_adi" placeholder="Yeni kategori adı" class="p-2 bg-slate-800 rounded w-64">
      <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded">Ekle</button>
    </form>

    <!-- Kategori Listesi -->
    <table class="w-full text-sm border border-white/10">
      <thead class="bg-slate-800">
        <tr>
          <th class="p-2 text-left">Kategori Adı</th>
          <th class="p-2 text-center">İşlem</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($kategoriler as $k): ?>
          <tr class="border-b border-white/10">
            <td class="p-2"><?= htmlspecialchars($k['ad']) ?></td>
            <td class="p-2 text-center">
              <a href="?kategorisil=<?= $k['id'] ?>" onclick="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')" class="text-red-400 hover:underline">Sil</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>

</body>
</html>
