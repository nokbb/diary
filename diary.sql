-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:8889
-- 生成日時: 2023 年 11 月 22 日 08:57
-- サーバのバージョン： 5.7.34
-- PHP のバージョン: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `diary`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `categories`
--

INSERT INTO `categories` (`id`, `name`, `user_id`) VALUES
(1, 'ALL', 0),
(17, 'え', 20),
(18, 'お', 20),
(19, 'a', 20),
(24, 'まろ', 21),
(34, 'カテゴリー1', 19),
(35, 'カテゴリー2', 19);

-- --------------------------------------------------------

--
-- テーブルの構造 `category_privacy_settings`
--

CREATE TABLE `category_privacy_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `privacy_setting` enum('public','private','friends_only') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `friendships`
--

CREATE TABLE `friendships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `friend_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','accepted','declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `friendships`
--

INSERT INTO `friendships` (`id`, `user_id`, `friend_id`, `status`, `created_at`, `updated_at`) VALUES
(2, 11, 12, 'accepted', '2023-09-08 02:24:29', '2023-09-08 02:24:29'),
(5, 21, 20, 'pending', '2023-11-16 22:04:25', '2023-11-16 22:04:25'),
(6, 20, 21, 'pending', '2023-11-17 05:44:01', '2023-11-17 05:44:01'),
(7, 19, 21, 'pending', '2023-11-18 03:10:06', '2023-11-18 03:10:06'),
(8, 19, 20, 'pending', '2023-11-21 02:51:48', '2023-11-21 02:51:48');

-- --------------------------------------------------------

--
-- テーブルの構造 `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `photo_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `photo_id`, `created_at`, `updated_at`) VALUES
(48, 19, 110, '2023-11-21 06:21:31', '2023-11-21 06:21:31');

-- --------------------------------------------------------

--
-- テーブルの構造 `memories`
--

CREATE TABLE `memories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` text COLLATE utf8mb4_unicode_ci,
  `entry` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `memories`
--

INSERT INTO `memories` (`id`, `post_id`, `user_id`, `file_path`, `caption`, `entry`, `created_at`, `updated_at`, `category_id`) VALUES
(109, NULL, 19, 'uploads/E8Dx3n50DYIAtnSlAVqoLBg2N5DAQ94xI1dFWGNc.jpg', '自分の投稿写真1', NULL, '2023-11-21 05:53:07', '2023-11-21 05:53:07', 1),
(110, NULL, 20, 'uploads/axULlaGj4UUHJCvvHz1XDjBHV73dmKc51PkAw9KU.jpg', 'Aさんの投稿写真1', NULL, '2023-11-21 06:00:29', '2023-11-21 06:00:29', 1),
(111, NULL, 19, 'uploads/Hem7XQ3XBCYDF32VGYsL3LCxzatuazSjwCbIegRX.jpg', '自分の投稿写真2', NULL, '2023-11-21 06:08:29', '2023-11-21 06:08:29', 1),
(113, NULL, 19, 'uploads/t9V54y9IyhYdd2ZcXwZeBPCVtC9FVDcm3UZKhQGP.jpg', '自分の投稿3', NULL, '2023-11-21 06:20:43', '2023-11-21 06:20:43', 1),
(114, NULL, 21, 'uploads/WVwNJ3DfRH72phDPzWq3MJemlbqFFdXsKkDpku3x.jpg', NULL, NULL, '2023-11-21 09:14:35', '2023-11-21 09:14:35', 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `memory_categories`
--

CREATE TABLE `memory_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `memory_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(21, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(22, '2023_09_05_171107_create_users_table', 1),
(23, '2023_09_05_171243_create_posts_table', 1),
(24, '2023_09_05_171445_create_memories_table', 1),
(25, '2023_09_05_171528_create_likes_table', 1),
(26, '2023_09_05_171619_create_friendships_table', 1),
(27, '2023_09_05_171710_create_categories_table', 1),
(28, '2023_09_05_171817_create_memory_categories_table', 1),
(29, '2023_09_05_171924_create_password_resets_table', 1),
(30, '2023_09_05_172044_create_category_privacy_settings_table', 1),
(31, '2023_09_09_152414_create_user_tokens_table', 2),
(35, '2023_10_31_184940_alter_memories_table', 3),
(38, '2023_11_03_190207_add_category_id_to_memories_table', 4),
(40, '2023_11_06_103152_add_user_id_to_categories_table', 5),
(41, '2023_11_08_095807_edit_photo_id_of_likes_table', 6);

-- --------------------------------------------------------

--
-- テーブルの構造 `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`, `updated_at`) VALUES
('kato.asuka@example.org', 'zv2MdypNqH', '2023-09-08 02:24:30', '2023-09-08 02:24:30'),
('soutaro26@example.org', 'RXYepCYn4G', '2023-09-08 02:24:30', '2023-09-08 02:24:30'),
('nokbb04@gmail.com', '$2y$10$CB7areHtwTOqMHmAEkVV3e5DESs/VB0LAdJwy.wGyC7cPi9u7l6rm', '2023-09-09 05:47:07', NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2023-09-08 02:24:26', '2023-09-08 02:24:26'),
(2, 1, '2023-09-08 02:24:26', '2023-09-08 02:24:26'),
(3, 1, '2023-09-08 02:24:26', '2023-09-08 02:24:26'),
(4, 3, '2023-09-08 02:24:26', '2023-09-08 02:24:26'),
(5, 4, '2023-09-08 02:24:27', '2023-09-08 02:24:27'),
(6, 4, '2023-09-08 02:24:27', '2023-09-08 02:24:27'),
(7, 1, '2023-09-08 02:24:29', '2023-09-08 02:24:29'),
(8, 8, '2023-09-08 02:24:29', '2023-09-08 02:24:29');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_pic` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locked_flg` tinyint(4) NOT NULL DEFAULT '0',
  `error_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `profile_pic`, `locked_flg`, `error_count`, `created_at`, `updated_at`) VALUES
