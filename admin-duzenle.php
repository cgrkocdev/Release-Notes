<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-giris.php");
    exit;
}

$vt = new PDO("mysql:host=localhost;dbname=toya_db;charset=utf8", "root", "");

if (!isset($_GET['id'])) {
    header("Location: admin-panel.php");
    exit;
}

$id = $_GET['id'];
$surum = $vt->prepare("SELECT * FROM surumler WHERE id = ?");
$surum->execute([$id]);
$veri = $surum->fetch(PDO::FETCH_ASSOC);

$kategoriler = $vt->query("SELECT * FROM kategoriler")->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    $baslik = $_POST['baslik'];
    $tarih = $_POST['tarih'];
    $detay = $_POST['detay'];  // TinyMCE HTML içerik
    $kategori = $_POST['kategori'];

    $guncelle = $vt->prepare("UPDATE surumler SET baslik=?, tarih=?, detay=?, kategori_id=? WHERE id=?");
    $guncelle->execute([$baslik, $tarih, $detay, $kategori, $id]);
    header("Location: admin-panel.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <title>Sürüm Düzenle - TinyMCE</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- TinyMCE kütüphanesi -->
  <script src="https://cdn.tiny.cloud/1/leo3ucotq0x3rjb0a8co4s5napznygm9v5qgbjwoeelw8uji/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

  <style>
    body {
      background-color: #0f172a;
      color: white;
      font-family: 'Poppins', sans-serif;
      padding: 2rem;
      margin: 0;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 2rem;
      background-color: #1e293b;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="text-3xl font-bold mb-6 text-sky-400 text-center">Sürüm Düzenle</h1>
    <form method="post" class="space-y-6">
      <input 
        name="baslik" 
        value="<?= htmlspecialchars($veri['baslik']) ?>" 
        required 
        class="w-full px-4 py-3 rounded bg-slate-800 text-white text-lg focus:outline-none focus:ring-2 focus:ring-sky-500" 
        placeholder="Sürüm Başlığı"
      >

      <input 
        type="date" 
        name="tarih" 
        value="<?= htmlspecialchars($veri['tarih']) ?>" 
        required 
        class="w-full px-4 py-3 rounded bg-slate-800 text-white text-lg focus:outline-none focus:ring-2 focus:ring-sky-500" 
      >

      <select 
        name="kategori" 
        required 
        class="w-full px-4 py-3 rounded bg-slate-800 text-white text-lg focus:outline-none focus:ring-2 focus:ring-sky-500"
      >
        <?php foreach($kategoriler as $kat): ?>
          <option value="<?= $kat['id'] ?>" <?= $kat['id'] == $veri['kategori_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($kat['ad']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <textarea 
        id="detay" 
        name="detay" 
        required
      ><?= htmlspecialchars($veri['detay']) ?></textarea>

      <button 
        type="submit" 
        class="w-full bg-yellow-500 py-3 rounded text-black font-semibold hover:bg-yellow-600 transition"
      >
        Güncelle
      </button>
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
