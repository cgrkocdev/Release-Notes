-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 10 Haz 2025, 16:41:05
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `toya_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `surumler`
--

CREATE TABLE `surumler` (
  `id` int(11) NOT NULL,
  `baslik` varchar(200) DEFAULT NULL,
  `tarih` date DEFAULT NULL,
  `detay` text DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `surumler`
--

INSERT INTO `surumler` (`id`, `baslik`, `tarih`, `detay`, `kategori_id`) VALUES
(17, 'Test', '2025-06-10', '<p>g&uuml;ncelledim</p>\r\n<p><img src=\"uploads/img_684839f7049ac8.73591428.png\" alt=\"\" width=\"300\" height=\"300\"></p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>', 1),
(19, 'deneme -2', '2025-06-10', '<p>deneme -2</p>\r\n<p><img src=\"uploads/68484257ca265-Colorful Modern Infinity Technology Free Logo (10).png\" alt=\"\" width=\"500\" height=\"500\"></p>', 2);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `surumler`
--
ALTER TABLE `surumler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `surumler`
--
ALTER TABLE `surumler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `surumler`
--
ALTER TABLE `surumler`
  ADD CONSTRAINT `surumler_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategoriler` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