(1, '吉本 学', 'rei.idaka', 'qnakatsugawa@example.net', '2023-09-08 02:24:25', '$2y$10$46.ldh41OFNW.k7XbnP0DudQY5kNd1BNFZcDWH9BqJ6goQX3pwJ3O', 'QZ9WcoV7Vj', 'https://via.placeholder.com/640x480.png/0066aa?text=et', 0, 0, '2023-09-08 02:24:26', '2023-09-09 13:36:32'),
(2, '加藤 加奈', 'rei37', 'hyamagishi@example.net', '2023-09-08 02:24:25', '$2y$10$R4Inb8Mxk4MVeJgcKOtWXu.6Ynk581vxP.xae9NB6TetcKC0eho5y', 'onkAKUy0bx', 'https://via.placeholder.com/640x480.png/00aa77?text=rerum', 0, 0, '2023-09-08 02:24:26', '2023-09-08 02:24:26'),
(3, '杉山 浩', 'kenichi79', 'takuma09@example.com', '2023-09-08 02:24:26', '$2y$10$ivwaRHdQIS.nSIRfiup8cOfNtEqLi/IKc5jgzU.q.5wgVpfqhd0AK', '1EulRjCRhj', NULL, 0, 0, '2023-09-08 02:24:26', '2023-10-20 09:16:15'),
(4, '杉山 拓真', 'xtanabe', 'nanami.tanabe@example.org', '2023-09-08 02:24:26', '$2y$10$E2YaRvk0BQCj09FXXSxEbOOjc9SCWN7Mzs5QoErxBuBr4I2boOfUu', 'kKxyV2CzbG', 'https://via.placeholder.com/640x480.png/0066dd?text=amet', 0, 0, '2023-09-08 02:24:26', '2023-09-08 02:24:26'),
(5, '渚 直人', 'haruka53', 'maaya19@example.com', '2023-09-08 02:24:26', '$2y$10$BAk3LgAZXO2/cVxydoSXO.bx3uPXHX8hbxxd29ma5FdDGOMLJWEhW', 'tGAIOKtcfg', 'https://via.placeholder.com/640x480.png/0077ff?text=quo', 0, 0, '2023-09-08 02:24:27', '2023-09-08 02:24:27'),
(6, '青田 幹', 'tomoya.matsumoto', 'yosuke.ekoda@example.com', '2023-09-08 02:24:27', '$2y$10$SuZP2DMmFKSGJIQZsesYweogPl0tVmTiu4HY.ko4ukz2.5OhnLEtO', 'jKAZqkdSNU', 'https://via.placeholder.com/640x480.png/00aacc?text=porro', 0, 0, '2023-09-08 02:24:27', '2023-09-08 02:24:27'),
(7, '喜嶋 学', 'yamamoto.shota', 'chiyo.ogaki@example.com', '2023-09-08 02:24:27', '$2y$10$/iVi3chaVMNmN0DO/jq2xebYGYfirfRwD/rTxI1EonRMOioTU/.KK', 'bXqtSahbDq', 'https://via.placeholder.com/640x480.png/003311?text=tempore', 0, 0, '2023-09-08 02:24:27', '2023-09-08 02:24:27'),
(8, '鈴木 篤司', 'sato.jun', 'naoto.hirokawa@example.com', '2023-09-08 02:24:27', '$2y$10$77TEYDlorbFywbbyflOIQ.jeHJq01JEh7cZiStxJNhpVn6hsiMr86', 'QpZ1lIXlt3', 'https://via.placeholder.com/640x480.png/001199?text=repellendus', 0, 0, '2023-09-08 02:24:28', '2023-09-08 02:24:28'),
(9, '石田 拓真', 'kyosuke05', 'suzuki.rei@example.com', '2023-09-08 02:24:28', '$2y$10$Myq4I5Lgjzu4yYJSXcyi.OXjeU2wCekfYA8gEtGZbyr1Z1yVARdfC', '22tIbcY3S9', 'https://via.placeholder.com/640x480.png/0022aa?text=voluptatem', 0, 0, '2023-09-08 02:24:28', '2023-09-08 02:24:28'),
(10, '藤本 あすか', 'takahashi.momoko', 'kyosuke.sakamoto@example.com', '2023-09-08 02:24:28', '$2y$10$nLCpv3n6u7Nxb4Fk5ME5J.5oMtyYf2Xh7SSLJ3HWfDJYOzHDNi1gK', 'Eb15x1XjTz', 'https://via.placeholder.com/640x480.png/003322?text=tempore', 0, 0, '2023-09-08 02:24:28', '2023-09-08 02:24:28'),
(11, '青田 太一', 'tanabe.chiyo', 'lnakamura@example.com', '2023-09-08 02:24:28', '$2y$10$ECpMmnKlC3913WCbo8IBEeTxMubwZJZYfWb3sclJz073EuLQfJ1.u', 'ShCZfYjXVu', 'https://via.placeholder.com/640x480.png/000044?text=incidunt', 0, 0, '2023-09-08 02:24:29', '2023-09-08 02:24:29'),
(12, '加納 七夏', 'yosuke87', 'pmiyazawa@example.org', '2023-09-08 02:24:29', '$2y$10$yGDB0nxeuVd4.b99Rm2olOFfSXoJ.XJO6YiTwVUugbg.NFGCjXdG.', 'hv7E3Rzn4m', 'https://via.placeholder.com/640x480.png/00dd22?text=quibusdam', 0, 0, '2023-09-08 02:24:29', '2023-09-08 02:24:29'),
(13, '小林 稔', 'harada.tsubasa', 'chiyo97@example.net', '2023-09-08 02:24:29', '$2y$10$KnZ6xejAt1oi/xzHhHXnbeR5dIi.bqcio2tl1ReGdG.zX13Qr88a.', 'waS6uVkK8b', 'https://via.placeholder.com/640x480.png/005588?text=ipsam', 0, 0, '2023-09-08 02:24:29', '2023-09-08 02:24:29'),
(14, '江古田 千代', 'kanou.mitsuru', 'yuta83@example.net', '2023-09-08 02:24:29', '$2y$10$9S2g1kSatTWQn7uxqQOKp.betRt1EnvYDQzxOlDLErrVLTnPMXy2S', 'azIR1tYJyG', 'https://via.placeholder.com/640x480.png/00ccdd?text=perferendis', 0, 0, '2023-09-08 02:24:30', '2023-09-08 02:24:30'),
(15, '吉本 修平', 'sayuri74', 'kiriyama.rei@example.com', '2023-09-08 02:24:30', '$2y$10$aQfdlyYwz9a7H9jMwzUFlOrcMNAXhqL5lwo3D403Aq/78xO.rBeLO', 'z41o7VMcbg', 'https://via.placeholder.com/640x480.png/009966?text=laudantium', 0, 0, '2023-09-08 02:24:30', '2023-09-08 02:24:30'),
(16, '佐藤 治', 'kudo.tomoya', 'takahashi.naoko@example.net', '2023-09-08 02:24:30', '$2y$10$8.OxSwqqM7z6qPYSv6idSeJ8iwBQoWl18quAOQG58nFRcmvljDKHK', 'cJt8Pjb2PM', 'https://via.placeholder.com/640x480.png/004466?text=iste', 0, 0, '2023-09-08 02:24:30', '2023-09-08 02:24:30'),
(19, 'My name', 'a_123', 'a@a.com', NULL, '$2y$10$17ubOPY1RYt74voTuuN/nO2gL6KpBoDU0gx8onWP7uIVV/3O5AMVe', NULL, 'TGceTriECiTxQ0Yn8Yw8HIlEzR00xnB1Rd5PMETD.jpg', 0, 0, '2023-09-08 05:26:34', '2023-11-21 06:10:03'),
(20, 'n', 'nokbb04', 'nokbb04@gmail.com', NULL, '$2y$10$xTf5S7ZfwxJZs8FRxWeCWeEzLL6HDV5CSRXLJhp4tDg8TyWl4k4Ka', NULL, 'cUCd4FGQ4VpJqi79tZ5ftDdHv1U31aEMhStjW8RK.jpg', 0, 0, '2023-09-09 05:44:28', '2023-11-21 05:59:30'),
(21, 'まろ', 'maro_maro', 'm.su.370@icloud.com', NULL, '$2y$10$5OmF7TMac/H2ReTIQPp6dO0obI6x2WVRzm20f/Xqk2VwgaXUc0/2u', NULL, 'cfwdAh8e5a2qfBf297LCca08PoYKSkJHAgu6ckyf.jpg', 0, 0, '2023-09-09 23:18:04', '2023-11-14 02:02:38');

