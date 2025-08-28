<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-giris.php");
    exit;
}

try {
    $vt = new PDO("mysql:host=localhost;dbname=toya_db;charset=utf8", "root", "");
    $vt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

$kategoriler = $vt->query("SELECT * FROM kategoriler ORDER BY ad ASC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik']);
    $tarih = $_POST['tarih'];
    $detay = $_POST['detay'];  // HTML olarak TinyMCE'den geliyor
    $kategori = $_POST['kategori'];

    $ekle = $vt->prepare("INSERT INTO surumler (baslik, tarih, detay, kategori_id) VALUES (?, ?, ?, ?)");
    $ekle->execute([$baslik, $tarih, $detay, $kategori]);

    header("Location: admin-panel.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
<title>Yeni Sürüm Ekle - TinyMCE</title>
<script src="https://cdn.tailwindcss.com"></script>

<!-- TinyMCE yükle (Kendi API anahtarını burada kullan) -->
<script src="https://cdn.tiny.cloud/1/leo3ucotq0x3rjb0a8co4s5napznygm9v5qgbjwoeelw8uji/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<style>
  body { background-color: #0f172a; color: white; font-family: 'Poppins', sans-serif; padding: 2rem; }
  input, select, textarea, button { font-family: inherit; }
  input, select, button {
    width: 100%; padding: 0.75rem; border-radius: 0.375rem;
    background-color: #1e293b; color: white; margin-top: 0.5rem;
  }
  button {
    background-color: #0ea5e9; margin-top: 1rem; cursor: pointer;
  }
  button:hover {
    background-color: #0284c7;
  }
</style>
</head>
<body>
<div class="max-w-xl mx-auto">
  <h1 class="text-3xl font-bold mb-8">Yeni Sürüm Ekle</h1>
  <form method="post" enctype="multipart/form-data" id="surumForm">
    <input type="text" name="baslik" placeholder="Sürüm Başlığı" required autocomplete="off" />
    <input type="date" name="tarih" required value="<?= date('Y-m-d') ?>" />
    <select name="kategori" required>
      <option value="">Kategori Seç</option>
      <?php foreach($kategoriler as $kat): ?>
        <option value="<?= htmlspecialchars($kat['id']) ?>"><?= htmlspecialchars($kat['ad']) ?></option>
      <?php endforeach; ?>
    </select>

    <textarea id="detay" name="detay"></textarea>

    <button type="submit">Kaydet</button>
  </form>
</div>

<script>
tinymce.init({
  selector: '#detay',
  height: 300,
  skin: 'oxide-dark',
  content_css: 'dark',
  plugins: 'image link media table lists code',
  toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | image media link | code',
  automatic_uploads: true,
  images_upload_url: 'upload.php',
  images_upload_handler: function (blobInfo) {
    return new Promise(function (resolve, reject) {
      let xhr = new XMLHttpRequest();
      xhr.withCredentials = false;
      xhr.open('POST', 'upload.php');
      xhr.onload = function() {
        if (xhr.status !== 200) {
          reject('HTTP Error: ' + xhr.status);
          return;
        }
        let json;
        try {
          json = JSON.parse(xhr.responseText);
        } catch (e) {
          reject('Geçersiz JSON: ' + xhr.responseText);
          return;
        }
        if (!json || typeof json.location != 'string') {
          reject('Geçersiz JSON: ' + xhr.responseText);
          return;
        }
        resolve(json.location);
      };
      xhr.onerror = function () {
        reject('Resim yükleme hatası.');
      };
      let formData = new FormData();
      formData.append('file', blobInfo.blob(), blobInfo.filename());
      xhr.send(formData);
    });
  }
});
</script>

</body>
</html>
