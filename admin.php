<?php
session_start();
if ($_POST) {
    $kadi = $_POST['kadi'];
    $sifre = $_POST['sifre'];

    
    if ($kadi === 'admin' && $sifre === '1234') {
        $_SESSION['admin'] = true;
        header("Location: admin-panel.php");
        exit;
    } else {
        $hata = "Kullanıcı adı veya şifre hatalı.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Admin Giriş</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white flex items-center justify-center min-h-screen">
  <form method="post" class="bg-white/10 backdrop-blur p-8 rounded shadow max-w-sm w-full">
    <h2 class="text-xl font-bold mb-4">Admin Girişi</h2>
    <?php if (isset($hata)): ?>
      <p class="text-red-400 mb-2"><?= $hata ?></p>
    <?php endif; ?>
    <input type="text" name="kadi" placeholder="Kullanıcı Adı" required class="w-full mb-3 px-3 py-2 rounded bg-slate-800 text-white">
    <input type="password" name="sifre" placeholder="Şifre" required class="w-full mb-4 px-3 py-2 rounded bg-slate-800 text-white">
    <button type="submit" class="w-full bg-sky-500 hover:bg-sky-600 py-2 rounded text-white">Giriş Yap</button>
  </form>
</body>
</html>