-- --------------------------------------------------------

--
-- テーブルの構造 `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'トークン',
  `expire_at` datetime DEFAULT NULL COMMENT 'トークンの有効期限',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ユーザートークン';

--
-- テーブルのデータのダンプ `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `token`, `expire_at`, `created_at`, `updated_at`) VALUES
(1, 1, '161566424964fc6a27755990.12455761', '2023-09-11 21:50:47', '2023-09-09 08:59:36', '2023-09-09 12:50:47'),
(2, 20, '37386753764fc3603caaa69.51481690', '2023-09-11 18:08:19', '2023-09-09 09:08:19', '2023-09-09 09:08:19'),
(3, 19, '33938580364fc75b596f409.14625083', '2023-09-11 22:40:05', '2023-09-09 13:38:06', '2023-09-09 13:40:05'),
(4, 21, '145689878764fcfd5a4fb4a3.44461665', '2023-09-12 08:18:50', '2023-09-09 23:18:50', '2023-09-09 23:18:50');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `category_privacy_settings`
--
ALTER TABLE `category_privacy_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_privacy_settings_category_id_user_id_unique` (`category_id`,`user_id`),
  ADD KEY `category_privacy_settings_user_id_foreign` (`user_id`);

--
-- テーブルのインデックス `friendships`
--
ALTER TABLE `friendships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `friendships_user_id_friend_id_unique` (`user_id`,`friend_id`),
  ADD KEY `friendships_friend_id_foreign` (`friend_id`);

--
-- テーブルのインデックス `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `likes_user_id_photo_id_unique` (`user_id`,`photo_id`),
  ADD KEY `likes_photo_id_foreign` (`photo_id`);

