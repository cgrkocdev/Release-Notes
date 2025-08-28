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
    $detay_raw = $_POST['detay'];
    $kategori = $_POST['kategori'];

    $allowed_tags = '<p><a><strong><b><em><i><u><ul><ol><li><br><img><h1><h2><h3><h4><h5><h6><table><tbody><tr><td><th><iframe>';
    $detay = strip_tags($detay_raw, $allowed_tags);

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

  <!-- TinyMCE yükle -->
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
    h1 {
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 1.5rem;
      text-align: center;
      color: #38bdf8; /* sky-400 */
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 1.25rem; /* aradaki boşluk */
    }
    input[type="text"],
    input[type="date"],
    select,
    textarea {
      width: 100%;
      padding: 0.75rem 1rem;
      border-radius: 0.5rem;
      border: none;
      background-color: #334155; /* tailwind slate-700 */
      color: white;
      font-size: 1rem;
      box-sizing: border-box;
      transition: background-color 0.3s ease;
      font-family: inherit;
      resize: vertical;
      min-height: 40px;
    }
    input[type="text"]:focus,
    input[type="date"]:focus,
    select:focus,
    textarea:focus {
      outline: none;
      background-color: #475569; /* slate-600 */
      box-shadow: 0 0 0 3px #0ea5e9; /* sky-500 */
    }
    textarea {
      min-height: 300px;
    }
    button {
      background-color: #0ea5e9; /* sky-500 */
      color: white;
      font-weight: 600;
      font-size: 1.125rem;
      padding: 0.75rem 0;
      border-radius: 0.5rem;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-family: inherit;
    }
    button:hover {
      background-color: #0284c7; /* sky-700 */
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Yeni Sürüm Ekle</h1>
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
