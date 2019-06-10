-- phpMyAdmin SQL Dump
-- version 4.4.15.9
-- https://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 24 2019 г., 10:08
-- Версия сервера: 5.6.37
-- Версия PHP: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `phpProject`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id_category` int(11) NOT NULL,
  `status` varchar(250) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `foto_category` varchar(500) NOT NULL,
  `description_category` varchar(1000) NOT NULL,
  `UUID_categiry` varchar(250) NOT NULL,
  `id_pages` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id_category`, `status`, `name`, `parent_id`, `foto_category`, `description_category`, `UUID_categiry`, `id_pages`) VALUES
(1, '1', 'Saxophones', 0, '', 'Just Saxophones', '', 6),
(2, '1', 'clarinetes', 0, '', 'About clarinet', '', 8),
(3, '1', 'Flutes', 0, '', 'About Flutes', '', 10),
(4, '1', 'Accessories', 0, '', 'About Accessories', '', 11),
(5, '1', 'Straps for Sax', 4, '', 'Accessoirs for Sax', '', 20),
(6, '1', 'Mouthpieces for Sax', 4, '', 'Accessoirs for Sax', '', 22),
(7, '1', 'Mouthpieces for Clarinetes', 4, '', 'Accessoirs for Clarinet', '', 23),
(8, '1', 'Straps for Clarinetes', 4, '', 'Accessoirs for Clarinet', '', 21),
(9, '1', 'Tenor saxophones', 1, '', 'About tenor saxophones', '', 12),
(10, '1', 'Alto saxophones', 1, '', 'About alto saxophones', '', 13),
(11, '1', 'Eb Clarinetes', 2, '', 'About Clarinetes', '', 14),
(12, '1', 'Bb Clarinetes', 2, '', 'About Clarinetes', '', 15),
(13, '1', 'Alto Flutes', 3, '', 'About Flutes', '', 18),
(14, '1', 'Regular Flutes', 3, '', 'About Flutes', '', 19);

-- --------------------------------------------------------

--
-- Структура таблицы `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `url` varchar(250) NOT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `pages`
--

INSERT INTO `pages` (`id`, `name`, `url`, `parent_id`) VALUES
(1, 'Main page', 'index', 0),
(2, 'About ', 'info', 1),
(3, 'Catalog.class', 'catalog', 1),
(4, 'Articles.class', 'article', 1),
(5, 'Contacts', 'contact', 1),
(6, 'Saxophones', 'sax', 3),
(8, 'Clarinets', 'clarinet', 3),
(10, 'Flutes', 'flute', 3),
(11, 'Accessoirs', 'accessoir', 3),
(12, 'Tenor sax', 'tenor', 6),
(13, 'Alto sax', 'alto', 6),
(14, 'Eb Clarinet', 'clarineteb', 8),
(15, 'Bb Clarinet', 'clarinetbb', 8),
(18, 'Alto Flutes', 'flutealto', 10),
(19, 'Regular Flutes', 'fluter', 10),
(20, 'Straps for Sax', 'strapsax', 11),
(21, 'Strap for Clarinetes', 'strapclar', 11),
(22, 'Mouthpieces for Sax', 'mouthsax', 11),
(23, 'Mouthpieces for Clarinetes', 'mouthclar', 11),
(24, 'Photos', 'galary', 1),
(25, 'Photos Ajax', 'galaryajax', 24),
(26, 'Photos Regular', 'photoreg', 24);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `img` varchar(255) COLLATE utf8_bin NOT NULL,
  `price` decimal(65,3) NOT NULL,
  `id_category` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `view` int(11) DEFAULT NULL,
  `salePrice` decimal(65,3) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` varchar(2048) COLLATE utf8_bin NOT NULL,
  `short_description` text COLLATE utf8_bin NOT NULL,
  `ID_UUID` varchar(250) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `img`, `price`, `id_category`, `status`, `view`, `salePrice`, `date`, `description`, `short_description`, `ID_UUID`) VALUES
(1, 'Sax Super', 'catalog1.jpg', '2.000', 0, 0, 2, '120.000', '2016-11-11', '', '', ''),
(2, 'Super Tenor', 'catalog2.jpg', '30.000', 0, 0, 8, NULL, '2015-11-12', '', '', ''),
(3, 'Super Alto', 'catalog3.jpg', '23.340', 0, 0, 1, NULL, '2016-12-01', '', '', ''),
(4, 'Bass Drum', 'catalog4.jpg', '1.999', 0, 0, 7, NULL, '2016-03-09', '', '', ''),
(5, 'Soprano Sax', 'catalog5.jpg', '2.230', 0, 0, 9, '29.000', '2015-07-07', '', '', ''),
(6, 'Bari Sax', 'catalog6.JPG', '4.777', 0, 0, 7, NULL, '2015-08-07', '', '', ''),
(7, 'Pop Sax', 'img1.png', '13.900', 0, 0, 3, '130.000', '2016-08-09', '', '', ''),
(8, 'Super New', 'img11.JPG', '24.000', 0, 0, 4, NULL, '2014-08-09', '', '', ''),
(9, 'Saxophone 1969', 'img12.JPG', '47.500', 0, 0, 2, NULL, '2015-05-04', '', '', ''),
(10, 'Best Sax', 'img13.JPG', '1.000', 0, 0, 13, '89.000', '2015-09-09', '', '', ''),
(11, 'Bad Sax', 'img2.png', '3.000', 0, 0, 8, NULL, '2015-01-21', '', '', ''),
(12, 'Dark Sax ', 'img3.JPG', '4.000', 0, 0, 10, NULL, '2016-01-21', '', '', ''),
(13, 'The Best Saxophone ', 'img4.jpg', '5.000', 0, 0, 8, NULL, '2017-03-13', '', '', ''),
(14, 'Jazz Sax', 'img5.jpg', '4.888', 0, 0, 11, NULL, '2018-03-04', '', '', ''),
(15, 'Classic Sax', 'img6.JPG', '3.321', 0, 0, 19, NULL, '2018-05-06', '', '', ''),
(16, 'Clarinet', 'img7.JPG', '6.000', 0, 0, 5, NULL, '2018-08-07', '', '', ''),
(17, 'Flute', 'img8.JPG', '3.567', 0, 0, 8, '13.000', '2018-09-01', '', '', ''),
(18, 'Chinese Sax', 'img9.JPG', '8.999', 0, 0, 3, NULL, '2018-07-06', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `pass` varchar(500) NOT NULL,
  `prim` varchar(500) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `patronymic` varchar(50) DEFAULT NULL,
  `telephone` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `comments` text,
  `sport` varchar(2) DEFAULT NULL,
  `turist` varchar(2) DEFAULT NULL,
  `padi` varchar(2) DEFAULT NULL,
  `travels` varchar(2) DEFAULT NULL,
  `auto` varchar(2) DEFAULT NULL,
  `it` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Структура таблицы `users_auth`
--

CREATE TABLE IF NOT EXISTS `users_auth` (
  `id_user` int(11) NOT NULL,
  `id_user_session` int(11) NOT NULL,
  `hash_cookie` varchar(500) NOT NULL,
  `date` date NOT NULL,
  `prim` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`),
  ADD KEY `id_pages` (`id_pages`);

--
-- Индексы таблицы `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Индексы таблицы `users_auth`
--
ALTER TABLE `users_auth`
  ADD PRIMARY KEY (`id_user_session`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT для таблицы `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `users_auth`
--
ALTER TABLE `users_auth`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