--
-- テーブルのインデックス `memories`
--
ALTER TABLE `memories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `memories_post_id_foreign` (`post_id`),
  ADD KEY `memories_user_id_foreign` (`user_id`);

--
-- テーブルのインデックス `memory_categories`
--
ALTER TABLE `memory_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `memory_categories_memory_id_category_id_unique` (`memory_id`,`category_id`),
  ADD KEY `memory_categories_category_id_foreign` (`category_id`);

--
-- テーブルのインデックス `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- テーブルのインデックス `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- テーブルのインデックス `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_user_id_foreign` (`user_id`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- テーブルのインデックス `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_tokens_token_unique` (`token`),
  ADD KEY `user_tokens_user_id_foreign` (`user_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- テーブルの AUTO_INCREMENT `category_privacy_settings`
--
ALTER TABLE `category_privacy_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `friendships`
--
ALTER TABLE `friendships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- テーブルの AUTO_INCREMENT `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- テーブルの AUTO_INCREMENT `memories`
--
ALTER TABLE `memories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- テーブルの AUTO_INCREMENT `memory_categories`
--
ALTER TABLE `memory_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- テーブルの AUTO_INCREMENT `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- テーブルの AUTO_INCREMENT `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- テーブルの AUTO_INCREMENT `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=5;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `category_privacy_settings`
--
ALTER TABLE `category_privacy_settings`
  ADD CONSTRAINT `category_privacy_settings_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `category_privacy_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- テーブルの制約 `friendships`
--
ALTER TABLE `friendships`
  ADD CONSTRAINT `friendships_friend_id_foreign` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `friendships_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- テーブルの制約 `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_photo_id_foreign` FOREIGN KEY (`photo_id`) REFERENCES `memories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- テーブルの制約 `memories`
--
ALTER TABLE `memories`
  ADD CONSTRAINT `memories_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `memories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- テーブルの制約 `memory_categories`
--
ALTER TABLE `memory_categories`
  ADD CONSTRAINT `memory_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `memory_categories_memory_id_foreign` FOREIGN KEY (`memory_id`) REFERENCES `memories` (`id`);

--
-- テーブルの制約 `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- テーブルの制約 `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
