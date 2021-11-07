-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 08 2021 г., 00:20
-- Версия сервера: 5.6.47
-- Версия PHP: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `startup`
--

-- --------------------------------------------------------

--
-- Структура таблицы `all_questions_confirm_gcp`
--

CREATE TABLE `all_questions_confirm_gcp` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `all_questions_confirm_gcp`
--

INSERT INTO `all_questions_confirm_gcp` (`id`, `title`, `user_id`, `created_at`) VALUES
(1, 'Чем вы занимаетесь в настоящее время?', 1, 1620597836),
(2, 'Чем вы занимаетесь в настоящее время?!', 1, 1620597848),
(3, 'Что понравилось в решении и что нет?', 1, 1620597857),
(4, 'Новый вопрос для подтверждения ГЦП', 1, 1620597914),
(5, 'Чем вы занимаетесь сейчас?', 1, 1624559584),
(6, 'Что неудобно по сравнению с продуктами, которыми пользуются сейчас?', 1, 1624999684),
(7, 'Какая цена решения должна быть по вашему мнению?', 1, 1624999704),
(8, 'Во сколько обходится решение этой проблемы?', 1, 1624999755);

-- --------------------------------------------------------

--
-- Структура таблицы `all_questions_confirm_mvp`
--

CREATE TABLE `all_questions_confirm_mvp` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `all_questions_confirm_mvp`
--

INSERT INTO `all_questions_confirm_mvp` (`id`, `title`, `user_id`, `created_at`) VALUES
(1, 'Чем вы занимаетесь в настоящее время?', 1, 1620598468),
(2, 'Чем вы занимаетесь в настоящее время?!', 1, 1620598479),
(3, 'Что понравилось в решении и что нет?', 1, 1620598512),
(4, 'Чем вы занимаетесь в настоящее время?!!!', 1, 1623786958),
(5, 'Чем вы занимаетесь в настоящее время, т.е. сейчас ?', 1, 1624734147),
(6, 'Какие важные аспекты в продукте не затронуты, которые следовало бы продумать?', 1, 1624743996),
(7, 'Какие важные аспекты в продукте не затронуты, которые следовало бы продумать??', 1, 1624744009),
(8, 'Что неудобно по сравнению с продуктами, которыми пользуются сейчас?', 1, 1624999810);

-- --------------------------------------------------------

--
-- Структура таблицы `all_questions_confirm_problem`
--

CREATE TABLE `all_questions_confirm_problem` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `all_questions_confirm_problem`
--

INSERT INTO `all_questions_confirm_problem` (`id`, `title`, `user_id`, `created_at`) VALUES
(1, 'Чем вы занимаетесь в настоящее время?', 1, 1620596666),
(2, 'Чем вы занимаетесь в настоящее время?!', 1, 1620596796),
(3, 'На каком этапе проекта вы находитесь?', 1, 1620596829),
(4, 'Случалось ли вам столкнуться с …?', 1, 1620596848),
(5, 'Как на вашу жизнь влияет ..?', 1, 1624392862),
(6, 'Как на вашу жизнь влияет ..?!', 1, 1624392914),
(7, 'Случалось ли вам столкнуться с какой-то проблемой?', 1, 1624395096),
(8, 'Когда вы последний раз оказывались в ситуации ..?', 1, 1624999437),
(9, 'Какие трудности у вас вызывает это решение?', 1, 1624999471),
(10, 'Как часто с вами происходит ..?', 1, 1625519015);

-- --------------------------------------------------------

--
-- Структура таблицы `all_questions_confirm_segment`
--

CREATE TABLE `all_questions_confirm_segment` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `all_questions_confirm_segment`
--

INSERT INTO `all_questions_confirm_segment` (`id`, `title`, `user_id`, `created_at`) VALUES
(16, 'Что получается и что не получается в вашем проекте? Приведите примеры.', 1, 1622752382),
(17, 'На каком этапе проекта вы находитесь?!', 1, 1622753455),
(18, 'На каком этапе проекта вы находитесь?', 1, 1622753465),
(19, 'Чем вы занимаетесь в настоящее время?', 1, 1622998384),
(20, 'Чем вы занимаетесь в настоящее время?!', 1, 1622998428),
(21, 'Что получается и что не получается в вашем проекте?', 1, 1623267454),
(22, 'Чем вы занимаетесь в настоящее время?!!', 1, 1624131590),
(23, 'Чем вы занимаетесь?', 1, 1624132716),
(24, 'Как вы определяете цели, задачи и последовательность действий?', 1, 1624393444),
(25, 'Что пытались сделать, чтобы определить верные последовательные действия?', 1, 1624914429);

-- --------------------------------------------------------

--
-- Структура таблицы `answers_questions_confirm_gcp`
--

