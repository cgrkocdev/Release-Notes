<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin-giris.php");
    exit;
}

if (isset($_GET['id'])) {
    $vt = new PDO("mysql:host=localhost;dbname=toya_db;charset=utf8", "root", "");
    $sorgu = $vt->prepare("DELETE FROM surumler WHERE id = ?");
    $sorgu->execute([$_GET['id']]);
}

header("Location: admin-panel.php");
exit;
?>
