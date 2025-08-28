<?php
$vt = new PDO("mysql:host=localhost;dbname=toya_db;charset=utf8", "root", "");
$id = $_GET['id'] ?? 0;
$s = $vt->prepare("SELECT s.*, k.ad AS kategori_ad FROM surumler s JOIN kategoriler k ON s.kategori_id = k.id WHERE s.id = ?");
$s->execute([$id]);
$veri = $s->fetch(PDO::FETCH_ASSOC);
if (!$veri) {
    echo "Geçersiz ID";
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($veri['baslik']) ?></title>

  <!-- Google Fonts Poppins Bold -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet" />
  
  <style>
    /* Genel reset ve temel stiller */
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #d0e7ff, #f0f8ff);
      color: #0a2540;
      min-height: 100vh;
      padding: 2rem 1rem;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    main.container {
      background: #ffffff;
      max-width: 720px;
      width: 100%;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(37, 99, 235, 0.15);
      padding: 2.5rem 3rem;
      margin-bottom: 2rem;
    }

    h1 {
      font-weight: 700;
      font-size: 2.8rem;
      margin-bottom: 0.5rem;
      color: #2563eb;
      text-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
      user-select: none;
    }

    .meta {
      font-weight: 600;
      font-size: 1rem;
      color: #4b6cb7;
      margin-bottom: 1.8rem;
      user-select: none;
    }

    .content {
      font-weight: 500;
      font-size: 1.1rem;
      line-height: 1.6;
      color: #334e7c;
      white-space: pre-line;
      user-select: text;
    }

    a.back-btn {
      display: inline-block;
      margin-top: 2.5rem;
      padding: 0.65rem 1.6rem;
      font-weight: 600;
      font-size: 1rem;
      background: linear-gradient(90deg, #2563eb, #3b82f6);
      color: white;
      border-radius: 30px;
      box-shadow: 0 6px 12px rgba(37, 99, 235, 0.35);
      text-decoration: none;
      user-select: none;
      transition: background 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
      user-select:none;
    }
    a.back-btn:hover,
    a.back-btn:focus {
      background: linear-gradient(90deg, #1e40af, #2563eb);
      box-shadow: 0 8px 20px rgba(37, 99, 235, 0.6);
      outline: none;
    }

    @media (max-width: 520px) {
      main.container {
        padding: 1.8rem 1.5rem;
      }
      h1 {
        font-size: 2.2rem;
      }
      .content {
        font-size: 1rem;
      }
    }
  </style>

</head>
<body>

<main class="container" role="main">
  <h1><?= htmlspecialchars($veri['baslik']) ?></h1>
  <div class="meta">Yayın Tarihi: <?= date('d.m.Y', strtotime($veri['tarih'])) ?> | Kategori: <?= htmlspecialchars($veri['kategori_ad']) ?></div>
  <div class="content"><?= ($veri['detay']) ?></div>
  <a href="index.php" class="back-btn" aria-label="Sürüm notları listesine geri dön">← Geri Dön</a>
</main>

</body>
</html>