CREATE TABLE `answers_questions_confirm_gcp` (
  `id` int(11) UNSIGNED NOT NULL,
  `question_id` int(11) NOT NULL,
  `respond_id` int(11) NOT NULL,
  `answer` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `answers_questions_confirm_gcp`
--

INSERT INTO `answers_questions_confirm_gcp` (`id`, `question_id`, `respond_id`, `answer`) VALUES
(1, 1, 1, 'Ответ 1'),
(3, 3, 1, 'Ответ 2'),
(4, 4, 2, 'Ответ'),
(5, 4, 3, 'Ответ'),
(6, 4, 4, 'Ответ'),
(24, 9, 26, 'Ответ'),
(25, 9, 27, 'Ответ'),
(26, 9, 28, ''),
(29, 11, 29, 'Ответ'),
(32, 11, 33, 'Ответ 3'),
(36, 13, 36, 'Ответ 1'),
(37, 13, 37, 'Ответ 2'),
(38, 14, 38, 'Ответ 1'),
(39, 14, 39, 'Ответ 11'),
(40, 15, 38, 'Ответ 2'),
(41, 15, 39, 'Ответ 22');

-- --------------------------------------------------------

--
-- Структура таблицы `answers_questions_confirm_mvp`
--

CREATE TABLE `answers_questions_confirm_mvp` (
  `id` int(11) UNSIGNED NOT NULL,
  `question_id` int(11) NOT NULL,
  `respond_id` int(11) NOT NULL,
  `answer` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `answers_questions_confirm_mvp`
--

INSERT INTO `answers_questions_confirm_mvp` (`id`, `question_id`, `respond_id`, `answer`) VALUES
(1, 1, 1, 'Ответ'),
(2, 2, 2, 'Ответ'),
(3, 2, 3, 'Ответ'),
(4, 2, 4, 'Ответ'),
(23, 7, 24, 'Ответ'),
(24, 7, 25, ''),
(25, 7, 26, ''),
(29, 9, 27, 'Ответ'),
(30, 9, 28, 'Ответ'),
(35, 11, 29, 'Ответ 1'),
(36, 11, 30, 'Ответ 2'),
(37, 12, 31, 'Ответ 1'),
(38, 12, 32, 'Ответ 11'),
(39, 13, 31, 'Ответ 2'),
(40, 13, 32, 'Ответ 22');

-- --------------------------------------------------------

--
-- Структура таблицы `answers_questions_confirm_problem`
--

CREATE TABLE `answers_questions_confirm_problem` (
  `id` int(11) UNSIGNED NOT NULL,
  `question_id` int(11) NOT NULL,
  `respond_id` int(11) NOT NULL,
  `answer` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `answers_questions_confirm_problem`
--

INSERT INTO `answers_questions_confirm_problem` (`id`, `question_id`, `respond_id`, `answer`) VALUES
(12, 13, 1, 'Ответ 1'),
(14, 15, 1, 'Ответ 2'),
(15, 16, 6, 'Ответ'),
(16, 16, 7, 'Ответ'),
(17, 16, 8, 'Ответ'),
(18, 17, 9, ''),
(19, 18, 9, ''),
(22, 17, 11, ''),
(23, 18, 11, ''),
(44, 26, 32, 'Ответ 1'),
(45, 26, 33, ''),
(46, 26, 34, ''),
(49, 28, 35, 'Ответ1'),
(50, 28, 36, ''),
(56, 28, 42, 'Ответ 2'),
(71, 36, 48, 'Ответ 1'),
(72, 36, 49, 'Ответ 2'),
(73, 37, 50, 'Ответ 1'),
(74, 37, 51, 'Ответ 11'),
(77, 39, 50, 'Ответ 2'),
(78, 39, 51, 'Ответ 22'),
(79, 40, 52, ''),
(80, 40, 53, ''),
(81, 40, 54, ''),
(82, 40, 55, '');

-- --------------------------------------------------------

--
-- Структура таблицы `answers_questions_confirm_segment`
--

CREATE TABLE `answers_questions_confirm_segment` (
  `id` int(11) UNSIGNED NOT NULL,
  `question_id` int(11) NOT NULL,
  `respond_id` int(11) NOT NULL,
  `answer` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `answers_questions_confirm_segment`
--

INSERT INTO `answers_questions_confirm_segment` (`id`, `question_id`, `respond_id`, `answer`) VALUES
(13, 7, 1, 'Ответ 1'),
(14, 7, 2, 'Ответ 3'),
(23, 12, 3, 'Ответ 1'),
(24, 12, 4, 'Ответ 1'),
(25, 13, 1, 'Ответ 2'),
(26, 13, 2, 'Ответ 4'),
(27, 14, 3, 'Ответ 2'),
(28, 14, 4, 'Ответ 2'),
(32, 16, 18, 'Ответ'),
(33, 16, 19, 'Ответ'),
(34, 16, 20, 'Ответ'),
(81, 23, 60, 'Ответ 1'),
(82, 23, 61, 'Ответ 1'),
(201, 44, 60, 'Ответ 2'),
(202, 44, 61, 'Ответ 2'),
(221, 54, 62, 'Ответ 11'),
(222, 54, 63, 'Ответ 1'),
(223, 55, 62, 'Ответ 22'),
(224, 55, 63, 'Ответ 2'),
(225, 56, 62, 'Ответ 33'),
(226, 56, 63, 'Ответ 3');

-- --------------------------------------------------------

--
-- Структура таблицы `authors`
--

CREATE TABLE `authors` (
  `id` int(11) UNSIGNED NOT NULL,
  `project_id` int(11) NOT NULL,
  `fio` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `experience` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `authors`
--

INSERT INTO `authors` (`id`, `project_id`, `fio`, `role`, `experience`) VALUES
(1, 1, 'Иванов Иван Иванович', 'Директор', '10 лет'),
(2, 1, 'Петров Петр Петрович', 'Заместитель директора', '5 лет'),
(3, 2, 'Карпов', 'директор', '4 года'),
(4, 2, 'Неклюдов', 'Зам.директора', '2 года'),
(5, 3, 'Карпов', 'проектант', '1 год'),
(6, 4, 'Порошин', 'президент', '30 лет'),
(7, 5, 'Иванов', 'работник', '5 лет');

-- --------------------------------------------------------

--
-- Структура таблицы `business_model`
--

CREATE TABLE `business_model` (
  `id` int(11) UNSIGNED NOT NULL,
  `basic_confirm_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `segment_id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `gcp_id` int(11) NOT NULL,
  `mvp_id` int(11) NOT NULL,
  `relations` varchar(255) NOT NULL,
  `partners` text NOT NULL,
  `distribution_of_sales` varchar(255) NOT NULL,
  `resources` varchar(255) NOT NULL,
  `cost` text NOT NULL,
  `revenue` text NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `business_model`
--

INSERT INTO `business_model` (`id`, `basic_confirm_id`, `project_id`, `segment_id`, `problem_id`, `gcp_id`, `mvp_id`, `relations`, `partners`, `distribution_of_sales`, `resources`, `cost`, `revenue`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 16, 1, 1, 1, 'Взаимоотношения с клиентами', 'Ключевые партнеры', 'Каналы коммуникации и сбыта', 'Ключевые ресурсы', 'Структура издержек', 'Потоки поступления доходов', 1620599072, 1633077005),
(3, 2, 1, 26, 5, 2, 2, 'Взаимоотношения с клиентами', 'Ключевые партнеры', 'Каналы коммуникации и сбыта', 'Ключевые ресурсы', 'Структура издержек', 'Потоки поступления доходов', 1620661584, 1620661584),
(4, 9, 1, 32, 15, 11, 9, 'Взаимоотношения с клиентами', 'Ключевые партнеры', 'Каналы коммуникации и сбыта', 'Ключевые ресурсы', 'Структура издержек', 'Потоки поступления доходов', 1623788878, 1623789053),
(5, 10, 1, 32, 17, 12, 12, 'Взаимоотношения с клиентами', 'Ключевые партнеры', 'Каналы коммуникации и сбыта', 'Ключевые ресурсы', 'Структура издержек', 'Потоки поступления доходов', 1624747751, 1624747789);

-- --------------------------------------------------------

--
-- Структура таблицы `checking_online_user`
--

CREATE TABLE `checking_online_user` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_active_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `checking_online_user`
--

INSERT INTO `checking_online_user` (`id`, `user_id`, `last_active_time`) VALUES
(1, 1, 1636282197),
(2, 28, 1636296198),
(4, 9, 1636282757),
(6, 21, 1636282226),
(7, 22, 1632745964),
(8, 16, 1629311867),
(12, 31, 1636284972),
(13, 37, 1636296121);

-- --------------------------------------------------------

--
-- Структура таблицы `communication_patterns`
--

CREATE TABLE `communication_patterns` (
  `id` int(11) UNSIGNED NOT NULL,
  `communication_type` int(11) NOT NULL,
  `initiator` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 NOT NULL,
  `project_access_period` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `is_remote` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `communication_patterns`
--

INSERT INTO `communication_patterns` (`id`, `communication_type`, `initiator`, `is_active`, `description`, `project_access_period`, `created_at`, `updated_at`, `is_remote`) VALUES
(1, 100, 28, 123, 'Готовы ли вы провести экспертизу по проекту {{наименование проекта, ссылка на проект}} ? И сколько времени на это уйдет. Необходимо срочно дать ответ. У вас открыт доступ к данному проекту на 3 дня.', 1, 1631126503, 1631126503, 0),
(2, 100, 28, 321, 'Описание шаблона коммуникации 2', 1, 1631126509, 1631126509, 0),
(3, 150, 28, 321, 'Описание шаблона коммуникации 1', NULL, 1631127238, 1631127238, 1),
(4, 150, 28, 321, 'Описание шаблона коммуникации 2', NULL, 1631127244, 1631127244, 1),
(5, 150, 28, 321, 'Описание шаблона коммуникации 3', NULL, 1631127250, 1631127250, 0),
(6, 300, 28, 321, 'Добрый день! Вы назначены на экспертизу по проекту {{наименование проекта, ссылка на проект}} по типам деятельности: {{список типов деятельности эксперта}}. Для того, чтобы задать интересующие вас вопросы используйте мессенджер.', NULL, 1631127816, 1631127816, 0),
(7, 300, 28, 321, 'Описание шаблона коммуникации 2', NULL, 1631127821, 1631127821, 0),
(8, 300, 28, 321, 'Описание шаблона коммуникации 3', NULL, 1631127825, 1631127825, 1),
(9, 350, 28, 321, 'Описание шаблона коммуникации 1', NULL, 1631128077, 1631128077, 0),
(10, 350, 28, 321, 'Описание шаблона коммуникации 2', NULL, 1631128090, 1631128090, 0),
(11, 350, 28, 321, 'Описание шаблона коммуникации 3', NULL, 1631128098, 1631128098, 0),
(12, 400, 28, 321, 'Описание шаблона коммуникации 1', NULL, 1631128304, 1631128304, 0),
(13, 400, 28, 321, 'Описание шаблона коммуникации 2', NULL, 1631128310, 1631128310, 0),
(14, 400, 28, 321, 'Описание шаблона коммуникации 3', NULL, 1631128315, 1631128315, 1),
(15, 100, 28, 321, 'Описание шаблона коммуникации 4', 5, 1631128545, 1631128545, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `communication_response`
--

CREATE TABLE `communication_response` (
  `id` int(11) UNSIGNED NOT NULL,
  `communication_id` int(11) NOT NULL,
  `answer` int(11) NOT NULL,
  `expert_types` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `confirm_gcp`
--

CREATE TABLE `confirm_gcp` (
  `id` int(11) UNSIGNED NOT NULL,
  `gcp_id` int(11) NOT NULL,
  `count_respond` int(11) NOT NULL,
  `count_positive` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `confirm_gcp`
--

INSERT INTO `confirm_gcp` (`id`, `gcp_id`, `count_respond`, `count_positive`) VALUES
(1, 1, 1, 1),
(2, 2, 3, 3),
(8, 8, 3, 2),
(9, 9, 2, 2),
(10, 11, 2, 2),
(11, 12, 2, 2),
(12, 14, 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `confirm_mvp`
--

CREATE TABLE `confirm_mvp` (
  `id` int(11) UNSIGNED NOT NULL,
  `mvp_id` int(11) NOT NULL,
  `count_respond` int(11) NOT NULL,
  `count_positive` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `confirm_mvp`
--

INSERT INTO `confirm_mvp` (`id`, `mvp_id`, `count_respond`, `count_positive`) VALUES
(1, 1, 1, 1),
(2, 2, 3, 3),
(8, 8, 3, 2),
(9, 9, 2, 2),
(10, 12, 2, 2),
(11, 13, 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `confirm_problem`
--

CREATE TABLE `confirm_problem` (
  `id` int(11) UNSIGNED NOT NULL,
  `problem_id` int(11) NOT NULL,
  `count_respond` int(11) NOT NULL,
  `count_positive` int(11) NOT NULL,
  `need_consumer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `confirm_problem`
--

INSERT INTO `confirm_problem` (`id`, `problem_id`, `count_respond`, `count_positive`, `need_consumer`) VALUES
(1, 1, 1, 1, 'Какую потребность потребителя сегмента проверяем!'),
(2, 3, 2, 2, 'Какую потребность потребителя сегмента проверяем!'),
(3, 4, 2, 2, 'Какую потребность потребителя сегмента проверяем'),
(4, 5, 3, 3, 'Какую потребность потребителя сегмента проверяем'),
(11, 12, 3, 3, 'Какую потребность потребителя сегмента проверяем'),
(12, 13, 2, 2, 'Какую потребность потребителя сегмента проверяем'),
(13, 15, 2, 2, 'Какую потребность потребителя сегмента проверяем'),
(15, 17, 2, 2, 'Какую потребность потребителя сегмента проверяем'),
(16, 18, 2, 1, 'Какую потребность потребителя сегмента проверяем'),
(17, 28, 4, 3, 'Какую потребность потребителя сегмента проверяем');

-- --------------------------------------------------------

--
-- Структура таблицы `confirm_segment`
--

CREATE TABLE `confirm_segment` (
  `id` int(11) UNSIGNED NOT NULL,
  `segment_id` int(11) NOT NULL,
  `count_respond` int(11) UNSIGNED NOT NULL,
  `count_positive` int(11) UNSIGNED NOT NULL,
  `greeting_interview` text NOT NULL,
  `view_interview` text NOT NULL,
  `reason_interview` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `confirm_segment`
--

INSERT INTO `confirm_segment` (`id`, `segment_id`, `count_respond`, `count_positive`, `greeting_interview`, `view_interview`, `reason_interview`) VALUES
(1, 16, 2, 1, 'Приветствие в начале встречи', 'Информация о вас для респондентов', 'Причина и тема (что побудило) для проведения исследования'),
(2, 17, 2, 2, 'Приветствие в начале встречи', 'Информация о вас для респондентов', 'Причина и тема (что побудило) для проведения исследования'),
(11, 26, 3, 2, 'Краткое описание сегмента', 'Краткое описание сегмента', 'Краткое описание сегмента'),
(17, 32, 2, 2, 'Приветствие в начале встречи', 'Информация о вас для респондентов!', 'Причина и тема (что побудило) для проведения исследования!'),
(18, 33, 2, 1, 'Приветствие в начале встречи', 'Информация о вас для респондентов', 'Причина и тема (что побудило) для проведения исследования');

-- --------------------------------------------------------

--
-- Структура таблицы `conversation_admin`
--

CREATE TABLE `conversation_admin` (
  `id` int(11) UNSIGNED NOT NULL,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `conversation_admin`
--

INSERT INTO `conversation_admin` (`id`, `admin_id`, `user_id`, `updated_at`) VALUES
(4, 21, 1, 1628622419),
(5, 21, 9, 1619020069),
(6, 21, 16, 1619017372);

-- --------------------------------------------------------

--
-- Структура таблицы `conversation_development`
--

CREATE TABLE `conversation_development` (
  `id` int(11) UNSIGNED NOT NULL,
  `dev_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `conversation_development`
--

INSERT INTO `conversation_development` (`id`, `dev_id`, `user_id`, `updated_at`) VALUES
(1, 22, 1, 1628622336),
(2, 22, 9, NULL),
(4, 22, 16, NULL),
(7, 22, 21, 1617353412),
(8, 22, 28, 1628455853),
(9, 22, 31, 1628622592),
(10, 22, 37, 1628795228);

-- --------------------------------------------------------

--
-- Структура таблицы `conversation_expert`
--

CREATE TABLE `conversation_expert` (
  `id` int(11) UNSIGNED NOT NULL,
  `expert_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `conversation_expert`
--

INSERT INTO `conversation_expert` (`id`, `expert_id`, `user_id`, `role`, `updated_at`) VALUES
(1, 31, 28, 30, 1628622560),
(2, 31, 21, 20, 1628622527),
(3, 31, 1, 10, 1628622976),
(4, 37, 28, 30, 1628795228),
(5, 37, 1, 10, 1632414079),
(6, 37, 21, 20, 1635872907),
(7, 31, 16, 10, 1635885548),
(8, 31, 9, 10, 1635886404),
(9, 37, 16, 10, 1635886412),
(10, 37, 9, 10, 1636203651);

-- --------------------------------------------------------

--
-- Структура таблицы `conversation_main_admin`
--

CREATE TABLE `conversation_main_admin` (
  `id` int(11) UNSIGNED NOT NULL,
  `main_admin_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `conversation_main_admin`
--

INSERT INTO `conversation_main_admin` (`id`, `main_admin_id`, `admin_id`, `updated_at`) VALUES
(4, 28, 21, 1617297612);

-- --------------------------------------------------------

--
-- Структура таблицы `duplicate_communications`
--

CREATE TABLE `duplicate_communications` (
  `id` int(11) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `adressee_id` int(11) NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `expected_results_interview_confirm_problem`
--

CREATE TABLE `expected_results_interview_confirm_problem` (
  `id` int(11) UNSIGNED NOT NULL,
  `problem_id` int(11) NOT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `expected_results_interview_confirm_problem`
--

INSERT INTO `expected_results_interview_confirm_problem` (`id`, `problem_id`, `question`, `answer`) VALUES
(9, 13, 'Вопрос 1', 'Ответ 1'),
(10, 13, 'Вопрос 2', 'Ответ 2'),
(11, 13, 'Вопрос 3', 'Ответ 3'),
(12, 15, 'Вопрос 1', 'Ответ 1'),
(13, 15, 'Вопрос 2', 'Ответ 2'),
(14, 25, 'Вопрос 1', 'Ответ 1'),
(15, 26, 'Вопрос 1', 'Ответ 1'),
(16, 26, 'Вопрос 2', 'Ответ 2'),
(19, 28, 'Вопрос 1', 'Ответ 1'),
(21, 28, 'Вопрос 2', 'Ответ 2'),
(22, 29, 'Вопрос 1', 'Ответ 1'),
(23, 29, 'Вопрос 2', 'Ответ 2'),
(24, 29, 'Вопрос 3', 'Ответ 3');

-- --------------------------------------------------------

--
-- Структура таблицы `expert_info`
--

CREATE TABLE `expert_info` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `education` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_degree` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope_professional_competence` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `publications` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `implemented_projects` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_in_implemented_projects` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `expert_info`
--

INSERT INTO `expert_info` (`id`, `user_id`, `education`, `academic_degree`, `position`, `type`, `scope_professional_competence`, `publications`, `implemented_projects`, `role_in_implemented_projects`) VALUES
(2, 31, 'Образование', 'Ученая степень, звание', 'Должность', '4|5|6', 'Сфера профессиональной компетенции Сфера профессиональной компетенции Сфера профессиональной компетенции Сфера профессиональной компетенции Сфера профессиональной компетенции Сфера профессиональной компетенции Сфера профессиональной компетенции Сфера профессиональной компетенции Сфера профессиональной компетенции', 'Научные публикации', 'Реализованные проекты', 'Роль в реализованных проектах'),
(8, 37, 'МГУ', 'Кандидат наук', 'Заведующий кафедрой', '1|2|3', 'Сфера профессиональной компетенции', 'Научные публикации', 'Реализованные проекты', 'Роль в реализованных проектах');

-- --------------------------------------------------------

--
-- Структура таблицы `gcps`
--

CREATE TABLE `gcps` (
  `id` int(11) UNSIGNED NOT NULL,
  `basic_confirm_id` int(11) UNSIGNED NOT NULL,
  `project_id` int(11) NOT NULL,
  `segment_id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `time_confirm` int(11) DEFAULT NULL,
  `exist_confirm` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `gcps`
--

INSERT INTO `gcps` (`id`, `basic_confirm_id`, `project_id`, `segment_id`, `problem_id`, `title`, `description`, `time_confirm`, `exist_confirm`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 16, 1, 'ГЦП 1', 'Наш продукт продукт 1 помогает сегмент 1, который хочет удовлетворить проблему описание гипотезы проблемы 1, избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, выгода, в отличии от продукт 2. Наш продукт продукт 1 помогает сегмент 1, который хочет удовлетворить проблему описание гипотезы проблемы 1, избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, выгода, в отличии от продукт 2.', 1620598011, 1, 1620597126, 1630229131),
(2, 4, 1, 26, 5, 'ГЦП 1', 'Наш продукт проблема 1 помогает сегмент 3, который хочет удовлетворить проблему описание гипотезы проблемы 1, избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, выгода, в отличии от проблема 2.', 1620661464, 1, 1620656987, 1620661464),
(8, 4, 1, 26, 5, 'ГЦП 2', 'Наш продукт продукт 3 помогает сегмент 3, который хочет удовлетворить проблему описание гипотезы проблемы 1, избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, выгода, в отличии от продукт 5.', NULL, NULL, 1621705573, 1621705573),
(9, 13, 1, 32, 15, 'ГЦП 1', 'Наш продукт продукт 1 помогает сегмент 4, который хочет удовлетворить проблему описание гипотезы проблемы 2, избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, выгода, в отличии от продукт 2.', 1623359145, 0, 1623353835, 1623359145),
(11, 13, 1, 32, 15, 'ГЦП 2', 'Наш продукт продукт 3 помогает сегмент 4, который хочет удовлетворить проблему описание гипотезы проблемы 2, избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, выгода, в отличии от продукт 4.', 1623359179, 1, 1623354865, 1623359179),
(12, 15, 1, 32, 17, 'ГЦП 1', 'Наш продукт продукт 1 помогает сегмент 4, который хочет удовлетворить проблему описание гипотезы проблемы 3, избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, выгода, в отличии от продукт 5.', 1624563764, 1, 1624476738, 1624563764),
(14, 15, 1, 32, 17, 'ГЦП 2', 'Наш продукт продукт 6 помогает сегмент 4, который хочет удовлетворить проблему описание гипотезы проблемы 3, избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, выгода, в отличии от продукт 7.', NULL, NULL, 1624477982, 1624477982),
(15, 15, 1, 32, 17, 'ГЦП 3', 'Наш продукт продукт 5 помогает сегмент 4, который хочет удовлетворить проблему описание гипотезы проблемы 3, избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, выгода, в отличии от продукт 2.', NULL, NULL, 1624647562, 1624647562),
(16, 1, 1, 16, 1, 'ГЦП 2', 'Наш продукт продукт 10 помогает сегмент 1, который хочет удовлетворить проблему описание гипотезы проблемы 1, избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, выгода, в отличии от продукт 5.', NULL, NULL, 1633513785, 1633513785);

-- --------------------------------------------------------

--
-- Структура таблицы `interview_confirm_gcp`
--

CREATE TABLE `interview_confirm_gcp` (
  `id` int(11) UNSIGNED NOT NULL,
  `respond_id` int(11) NOT NULL,
  `interview_file` varchar(255) DEFAULT NULL,
  `server_file` varchar(255) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `interview_confirm_gcp`
--

INSERT INTO `interview_confirm_gcp` (`id`, `respond_id`, `interview_file`, `server_file`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, '1', 1620597990, 1620598001),
(2, 2, NULL, NULL, '1', 1620661448, 1620661448),
(3, 3, NULL, NULL, '1', 1620661455, 1620661455),
(4, 4, NULL, NULL, '1', 1620661460, 1620661460),
(5, 26, NULL, NULL, '1', 1622144363, 1622144386),
(6, 27, NULL, NULL, '1', 1622144394, 1622144394),
(7, 29, '290421 корректировки спакселя в части вопросов и в сегменте.docx', 'ImtPU8szPEkly8K.docx', '1', 1623359034, 1623359059),
(9, 33, NULL, NULL, '0', 1623359088, 1623359129),
(10, 34, NULL, NULL, '1', 1623359171, 1623359171),
(11, 35, NULL, NULL, '1', 1623359175, 1623359175),
(12, 36, NULL, NULL, '1', 1624563109, 1624563260),
(13, 37, NULL, NULL, '1', 1624563296, 1624563296),
(14, 38, NULL, NULL, '1', 1625003696, 1625003696),
(15, 39, NULL, NULL, '1', 1625003709, 1625003709);

-- --------------------------------------------------------

--
-- Структура таблицы `interview_confirm_mvp`
--

CREATE TABLE `interview_confirm_mvp` (
  `id` int(11) UNSIGNED NOT NULL,
  `respond_id` int(11) NOT NULL,
  `interview_file` varchar(255) DEFAULT NULL,
  `server_file` varchar(255) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `interview_confirm_mvp`
--

INSERT INTO `interview_confirm_mvp` (`id`, `respond_id`, `interview_file`, `server_file`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, '1', 1620598590, 1620598590),
(2, 2, NULL, NULL, '1', 1620661543, 1620661543),
(3, 3, NULL, NULL, '1', 1620661549, 1620661549),
(4, 4, NULL, NULL, '1', 1620661555, 1620661555),
(5, 24, NULL, NULL, '1', 1622147621, 1622147636),
(6, 27, NULL, NULL, '1', 1623787544, 1623787544),
(7, 28, NULL, NULL, '1', 1623787567, 1623787606),
(8, 29, NULL, NULL, '1', 1624745676, 1624745696),
(11, 30, NULL, NULL, '1', 1624746143, 1624746143),
(12, 31, NULL, NULL, '1', 1625003940, 1625003940),
(13, 32, NULL, NULL, '1', 1625003966, 1625003966);

-- --------------------------------------------------------

--
-- Структура таблицы `interview_confirm_problem`
--

CREATE TABLE `interview_confirm_problem` (
  `id` int(11) UNSIGNED NOT NULL,
  `respond_id` int(11) NOT NULL,
  `interview_file` varchar(255) DEFAULT NULL,
  `server_file` varchar(255) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `interview_confirm_problem`
--

INSERT INTO `interview_confirm_problem` (`id`, `respond_id`, `interview_file`, `server_file`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, '1', 1620596938, 1620596938),
(2, 6, NULL, NULL, '1', 1620656952, 1620656952),
(3, 7, NULL, NULL, '1', 1620656957, 1620656957),
(4, 8, NULL, NULL, '1', 1620656962, 1620656962),
(5, 32, NULL, NULL, '1', 1622061828, 1622061861),
(6, 35, '290421 корректировки спакселя в части вопросов и в сегменте.docx', '8pvBeClqbbtFT18.docx', '1', 1623274519, 1623274567),
(7, 42, NULL, NULL, '0', 1623274559, 1623274594),
(8, 43, NULL, NULL, '1', 1623274670, 1623274670),
(9, 44, NULL, NULL, '1', 1623274674, 1623274674),
(10, 48, '270521_Комментарии к платформе.docx', '-3-aLqDGC9Ksldx.docx', '1', 1624472572, 1624473466),
(11, 49, NULL, NULL, '1', 1624473473, 1624473473),
(12, 50, NULL, NULL, '1', 1625003440, 1625003440),
(13, 51, NULL, NULL, '1', 1625003454, 1625003454);

-- --------------------------------------------------------

--
-- Структура таблицы `interview_confirm_segment`
--

CREATE TABLE `interview_confirm_segment` (
  `id` int(11) UNSIGNED NOT NULL,
  `respond_id` int(11) NOT NULL,
  `interview_file` varchar(255) DEFAULT NULL,
  `server_file` varchar(255) DEFAULT NULL,
  `result` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `interview_confirm_segment`
--

INSERT INTO `interview_confirm_segment` (`id`, `respond_id`, `interview_file`, `server_file`, `result`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 'Варианты проблем', '0', 1620580924, 1620592847),
(2, 2, NULL, NULL, 'Варианты проблем', '1', 1620580949, 1620580949),
(3, 3, NULL, NULL, 'Варианты проблем', '1', 1620655120, 1620655120),
(4, 4, NULL, NULL, 'Варианты проблем', '1', 1620655137, 1620655137),
(5, 18, NULL, NULL, 'Варианты проблем', '1', 1620656522, 1620656522),
(6, 19, NULL, NULL, 'Варианты проблем', '1', 1620656530, 1620656530),
(7, 20, NULL, NULL, 'Варианты проблем', '1', 1620656540, 1620656540),
(13, 60, '290421 корректировки спакселя в части вопросов и в сегменте.docx', 'NiSTbatxy73yi3T.docx', 'Варианты проблем...', '1', 1623267532, 1623267574),
(14, 61, NULL, NULL, 'Варианты проблем', '1', 1623267596, 1623267596),
(15, 62, '270521_Комментарии к платформе.docx', 'EUEWKfg-kP5TbtF.docx', 'Варианты проблем', '1', 1624470924, 1624470957),
(16, 63, '290421 корректировки спакселя в части вопросов и в сегменте.docx', 'SpjNmH12QaRHGZe.docx', 'Варианты проблем', '1', 1625001091, 1625001144);

-- --------------------------------------------------------

--
-- Структура таблицы `keywords_expert`
--

CREATE TABLE `keywords_expert` (
  `id` int(11) UNSIGNED NOT NULL,
  `expert_id` int(11) NOT NULL,
  `description` text CHARACTER SET utf8
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `keywords_expert`
--

INSERT INTO `keywords_expert` (`id`, `expert_id`, `description`) VALUES
(5, 37, 'Профессор доцент наука'),
(6, 31, 'Обучение Курсы программирования интересная работа подготовка кадров инструкции новые технологии трансфер технологий и другое');

-- --------------------------------------------------------

--
-- Структура таблицы `message_admin`
--

CREATE TABLE `message_admin` (
  `id` int(11) UNSIGNED NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `adressee_id` int(11) NOT NULL,
  `description` text,
  `status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `message_admin`
--

INSERT INTO `message_admin` (`id`, `conversation_id`, `sender_id`, `adressee_id`, `description`, `status`, `created_at`, `updated_at`) VALUES
(10, 4, 21, 1, 'Привет! Иван, как твои дела?', 20, 1605213553, 1605213553),
(11, 4, 1, 21, 'Нормально)) Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi. Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1605213578, 1605293578),
(13, 4, 21, 1, '— На самом деле, сэр, холода не существует. В соответствии с законами физики, то, что мы считаем холодом, в действительности является отсутствием тепла. Человек или предмет можно изучить на предмет того, имеет ли он или передаёт энергию. Абсолютный ноль (–460 градусов по Фаренгейту) есть полное отсутствие тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1615530335, 1615530335),
(17, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1615631488, 1615631488),
(19, 4, 21, 1, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1615736654, 1615736654),
(60, 4, 1, 21, '— На самом деле, сэр, холода не существует.', 20, 1615829715, 1615829715),
(61, 4, 21, 1, 'В соответствии с законами физики', 20, 1615829785, 1615829785),
(62, 4, 1, 21, 'Абсолютный ноль (–460 градусов по Фаренгейту)', 20, 1615829829, 1615829829),
(63, 4, 21, 1, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1615830202, 1615830202),
(74, 4, 1, 21, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1615903015, 1615903015),
(76, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1615906217, 1615906217),
(77, 4, 1, 21, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1615911528, 1615911528),
(78, 4, 21, 1, 'Нормально)) Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi. Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1615911584, 1615911584),
(79, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1615913106, 1615913106),
(80, 4, 21, 1, '', 20, 1615913161, 1616263565),
(81, 4, 1, 21, '', 20, 1615913261, 1616278222),
(82, 4, 1, 21, '', 20, 1615913444, 1616278224),
(83, 4, 21, 1, '', 20, 1615913562, 1616263619),
(84, 4, 1, 21, 'я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться', 20, 1615917130, 1616278233),
(85, 4, 1, 21, '', 20, 1615958909, 1616278236),
(86, 4, 1, 21, 'вот наконец есть возможность этим поделиться', 20, 1615959048, 1616278237),
(87, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1615989569, 1616278239),
(88, 4, 21, 1, 'их я выложу или позже, или уже когда вернусь домой.', 20, 1615990684, 1616263753),
(89, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь', 20, 1615990748, 1616278242),
(90, 4, 1, 21, 'Фотографий пока не будет', 20, 1615990967, 1616278244),
(91, 4, 21, 1, 'когда вернусь домой.', 20, 1615991101, 1616263853),
(92, 4, 21, 1, 'уже больше месяца', 20, 1615991187, 1616264057),
(93, 4, 1, 21, 'Нормально)) Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi.', 20, 1615991270, 1616278247),
(94, 4, 1, 21, 'Нормально)) Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi.', 20, 1615991275, 1616278249),
(95, 4, 1, 21, 'сейчас мы находимся в Панамском канале и здесь есть wifi.', 20, 1615991376, 1616278254),
(96, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1615991449, 1616264331),
(97, 4, 1, 21, '', 20, 1615991477, 1616278261),
(98, 4, 1, 21, 'что мы чувствуем при отсутствии тепла.', 20, 1615993302, 1616278269),
(99, 4, 21, 1, 'Холода не существует', 20, 1616051505, 1616264582),
(100, 4, 1, 21, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616054297, 1616278278),
(101, 4, 21, 1, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616054420, 1616264596),
(102, 4, 1, 21, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616054550, 1616278284),
(103, 4, 21, 1, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует.', 20, 1616054741, 1616264697),
(104, 4, 1, 21, 'Холода не существует.', 20, 1616055592, 1616278290),
(105, 4, 21, 1, 'мы знаем', 20, 1616055648, 1616264704),
(106, 4, 21, 1, 'Ок', 20, 1616055830, 1616264709),
(107, 4, 21, 1, 'Продолжаем', 20, 1616056412, 1616264746),
(108, 4, 21, 1, 'ещё раз', 20, 1616056477, 1616264804),
(109, 4, 1, 21, 'Здрасте', 20, 1616057014, 1616278302),
(110, 4, 1, 21, 'Ну что там?', 20, 1616057088, 1616278306),
(111, 4, 1, 21, 'Идем дальше', 20, 1616057400, 1616278313),
(112, 4, 1, 21, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует.', 20, 1616076046, 1616278317),
(113, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре.', 20, 1616076152, 1616264832),
(114, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Вся материя становится инертной и неспособной реагировать при этой температуре.', 20, 1616076171, 1616264834),
(115, 4, 1, 21, 'Холода не существует.', 20, 1616076191, 1616278320),
(116, 4, 21, 1, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616078150, 1616264938),
(117, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре.', 20, 1616079478, 1616264943),
(118, 4, 21, 1, 'Еще раз', 20, 1616079514, 1616264994),
(119, 4, 21, 1, 'И вот еще разок', 20, 1616079842, 1616264996),
(120, 4, 21, 1, 'Холода не существует.', 20, 1616080021, 1616264998),
(121, 4, 21, 1, 'неспособной реагировать при этой температуре.', 20, 1616080130, 1616265010),
(122, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует.', 20, 1616080523, 1616265012),
(123, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует.', 20, 1616080682, 1616265015),
(124, 4, 21, 1, 'я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться', 20, 1616080827, 1616265016),
(125, 4, 21, 1, 'и вот наконец', 20, 1616080845, 1616265017),
(126, 4, 21, 1, 'неспособной реагировать при этой температуре', 20, 1616080988, 1616265025),
(127, 4, 21, 1, 'неспособной реагировать при этой температуре неспособной реагировать при этой температуре неспособной реагировать при этой температуре неспособной реагировать при этой температуренеспособной реагировать при этой температуре', 20, 1616081011, 1616265028),
(128, 4, 21, 1, 'реагировать при этой температуре', 20, 1616085949, 1616265034),
(129, 4, 21, 1, 'есть возможность этим поделиться', 20, 1616086047, 1616265035),
(130, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует.', 20, 1616086073, 1616265068),
(131, 4, 21, 1, 'Холода не существует.', 20, 1616086181, 1616265071),
(132, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует.', 20, 1616086195, 1616265075),
(133, 4, 1, 21, 'это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616086718, 1616278326),
(134, 4, 1, 21, 'Холода не существует.', 20, 1616086747, 1616278327),
(135, 4, 1, 21, 'реагировать при этой температуре', 20, 1616086775, 1616278341),
(136, 4, 1, 21, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует.', 20, 1616091602, 1616278347),
(137, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует.', 20, 1616140212, 1616265104),
(138, 4, 21, 1, 'Холода не существует.', 20, 1616140334, 1616265105),
(139, 4, 21, 1, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует.', 20, 1616143135, 1616265107),
(140, 4, 21, 1, 'реагировать при этой температуре', 20, 1616144322, 1616265107),
(141, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре.', 20, 1616144399, 1616265106),
(142, 4, 21, 1, 'Холода не существует.', 20, 1616144570, 1616265105),
(143, 4, 21, 1, '', 20, 1616145102, 1616265108),
(144, 4, 21, 1, 'при этой температуре.', 20, 1616146204, 1616265108),
(145, 4, 21, 1, '', 20, 1616146827, 1616265109),
(146, 4, 21, 1, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует.', 20, 1616153408, 1616265110),
(147, 4, 21, 1, 'реагировать при этой температуре', 20, 1616154284, 1616265110),
(148, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует.', 20, 1616154348, 1616265111),
(149, 4, 1, 21, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616158194, 1616278361),
(150, 4, 21, 1, 'мы чувствуем при отсутствии тепла', 20, 1616158408, 1616265355),
(151, 4, 21, 1, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла', 20, 1616158562, 1616265357),
(152, 4, 1, 21, 'становится инертной и неспособной реагировать при этой температуре. Холода не существует.', 20, 1616159187, 1616278369),
(153, 4, 21, 1, 'Холода не существует', 20, 1616159586, 1616265368),
(154, 4, 1, 21, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла', 20, 1616159628, 1616278374),
(155, 4, 21, 1, 'Холода не существует.', 20, 1616160079, 1616265377),
(156, 4, 21, 1, 'при отсутствии тепла', 20, 1616160854, 1616265380),
(157, 4, 21, 1, 'Нормально)) Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi. Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616162594, 1616265382),
(158, 4, 1, 21, 'Итак, понеслась:', 20, 1616162637, 1616278394),
(159, 4, 21, 1, 'Я на судне уже больше месяца и пока я здесь', 20, 1616163259, 1616265388),
(160, 4, 1, 21, 'Нормально))', 20, 1616163307, 1616278397),
(161, 4, 21, 1, 'есть возможность этим поделиться', 20, 1616163422, 1616265391),
(208, 4, 1, 21, 'Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi. Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616222151, 1616222151),
(209, 4, 21, 1, 'Я на судне уже больше месяца и пока я здесь', 20, 1616222185, 1616222185),
(210, 4, 1, 21, 'Итак, понеслась:', 20, 1616225842, 1616278413),
(211, 4, 21, 1, 'Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой.', 20, 1616225874, 1616265399),
(212, 4, 1, 21, 'Нормально)) Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi. Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616242012, 1616278421),
(213, 4, 21, 1, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616278485, 1616278494),
(214, 4, 21, 1, 'их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616278540, 1616281843),
(215, 5, 15, 9, 'Я на судне уже больше месяца и пока я здесь', 20, 1616282764, 1616282935),
(216, 5, 9, 15, 'Добрый день!', 20, 1616283179, 1616283203),
(217, 4, 1, 21, 'Нормально))', 20, 1616310560, 1616310570),
(218, 4, 21, 1, 'я писал все интересное что здесь происходит и вот', 20, 1616310621, 1616310635),
(219, 4, 21, 1, 'Нормально)) Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi. Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616310710, 1616310721),
(220, 4, 21, 1, 'Итак, понеслась:', 20, 1616310854, 1616310863),
(221, 4, 21, 1, 'сейчас мы находимся в Панамском канале', 20, 1616311039, 1616311047),
(222, 4, 21, 1, 'Я на судне уже больше месяца', 20, 1616311200, 1616311215),
(223, 4, 21, 1, 'их я выложу или позже,', 20, 1616311657, 1616311669),
(224, 4, 21, 1, 'сейчас мы находимся в Панамском канале', 20, 1616311757, 1616311766),
(225, 4, 21, 1, 'Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канал', 20, 1616312059, 1616312071),
(226, 4, 21, 1, 'возможность добраться до интернета', 20, 1616312176, 1616312182),
(227, 4, 21, 1, 'Итак, понеслась:', 20, 1616312247, 1616312257),
(228, 4, 21, 1, 'есть возможность этим поделиться.', 20, 1616312610, 1616312620),
(229, 4, 21, 1, 'Нормально))', 20, 1616313274, 1616313282),
(230, 4, 21, 1, 'Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канал', 20, 1616313697, 1616313709),
(231, 4, 21, 1, 'Итак, понеслась:', 20, 1616313819, 1616313830),
(232, 5, 15, 9, 'пока я здесь', 20, 1616314280, 1616314341),
(233, 5, 15, 9, 'Я на судне уже больше месяца и пока я здесь', 20, 1616314432, 1616314488),
(234, 4, 1, 21, 'наконец есть возможность этим поделиться', 20, 1616314874, 1616314886),
(237, 4, 21, 1, 'Нормально)) Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi. Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616319681, 1616319737),
(238, 4, 1, 21, 'и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616319925, 1616319939),
(239, 4, 21, 1, 'Итак, понеслась:', 20, 1616320042, 1616320057),
(240, 4, 21, 1, 'Я на судне уже больше месяца и пока я здесь,', 20, 1616320245, 1616320279),
(241, 4, 1, 21, 'Фотографий пока не будет,', 20, 1616320408, 1616320416),
(242, 4, 21, 1, 'и вот наконец есть возможность', 20, 1616320531, 1616320540),
(243, 4, 1, 21, 'Нормально)) Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi. Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616321189, 1616321203),
(244, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616321321, 1616321328),
(245, 4, 1, 21, 'Наконец появилась возможность', 20, 1616321477, 1616321484),
(246, 4, 21, 1, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616321520, 1616321527),
(247, 4, 21, 1, 'Фотографий пока не будет', 20, 1616321714, 1616321725),
(248, 4, 1, 21, 'Нормально)) Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi.', 20, 1616321751, 1616321771),
(249, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616321757, 1616321771),
(250, 4, 21, 1, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616321863, 1616321883),
(251, 4, 21, 1, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616321871, 1616321888),
(252, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616321899, 1616321914),
(253, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616321907, 1616321920),
(254, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616322014, 1616322023),
(255, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616322175, 1616322182),
(256, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616322290, 1616322297),
(257, 4, 1, 21, 'Итак, понеслась:', 20, 1616322353, 1616322360),
(258, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616322500, 1616322507),
(259, 4, 1, 21, 'Итак, понеслась:', 20, 1616322583, 1616322588),
(260, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616323729, 1616323740),
(261, 4, 1, 21, 'Фотографий пока не будет', 20, 1616323852, 1616323858),
(262, 4, 21, 1, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616323866, 1616323872),
(263, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616323905, 1616323910),
(264, 4, 21, 1, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616323920, 1616323926),
(265, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616324098, 1616324107),
(266, 4, 21, 1, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616324139, 1616324146),
(267, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616324231, 1616324239),
(268, 4, 1, 21, 'Фотографий пока не будет', 20, 1616324738, 1616324745),
(269, 4, 21, 1, 'Фотографий пока не будет11', 20, 1616324758, 1616324764),
(270, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:  Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616324845, 1616324884),
(271, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616324875, 1616324888),
(272, 4, 21, 1, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616324899, 1616324917),
(273, 4, 21, 1, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616324908, 1616324918),
(274, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616325299, 1616325344),
(275, 4, 1, 21, 'Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:  Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:  Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:  Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:  Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616325328, 1616325345),
(276, 4, 21, 1, 'Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616325376, 1616325420),
(277, 4, 21, 1, 'Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась: Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616325390, 1616325439),
(278, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616344095, 1616344127),
(279, 4, 21, 1, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616344189, 1616344494),
(282, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616346896, 1616346905),
(283, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616369508, 1616369531),
(284, 4, 1, 21, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616369717, 1616369728),
(285, 4, 1, 21, 'Добрый день', 20, 1616401031, 1616401046),
(286, 4, 1, 21, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует.  Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616401130, 1616401269),
(287, 4, 1, 21, 'Холода не существует.', 20, 1616401283, 1616401370),
(288, 4, 1, 21, 'Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616401329, 1616401369),
(289, 4, 1, 21, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616401661, 1616401669),
(290, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616402194, 1616402288),
(291, 4, 1, 21, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616402299, 1616402307),
(292, 4, 21, 1, 'Вся материя становится инертной и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла. Вся материя становится инертной  и неспособной реагировать при этой температуре. Холода не существует. Мы создали это слово для описания того, что мы чувствуем при отсутствии тепла.', 20, 1616402451, 1616402463),
(293, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616447896, 1616447910),
(294, 4, 21, 1, 'я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616447935, 1616447943),
(297, 4, 1, 21, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616585176, 1616590976),
(298, 5, 9, 15, 'Вы не можете измерить темноту. Простой луч света может ворваться в мир темноты и осветить его. Как вы можете узнать, насколько тёмным является какое-либо пространство?', 20, 1616585716, 1616585848),
(299, 4, 1, 21, 'Воспроизведение текста оттисками отдельных штампов было известно с давних времён (самый ранний известный пример — Фестский диск).', 20, 1616593257, 1616593306),
(300, 4, 21, 1, 'Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1616593386, 1616593403),
(301, 4, 21, 1, 'В XV веке Иоганн Гутенберг первым в Европе использовал для печати типографский станок с металлическими подвижными литерами, это позволяло получить большое количество оттисков. Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1616678130, 1616678241),
(302, 4, 1, 21, 'в Европе использовал для печати типографский станок с металлическими подвижными литерами, это позволяло получить большое количество оттисков. Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1616921118, 1616921153),
(303, 4, 21, 1, 'Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1616921179, 1616921204),
(304, 4, 1, 21, 'В XV веке Иоганн Гутенберг первым в Европе использовал для печати типографский станок с металлическими подвижными литерами, это позволяло получить большое количество оттисков. Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги', 20, 1616921986, 1616922002),
(305, 4, 21, 1, 'Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги', 20, 1616922028, 1616922044),
(306, 4, 1, 21, 'в Европе использовал для печати типографский станок с металлическими подвижными литерами, это позволяло получить большое количество оттисков. Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1616922373, 1616922385),
(307, 4, 21, 1, 'Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1616922394, 1616922400),
(308, 4, 1, 21, 'в Европе использовал для печати типографский станок с металлическими подвижными литерами, это позволяло получить большое количество оттисков. Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1616922528, 1616922539),
(309, 4, 1, 21, 'В XV веке Иоганн Гутенберг первым в Европе использовал для печати типографский станок с металлическими подвижными литерами, это позволяло получить большое количество оттисков.', 20, 1616922733, 1616922740),
(310, 4, 1, 21, 'в Европе использовал для печати типографский станок с металлическими подвижными литерами, это позволяло получить большое количество оттисков. Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1616922910, 1616922918),
(311, 4, 21, 1, 'Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1616922936, 1616922945),
(312, 4, 21, 1, 'В XV веке Иоганн Гутенберг первым в Европе использовал для печати типографский станок с металлическими подвижными литерами, это позволяло получить большое количество оттисков.', 20, 1616923469, 1616923481),
(313, 4, 21, 1, 'Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1616923506, 1616923518),
(314, 4, 1, 21, 'в Европе использовал для печати типографский станок с металлическими подвижными литерами, это позволяло получить большое количество оттисков. Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1616924113, 1616924135),
(315, 4, 1, 21, 'Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1616924156, 1616924169),
(316, 4, 21, 1, 'в Европе использовал для печати типографский станок с металлическими подвижными литерами, это позволяло получить большое количество оттисков. Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1617196014, 1617196370),
(317, 4, 21, 1, 'Механизмы, подобные станку Гутенберга, издавна применялись в виноделии и при производстве бумаги.', 20, 1617196358, 1617196389),
(318, 4, 21, 1, 'В XV веке Иоганн Гутенберг первым в Европе использовал для печати типографский станок с металлическими подвижными литерами, это позволяло получить большое количество оттисков.', 20, 1617197237, 1617197365),
(319, 4, 21, 1, 'Иоганн Гутенберг первым в Европе использовал для печати типографский станок с металлическими подвижными литерами, это позволяло получить большое количество оттисков.', 20, 1617197304, 1617197364),
(320, 4, 1, 21, 'Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617197363, 1617197698),
(321, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617198180, 1617198204),
(322, 4, 21, 1, 'Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617199459, 1617199501),
(323, 4, 21, 1, 'кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617199725, 1617199744),
(324, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617199743, 1617199961),
(325, 4, 21, 1, 'Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617199987, 1617200003),
(326, 4, 1, 21, 'был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617200001, 1617200060),
(327, 4, 1, 21, 'литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617200039, 1617200060),
(328, 4, 21, 1, 'кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617200056, 1617200154),
(329, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617200288, 1617200388),
(330, 4, 1, 21, 'Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617200402, 1617200578),
(331, 4, 21, 1, 'был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617200610, 1617200625),
(332, 4, 21, 1, 'Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617200715, 1617200738),
(333, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617200781, 1617200803),
(334, 4, 21, 1, 'был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617202167, 1617202179),
(335, 4, 1, 21, 'Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617202177, 1617202201),
(336, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617202247, 1617202295),
(337, 4, 1, 21, 'Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617202343, 1617202397),
(338, 4, 21, 1, 'набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617202423, 1617202450),
(339, 4, 21, 1, 'Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617202495, 1617202508),
(340, 4, 1, 21, 'был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617204029, 1617204052),
(341, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617204047, 1617204072),
(342, 4, 21, 1, 'Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617208224, 1617208255),
(343, 4, 21, 1, 'был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617208243, 1617208266),
(344, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617208279, 1617208331),
(345, 4, 21, 1, 'Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617208292, 1617208331),
(346, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617208932, 1617208985),
(347, 4, 21, 1, 'Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. Изобретателем печати с наборной печатной формы считается китайский кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617208962, 1617208986),
(348, 4, 1, 21, 'кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].  кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617209597, 1617209636),
(349, 4, 1, 21, 'набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617209611, 1617209644),
(350, 4, 1, 21, 'кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].  кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].  кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617209618, 1617209647),
(351, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617210127, 1617210243),
(352, 4, 21, 1, 'кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617210192, 1617210244),
(353, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617210289, 1617211229),
(354, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617211205, 1617211272),
(355, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28].', 20, 1617211360, 1617211378),
(356, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617211777, 1617211786),
(357, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28] Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617211785, 1617212033),
(358, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28] Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617212080, 1617212091);
INSERT INTO `message_admin` (`id`, `conversation_id`, `sender_id`, `adressee_id`, `description`, `status`, `created_at`, `updated_at`) VALUES
(359, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28] Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617212089, 1617212103),
(360, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617212879, 1617212940),
(361, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]. кузнец Би Шэн (990—1051). Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617212984, 1617213017),
(362, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617213127, 1617213153),
(363, 4, 1, 21, 'набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617219258, 1617219321),
(364, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617219329, 1617219352),
(365, 4, 1, 21, 'набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617221978, 1617222113),
(366, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617222399, 1617222413),
(367, 4, 1, 21, 'набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617223107, 1617223127),
(368, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617223616, 1617223650),
(369, 4, 1, 21, 'набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617223693, 1617223713),
(370, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617223779, 1617223807),
(372, 4, 21, 1, 'в «Записках о ручье снов»[28]', 20, 1617224811, 1617224982),
(374, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617225001, 1617225018),
(375, 4, 21, 1, 'с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617226907, 1617226927),
(376, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617226941, 1617226954),
(377, 4, 21, 1, 'в «Записках о ручье снов»[28]', 20, 1617226952, 1617226979),
(378, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617227030, 1617227037),
(379, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617227036, 1617227050),
(380, 4, 1, 21, 'в «Записках о ручье снов»[28]', 20, 1617227084, 1617227091),
(381, 4, 21, 1, 'в «Записках о ручье снов»[28]  в «Записках о ручье снов»[28]', 20, 1617227089, 1617227136),
(382, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617274894, 1617274939),
(383, 4, 21, 1, 'набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617274968, 1617275003),
(384, 4, 1, 21, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617280290, 1617280346),
(385, 4, 1, 21, 'набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1617556118, 1617557892),
(386, 4, 21, 1, 'Его способ печати сотен и тысяч оттисков с одной формы, набранной литерами из обожжённой глины, был описан учёным Шэнь Ко в «Записках о ручье снов»[28]', 20, 1628622419, 1628622458);

-- --------------------------------------------------------

--
-- Структура таблицы `message_development`
--

CREATE TABLE `message_development` (
  `id` int(11) UNSIGNED NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `adressee_id` int(11) NOT NULL,
  `description` text,
  `status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `message_development`
--

INSERT INTO `message_development` (`id`, `conversation_id`, `sender_id`, `adressee_id`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 22, 1, 'Здравствуйте! У Вас все хорошо?', 20, 1605219999, 1616620894),
(2, 7, 21, 22, 'Хорошего дня, товарищи!', 20, 1605295999, 1605295999),
(3, 1, 1, 22, 'Подъезжая к морю на машине, либо подходя пешком, всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1616673770, 1616673819),
(4, 1, 22, 1, 'Не громко, находясь в каюте лишь слышно низкий гул, но его практически не замечаешь.', 20, 1616673839, 1616673852),
(5, 1, 1, 22, 'Чернила с поверхности папируса легко смывались и лист мог использоваться вторично для новых записей. Длинная полоса (по-гречески «хартия») склеенных листов папируса (обычно около 20) исписывалась с одной стороны.', 20, 1616677276, 1616677300),
(6, 1, 1, 22, 'В V веке Исидор Севильский разъяснял различия между книгой, свитком и кодексом в соответствии с существовавшими тогда представлениями следующим образом: кодекс составлен из множества книг, книга — из одного свитка.', 20, 1616677323, 1616677341),
(7, 1, 22, 1, 'О торговцах книгами сообщают греческие авторы V века до н. э. (например, Ксенофонт). Книгопродавцев (библиополов) упоминают в своих комедиях Аристомен и Никофон.', 20, 1616677363, 1616677379),
(8, 1, 22, 1, 'Рукописные книги (манускрипты) создавались в мастерских-скрипториях (от латинского «скриптор» — «писец»). В столице Византии Константинополе скрипторий (известно, что он работал уже в 356 году) основал Констанций II, при котором также в столице появилась публичная библиотека (в 475 году в ней насчитывалось 120 000 книг).', 20, 1616677426, 1616677459),
(9, 1, 1, 22, 'Падение Римской империи в V веке уменьшило её культурное влияние на остальной мир.', 20, 1616677512, 1616677604),
(11, 1, 22, 1, 'Книги стали сравнительно доступными, хотя для большинства всё ещё весьма дорогими.', 20, 1616678205, 1616678219),
(12, 1, 1, 22, 'Методы, используемые для печатания и переплетания книг, практически не подвергались никаким изменениям в период с XV по начало XX века[источник не указан 84 дня].', 20, 1616678770, 1616678787),
(13, 1, 22, 1, 'После сбора листов с оттисками осуществляются переплётные работы.', 20, 1616678808, 1616678856),
(14, 1, 22, 1, 'После сбора листов с оттисками осуществляются переплётные работы. После сбора листов с оттисками осуществляются переплётные работы.', 20, 1616678816, 1616678857),
(15, 1, 1, 22, 'Для современного книгопечатания характерна стандартизация: изготавливаемые книги имеют, как правило, определённый размер и формат.', 20, 1616678853, 1616678867),
(16, 1, 1, 22, '', 20, 1616678933, 1616678946),
(17, 1, 22, 1, 'После сбора листов с оттисками осуществляются переплётные работы.', 20, 1616679056, 1616679092),
(18, 1, 22, 1, 'После сбора листов с оттисками осуществляются переплётные работы. После сбора листов с оттисками осуществляются переплётные работы.', 20, 1616679062, 1616679092),
(19, 1, 22, 1, 'После сбора листов с оттисками осуществляются переплётные работы. После сбора листов с оттисками осуществляются переплётные работы. После сбора листов с оттисками осуществляются переплётные работы.', 20, 1616679069, 1616679091),
(20, 1, 22, 1, '', 20, 1616679110, 1616679130),
(21, 1, 1, 22, 'Появление цифровой печати содействовало формированию нового подхода к книгоизданию — т. н. печати по требованию, когда копии книг изготавливаются специально для конкретного клиента, уже после того, как он оформит заказ на то или иное издание.', 20, 1616679151, 1616679158),
(22, 1, 1, 22, 'Нарастающее число публикаций (так называемый информационный взрыв) поставил перед библиотеками вопрос хранения столь большого массива информации.', 20, 1616679216, 1616679225),
(23, 1, 22, 1, 'Нарастающее число публикаций (так называемый информационный взрыв) поставил перед библиотеками вопрос хранения столь большого массива информации. Нарастающее число публикаций (так называемый информационный взрыв) поставил перед библиотеками вопрос хранения столь большого массива информации.', 20, 1616679231, 1616679239),
(24, 8, 28, 22, 'Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi. Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1616758631, 1616758678),
(25, 8, 28, 22, 'Доехали вопреки ожиданиям быстро, примерно за 3-4 часа.', 20, 1616759345, 1616759365),
(26, 8, 22, 28, 'Сразу, как только мы заселились, я не успел разложить вещи, как в мою голову ворвался такой поток информации, что ни в сказке сказать, ни топором не вырубить. Во-первых, на судне абсолютно все бумаги - мануалы, журналы, и так далее - все на английском языке.', 20, 1616761776, 1616761806),
(27, 8, 22, 28, 'Неплохая одноместная каюта. Внутри несколько шкафов и полок, удобная кровать, койка напередохнуть, стол, стул, умывальник и внутрисудовой телефон.', 20, 1616762083, 1616762107),
(28, 8, 22, 28, 'Возле входа в канал находится якорная стоянка, где куча судов стоит и ждет своей очереди на проход.', 20, 1616762622, 1616762646),
(29, 8, 22, 28, 'Только мы подошли к шлюзу, как тут же нас привязали к резвым локомотивам - Мулам.', 20, 1616762737, 1616762848),
(30, 8, 22, 28, 'Так как наше судно не очень широкое, проблем с заходом в шлюз не было, а по длине получилось так, что за нами сзади поместилось еще одно небольшое судно.', 20, 1616762876, 1616762888),
(31, 8, 28, 22, 'А через десять минут открылось еще одно не менее грандиозное зрелище - строительство новых шлюзов, которые будут пропускать суда бОльших размеров, чем нынешние шлюзы.', 20, 1616762904, 1616762917),
(32, 8, 22, 28, 'Возле входа в канал находится якорная стоянка, где куча судов стоит и ждет своей очереди на проход.', 20, 1616762978, 1616762995),
(33, 8, 22, 28, 'Возле входа в канал находится якорная стоянка, где куча судов стоит и ждет своей очереди на проход. Возле входа в канал находится якорная стоянка, где куча судов стоит и ждет своей очереди на проход.', 20, 1616763020, 1616763440),
(34, 8, 22, 28, 'Стрелка тахометра поползла вверх, судно слегка задрожало и мы медленно но верно начали набирать скорость и двигаться в сторону канала.', 20, 1616763603, 1616763638),
(35, 8, 28, 22, 'Стрелка тахометра поползла вверх, судно слегка задрожало и мы медленно но верно начали набирать скорость и двигаться в сторону канала.  Стрелка тахометра поползла вверх, судно слегка задрожало и мы медленно но верно начали набирать скорость и двигаться в сторону канала.  Стрелка тахометра поползла вверх, судно слегка задрожало и мы медленно но верно начали набирать скорость и двигаться в сторону канала.', 20, 1616763650, 1616763686),
(36, 8, 28, 22, 'Это значит что мы подходим к шлюзам. Только мы подошли к шлюзу, как тут же нас привязали к резвым локомотивам - Мулам.', 20, 1616763726, 1616763740),
(37, 8, 28, 22, 'Это значит что мы подходим к шлюзам. Только мы подошли к шлюзу, как тут же нас привязали к резвым локомотивам - Мулам.  Это значит что мы подходим к шлюзам. Только мы подошли к шлюзу, как тут же нас привязали к резвым локомотивам - Мулам.', 20, 1616763781, 1616763792),
(38, 8, 28, 22, 'Проходя мимо Бермудских островов, мы попали под влияние местной погоды - то светит солнце и стоит невыносимое пекло, то идет огромная туча и под ней стена ливня, сквозь которую ничего не видно, то все небо усыпано облаками разных форм, оттенков и размеров и сияет радуга.', 20, 1616763981, 1616763995),
(39, 8, 28, 22, 'В этом городе случилось то, что я так долго ждал - наконец-то получилось сойти на берег. Город относительно чистый, хотя периодически мусор попадается, но совсем немного. Много парков, да и вообще много зелени.', 20, 1616764093, 1616764108),
(40, 8, 28, 22, 'В Роттердаме мы простояли почти 3 дня и вот, погрузка завершена и по причалу в нашу сторону идет человек в спасательном жилете и с чемоданом. Это лоцман.', 20, 1616764207, 1616764218),
(41, 8, 28, 22, 'Впереди нас ждет 10-дневный переход через Атлантический океан из Роттердама в Парамарибо.', 20, 1616764376, 1616764391),
(42, 8, 28, 22, 'Подъезжая к морю на машине, либо подходя пешком, всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1616764544, 1616764623),
(43, 8, 28, 22, 'к морю на машине, либо подходя пешком, всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет. Подъезжая к морю на машине, либо подходя пешком, всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1616764590, 1616764632),
(44, 8, 28, 22, 'Подъезжая к морю на машине, либо подходя пешком, всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет. Подъезжая к морю на машине, либо подходя пешком, всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1616764613, 1616764631),
(45, 8, 22, 28, 'Поэтому там все носят шумозащитные наушники и не разговаривают :) Ну да ладно, вернемся на верхнюю палубу. А там стоят рефконтейнеры, в них находятся рефрежираторные установки для охлаждения содержимого и эти установки постоянно жужжат своими вентиляторами.', 20, 1616764667, 1616764717),
(46, 8, 22, 28, 'Ну да ладно, вернемся на верхнюю палубу. А там стоят рефконтейнеры, в них находятся рефрежираторные установки для охлаждения содержимого и эти установки постоянно жужжат своими вентиляторами.', 20, 1616764741, 1616764752),
(47, 8, 22, 28, '', 20, 1616764855, 1616764865),
(48, 8, 22, 28, 'Поэтому там все носят шумозащитные наушники и не разговаривают :) Ну да ладно, вернемся на верхнюю палубу. А там стоят рефконтейнеры, в них находятся рефрежираторные установки для охлаждения содержимого и эти установки постоянно жужжат своими вентиляторами.', 20, 1616764936, 1616764981),
(49, 8, 28, 22, 'Ну да ладно, вернемся на верхнюю палубу. А там стоят рефконтейнеры, в них находятся рефрежираторные установки для охлаждения содержимого и эти установки постоянно жужжат своими вентиляторами.', 20, 1616765083, 1616766695),
(50, 7, 21, 22, 'Время без пяти, надо делать запись в судовой журнал, стою, пишу. И тут в ночной тиши из УКВ-радиостанции раздается смех.', 20, 1616773412, 1616773450),
(51, 7, 22, 21, 'Время без пяти, надо делать запись в судовой журнал, стою, пишу. И тут в ночной тиши из УКВ-радиостанции раздается смех.', 20, 1616773463, 1616775510),
(52, 7, 22, 21, 'А мы уже несколько дней в эфире ничего не слышали - судов нету, а если и попадаются, то говорить с ними не о чем, всегда расходимя молча.', 20, 1616773716, 1616775517),
(53, 8, 28, 22, 'Время без пяти, надо делать запись в судовой журнал, стою, пишу.', 20, 1616773953, 1616773980),
(54, 8, 22, 28, 'Время без пяти, надо делать запись в судовой журнал, стою, пишу.  Время без пяти, надо делать запись в судовой журнал, стою, пишу.', 20, 1616773989, 1616773999),
(55, 7, 21, 22, 'А мы уже несколько дней в эфире ничего не слышали - судов нету, а если и попадаются, то говорить с ними не о чем, всегда расходимя молча. А мы уже несколько дней в эфире ничего не слышали - судов нету, а если и попадаются, то говорить с ними не о чем, всегда расходимя молча.', 20, 1616775577, 1616775610),
(56, 7, 21, 22, 'А мы уже несколько дней в эфире ничего не слышали - судов нету, а если и попадаются, то говорить с ними не о чем, всегда расходимя молча.', 20, 1616790800, 1616790854),
(57, 7, 21, 22, 'то говорить с ними не о чем, всегда расходимя молча.', 20, 1616790870, 1616790892),
(58, 7, 22, 21, 'а если и попадаются, то говорить с ними не о чем, всегда расходимя молча.', 20, 1616790909, 1616790947),
(59, 7, 21, 22, 'А мы уже несколько дней в эфире ничего не слышали - судов нету, а если и попадаются, то говорить с ними не о чем, всегда расходимя молча.', 20, 1616791131, 1616791157),
(60, 7, 21, 22, 'а если и попадаются, то говорить с ними не о чем, всегда расходимя молча.', 20, 1616791534, 1616791617),
(61, 7, 21, 22, 'всегда расходимя молча.', 20, 1616791603, 1616791780),
(62, 7, 21, 22, 'А мы уже несколько дней в эфире ничего не слышали - судов нету, а если и попадаются, то говорить с ними не о чем, всегда расходимя молча.', 20, 1616791679, 1616791795),
(63, 7, 21, 22, 'судов нету, а если и попадаются, то говорить с ними не о чем, всегда расходимя молча.', 20, 1616791808, 1616791890),
(64, 7, 21, 22, 'А мы уже несколько дней в эфире ничего не слышали - судов нету, а если и попадаются, то говорить с ними не о чем, всегда расходимя молча.', 20, 1616791881, 1616791915),
(65, 7, 21, 22, 'то говорить с ними не о чем, всегда расходимя молча.', 20, 1616791976, 1616792549),
(66, 7, 21, 22, 'а если и попадаются, то говорить с ними не о чем, всегда расходимя молча.', 20, 1616792566, 1616792589),
(67, 7, 21, 22, 'всегда расходимя молча.', 20, 1616792879, 1616792891),
(68, 7, 21, 22, 'то говорить с ними не о чем, всегда расходимя молча.', 20, 1616793258, 1616793357),
(69, 7, 21, 22, 'а если и попадаются, то говорить с ними не о чем, всегда расходимя молча.', 20, 1616793347, 1616793357),
(70, 7, 21, 22, 'всегда расходимя молча.', 20, 1616793506, 1616793617),
(71, 7, 21, 22, 'Время без пяти, надо делать запись в судовой журнал, стою, пишу. И тут в ночной тиши из УКВ-радиостанции раздается смех.', 20, 1616793644, 1616793800),
(72, 7, 21, 22, 'И тут в ночной тиши из УКВ-радиостанции раздается смех.', 20, 1616793787, 1616793802),
(73, 7, 21, 22, 'На самом деле вся путаница в понимании этого слова появилась с появлением \"Апокалипсиса\" И.Богослова, в котором описываются ужасы. Хотя, на самом деле это слово переводится с древнегреческого как Откровение (Отворение, Открывание, Открытие, Озарение).', 20, 1616794507, 1616794544),
(74, 7, 21, 22, 'в котором описываются ужасы. Хотя, на самом деле это слово переводится с древнегреческого как Откровение (Отворение, Открывание, Открытие, Озарение).', 20, 1616794559, 1616794570),
(75, 7, 22, 21, 'Т.е. взаимодействие дихотомий друг с другом создало непрекращающийся колебательный процесс очень похожий на генерацию колебаний мультивибратора, в котором запуск генерации не возможен, если все параметры всех элементов одинаковы или оба плеча мультивибратора полностью одинаковы и симметричны относительно друг друга.', 20, 1616794599, 1616794614),
(76, 7, 21, 22, 'в котором запуск генерации не возможен, если все параметры всех элементов одинаковы или оба плеча мультивибратора полностью одинаковы и симметричны относительно друг друга.', 20, 1616835089, 1616835126),
(77, 7, 22, 21, '', 20, 1616835171, 1616835194),
(78, 8, 28, 22, 'Ну да ладно, вернемся на верхнюю палубу. А там стоят рефконтейнеры, в них находятся рефрежираторные установки для охлаждения содержимого и эти установки постоянно жужжат своими вентиляторами.', 20, 1616835295, 1616835486),
(79, 1, 1, 22, 'Появление цифровой печати содействовало формированию нового подхода к книгоизданию — т. н. печати по требованию, когда копии книг изготавливаются специально для конкретного клиента, уже после того, как он оформит заказ на то или иное издание.', 20, 1616835384, 1616835419),
(80, 1, 1, 22, 'когда копии книг изготавливаются специально для конкретного клиента, уже после того, как он оформит заказ на то или иное издание.', 20, 1616926339, 1616926353),
(81, 1, 22, 1, 'как он оформит заказ на то или иное издание.', 20, 1616926378, 1616926388),
(82, 1, 1, 22, 'Нарастающее число публикаций (так называемый информационный взрыв) поставил перед библиотеками вопрос хранения столь большого массива информации.', 20, 1616926755, 1616926775),
(83, 1, 22, 1, 'когда копии книг изготавливаются специально для конкретного клиента, уже после того, как он оформит заказ на то или иное издание.', 20, 1616926799, 1616926817),
(84, 7, 22, 21, 'запуск генерации не возможен, если все параметры всех элементов одинаковы или оба плеча мультивибратора полностью одинаковы и симметричны относительно друг друга.', 20, 1616930830, 1616930867),
(85, 7, 21, 22, 'Т.е. взаимодействие дихотомий друг с другом создало непрекращающийся колебательный процесс очень похожий на генерацию колебаний мультивибратора', 20, 1616930882, 1616930897),
(86, 8, 28, 22, 'Время без пяти, надо делать запись в судовой журнал, стою, пишу. Время без пяти, надо делать запись в судовой журнал, стою, пишу.', 20, 1616931741, 1616931769),
(87, 8, 22, 28, 'Ну да ладно, вернемся на верхнюю палубу.', 20, 1616931782, 1616931800),
(88, 8, 22, 28, 'Поэтому там все носят шумозащитные наушники и не разговаривают :) Ну да ладно, вернемся на верхнюю палубу. А там стоят рефконтейнеры, в них находятся рефрежираторные установки для охлаждения содержимого и эти установки постоянно жужжат своими вентиляторами.', 20, 1616932632, 1616932650),
(89, 8, 28, 22, 'Ну да ладно, вернемся на верхнюю палубу.', 20, 1616932671, 1616932681),
(90, 8, 22, 28, 'Поэтому там все носят шумозащитные наушники и не разговаривают :) Ну да ладно, вернемся на верхнюю палубу. А там стоят рефконтейнеры, в них находятся рефрежираторные установки для охлаждения содержимого и эти установки постоянно жужжат своими вентиляторами.', 20, 1617310192, 1617310223),
(91, 8, 28, 22, 'Ну да ладно, вернемся на верхнюю палубу. А там стоят рефконтейнеры, в них находятся рефрежираторные установки для охлаждения содержимого и эти установки постоянно жужжат своими вентиляторами.', 20, 1617310256, 1617310275),
(92, 8, 22, 28, 'Поэтому там все носят шумозащитные наушники и не разговаривают :)', 20, 1617310294, 1617310398),
(93, 8, 22, 28, 'Время без пяти, надо делать запись в судовой журнал, стою, пишу. Время без пяти, надо делать запись в судовой журнал, стою, пишу.', 20, 1617310434, 1617310464),
(94, 8, 28, 22, 'Ну да ладно, вернемся на верхнюю палубу. А там стоят рефконтейнеры, в них находятся рефрежираторные установки для охлаждения содержимого и эти установки постоянно жужжат своими вентиляторами.', 20, 1617310483, 1617310496),
(95, 8, 22, 28, 'Время без пяти, надо делать запись в судовой журнал, стою, пишу.', 20, 1617310506, 1617310526),
(96, 8, 22, 28, 'Время без пяти, надо делать запись в судовой журнал, стою, пишу. Время без пяти, надо делать запись в судовой журнал, стою, пишу.', 20, 1617310596, 1617310615),
(97, 8, 28, 22, 'Ну да ладно, вернемся на верхнюю палубу. А там стоят рефконтейнеры, в них находятся рефрежираторные установки для охлаждения содержимого и эти установки постоянно жужжат своими вентиляторами.', 20, 1617310713, 1617310729),
(98, 8, 22, 28, 'Время без пяти, надо делать запись в судовой журнал, стою, пишу.', 20, 1617310722, 1617310745),
(99, 8, 22, 28, 'Ну да ладно, вернемся на верхнюю палубу. А там стоят рефконтейнеры, в них находятся рефрежираторные установки для охлаждения содержимого и эти установки постоянно жужжат своими вентиляторами.', 20, 1617310801, 1617310809),
(100, 8, 28, 22, 'Время без пяти, надо делать запись в судовой журнал, стою, пишу.', 20, 1617310807, 1617310822),
(101, 8, 28, 22, 'к морю на машине, либо подходя пешком, всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет. Подъезжая к морю на машине, либо подходя пешком, всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617310866, 1617310883),
(102, 8, 22, 28, 'всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617310880, 1617310972),
(103, 8, 22, 28, 'к морю на машине, либо подходя пешком, всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617312629, 1617312656),
(104, 8, 28, 22, 'всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617312664, 1617312771),
(105, 8, 22, 28, 'всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617312767, 1617312827),
(106, 8, 28, 22, 'к морю на машине, либо подходя пешком, всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617312862, 1617312869),
(107, 8, 22, 28, 'всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617312866, 1617312886),
(108, 8, 28, 22, 'ff', 20, 1617312881, 1617312903),
(109, 8, 22, 28, 'всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617313173, 1617313186),
(110, 8, 22, 28, 'всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет. всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617313222, 1617313257),
(111, 8, 28, 22, 'обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617314756, 1617314772),
(112, 8, 22, 28, 'к морю на машине, либо подходя пешком, всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617314819, 1617314845),
(113, 8, 28, 22, 'знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617314873, 1617314896),
(114, 8, 22, 28, 'обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617315532, 1617315567),
(115, 8, 28, 22, 'к морю на машине, либо подходя пешком, всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617349795, 1617350078),
(116, 8, 28, 22, 'всегда замечаешь знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617349846, 1617350091),
(117, 8, 28, 22, 'которая иногда произносится вслух: Морем пахнет.', 20, 1617349880, 1617350095),
(118, 8, 28, 22, 'Морем пахнет.', 20, 1617349915, 1617350099),
(119, 8, 28, 22, 'знакомый аромат и в голове появляется обычная мысль, которая иногда произносится вслух: Морем пахнет.', 20, 1617349959, 1617350099),
(120, 8, 28, 22, 'которая иногда произносится вслух: Морем пахнет.', 20, 1617350039, 1617350108),
(121, 7, 22, 21, '', 20, 1617353150, 1617353177),
(122, 7, 21, 22, 'непрекращающийся колебательный процесс очень похожий на генерацию колебаний мультивибратора', 20, 1617353213, 1617353249),
(123, 7, 21, 22, 'Т.е. взаимодействие дихотомий друг с другом создало непрекращающийся колебательный процесс очень похожий на генерацию колебаний мультивибратора, в котором запуск генерации не возможен, если все параметры всех элементов одинаковы или оба плеча мультивибратора полностью одинаковы и симметричны относительно друг друга.', 20, 1617353363, 1617353397),
(124, 7, 22, 21, 'очень похожий на генерацию колебаний мультивибратора, в котором запуск генерации не возможен, если все параметры всех элементов одинаковы или оба плеча мультивибратора полностью одинаковы и симметричны относительно друг друга.', 20, 1617353412, 1617353422),
(125, 1, 22, 1, 'так называемый информационный взрыв', 20, 1617360976, 1617361017),
(126, 1, 1, 22, 'Нарастающее число публикаций (так называемый информационный взрыв) поставил перед библиотеками вопрос хранения столь большого массива информации.', 20, 1617361083, 1617361119),
(128, 1, 22, 1, 'поставил перед библиотеками вопрос хранения столь большого массива информации.', 20, 1617385044, 1617385077),
(129, 8, 22, 28, 'Привет', 20, 1619120970, 1619123719),
(130, 8, 22, 28, 'Ещё привет', 20, 1619122376, 1619123731),
(131, 8, 22, 28, 'Ещё привет', 20, 1619122381, 1619123731),
(132, 8, 22, 28, 'Ещё привет', 20, 1619122382, 1619123731),
(133, 8, 22, 28, 'И ещё привет!', 20, 1619123562, 1619123731),
(134, 8, 28, 22, 'Ну привет!', 20, 1619123766, 1619123790),
(135, 8, 22, 28, 'Ну привет', 20, 1619123798, 1619123808),
(136, 9, 22, 31, 'Привет 1! Это тех.поддержка)', 20, 1628452884, 1628452921),
(137, 9, 22, 31, 'Привет 1.1! Это тех.поддержка)', 20, 1628452943, 1628452953),
(138, 9, 31, 22, 'Привет 1! Это эксперт)', 20, 1628453011, 1628453051),
(139, 9, 22, 31, 'Привет 1.1! Это эксперт)', 20, 1628454347, 1628454369),
(140, 9, 22, 31, 'Привет 2! Это тех.поддержка)', 20, 1628454983, 1628454999),
(141, 9, 22, 31, 'Привет 3! Это тех.поддержка)', 20, 1628455121, 1628455250),
(142, 9, 22, 31, 'Привет 3! Это тех.поддержка)', 20, 1628455132, 1628455253),
(143, 9, 22, 31, 'Привет 4! Это тех.поддержка)', 20, 1628455175, 1628455253),
(144, 9, 22, 31, 'Привет 5! Это тех.поддержка)', 20, 1628455327, 1628455352),
(145, 9, 31, 22, 'Привет 2! Это эксперт)', 20, 1628455373, 1628455400),
(146, 9, 31, 22, 'Привет 3! Это эксперт)', 20, 1628455387, 1628455400),
(147, 9, 22, 31, 'Привет 6! Это тех.поддержка)', 20, 1628455602, 1628455666),
(148, 9, 22, 31, 'Привет 7! Это тех.поддержка)', 20, 1628455617, 1628455669),
(149, 9, 22, 31, 'Привет 8! Это тех.поддержка)', 20, 1628455624, 1628455669),
(150, 9, 31, 22, 'Предисловие: Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi. Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1628455696, 1628455735),
(151, 9, 31, 22, 'Предисловие: Наконец появилась возможность добраться до интернета, сейчас мы находимся в Панамском канале и здесь есть wifi.', 20, 1628455708, 1628455736),
(152, 1, 1, 22, 'Нарастающее число публикаций (так называемый информационный взрыв) поставил перед библиотеками вопрос хранения столь большого массива информации.', 20, 1628455778, 1628455905),
(153, 8, 28, 22, 'Переночевав в гостинице в Гуаякиле, мы сели к агенту в машину и поехали на судно в Пуэрто Боливар. Доехали вопреки ожиданиям быстро, примерно за 3-4 часа. Погода была пасмурная и даже не смотря на то, что мы находимся недалеко от экватора, было прохладно. Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628455853, 1628455914),
(154, 9, 22, 31, 'Я на судне уже больше месяца и пока я здесь, я писал все интересное что здесь происходит и вот наконец есть возможность этим поделиться. Фотографий пока не будет, их я выложу или позже, или уже когда вернусь домой. Итак, понеслась:', 20, 1628455969, 1628456025),
(155, 9, 22, 31, '', 20, 1628455989, 1628456025),
(156, 9, 22, 31, 'Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628456162, 1628456204),
(157, 9, 22, 31, 'Переночевав в гостинице в Гуаякиле, мы сели к агенту в машину и поехали на судно в Пуэрто Боливар. Доехали вопреки ожиданиям быстро, примерно за 3-4 часа. Погода была пасмурная и даже не смотря на то, что мы находимся недалеко от экватора, было прохладно. Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации,', 20, 1628456169, 1628456204),
(158, 9, 22, 31, 'Переночевав в гостинице в Гуаякиле, мы сели к агенту в машину и поехали на судно в Пуэрто Боливар. Доехали вопреки ожиданиям быстро, примерно за 3-4 часа. Погода была пасмурная и даже не смотря на то, что мы находимся недалеко от экватора, было прохладно. Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628456172, 1628456204),
(159, 1, 22, 1, 'поставил перед библиотеками вопрос хранения столь большого массива информации.', 20, 1628622336, 1628622462),
(160, 9, 22, 31, 'Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622592, 1628622661);

-- --------------------------------------------------------

--
-- Структура таблицы `message_expert`
--

CREATE TABLE `message_expert` (
  `id` int(11) UNSIGNED NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `adressee_id` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `message_expert`
--

INSERT INTO `message_expert` (`id`, `conversation_id`, `sender_id`, `adressee_id`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 31, 28, 'Привет! Я эксперт)', 20, 1628325127, 1628325155),
(2, 1, 28, 31, 'Привет! Я главный админ)', 20, 1628325197, 1628326829),
(3, 1, 28, 31, 'Привет 2 ! Я главный админ)', 20, 1628329475, 1628329490),
(4, 1, 31, 28, 'Привет 2! Я эксперт)', 20, 1628329548, 1628329578),
(5, 1, 28, 31, 'Привет 3 ! Я главный админ)', 20, 1628329616, 1628329640),
(6, 1, 31, 28, 'Привет 3! Я эксперт)', 20, 1628330752, 1628330766),
(7, 1, 28, 31, 'Привет 4 ! Я главный админ)', 20, 1628330916, 1628330943),
(8, 1, 31, 28, 'Привет 4.1! Я эксперт)', 20, 1628330963, 1628331005),
(9, 1, 31, 28, 'Привет 4.2! Я эксперт)', 20, 1628330971, 1628331009),
(10, 1, 31, 28, 'Привет 4.3! Я эксперт)', 20, 1628330978, 1628331009),
(11, 1, 31, 28, 'Привет 4.4! Я эксперт)', 20, 1628330987, 1628331012),
(12, 1, 28, 31, 'Привет 5.1 ! Я главный админ)', 20, 1628331048, 1628331089),
(13, 1, 28, 31, 'Привет 5.2 ! Я главный админ)', 20, 1628331055, 1628331093),
(14, 1, 28, 31, 'Привет 5.3 ! Я главный админ)', 20, 1628331061, 1628331095),
(15, 1, 28, 31, 'Привет 5.4 ! Я главный админ)', 20, 1628331066, 1628331095),
(16, 1, 31, 28, 'Привет 5! Я эксперт)', 20, 1628331690, 1628331780),
(17, 1, 31, 28, 'Привет 5.1! Я эксперт)', 20, 1628331732, 1628331780),
(18, 1, 28, 31, 'Привет 6.1 ! Я главный админ)', 20, 1628331881, 1628331915),
(19, 1, 28, 31, 'Привет 6.2 ! Я главный админ)', 20, 1628331887, 1628331915),
(20, 1, 31, 28, 'Привет 7.1! Я эксперт)', 20, 1628331982, 1628332022),
(21, 1, 31, 28, 'Привет 7.2! Я эксперт)', 20, 1628331989, 1628332025),
(22, 1, 31, 28, 'Привет 7.3! Я эксперт)', 20, 1628331996, 1628332025),
(23, 1, 31, 28, '', 20, 1628358885, 1628358924),
(24, 1, 28, 31, '', 20, 1628358948, 1628358987),
(25, 2, 31, 21, 'Привет 1! Это эксперт)', 20, 1628418529, 1628418552),
(26, 2, 31, 21, 'Привет 1.1! Это эксперт)', 20, 1628420919, 1628420925),
(27, 2, 31, 21, 'Привет 1.2! Это эксперт)', 20, 1628421504, 1628421534),
(28, 2, 21, 31, 'Привет 1! Это админ)', 20, 1628422343, 1628422362),
(29, 2, 21, 31, 'Привет 1.1! Это админ)', 20, 1628422477, 1628422694),
(30, 2, 21, 31, 'Привет 1.2! Это админ)', 20, 1628422682, 1628422695),
(31, 2, 21, 31, '', 20, 1628422726, 1628422764),
(32, 2, 31, 21, '', 20, 1628422764, 1628422772),
(33, 2, 21, 31, 'прп', 20, 1628422771, 1628422788),
(34, 2, 21, 31, 'Переночевав в гостинице в Гуаякиле, мы сели к агенту в машину и поехали на судно в Пуэрто Боливар. Доехали вопреки ожиданиям быстро, примерно за 3-4 часа. Погода была пасмурная и даже не смотря на то, что мы находимся недалеко от экватора, было прохладно. Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628427407, 1628427465),
(35, 2, 21, 31, 'Переночевав в гостинице в Гуаякиле, мы сели к агенту в машину и поехали на судно в Пуэрто Боливар. Доехали вопреки ожиданиям быстро, примерно за 3-4 часа. Погода была пасмурная и даже не смотря на то, что мы находимся недалеко от экватора, было прохладно. Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.. Погода была пасмурная и даже не смотря на то, что мы находимся недалеко от экватора, было прохладно. Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628427421, 1628427469),
(36, 2, 21, 31, 'Переночевав в гостинице в Гуаякиле, мы сели к агенту в машину и поехали на судно в Пуэрто Боливар. Доехали вопреки ожиданиям быстро, примерно за 3-4 часа.', 20, 1628427433, 1628427492),
(37, 2, 21, 31, 'Переночевав в гостинице в Гуаякиле, мы сели к агенту в машину и поехали на судно в Пуэрто Боливар. Доехали вопреки ожиданиям быстро, примерно за 3-4 часа.Переночевав в гостинице в Гуаякиле, мы сели к агенту в машину и поехали на судно в Пуэрто Боливар. Доехали вопреки ожиданиям быстро, примерно за 3-4 часа.', 20, 1628427447, 1628427492),
(38, 2, 31, 21, 'Доехали вопреки ожиданиям быстро, примерно за 3-4 часа.', 20, 1628427544, 1628427592),
(39, 2, 31, 21, 'Доехали вопреки ожиданиям быстро, примерно за 3-4 часа.Доехали вопреки ожиданиям быстро, примерно за 3-4 часа.Доехали вопреки ожиданиям быстро, примерно за 3-4 часа.', 20, 1628427548, 1628427603),
(40, 2, 31, 21, 'Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.. Погода была пасмурная и даже не смотря на то, что мы находимся недалеко от экватора, было прохладно. Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628427559, 1628427607),
(41, 2, 31, 21, 'Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.. Погода была пасмурная и даже не смотря на то, что мы находимся недалеко от экватора, было прохладно. Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628427562, 1628427608),
(42, 2, 31, 21, 'Это ж несчастные бананы должны расти быстрее чем грибы.. Погода была пасмурная и даже не смотря на то, что мы находимся недалеко от экватора, было прохладно. Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628427572, 1628427608),
(43, 2, 31, 21, 'Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628427582, 1628427608),
(44, 2, 31, 21, 'Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628427626, 1628427640),
(45, 2, 31, 21, 'Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628427630, 1628427640),
(46, 2, 31, 21, 'Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628427634, 1628427645),
(47, 2, 31, 21, 'Привет!', 20, 1628428943, 1628428972),
(48, 2, 21, 31, 'Привет!', 20, 1628429016, 1628429034),
(49, 3, 31, 1, 'Привет 1! Это эксперт)', 20, 1628621820, 1628621853),
(50, 3, 1, 31, 'Привет 1! Это проектант)', 20, 1628621887, 1628621935),
(51, 3, 1, 31, '', 20, 1628621961, 1628621966),
(52, 3, 31, 1, '', 20, 1628622216, 1628622242),
(53, 2, 21, 31, 'Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622527, 1628622654),
(54, 1, 28, 31, 'Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622559, 1628622668),
(55, 3, 1, 31, 'Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622621, 1628622645),
(56, 3, 31, 1, 'Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622683, 1628622709),
(57, 3, 31, 1, 'Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации,', 20, 1628622690, 1628622711),
(58, 3, 31, 1, ', круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622699, 1628622711),
(59, 3, 1, 31, 'нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622822, 1628622855),
(60, 3, 1, 31, 'нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622826, 1628622855),
(61, 3, 1, 31, 'Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622832, 1628622856),
(62, 3, 1, 31, 'нескольких портах Эквадора', 20, 1628622841, 1628622856),
(63, 3, 1, 31, 'нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622844, 1628622857),
(64, 3, 31, 1, 'Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622870, 1628622918),
(65, 3, 31, 1, 'Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622884, 1628622920),
(66, 3, 31, 1, 'Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации,', 20, 1628622890, 1628622921),
(67, 3, 31, 1, 'Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622893, 1628622921),
(68, 3, 31, 1, 'Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622902, 1628622922),
(69, 3, 1, 31, 'Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622926, 1628622930),
(70, 3, 31, 1, 'Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622930, 1628622948),
(71, 3, 31, 1, 'Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622973, 1628622990),
(72, 3, 31, 1, 'Это ж несчастные бананы должны расти быстрее чем грибы.Почти все время, пока мы ехали, по обе стороны дороги были банановые плантации, но все равно в голове не укладывается: эти бананы грузят на суда в нескольких портах Эквадора десятками тысяч тонн каждый день, круглый год. Это ж несчастные бананы должны расти быстрее чем грибы.', 20, 1628622976, 1628622990);

-- --------------------------------------------------------

--
-- Структура таблицы `message_files`
--

CREATE TABLE `message_files` (
  `id` int(11) UNSIGNED NOT NULL,
  `message_id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `server_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `message_files`
--

INSERT INTO `message_files` (`id`, `message_id`, `category`, `file_name`, `server_file`) VALUES
(4, 74, 1, 'Новый текстовый документ.txt', 'oJpRFUa0LnpyMtP.txt'),
(5, 74, 1, 'По поводу сообщений.txt', 'DGD_F_jBdLklLiQ.txt'),
(6, 74, 1, 'Прелоадоры.txt', 'u4z6PwfaM_fBjEY.txt'),
(7, 76, 1, 'Новый текстовый документ.txt', 'muk0y56dmOIXQ4K.txt'),
(8, 76, 1, 'По поводу сообщений.txt', 'QFcBUbamituh_LN.txt'),
(9, 76, 1, 'Прелоадоры.txt', 'ZY_lJgxmhdIysff.txt'),
(10, 76, 1, 'Регулярные выражения для валидации распространенных видов данных.txt', 'PHAhYIZuEDbtFev.txt'),
(11, 77, 1, 'Новый текстовый документ.txt', 'x-ugtzQmZn8ebLt.txt'),
(12, 77, 1, 'По поводу сообщений.txt', 'SxIfSpxF1c19gWI.txt'),
(13, 77, 1, 'Прелоадоры.txt', 'tOoEpOhegcJdqfo.txt'),
(14, 77, 1, 'Регулярные выражения для валидации распространенных видов данных.txt', 'LX8tz-hMhilNmlf.txt'),
(15, 78, 1, 'Workerman - Websocket (вебсокеты) связка PHP + JavaScript.txt', 'fLUYWAc5M6P4wiH.txt'),
(16, 78, 1, 'Анимированые картинки.txt', 'SJG4h0CPAFGvCZa.txt'),
(17, 79, 1, 'По поводу сообщений.txt', 'zQv1DiErrFz63Tj.txt'),
(18, 79, 1, 'Прелоадоры.txt', 'wXNIC1iETadrXh-.txt'),
(19, 79, 1, 'Регулярные выражения для валидации распространенных видов данных.txt', 'hiMr7GR2TXWOvwB.txt'),
(20, 80, 1, 'Workerman - Websocket (вебсокеты) связка PHP + JavaScript.txt', 'TvodVC0ROFUeXvB.txt'),
(21, 80, 1, 'Анимированые картинки.txt', '4IPsLEJrYEHDnhi.txt'),
(22, 81, 1, 'Прелоадоры.txt', 'ee5w3G2evDo9Bnb.txt'),
(23, 81, 1, 'Регулярные выражения для валидации распространенных видов данных.txt', 'eZOFh0QmZunuovk.txt'),
(24, 82, 1, 'Новый текстовый документ.txt', '8tKzHI4M7C6nwNY.txt'),
(25, 83, 1, 'По поводу сообщений.txt', 'XvD20oFnJf8e_rA.txt'),
(26, 84, 1, 'Jquery-плагины.txt', 'gxcC3zOyplp5PpP.txt'),
(27, 84, 1, 'Workerman - Websocket (вебсокеты) связка PHP + JavaScript.txt', '6MngjSrrmNfKdeI.txt'),
(28, 84, 1, 'Анимированые картинки.txt', 'hTiWsN06d8_TRVR.txt'),
(29, 85, 1, 'Прелоадоры.txt', 'QUqciwqyq4zvrnt.txt'),
(30, 85, 1, 'Регулярные выражения для валидации распространенных видов данных.txt', 'IQ_8enNhx1laL-v.txt'),
(31, 97, 1, 'Jquery-плагины.txt', 'rSd8OpZjIQa7oWh.txt'),
(32, 97, 1, 'Workerman - Websocket (вебсокеты) связка PHP + JavaScript.txt', 'xv7debsc4OSQ5fl.txt'),
(33, 97, 1, 'workerman SSL-протокол.txt', 'vSWUOtjnhwSkaJ9.txt'),
(34, 124, 1, 'Workerman - Websocket (вебсокеты) связка PHP + JavaScript.txt', 'dtl1GQXd568krCO.txt'),
(35, 124, 1, 'workerman SSL-протокол.txt', 'Zbchikk18uFhA8K.txt'),
(36, 133, 1, 'По поводу сообщений.txt', 'Gz4AFLnLrjD5Txd.txt'),
(37, 133, 1, 'Прелоадоры.txt', 'vmZ4vymrmqlsQ7x.txt'),
(38, 136, 1, 'Jquery-плагины.txt', 'Q5OtHjYAk7cqCci.txt'),
(39, 143, 1, 'workerman SSL-протокол.txt', 'gDKANAoE7zRE3xg.txt'),
(40, 145, 1, 'workerman SSL-протокол.txt', 'xh5lOdYo0fKq7Ji.txt'),
(41, 282, 1, 'Jquery-плагины.txt', 'kJfjaZSDMvGL14F.txt'),
(42, 282, 1, 'Workerman - Websocket (вебсокеты) связка PHP + JavaScript.txt', 'f7ZCh8KgkiytwiI.txt'),
(43, 282, 1, 'workerman SSL-протокол.txt', 'nMUqqjJ8kX41Sw0.txt'),
(44, 292, 1, 'Jquery-плагины.txt', 'cLjG-ZropXwObOl.txt'),
(45, 292, 1, 'Workerman - Websocket (вебсокеты) связка PHP + JavaScript.txt', 'JfHLL8Qa5ZVRw0f.txt'),
(46, 292, 1, 'workerman SSL-протокол.txt', 'j91DAFO00RiCMci.txt'),
(47, 43, 2, 'Workerman - Websocket (вебсокеты) связка PHP + JavaScript.txt', 'xlDY4gG9zmPeZJk.txt'),
(48, 43, 2, 'workerman SSL-протокол.txt', '3nzrCnsqrRHtzE7.txt'),
(49, 44, 2, 'По поводу сообщений.txt', 'YwMSFXAcy_N2Dvr.txt'),
(50, 44, 2, 'Прелоадоры.txt', 'k8lO9RFJtFRPWO9.txt'),
(51, 16, 3, 'Jquery-плагины.txt', 'a2he1lHa1LH36DD.txt'),
(52, 16, 3, 'Прелоадоры.txt', 'vCQrOwEHXccMZSL.txt'),
(53, 20, 3, 'workerman SSL-протокол.txt', 'kPATCEp7FLQRsh6.txt'),
(54, 47, 3, 'Workerman - Websocket (вебсокеты) связка PHP + JavaScript.txt', 'UYLPFatrgiOUVXx.txt'),
(55, 47, 3, 'workerman SSL-протокол.txt', 'L6AQ3SAiMb_xCeF.txt'),
(56, 77, 3, 'Workerman - Websocket (вебсокеты) связка PHP + JavaScript.txt', 'f-H6ojzUf1nG7Bm.txt'),
(57, 77, 3, 'workerman SSL-протокол.txt', 'dr24XHylU_Qbc4q.txt'),
(58, 121, 3, 'интернет маркетинг.doc', 'UGsztuX4962P0Xn.doc'),
(59, 126, 3, 'интернет маркетинг.doc', 'UJYi0Qw-WaWTUF5.doc'),
(60, 23, 4, '270521_Комментарии к платформе.docx', 'REo3rEyNexlO8Fq.docx'),
(61, 24, 4, '100721Раздел экспертиза.xlsx', 'RRv69HSmAE8g6gl.xlsx'),
(62, 31, 4, '100721Раздел экспертиза.xlsx', 'u6sEooScEJ-cPjM.xlsx'),
(63, 32, 4, '170721Раздел экспертиза.xlsx+Назначение экспертизы администратором.xlsx', 'ovBLRI_o7bt5yON.xlsx'),
(64, 155, 3, '170721Раздел экспертиза.xlsx+Назначение экспертизы администратором.xlsx', 'XiZMbIubCznjjb1.xlsx'),
(65, 51, 4, '270521_Комментарии к платформе.docx', '8UAuDICOVDgB20i.docx'),
(66, 52, 4, '100721Раздел экспертиза.xlsx', 'VS-nlRTe6RhhsYH.xlsx');

-- --------------------------------------------------------

--
-- Структура таблицы `message_main_admin`
--

CREATE TABLE `message_main_admin` (
  `id` int(11) UNSIGNED NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `adressee_id` int(11) NOT NULL,
  `description` text,
  `status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `message_main_admin`
--

INSERT INTO `message_main_admin` (`id`, `conversation_id`, `sender_id`, `adressee_id`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 21, 28, 'Добрый день! Как у Вас дела?', 20, 1588879999, 1616530928),
(2, 4, 28, 21, 'В V веке Исидор Севильский разъяснял различия между книгой, свитком и кодексом в соответствии с существовавшими тогда представлениями следующим образом: кодекс составлен из множества книг, книга — из одного свитка.', 20, 1616578794, 1616578842),
(3, 4, 28, 21, 'кодекс составлен из множества книг, книга — из одного свитка.', 20, 1616579864, 1616580089),
(4, 4, 28, 21, 'В V веке Исидор Севильский разъяснял различия между книгой, свитком и кодексом в соответствии с существовавшими тогда представлениями следующим образом: кодекс составлен из множества книг, книга — из одного свитка.', 20, 1616580110, 1616580621),
(5, 4, 28, 21, 'Преимущества такого формата состояли в его экономичности (можно было использовать обе стороны носителя письменной информации), портативности и удобстве поиска сведений. Кроме того, пергамен — материал многократного применения: с него легко удаляется краска.', 20, 1616580650, 1616580714),
(6, 4, 28, 21, 'О торговцах книгами сообщают греческие авторы V века до н. э. (например, Ксенофонт). Книгопродавцев (библиополов) упоминают в своих комедиях Аристомен и Никофон. Торговля книгами в Риме уже была обычным явлением.', 20, 1616580781, 1616580983),
(7, 4, 28, 21, 'Для того, чтобы строки выходили ровными, пергамен разлиновывался пластинкой из мягкого свинца, позднее для этой цели использовался грифель.', 20, 1616581000, 1616581016),
(8, 4, 21, 28, 'Кроме того, пергамен — материал многократного применения: с него легко удаляется краска.', 20, 1616581058, 1616581068),
(16, 4, 28, 21, 'Скриптории были также при монастырях, крупнейший из них работал в Студийском монастыре. Для того, чтобы строки выходили ровными, пергамен разлиновывался пластинкой из мягкого свинца, позднее для этой цели использовался грифель.', 20, 1616591178, 1616591284),
(17, 4, 28, 21, 'Со временем листы некоторых рукописей стали окрашивать в разные цвета, текст выполнялся цветными чернилами. Начало нового абзаца писалось красными чернилами (их делали из киновари или сурика).', 20, 1616592446, 1616593200),
(18, 4, 28, 21, 'Падение Римской империи в V веке уменьшило её культурное влияние на остальной мир. В Западной Римской империи традиции письма латынью хранили в монастырях, так как сначала Кассиодор в монастыре Вивария, а позже Бенедикт Нурсийский в VI веке подчеркнули важность переписывания текстов.', 20, 1616592615, 1616593203),
(19, 4, 28, 21, 'Это очень повлияло на значимость книг в период Средневековья, хотя тогда книги, в основном, читало духовенство.', 20, 1616592809, 1616593206),
(20, 4, 28, 21, 'В начале XIV века в Западной Европе появилась ксилография (она была разработана задолго до этого на Востоке (один из самых ранних образцов датируется VIII веком), издавна ксилографические книги печатались в Корее)', 20, 1616592997, 1616593210),
(21, 4, 28, 21, 'Создание книги было кропотливым процессом, так как для каждой страницы нужно было делать свою резьбу (на что уходило около месяца).', 20, 1616593174, 1616593211),
(22, 4, 21, 28, 'Книги стали сравнительно доступными, хотя для большинства всё ещё весьма дорогими.', 20, 1616593440, 1616593463),
(23, 4, 21, 28, 'Несмотря на рост масштабов книгопечати в XV веке, книги ещё издавались в ограниченных тиражах и были весьма дороги. Необходимость бережного отношения к ним была очевидна.', 20, 1616593488, 1616593519),
(25, 4, 21, 28, 'Методы, используемые для печатания и переплетания книг, практически не подвергались никаким изменениям в период с XV по начало XX века[источник не указан 84 дня]. Хотя в процесс постепенно вносились те или иные средства автоматизации, в 1900 году печатный механизм всё ещё имел много общего со станком Гутенберга[источник не указан 84 дня].', 20, 1616595045, 1616595491),
(26, 4, 21, 28, 'Хотя в процесс постепенно вносились те или иные средства автоматизации, в 1900 году печатный механизм всё ещё имел много общего со станком Гутенберга[источник не указан 84 дня].', 20, 1616595080, 1616595494),
(27, 4, 21, 28, 'Хотя в процесс постепенно вносились те или иные средства', 20, 1616595118, 1616595497),
(28, 4, 21, 28, 'в 1900 году печатный механизм', 20, 1616595222, 1616595499),
(29, 4, 21, 28, 'те или иные средства автоматизации, в 1900 году печатный механизм всё ещё имел много общего со станком Гутенберга[источник не указан 84 дня].', 20, 1616595300, 1616595501),
(30, 4, 21, 28, 'в 1900 году печатный механизм всё ещё имел много общего со станком Гутенберга[источник не указан 84 дня].', 20, 1616595327, 1616595503),
(31, 4, 21, 28, 'Методы, используемые для печатания и переплетания книг, практически не подвергались никаким изменениям в период с XV по начало XX века[источник не указан 84 дня]. Хотя в процесс постепенно вносились те или иные средства автоматизации, в 1900 году печатный механизм всё ещё имел много общего со станком Гутенберга[источник не указан 84 дня].', 20, 1616595372, 1616595506),
(32, 4, 21, 28, 'После сбора листов с оттисками осуществляются переплётные работы. В середине прошлого века соответствующая часть работы выполнялась обособленными предприятиями, которые не занимались книгопечатанием и выполняли только лишь работу по переплетанию книг;', 20, 1616595407, 1616595508),
(33, 4, 21, 28, 'Для современного книгопечатания характерна стандартизация: изготавливаемые книги имеют, как правило, определённый размер и формат.', 20, 1616595449, 1616595508),
(34, 4, 28, 21, 'В англоговорящих странах, за исключением США, доминируют британские стандарты; собственные нормы и правила действуют в странах Европы.', 20, 1616595609, 1616595800),
(35, 4, 28, 21, 'Бумага также изготавливается специально для полиграфических нужд; для облегчения процесса чтения она традиционно делается не чисто белой, а слегка затемнённой, а также имеет определённую плотность, чтобы напечатанный на одной стороне листа текст не просвечивал с другой стороны.', 20, 1616595644, 1616595805),
(36, 4, 28, 21, 'В зависимости от конкретного типа книги применяется бумага определённого качества; наиболее распространённой разновидностью является мелованная бумага того или иного сорта.', 20, 1616595679, 1616595808),
(37, 4, 28, 21, 'Помимо прочего, на современном этапе книги изготавливаются также и методом цифровой печати.', 20, 1616595706, 1616595846),
(38, 4, 28, 21, 'При этом страницы формируются примерно тем же способом, что и документы, печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.', 20, 1616595745, 1616595853),
(39, 4, 28, 21, 'Такой подход позволяет печатать малые тиражи изданий (до нескольких сотен экземпляров), в том числе благодаря отсутствию подготовительных этапов, необходимых в работе с офсетным станком.', 20, 1616595775, 1616595854),
(40, 4, 21, 28, 'Для современного книгопечатания характерна стандартизация: изготавливаемые книги имеют, как правило, определённый размер и формат.', 20, 1616595921, 1616595934),
(41, 4, 21, 28, 'При этом страницы формируются примерно тем же способом, что и документы, печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.', 20, 1616600971, 1616601011),
(42, 4, 21, 28, 'Для современного книгопечатания характерна стандартизация: изготавливаемые книги имеют, как правило, определённый размер и формат.', 20, 1616601000, 1616601011),
(43, 4, 28, 21, 'При этом страницы формируются примерно тем же способом, что и документы, печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.', 20, 1616604714, 1616604735),
(44, 4, 21, 28, '', 20, 1616604818, 1616604835),
(45, 4, 28, 21, 'с использованием тонера, а не чернил', 20, 1616604850, 1616604857),
(46, 4, 21, 28, 'Поэтому там все носят шумозащитные наушники и не разговаривают :) Ну да ладно, вернемся на верхнюю палубу.', 20, 1616766629, 1616766671),
(47, 4, 21, 28, 'При этом страницы формируются примерно тем же способом, что и документы, печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.', 20, 1616933983, 1616934000),
(48, 4, 28, 21, 'печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.', 20, 1616934012, 1616934028),
(49, 4, 28, 21, 'При этом страницы формируются примерно тем же способом, что и документы, печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.', 20, 1617296516, 1617296563),
(50, 4, 21, 28, 'печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.', 20, 1617296602, 1617296626),
(51, 4, 21, 28, 'При этом страницы формируются примерно тем же способом, что и документы, печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.', 20, 1617296664, 1617296677),
(52, 4, 28, 21, 'При этом страницы формируются примерно тем же способом, что и документы, печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.При этом страницы формируются примерно тем же способом, что и документы, печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.', 20, 1617296675, 1617296704),
(53, 4, 21, 28, 'При этом страницы формируются примерно тем же способом, что и документы, печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.', 20, 1617297359, 1617297482),
(54, 4, 21, 28, 'печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.', 20, 1617297520, 1617297564),
(55, 4, 28, 21, 'При этом страницы формируются примерно тем же способом, что и документы, печатаемые офисной техникой — лазерными принтерами или копировальными аппаратами, с использованием тонера, а не чернил.', 20, 1617297612, 1617297653);

-- --------------------------------------------------------

--
-- Структура таблицы `mvps`
--

CREATE TABLE `mvps` (
  `id` int(11) UNSIGNED NOT NULL,
  `basic_confirm_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `segment_id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `gcp_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `time_confirm` int(11) DEFAULT NULL,
  `exist_confirm` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `mvps`
--

INSERT INTO `mvps` (`id`, `basic_confirm_id`, `project_id`, `segment_id`, `problem_id`, `gcp_id`, `title`, `description`, `time_confirm`, `exist_confirm`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 16, 1, 1, 'MVP 1', 'Описание минимально жизнеспособного продукта 1', 1620598592, 1, 1620598446, 1620598592),
(2, 2, 1, 26, 5, 2, 'MVP 1', 'Описание минимально жизнеспособного продукта 1', 1620661557, 1, 1620661479, 1620661557),
(8, 2, 1, 26, 5, 2, 'MVP 2', 'Описание минимально жизнеспособного продукта 2', NULL, NULL, 1621705795, 1621705795),
(9, 10, 1, 32, 15, 11, 'MVP 1', 'Описание минимально жизнеспособного продукта 1', 1623787609, 1, 1623591795, 1623787609),
(11, 10, 1, 32, 15, 11, 'MVP 2', 'Описание минимально жизнеспособного продукта 2', NULL, NULL, 1623786080, 1623786080),
(12, 11, 1, 32, 17, 12, 'MVP 1', 'Описание минимально жизнеспособного продукта 1', 1624746150, 1, 1624567071, 1624746150),
(13, 11, 1, 32, 17, 12, 'MVP 2', 'Описание минимально жизнеспособного продукта 2', NULL, NULL, 1624647234, 1624647234),
(14, 1, 1, 16, 1, 1, 'MVP 2', 'Описание минимально жизнеспособного продукта 2', NULL, NULL, 1633031528, 1633031528);

-- --------------------------------------------------------

--
-- Структура таблицы `pre_files`
--

CREATE TABLE `pre_files` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `server_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `pre_files`
--

INSERT INTO `pre_files` (`id`, `project_id`, `file_name`, `server_file`) VALUES
(1, 1, 'Бизнес требования для сервиса Акселератор.docx', 'wuhxOJ2z26Lz0y7.docx'),
(2, 1, 'Листинг для Spaccel.doc', '6CfFKmODBPnT-aX.doc'),
(3, 1, 'протокол проекта.xlsx', 'zn58kYcuXxzTN3u.xlsx');

-- --------------------------------------------------------

--
-- Структура таблицы `problems`
--

CREATE TABLE `problems` (
  `id` int(11) UNSIGNED NOT NULL,
  `basic_confirm_id` int(11) NOT NULL,
  `segment_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `indicator_positive_passage` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `time_confirm` int(11) DEFAULT NULL,
  `exist_confirm` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `problems`
--

INSERT INTO `problems` (`id`, `basic_confirm_id`, `segment_id`, `project_id`, `title`, `description`, `indicator_positive_passage`, `created_at`, `updated_at`, `time_confirm`, `exist_confirm`) VALUES
(1, 1, 16, 1, 'ГПС 1', 'Описание гипотезы проблемы 1', 0, 1620592901, 1620596943, 1620596943, 1),
(2, 1, 16, 1, 'ГПС 2', 'Описание гипотезы проблемы 2', 0, 1620652673, 1620652673, NULL, NULL),
(3, 2, 17, 1, 'ГПС 1', 'Описание гипотезы проблемы 1', 0, 1620655158, 1620655158, NULL, NULL),
(4, 2, 17, 1, 'ГПС 2', 'Описание гипотезы проблемы 2', 0, 1620656140, 1620656140, NULL, NULL),
(5, 11, 26, 1, 'ГПС 1', 'Описание гипотезы проблемы 1', 0, 1620656570, 1620656964, 1620656964, 1),
(12, 11, 26, 1, 'ГПС 2', 'Описание гипотезы проблемы 2', 0, 1621705166, 1621705166, NULL, NULL),
(13, 17, 32, 1, 'ГПС 1', 'Описание гипотезы проблемы 1', 0, 1623268991, 1623274609, 1623274609, 0),
(15, 17, 32, 1, 'ГПС 2', 'Описание гипотезы проблемы 2', 0, 1623274632, 1623274676, 1623274676, 1),
(17, 17, 32, 1, 'ГПС 3', 'Описание гипотезы проблемы 3', 0, 1624388310, 1624473511, 1624473511, 1),
(18, 17, 32, 1, 'ГПС 4', 'Описание гипотезы проблемы 4', 0, 1624647703, 1624647703, NULL, NULL),
(25, 17, 32, 1, 'ГПС 5', 'Описание гипотезы проблемы 5', 10, 1625430570, 1625430570, NULL, NULL),
(26, 17, 32, 1, 'ГПС 6', 'Описание гипотезы проблемы 6', 25, 1625431156, 1625431368, NULL, NULL),
(28, 18, 33, 1, 'ГПС 1', 'Описание гипотезы проблемы 1', 70, 1625516729, 1625518503, NULL, NULL),
(29, 18, 33, 1, 'ГПС 2', 'Описание гипотезы проблемы 2', 25, 1625520335, 1625520335, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `projects`
--

CREATE TABLE `projects` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `project_fullname` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `purpose_project` text NOT NULL,
  `rid` varchar(255) DEFAULT NULL,
  `patent_number` varchar(255) DEFAULT NULL,
  `patent_date` int(11) DEFAULT NULL,
  `patent_name` varchar(255) DEFAULT NULL,
  `core_rid` text,
  `technology` varchar(255) DEFAULT NULL,
  `layout_technology` text,
  `register_name` varchar(255) DEFAULT NULL,
  `register_date` int(11) DEFAULT NULL,
  `site` varchar(255) DEFAULT NULL,
  `invest_name` varchar(255) DEFAULT NULL,
  `invest_date` int(11) DEFAULT NULL,
  `invest_amount` int(11) DEFAULT NULL,
  `date_of_announcement` int(11) DEFAULT NULL,
  `announcement_event` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `created_at`, `updated_at`, `project_fullname`, `project_name`, `description`, `purpose_project`, `rid`, `patent_number`, `patent_date`, `patent_name`, `core_rid`, `technology`, `layout_technology`, `register_name`, `register_date`, `site`, `invest_name`, `invest_date`, `invest_amount`, `date_of_announcement`, `announcement_event`) VALUES
(1, 1, 1620473372, 1633513785, 'Полное наименование проекта', 'Проект 1', 'Описание проекта', 'Цель проекта', 'Результат интеллектуальной деятельности', 'Номер патента', 1622062800, 'Наименование патента', 'Суть результата интеллектуальной деятельности', 'На какой технологии основан проект', 'Макет базовой технологии', 'Зарегистрированное юр. лицо', 1619643600, 'Адрес сайта', 'Инвестор', 1622062800, 50000, 1622149200, 'Мероприятие, на котором проект анонсирован впервые'),
(2, 9, 1629311577, 1629311577, 'Проект 1 (Карпов)', 'Проект 1 (Карпов)', 'Описание проекта', 'Цель проекта', 'Результат интеллектуальной деятельности', 'Номер патента', 1627938000, 'Наименование патента', 'Суть результата интеллектуальной деятельности', 'На какой технологии основан проект', 'Макет базовой технологии', 'Зарегистрированное юр. лицо', 1629406800, 'Адрес сайта', 'Инвестор', 1628197200, 50000, 1628542800, 'Мероприятие, на котором проект анонсирован впервые'),
(3, 9, 1629311661, 1629311661, 'Проект 2 (Карпов)', 'Проект 2 (Карпов)', 'Описание проекта', 'Цель проекта', 'Результат интеллектуальной деятельности', 'Номер патента', 1629406800, 'Наименование патента', 'Суть результата интеллектуальной деятельности', 'На какой технологии основан проект', 'Макет базовой технологии', '', NULL, '', '', NULL, NULL, NULL, ''),
(4, 16, 1629311842, 1629311842, 'Сокращение расходов населения', 'Сокращение расходов населения', 'Описание проекта', 'Цель проекта', 'Результат интеллектуальной деятельности', 'Номер патента', 1629925200, 'Наименование патента', 'Суть результата интеллектуальной деятельности', 'На какой технологии основан проект', 'Макет базовой технологии', '', NULL, '', '', NULL, NULL, NULL, ''),
(5, 1, 1629312867, 1629312867, 'Перспективы города', 'Перспективы города', 'Описание проекта', 'Цель проекта', 'Результат интеллектуальной деятельности', 'Номер патента', 1629234000, 'Наименование патента', 'Суть результата интеллектуальной деятельности', 'На какой технологии основан проект', 'Макет базовой технологии', '', NULL, '', '', NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Структура таблицы `project_communications`
--

CREATE TABLE `project_communications` (
  `id` int(11) UNSIGNED NOT NULL,
  `sender_id` int(11) NOT NULL,
  `adressee_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `pattern_id` int(11) DEFAULT NULL,
  `triggered_communication_id` int(11) DEFAULT NULL,
  `cancel` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `questions_confirm_gcp`
--

CREATE TABLE `questions_confirm_gcp` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `questions_confirm_gcp`
--

INSERT INTO `questions_confirm_gcp` (`id`, `confirm_id`, `title`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Чем вы занимаетесь в настоящее время?', 0, 1620597835, 1620597879),
(3, 1, 'Что понравилось в решении и что нет?', 0, 1620597891, 1620597891),
(4, 2, 'Чем вы занимаетесь в настоящее время?', 0, 1620659862, 1620659862),
(9, 8, 'Чем вы занимаетесь в настоящее время?', 0, 1621705666, 1621705666),
(11, 9, 'Чем вы занимаетесь в настоящее время?', 0, 1623358253, 1623358253),
(13, 11, 'Что понравилось в решении и что нет?', 0, 1624559607, 1624559607),
(14, 12, 'Что неудобно по сравнению с продуктами, которыми пользуются сейчас?', 1, 1624999684, 1624999740),
(15, 12, 'Какая цена решения должна быть по вашему мнению?', 0, 1624999703, 1624999740);

-- --------------------------------------------------------

--
-- Структура таблицы `questions_confirm_mvp`
--

CREATE TABLE `questions_confirm_mvp` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `questions_confirm_mvp`
--

INSERT INTO `questions_confirm_mvp` (`id`, `confirm_id`, `title`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Чем вы занимаетесь в настоящее время?', 0, 1620598467, 1620598533),
(2, 2, 'Что понравилось в решении и что нет?', 0, 1620661499, 1620661499),
(7, 8, 'Чем вы занимаетесь в настоящее время?', 0, 1621705809, 1621705809),
(9, 9, 'Чем вы занимаетесь в настоящее время?', 0, 1623786986, 1623786986),
(11, 10, 'Чем вы занимаетесь в настоящее время, т.е. сейчас ?', 0, 1624734169, 1624734169),
(12, 11, 'Какие важные аспекты в продукте не затронуты, которые следовало бы продумать?', 1, 1624999787, 1624999790),
(13, 11, 'Что неудобно по сравнению с продуктами, которыми пользуются сейчас?', 0, 1624999809, 1624999809);

-- --------------------------------------------------------

--
-- Структура таблицы `questions_confirm_problem`
--

CREATE TABLE `questions_confirm_problem` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `questions_confirm_problem`
--

INSERT INTO `questions_confirm_problem` (`id`, `confirm_id`, `title`, `status`, `created_at`, `updated_at`) VALUES
(13, 1, 'Чем вы занимаетесь в настоящее время?', 0, 1620596768, 1620596818),
(15, 1, 'Случалось ли вам столкнуться с …?', 0, 1620596847, 1620596847),
(16, 4, 'Чем вы занимаетесь в настоящее время?', 0, 1620656938, 1620656938),
(17, 2, 'Чем вы занимаетесь в настоящее время?', 0, 1620767606, 1620767606),
(18, 2, 'На каком этапе проекта вы находитесь?', 0, 1620767611, 1620767611),
(19, 3, 'Чем вы занимаетесь в настоящее время?', 0, 1621279046, 1621279046),
(20, 3, 'На каком этапе проекта вы находитесь?', 0, 1621279049, 1621279049),
(26, 11, 'Чем вы занимаетесь в настоящее время?', 0, 1621705187, 1621705187),
(28, 12, 'Чем вы занимаетесь в настоящее время?', 0, 1623273233, 1623273233),
(36, 15, 'Чем вы занимаетесь в настоящее время?', 0, 1624394378, 1624394378),
(37, 16, 'Случалось ли вам столкнуться с …?', 1, 1624999409, 1624999476),
(39, 16, 'Какие трудности у вас вызывает это решение?', 0, 1624999471, 1624999477),
(40, 17, 'Как часто с вами происходит ..?', 1, 1625519014, 1625519017);

-- --------------------------------------------------------

--
-- Структура таблицы `questions_confirm_segment`
--

CREATE TABLE `questions_confirm_segment` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `questions_confirm_segment`
--

INSERT INTO `questions_confirm_segment` (`id`, `confirm_id`, `title`, `status`, `created_at`, `updated_at`) VALUES
(7, 1, 'Как вы определяете цели, задачи и последовательность действий?', 0, 1620580614, 1620580675),
(12, 2, 'Как вы добиваетесь достижения поставленной цели?', 0, 1620582094, 1620582094),
(13, 1, 'Чем вы занимаетесь в настоящее время?', 0, 1620592823, 1620592823),
(14, 2, 'Чем вы занимаетесь в настоящее время?', 0, 1620594637, 1620594637),
(16, 11, 'Чем вы занимаетесь в настоящее время?', 0, 1620656435, 1620656435),
(23, 17, 'Чем вы занимаетесь в настоящее время?', 0, 1621703269, 1621703269),
(44, 17, 'Что получается и что не получается в вашем проекте?', 0, 1623267444, 1623267453),
(54, 18, 'Что пытались сделать, чтобы определить верные последовательные действия?', 1, 1624914429, 1624998654),
(55, 18, 'Чем вы занимаетесь в настоящее время?', 0, 1624995837, 1624999198),
(56, 18, 'На каком этапе проекта вы находитесь?', 0, 1624998978, 1624999150);

-- --------------------------------------------------------

--
-- Структура таблицы `responds_gcp`
--

CREATE TABLE `responds_gcp` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `info_respond` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `date_plan` int(11) DEFAULT NULL,
  `place_interview` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `responds_gcp`
--

INSERT INTO `responds_gcp` (`id`, `confirm_id`, `name`, `info_respond`, `email`, `date_plan`, `place_interview`) VALUES
(1, 1, 'Респондент 2', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(2, 2, 'Иванов Иван Иванович', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(3, 2, 'Петров Петр Петрович', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(4, 2, 'Сидоров Николай Николаевич', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(26, 8, 'Иванов Иван Иванович', 'Данные респондента', '', 1622062800, 'Место проведения интервью'),
(27, 8, 'Петров Петр Петрович', 'Данные респондента', '', 1622062800, 'Место проведения интервью'),
(28, 8, 'Сидоров Николай Николаевич', 'Данные респондента', '', 1622062800, 'Место проведения интервью'),
(29, 9, 'Иванов Иван Иванович', 'Данные респондента', 'ivanov@mail.com', 1623272400, 'Место проведения интервью'),
(33, 9, 'Петров Петр Петрович', 'Данные респондента', '', 1623272400, 'Место проведения интервью'),
(34, 10, 'Иванов Иван Иванович', 'Данные респондента', 'ivanov@mail.com', 1623358800, 'Место проведения интервью'),
(35, 10, 'Респондент 2', 'Данные респондента!', '', 1623358800, 'Место проведения интервью'),
(36, 11, 'Иванов Иван Иванович', 'Данные респондента', 'ivanov@mail.com', 1624482000, 'Место проведения интервью'),
(37, 11, 'Петров Петр Петрович', 'Данные респондента!', '', 1624482000, 'Место проведения интервью'),
(38, 12, 'Иванов Иван Иванович', 'Данные респондента', 'ivanov@mail.com', 1625000400, 'Место проведения интервью'),
(39, 12, 'Петров Петр Петрович', 'Данные респондента!', '', 1625000400, 'Место проведения интервью');

-- --------------------------------------------------------

--
-- Структура таблицы `responds_mvp`
--

CREATE TABLE `responds_mvp` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `info_respond` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `date_plan` int(11) DEFAULT NULL,
  `place_interview` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `responds_mvp`
--

INSERT INTO `responds_mvp` (`id`, `confirm_id`, `name`, `info_respond`, `email`, `date_plan`, `place_interview`) VALUES
(1, 1, 'Респондент 2', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(2, 2, 'Иванов Иван Иванович', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(3, 2, 'Петров Петр Петрович', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(4, 2, 'Сидоров Николай Николаевич', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(24, 8, 'Иванов Иван Иванович', 'Данные респондента', '', 1622062800, 'Место проведения интервью'),
(25, 8, 'Петров Петр Петрович', 'Данные респондента', '', 1622062800, 'Место проведения интервью'),
(26, 8, 'Сидоров Николай Николаевич', 'Данные респондента', '', 1622062800, 'Место проведения интервью'),
(27, 9, 'Иванов Иван Иванович', 'Данные респондента', 'ivanov@mail.com', 1623704400, 'Место проведения интервью'),
(28, 9, 'Петров Петр Петрович', 'Данные респондента!', '', 1623704400, 'Место проведения интервью'),
(29, 10, 'Иванов Иван Иванович', 'Данные респондента', 'ivanov@mail.com', 1624741200, 'Место проведения интервью'),
(30, 10, 'Петров Петр Петрович', 'Данные респондента!', '', 1624741200, 'Место проведения интервью'),
(31, 11, 'Иванов Иван Иванович', 'Данные респондента', 'ivanov@mail.com', 1625000400, 'Место проведения интервью'),
(32, 11, 'Петров Петр Петрович', 'Данные респондента!', '', 1625000400, 'Место проведения интервью');

-- --------------------------------------------------------

--
-- Структура таблицы `responds_problem`
--

CREATE TABLE `responds_problem` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `info_respond` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `date_plan` int(11) DEFAULT NULL,
  `place_interview` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `responds_problem`
--

INSERT INTO `responds_problem` (`id`, `confirm_id`, `name`, `info_respond`, `email`, `date_plan`, `place_interview`) VALUES
(1, 1, 'Респондент 2', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(6, 4, 'Иванов Иван Иванович', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(7, 4, 'Петров Петр Петрович', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(8, 4, 'Сидоров Николай Николаевич', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(9, 2, 'Михайлов Федор Сергеевич', 'Данные респондента', '', 1621112400, 'Место проведения интервью'),
(11, 2, 'Иванов Иван Иванович', '', '', NULL, ''),
(32, 11, 'Иванов Иван Иванович', 'Данные респондента', '', 1622062800, 'Место проведения интервью'),
(33, 11, 'Петров Петр Петрович', 'Данные респондента', '', 1621976400, 'Место проведения интервью'),
(34, 11, 'Сидоров Николай Николаевич', 'Данные респондента', '', 1621976400, 'Место проведения интервью'),
(35, 12, 'Иванов Иван Иванович', 'Данные респондента', 'ivanov@mail.com', 1623272400, 'Место проведения интервью'),
(42, 12, 'Петров Петр Петрович', 'Данные респондента', '', 1623272400, 'Место проведения интервью'),
(43, 13, 'Иванов Иван Иванович', 'Данные респондента', 'ivanov@mail.com', 1623272400, 'Место проведения интервью'),
(44, 13, 'Респондент 2', 'Данные респондента!', '', 1623272400, 'Место проведения интервью'),
(48, 15, 'Иванов Иван Иванович', 'Данные респондента', 'ivanov@mail.com', 1624309200, 'Место проведения интервью'),
(49, 15, 'Петров Петр Петрович', 'Данные респондента!', '', 1624309200, 'Место проведения интервью'),
(50, 16, 'Иванов Иван Иванович', 'Данные респондента', 'ivanov@mail.com', 1625000400, 'Место проведения интервью'),
(51, 16, 'Респондент 2', 'Данные респондента!', '', 1625000400, 'Место проведения интервью'),
(52, 17, 'Респондент 1', 'Данные респондента', '', NULL, 'Место проведения интервью'),
(53, 17, 'Респондент 2', 'Данные респондента', '', NULL, 'Место проведения интервью'),
(54, 17, 'Респондент 3', '', '', NULL, ''),
(55, 17, 'Респондент 4', '', '', NULL, '');

-- --------------------------------------------------------

--
-- Структура таблицы `responds_segment`
--

CREATE TABLE `responds_segment` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `info_respond` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `date_plan` int(11) DEFAULT NULL,
  `place_interview` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `responds_segment`
--

INSERT INTO `responds_segment` (`id`, `confirm_id`, `name`, `info_respond`, `email`, `date_plan`, `place_interview`) VALUES
(1, 1, 'Респондент 1', 'Данные респондента', '', 1620507600, 'Место проведения интервью'),
(2, 1, 'Респондент 2', 'Данные респондента', '', 1620507600, 'Место проведения интервью'),
(3, 2, 'Респондент 1', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(4, 2, 'Респондент 2', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(18, 11, 'Иванов Иван Иванович', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(19, 11, 'Петров Петр Петрович', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(20, 11, 'Сидоров Николай Николаевич', 'Данные респондента', '', 1620594000, 'Место проведения интервью'),
(60, 17, 'Иванов Иван Иванович', 'Данные респондента', 'ivanov@mail.com', 1622062800, 'Место проведения интервью'),
(61, 17, 'Респондент 2', 'Данные респондента!', '', 1621890000, 'Место проведения интервью'),
(62, 18, 'Респондент 1', 'Данные респондента', '', 1624395600, 'Место проведения интервью'),
(63, 18, 'Респондент 2', 'Данные респондента', '', 1624395600, 'Место проведения интервью');

-- --------------------------------------------------------

--
-- Структура таблицы `segments`
--

CREATE TABLE `segments` (
  `id` int(11) UNSIGNED NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type_of_interaction_between_subjects` int(11) DEFAULT NULL,
  `field_of_activity` varchar(255) DEFAULT NULL,
  `sort_of_activity` varchar(255) DEFAULT NULL,
  `age_from` int(11) DEFAULT NULL,
  `age_to` int(11) DEFAULT NULL,
  `gender_consumer` int(11) DEFAULT NULL,
  `education_of_consumer` int(11) DEFAULT NULL,
  `income_from` int(11) DEFAULT NULL,
  `income_to` int(11) DEFAULT NULL,
  `quantity_from` int(11) DEFAULT NULL,
  `quantity_to` int(11) DEFAULT NULL,
  `market_volume` int(11) DEFAULT NULL,
  `company_products` text,
  `company_partner` text,
  `add_info` text,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `time_confirm` int(11) DEFAULT NULL,
  `exist_confirm` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `segments`
--

INSERT INTO `segments` (`id`, `project_id`, `name`, `description`, `type_of_interaction_between_subjects`, `field_of_activity`, `sort_of_activity`, `age_from`, `age_to`, `gender_consumer`, `education_of_consumer`, `income_from`, `income_to`, `quantity_from`, `quantity_to`, `market_volume`, `company_products`, `company_partner`, `add_info`, `created_at`, `updated_at`, `time_confirm`, `exist_confirm`) VALUES
(16, 1, 'Сегмент 1', 'Краткое описание сегмента', 100, 'Медицина', 'Оказание услуг населению', 4, 5, 70, 50, 5000, 5000, 7, 8, 55, NULL, NULL, '', 1620512807, 1620936528, 1620592870, 1),
(17, 1, 'Сегмент 2', 'Краткое описание сегмента', 200, 'Предпринимательство', 'Поликлинические услуги', NULL, NULL, NULL, NULL, 7, 8, 5, 6, 11, 'Краткое описание сегмента', 'Краткое описание сегмента', '', 1620512969, 1620937093, 1620655139, 1),
(26, 1, 'Сегмент 3', 'Краткое описание сегмента', 100, 'Образование', 'Репетиторство', 3, 4, 60, 50, 5000, 5000, 5, 6, 7, NULL, NULL, '', 1620656418, 1620936585, 1620656543, 1),
(32, 1, 'Сегмент 4', 'Краткое описание сегмента', 100, 'Сфера деятельности потребителя', 'Вид / специализация деятельности потребителя', 4, 5, 70, 50, 5000, 5000, 3, 9, 5, NULL, NULL, '', 1621703242, 1623267598, 1623267598, 1),
(33, 1, 'Сегмент 5', 'Краткое описание сегмента', 200, 'Сфера деятельности предприятия', 'Вид / специализация деятельности предприятия', NULL, NULL, NULL, NULL, 6, 7, 4, 5, 8, 'Продукция / услуги предприятия', 'Партнеры предприятия', '', 1624393383, 1625001207, 1625001207, 1),
(34, 1, 'Сегмент 6', 'Краткое описание сегмента', 200, 'Сфера деятельности предприятия', 'Вид / специализация деятельности предприятия', NULL, NULL, NULL, NULL, 77, 88, 55, 66, 5678, 'Продукция / услуги предприятия', 'Партнеры предприятия', 'Дополнительная информация', 1633512488, 1633512488, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `types_access_to_expertise`
--

CREATE TABLE `types_access_to_expertise` (
  `id` int(11) UNSIGNED NOT NULL,
  `communication_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `types` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `second_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar_max_image` varchar(255) DEFAULT NULL,
  `avatar_image` varchar(255) DEFAULT NULL,
  `auth_key` varchar(255) DEFAULT NULL,
  `secret_key` varchar(255) DEFAULT NULL,
  `role` int(11) NOT NULL,
  `status` smallint(6) NOT NULL,
  `confirm` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `second_name`, `first_name`, `middle_name`, `telephone`, `email`, `username`, `password_hash`, `avatar_max_image`, `avatar_image`, `auth_key`, `secret_key`, `role`, `status`, `confirm`, `id_admin`, `created_at`, `updated_at`) VALUES
(1, 'Иванов', 'Иван', 'Иванович', '+7(999)999-99-99', 'ivanov@mail.com', 'IvanoV', '$2y$13$GHlJeyDLCETcXCeFH5URZu/MCljd/xdr9DUQsAE.KBnAI.BC0tdva', 'tBH4LLXzmH4.jpg', 'avatar_5eJwCEX2_min.png', '32hRL8j7MkkANX9mIy7vYPhsxBuO5JD8', NULL, 10, 10, 20, 21, 1582408272, 1633513785),
(9, 'Карпов', 'Антон', 'Петрович', '+7(999)99-99-99', 'karpov@mail.com', 'karpov', '$2y$13$aDvsycNgzvtoTq7.fLGI7ekA.HLbR93pCudDUsidh.qEl.fwf7xBG', '', '', '8ttyGMNTTQgVTM-5vPftdvK1Y7LVLkM1', NULL, 10, 10, 20, 21, 1583859778, 1629311661),
(16, 'Порошин', 'Виктор', 'Николаевич', '+7(999)99-99-99', 'viktor@mail.ru', 'Viktor', '$2y$13$EU.K51p/fg4CbVtmMG/zHeimVSxiY5VE7YTrToUKCzuBBymYoBtk2', '', '', '9iGl39KVGbXS2DNazKaS4cgjbDwrBybF', NULL, 10, 10, 20, 21, 1585414934, 1629311842),
(21, 'Дибров', 'Дмитрий', 'Владимирович', '+7(999)999-99-99', 'dibrov@mail.ru', 'Dibrov', '$2y$13$8qmD5Jj5YMbUl4HlyU9OqODvek.tgm1GU/zhmk6iM8lBeJh1hWhjK', '7AzQAtxQm1c.jpg', 'avatar_9Vc9GrGT_min.png', 'wd9PcB_fY8b_L_W9Rjdp3hxteBd0OQyy', NULL, 20, 10, 20, NULL, 1586614816, 1627203712),
(22, 'Техническая', 'поддержка', 'StartPool', '+7(999)999-99-99', 'dev@mail.com', 'dev', '$2y$13$KAt3g3ocWpm5cow7dsZOKOqRhaYZM1Qq27QTApSf/UYdRJPjU4Uji', 'technical-support-services.png', 'avatar_-6gVzcWi_min.png', 'hPQR4GvMfRsUnepawhOYlHhy80y--6ki', NULL, 100, 10, 20, NULL, 1586957787, 1617049090),
(28, 'Главный', 'Админ', 'Сайта', '+7(999)999-99-99', 'alex.latukhin@mail.ru', 'MainAdmin', '$2y$13$cTWHKk2IziK6WtZeN9r0xON0GsDl6G29ll46dJ7ySFfchjV/llFwm', 'background_for_main_page.png', 'avatar_dy9VW45c_min.png', 'HKGOWAZUrUpkLjP4knCiJGKQD7XLeBIc', NULL, 30, 10, 20, NULL, 1587560218, 1615307246),
(29, 'Хрущев', 'Никита', 'Сергеевич', '+79999999999', 'alex.latukhin2015@yandex.ru', 'hrust', '$2y$13$VqFILzTszNEYP3vKNvTo/OR0qrfIKFYP1/ZSnvJExP13w4y.oDVW.', '', '', 'CA4mobzRtKA2LLSohPZIWTHT-XkdBd3d', NULL, 10, 0, 20, 21, 1613504385, 1630236539),
(31, 'Экспертов', 'Петр', 'Иванович', '7890000000', 'aleksejj.latukhin@mail.ru', 'expertov', '$2y$13$CoDvU0pYkxcW9.Ksz8ynBOZnasD1jxvxThXH3izeHwN9eNt5SAs3m', 'shutterstock_235978021.jpg', 'avatar_64T4qnrK_min.png', 'ZE5naj0yFMRZhC0sffb-gH06bqyZsnHD', NULL, 40, 10, 20, NULL, 1627149710, 1629032476),
(37, 'Суриков', 'Иван', 'Федорович', '7899999999', 'aleksejj.latukhin@rambler.ru', 'SurikoV', '$2y$13$UAhxmjmY0BU7zoppdsZeJ.B0fo9a/5kS2olqixpkUb1tlfAU7sBMS', 'iStock_000040123844_small.jpg', 'avatar_-3zHF6rV_min.png', 'kWUWoylcl_KEaW5Ps86EFzk4lVBOJl-6', NULL, 40, 10, 20, NULL, 1628794951, 1629030988);

-- --------------------------------------------------------

--
-- Структура таблицы `user_access_to_projects`
--

CREATE TABLE `user_access_to_projects` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `communication_id` int(11) NOT NULL,
  `communication_type` int(11) NOT NULL,
  `cancel` int(11) NOT NULL,
  `date_stop` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `all_questions_confirm_gcp`
--
ALTER TABLE `all_questions_confirm_gcp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `all_questions_confirm_mvp`
--
ALTER TABLE `all_questions_confirm_mvp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `all_questions_confirm_problem`
--
ALTER TABLE `all_questions_confirm_problem`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `all_questions_confirm_segment`
--
ALTER TABLE `all_questions_confirm_segment`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `answers_questions_confirm_gcp`
--
ALTER TABLE `answers_questions_confirm_gcp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `answers_questions_confirm_mvp`
--
ALTER TABLE `answers_questions_confirm_mvp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `answers_questions_confirm_problem`
--
ALTER TABLE `answers_questions_confirm_problem`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `answers_questions_confirm_segment`
--
ALTER TABLE `answers_questions_confirm_segment`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Индексы таблицы `business_model`
--
ALTER TABLE `business_model`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `checking_online_user`
--
ALTER TABLE `checking_online_user`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `communication_patterns`
--
ALTER TABLE `communication_patterns`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `communication_response`
--
ALTER TABLE `communication_response`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `confirm_gcp`
--
ALTER TABLE `confirm_gcp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `confirm_mvp`
--
ALTER TABLE `confirm_mvp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `confirm_problem`
--
ALTER TABLE `confirm_problem`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `confirm_segment`
--
ALTER TABLE `confirm_segment`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `conversation_admin`
--
ALTER TABLE `conversation_admin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `conversation_development`
--
ALTER TABLE `conversation_development`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `conversation_expert`
--
ALTER TABLE `conversation_expert`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `conversation_main_admin`
--
ALTER TABLE `conversation_main_admin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `duplicate_communications`
--
ALTER TABLE `duplicate_communications`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `expected_results_interview_confirm_problem`
--
ALTER TABLE `expected_results_interview_confirm_problem`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `expert_info`
--
ALTER TABLE `expert_info`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `gcps`
--
ALTER TABLE `gcps`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `interview_confirm_gcp`
--
ALTER TABLE `interview_confirm_gcp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `interview_confirm_mvp`
--
ALTER TABLE `interview_confirm_mvp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `interview_confirm_problem`
--
ALTER TABLE `interview_confirm_problem`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `interview_confirm_segment`
--
ALTER TABLE `interview_confirm_segment`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `keywords_expert`
--
ALTER TABLE `keywords_expert`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `message_admin`
--
ALTER TABLE `message_admin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `message_development`
--
ALTER TABLE `message_development`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `message_expert`
--
ALTER TABLE `message_expert`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `message_files`
--
ALTER TABLE `message_files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `message_main_admin`
--
ALTER TABLE `message_main_admin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mvps`
--
ALTER TABLE `mvps`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pre_files`
--
ALTER TABLE `pre_files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `problems`
--
ALTER TABLE `problems`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `project_communications`
--
ALTER TABLE `project_communications`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `questions_confirm_gcp`
--
ALTER TABLE `questions_confirm_gcp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `questions_confirm_mvp`
--
ALTER TABLE `questions_confirm_mvp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `questions_confirm_problem`
--
ALTER TABLE `questions_confirm_problem`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `questions_confirm_segment`
--
ALTER TABLE `questions_confirm_segment`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `responds_gcp`
--
ALTER TABLE `responds_gcp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `responds_mvp`
--
ALTER TABLE `responds_mvp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `responds_problem`
--
ALTER TABLE `responds_problem`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `responds_segment`
--
ALTER TABLE `responds_segment`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `segments`
--
ALTER TABLE `segments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `types_access_to_expertise`
--
ALTER TABLE `types_access_to_expertise`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Индексы таблицы `user_access_to_projects`
--
ALTER TABLE `user_access_to_projects`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `all_questions_confirm_gcp`
--
ALTER TABLE `all_questions_confirm_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `all_questions_confirm_mvp`
--
ALTER TABLE `all_questions_confirm_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `all_questions_confirm_problem`
--
ALTER TABLE `all_questions_confirm_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `all_questions_confirm_segment`
--
ALTER TABLE `all_questions_confirm_segment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `answers_questions_confirm_gcp`
--
ALTER TABLE `answers_questions_confirm_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT для таблицы `answers_questions_confirm_mvp`
--
ALTER TABLE `answers_questions_confirm_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT для таблицы `answers_questions_confirm_problem`
--
ALTER TABLE `answers_questions_confirm_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT для таблицы `answers_questions_confirm_segment`
--
ALTER TABLE `answers_questions_confirm_segment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT для таблицы `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `business_model`
--
ALTER TABLE `business_model`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `checking_online_user`
--
ALTER TABLE `checking_online_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `communication_patterns`
--
ALTER TABLE `communication_patterns`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `communication_response`
--
ALTER TABLE `communication_response`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT для таблицы `confirm_gcp`
--
ALTER TABLE `confirm_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `confirm_mvp`
--
ALTER TABLE `confirm_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `confirm_problem`
--
ALTER TABLE `confirm_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `confirm_segment`
--
ALTER TABLE `confirm_segment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `conversation_admin`
--
ALTER TABLE `conversation_admin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `conversation_development`
--
ALTER TABLE `conversation_development`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `conversation_expert`
--
ALTER TABLE `conversation_expert`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `conversation_main_admin`
--
ALTER TABLE `conversation_main_admin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `duplicate_communications`
--
ALTER TABLE `duplicate_communications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;

--
-- AUTO_INCREMENT для таблицы `expected_results_interview_confirm_problem`
--
ALTER TABLE `expected_results_interview_confirm_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `expert_info`
--
ALTER TABLE `expert_info`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `gcps`
--
ALTER TABLE `gcps`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `interview_confirm_gcp`
--
ALTER TABLE `interview_confirm_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `interview_confirm_mvp`
--
ALTER TABLE `interview_confirm_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `interview_confirm_problem`
--
ALTER TABLE `interview_confirm_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `interview_confirm_segment`
--
ALTER TABLE `interview_confirm_segment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `keywords_expert`
--
ALTER TABLE `keywords_expert`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `message_admin`
--
ALTER TABLE `message_admin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=387;

--
-- AUTO_INCREMENT для таблицы `message_development`
--
ALTER TABLE `message_development`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT для таблицы `message_expert`
--
ALTER TABLE `message_expert`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT для таблицы `message_files`
--
ALTER TABLE `message_files`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT для таблицы `message_main_admin`
--
ALTER TABLE `message_main_admin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT для таблицы `mvps`
--
ALTER TABLE `mvps`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `pre_files`
--
ALTER TABLE `pre_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `problems`
--
ALTER TABLE `problems`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `project_communications`
--
ALTER TABLE `project_communications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=506;

--
-- AUTO_INCREMENT для таблицы `questions_confirm_gcp`
--
ALTER TABLE `questions_confirm_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `questions_confirm_mvp`
--
ALTER TABLE `questions_confirm_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `questions_confirm_problem`
--
ALTER TABLE `questions_confirm_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT для таблицы `questions_confirm_segment`
--
ALTER TABLE `questions_confirm_segment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT для таблицы `responds_gcp`
--
ALTER TABLE `responds_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `responds_mvp`
--
ALTER TABLE `responds_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT для таблицы `responds_problem`
--
ALTER TABLE `responds_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT для таблицы `responds_segment`
--
ALTER TABLE `responds_segment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT для таблицы `segments`
--
ALTER TABLE `segments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT для таблицы `types_access_to_expertise`
--
ALTER TABLE `types_access_to_expertise`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT для таблицы `user_access_to_projects`
--
ALTER TABLE `user_access_to_projects`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=361;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
