<?php
$vt = new PDO("mysql:host=localhost;dbname=toya_db;charset=utf8", "root", "");
$kategoriler = $vt->query("SELECT * FROM kategoriler")->fetchAll(PDO::FETCH_ASSOC);
$surumler = $vt->query("
  SELECT s.*, k.ad AS kategori_ad 
  FROM surumler s 
  JOIN kategoriler k ON s.kategori_id = k.id 
  ORDER BY s.tarih DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <title>Toya Yazılım | Sürüm Notları</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Unisans Bold fontu için buraya kendi font dosyanızı koymalısınız, örnek aşağıdaki gibi -->
  <!--
  <style>
    @font-face {
      font-family: 'Unisans';
      src: url('fonts/Unisans-Bold.woff2') format('woff2'),
           url('fonts/Unisans-Bold.woff') format('woff');
      font-weight: 700;
      font-style: normal;
      font-display: swap;
    }
  </style>
  -->

  <!-- Alternatif olarak Google Fonts Poppins Bold kullanıyorum -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet" />
  
  <style>
    /* Reset */
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif; /* Burayı 'Unisans' yapabilirsiniz */
      background: linear-gradient(135deg, #d0e7ff, #f0f8ff);
      color: #0a2540;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 2rem 1rem;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    header {
      text-align: center;
      margin-bottom: 3rem;
      user-select: none;
    }
    header h1 {
      font-weight: 700;
      font-size: 3.5rem;
      letter-spacing: -0.05em;
      color: #2563eb;
      margin-bottom: 0.25rem;
      text-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
    }
    header p {
      font-weight: 600;
      font-size: 1.2rem;
      color: #4b6cb7;
    }

    /* Kategori Tabları */
    .tabs {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      justify-content: center;
      margin-bottom: 3rem;
      font-weight: 600;
      user-select: none;
    }
    .tab-btn {
      background: #e2ecff;
      border: none;
      padding: 0.7rem 1.6rem;
      font-size: 1rem;
      color: #2563eb;
      cursor: pointer;
      border-radius: 30px;
      box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15);
      transition:
        background-color 0.3s ease,
        color 0.3s ease,
        box-shadow 0.3s ease;
    }
    .tab-btn:hover {
      background-color: #2563eb;
      color: #f0f8ff;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    }
    .tab-btn.active {
      background-color: #2563eb;
      color: #f0f8ff;
      box-shadow: 0 6px 20px rgba(37, 99, 235, 0.6);
    }

    /* Ana içerik - sürüm kartları */
    main#versionList {
      width: 100%;
      max-width: 720px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      padding-bottom: 4rem;
      transition: grid-template-columns 0.3s ease;
    }

    /* Tümü seçiliyse alt alta */
    main#versionList.list-view {
      grid-template-columns: 1fr !important;
    }

    .version-card {
      background: #ffffff;
      border-radius: 16px;
      padding: 1.8rem 2rem;
      box-shadow: 0 10px 25px rgba(37, 99, 235, 0.1);
      transition:
        transform 0.3s ease,
        box-shadow 0.3s ease;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 180px;
      color: #0a2540;
    }
    .version-card:hover,
    .version-card:focus {
      transform: translateY(-6px);
      box-shadow: 0 14px 40px rgba(37, 99, 235, 0.3);
      outline: none;
    }
    .version-card h2 {
      font-weight: 700;
      font-size: 1.6rem;
      margin: 0 0 0.5rem 0;
      line-height: 1.2;
    }
    .version-card .category {
      font-weight: 600;
      font-size: 0.9rem;
      color: #2563eb;
      margin-bottom: 0.8rem;
    }
    .version-card .date {
      font-size: 0.9rem;
      color: #4b6cb7;
      margin-bottom: 1rem;
      font-weight: 500;
    }
    .version-card p.description {
      flex-grow: 1;
      font-size: 1rem;
      color: #334e7c;
      line-height: 1.4;
      margin-bottom: 1rem;
      white-space: pre-line;
    }
    .detail-btn {
      align-self: flex-start;
      background: linear-gradient(90deg, #2563eb, #3b82f6);
      border: none;
      padding: 0.5rem 1.3rem;
      border-radius: 30px;
      font-weight: 600;
      font-size: 0.9rem;
      color: white;
      cursor: pointer;
      box-shadow: 0 6px 12px rgba(37, 99, 235, 0.35);
      transition: background 0.3s ease, box-shadow 0.3s ease;
      text-decoration: none;
      user-select: none;
    }
    .detail-btn:hover,
    .detail-btn:focus {
      background: linear-gradient(90deg, #1e40af, #2563eb);
      box-shadow: 0 8px 20px rgba(37, 99, 235, 0.6);
      outline: none;
    }

    /* Responsive */
    @media (max-width: 520px) {
      body {
        padding: 1.5rem 1rem;
      }
      header h1 {
        font-size: 2.8rem;
      }
      main#versionList {
        grid-template-columns: 1fr !important;
        gap: 1.5rem;
      }
      .version-card {
        min-height: auto;
      }
    }
  </style>
</head>
<body>

<header>
  <h1>Toya Yazılım</h1>
  <p>Sürüm notları & geliştirme güncellemeleri</p>
</header>

<nav class="tabs" role="tablist" aria-label="Sürüm Notları Kategorileri">
  <button class="tab-btn active" aria-selected="true" role="tab" data-filter="all">Tümü</button>
  <?php foreach ($kategoriler as $kategori): ?>
    <button class="tab-btn" role="tab" data-filter="kategori-<?= $kategori['id'] ?>">
      <?= htmlspecialchars($kategori['ad']) ?>
    </button>
  <?php endforeach; ?>
</nav>

<main id="versionList" role="tabpanel" aria-live="polite" class="list-view">
  <?php foreach ($surumler as $s): ?>
    <article 
      class="version-card" 
      tabindex="0" 
      data-kategori="kategori-<?= $s['kategori_id'] ?>" 
      role="button" 
      aria-pressed="false"
      onclick="location.href='detay.php?id=<?= $s['id'] ?>'"
      onkeypress="if(event.key==='Enter'){location.href='detay.php?id=<?= $s['id'] ?>'}"
    >
      <h2><?= htmlspecialchars($s['baslik']) ?></h2>
      <div class="category"><?= htmlspecialchars($s['kategori_ad']) ?></div>
      <div class="date">Yayın Tarihi: <?= date("d.m.Y", strtotime($s['tarih'])) ?></div>
     <p class="description">
  <?= strip_tags(mb_substr($s['aciklama'] ?? '', 0, 120)) ?><?= (strlen(strip_tags($s['aciklama'] ?? '')) > 120 ? '...' : '') ?>
</p>

      <a href="detay.php?id=<?= $s['id'] ?>" class="detail-btn" tabindex="-1" aria-label="<?= htmlspecialchars($s['baslik']) ?> detaylarını görüntüle">Detaylar</a>
    </article>
  <?php endforeach; ?>
</main>

<script>
  const tabs = document.querySelectorAll('.tab-btn');
  const cards = document.querySelectorAll('.version-card');
  const versionList = document.getElementById('versionList');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      // Aktif sekmeyi değiştir
      tabs.forEach(t => {
        t.classList.remove('active');
        t.setAttribute('aria-selected', 'false');
      });
      tab.classList.add('active');
      tab.setAttribute('aria-selected', 'true');

      const filter = tab.getAttribute('data-filter');

      // Grid görünümünü ayarla
      if(filter === 'all') {
        versionList.classList.add('list-view');  // Alt alta
      } else {
        versionList.classList.remove('list-view'); // Yan yana
      }

      // Kartları filtrele
      cards.forEach(card => {
        if(filter === 'all' || card.dataset.kategori === filter) {
          card.style.display = 'flex';
          card.setAttribute('aria-hidden', 'false');
        } else {
          card.style.display = 'none';
          card.setAttribute('aria-hidden', 'true');
        }
      });
    });
  });
</script>

</body>
</html>
