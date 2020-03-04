-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 05 2020 г., 01:05
-- Версия сервера: 5.6.43
-- Версия PHP: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
(1, 1, 'Иванов Иван Иванович', 'Главный', 'Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.'),
(2, 2, 'Никитин Федор Николаевич', 'Директор', 'Опыт работы\r\n'),
(3, 3, 'Никитин Федор Николаевич', 'Главный', '');

-- --------------------------------------------------------

--
-- Структура таблицы `business_model`
--

CREATE TABLE `business_model` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_mvp_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sort_of_activity` varchar(255) NOT NULL,
  `relations` varchar(255) NOT NULL,
  `partners` varchar(255) NOT NULL,
  `distribution_of_sales` varchar(255) NOT NULL,
  `resources` varchar(255) NOT NULL,
  `cost` varchar(255) NOT NULL,
  `revenue` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `business_model`
--

INSERT INTO `business_model` (`id`, `confirm_mvp_id`, `quantity`, `sort_of_activity`, `relations`, `partners`, `distribution_of_sales`, `resources`, `cost`, `revenue`) VALUES
(1, 1, 600000, 'Род деятельности потребителя', '\r\nВзаимоотношения с клиентами\r\n', 'Ключевые партнеры', 'Каналы коммуникации и сбыта', 'Ключевые ресурсы', 'Структура издержек', 'Потоки поступления доходов');

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
(1, 1, 3, 3),
(2, 3, 3, 3),
(3, 4, 2, 1);

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
(1, 1, 3, 3),
(2, 2, 3, 3),
(3, 3, 3, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `confirm_problem`
--

CREATE TABLE `confirm_problem` (
  `id` int(11) UNSIGNED NOT NULL,
  `gps_id` int(11) NOT NULL,
  `count_respond` int(11) NOT NULL,
  `count_positive` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `confirm_problem`
--

INSERT INTO `confirm_problem` (`id`, `gps_id`, `count_respond`, `count_positive`) VALUES
(1, 1, 3, 3),
(2, 3, 3, 3),
(3, 2, 3, 2),
(4, 5, 3, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `desc_interview`
--

CREATE TABLE `desc_interview` (
  `id` int(11) UNSIGNED NOT NULL,
  `respond_id` int(11) NOT NULL,
  `date_fact` date NOT NULL,
  `description` text NOT NULL,
  `interview_file` varchar(255) DEFAULT NULL,
  `server_file` varchar(255) DEFAULT NULL,
  `result` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `desc_interview`
--

INSERT INTO `desc_interview` (`id`, `respond_id`, `date_fact`, `description`, `interview_file`, `server_file`, `result`, `status`) VALUES
(1, 1, '2020-02-23', 'Материалы полученные во время интервью', 'Tekh_RAJDER.doc', 'F_wprT_ag1WZn2_.doc', 'Вывод', '1'),
(2, 2, '2020-02-23', 'Материалы полученные во время интервью', 'Бизнес требования для сервиса Акселератор.docx', 'omIj2-NixLj_IhE.docx', 'Вывод', '1'),
(3, 3, '2020-02-23', 'Материалы полученные во время интервью', NULL, NULL, 'Вывод', '1'),
(4, 4, '2020-02-24', 'Материалы полученные во время интервью', NULL, NULL, 'Вывод', '0'),
(5, 6, '2020-02-26', 'Материалы полученные во время интервью', NULL, NULL, 'Вывод', '1'),
(6, 7, '2020-02-26', 'Материалы полученные во время интервью', NULL, NULL, 'Вывод', '1'),
(7, 8, '2020-02-26', 'Материалы полученные во время интервью', NULL, NULL, 'Вывод', '1'),
(8, 5, '2020-03-02', 'Материалы полученные во время интервью', '200220Описание этапов для 1 стр_3.docx', 'sCJOoMdGY2XOwL_.docx', 'Вывод', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `desc_interview_confirm`
--

CREATE TABLE `desc_interview_confirm` (
  `id` int(11) UNSIGNED NOT NULL,
  `responds_confirm_id` int(11) NOT NULL,
  `date_fact` date NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `desc_interview_confirm`
--

INSERT INTO `desc_interview_confirm` (`id`, `responds_confirm_id`, `date_fact`, `status`) VALUES
(1, 1, '2020-02-23', '1'),
(2, 2, '2020-02-23', '1'),
(3, 3, '2020-02-23', '1'),
(4, 7, '2020-02-26', '1'),
(5, 8, '2020-02-26', '1'),
(6, 10, '2020-02-27', '1'),
(7, 11, '2020-02-27', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `desc_interview_gcp`
--

CREATE TABLE `desc_interview_gcp` (
  `id` int(11) UNSIGNED NOT NULL,
  `responds_gcp_id` int(11) NOT NULL,
  `date_fact` date NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `desc_interview_gcp`
--

INSERT INTO `desc_interview_gcp` (`id`, `responds_gcp_id`, `date_fact`, `status`) VALUES
(1, 1, '2020-02-23', '1'),
(2, 2, '2020-02-23', '1'),
(3, 3, '2020-02-23', '1'),
(4, 7, '2020-02-27', '1'),
(5, 8, '2020-02-27', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `desc_interview_mvp`
--

CREATE TABLE `desc_interview_mvp` (
  `id` int(11) UNSIGNED NOT NULL,
  `responds_mvp_id` int(11) NOT NULL,
  `date_fact` date NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `desc_interview_mvp`
--

INSERT INTO `desc_interview_mvp` (`id`, `responds_mvp_id`, `date_fact`, `status`) VALUES
(1, 1, '2020-02-23', 2),
(2, 2, '2020-02-23', 1),
(3, 3, '2020-02-23', 2),
(4, 4, '2020-02-26', 0),
(5, 5, '2020-02-26', 0),
(6, 6, '2020-02-26', 1),
(7, 7, '2020-02-27', 1),
(8, 8, '2020-02-27', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `feedback_expert`
--

CREATE TABLE `feedback_expert` (
  `id` int(11) UNSIGNED NOT NULL,
  `interview_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `feedback_file` varchar(255) DEFAULT NULL,
  `server_file` varchar(255) DEFAULT NULL,
  `comment` varchar(255) NOT NULL,
  `date_feedback` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `feedback_expert`
--

INSERT INTO `feedback_expert` (`id`, `interview_id`, `title`, `name`, `position`, `feedback_file`, `server_file`, `comment`, `date_feedback`) VALUES
(6, 1, 'Отзыв 1', 'Новиков Андрей Борисович', 'Организация/Должность', 'Бизнес требования для сервиса Акселератор.docx', 'rKHz8bWAFl49UP_.docx', 'Комментарий', '2020-03-02'),
(7, 1, 'Отзыв 2', 'Антонов Юрий Николаевич', 'Организация/Должность', 'Методика проведения процедур на аппарате Армед.doc', 'Q3LVnlG86IVvUSj.doc', 'Комментарий', '2020-03-02');

-- --------------------------------------------------------

--
-- Структура таблицы `feedback_expert_confirm`
--

CREATE TABLE `feedback_expert_confirm` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_problem_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `feedback_file` varchar(255) DEFAULT NULL,
  `server_file` varchar(255) DEFAULT NULL,
  `comment` varchar(255) NOT NULL,
  `date_feedback` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `feedback_expert_confirm`
--

INSERT INTO `feedback_expert_confirm` (`id`, `confirm_problem_id`, `title`, `name`, `position`, `feedback_file`, `server_file`, `comment`, `date_feedback`) VALUES
(1, 1, 'Отзыв 1', 'Новиков Олег Владимирович', 'Организация / Должность', 'Бизнес требования для сервиса Акселератор.docx', 'JXYtoEFF7KZO5_R.docx', 'Комментарий', '2020-02-23'),
(2, 1, 'Отзыв 2', 'Аленина Юлия Антоновна', 'Организация / Должность', '200220Описание этапов для 1 стр_3.docx', 'Gv4tTiq8zGeAsdq.docx', 'Комментарий', '2020-02-23'),
(3, 4, 'Отзыв 1', 'Новиков Олег Владимирович', 'Организация / Должность', NULL, NULL, 'Комментарий', '2020-02-27'),
(4, 4, 'Отзыв 2', 'Аленина Юлия Антоновна', 'Организация / Должность', NULL, NULL, 'Комментарий', '2020-02-27'),
(6, 1, 'Отзыв 3', 'Борисов Николай Петрович', 'Организация / Должность', '051119_Дизайн_акселератора.xlsx', 'SD4qOp9TVbIzx5b.xlsx', 'Комментарий', '2020-03-02');

-- --------------------------------------------------------

--
-- Структура таблицы `feedback_expert_gcp`
--

CREATE TABLE `feedback_expert_gcp` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_gcp_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `feedback_file` varchar(255) DEFAULT NULL,
  `server_file` varchar(255) DEFAULT NULL,
  `comment` varchar(255) NOT NULL,
  `date_feedback` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `feedback_expert_gcp`
--

INSERT INTO `feedback_expert_gcp` (`id`, `confirm_gcp_id`, `title`, `name`, `position`, `feedback_file`, `server_file`, `comment`, `date_feedback`) VALUES
(3, 1, 'Отзыв 1', 'Архипов Николай Петрович', 'ННГУ им.Лобачевского / эсперт', 'Методика проведения процедур на аппарате Армед.doc', 'UAVioPw-XV6oPcJ.doc', 'Комментарий', '2020-03-02'),
(4, 1, 'Отзыв 2', 'Новиков Алексей Иванович', 'ННГУ им.Лобачевского / эсперт', '200220Описание этапов для 1 стр_3.docx', 'kXbPMyNSk71eVsc.docx', 'Комментарий', '2020-03-02');

-- --------------------------------------------------------

--
-- Структура таблицы `feedback_expert_mvp`
--

CREATE TABLE `feedback_expert_mvp` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_mvp_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `feedback_file` varchar(255) DEFAULT NULL,
  `server_file` varchar(255) DEFAULT NULL,
  `comment` varchar(255) NOT NULL,
  `date_feedback` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `feedback_expert_mvp`
--

INSERT INTO `feedback_expert_mvp` (`id`, `confirm_mvp_id`, `title`, `name`, `position`, `feedback_file`, `server_file`, `comment`, `date_feedback`) VALUES
(3, 1, 'Отзыв 1', 'Никулин Игорь Борисович', 'ННГУ / руководитель', 'Методика проведения процедур на аппарате Армед.doc', 'mVum2zQw8SWaiRM.doc', 'Комментарий', '2020-03-02'),
(4, 1, 'Отзыв 2', 'Николаев Борис Николаевич', 'ООО \"Главбух\"', '051119_Дизайн_акселератора.xlsx', '9mxfNZtLxvE2-xB.xlsx', 'Комментарий', '2020-03-02');

-- --------------------------------------------------------

--
-- Структура таблицы `gcp`
--

CREATE TABLE `gcp` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_problem_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `good` varchar(255) NOT NULL,
  `benefit` varchar(255) NOT NULL,
  `contrast` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_create` date DEFAULT NULL,
  `date_time_create` datetime DEFAULT NULL,
  `date_confirm` date DEFAULT NULL,
  `date_time_confirm` datetime DEFAULT NULL,
  `exist_confirm` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `gcp`
--

INSERT INTO `gcp` (`id`, `confirm_problem_id`, `title`, `good`, `benefit`, `contrast`, `description`, `date_create`, `date_time_create`, `date_confirm`, `date_time_confirm`, `exist_confirm`) VALUES
(1, 1, 'ГЦП 1', 'Продукт 1', 'выгода', 'Продукт 2', 'Наш продукт \"продукт 1\" помогает \"сегмент 1\", который хочет удовлетворить проблему \"напишите описание гипотезы проблемы сегмента\", избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, \"выгода\", в отличии от \"продукт 2\".', '2020-02-23', '2020-02-23 19:50:51', '2020-02-23', '2020-02-23 20:18:55', 1),
(2, 1, 'ГЦП 2', 'Продукт 1', 'выгоду', 'Продукт 2', 'Наш продукт \"продукт 1\" помогает \"сегмент 1\", который хочет удовлетворить проблему \"напишите описание гипотезы проблемы сегмента\", избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, \"выгоду\", в отличии от \"продукт 2\".', '2020-02-23', '2020-02-23 19:51:50', NULL, NULL, NULL),
(3, 1, 'ГЦП 3', 'Продукт 1', 'выгода', 'Продукт', 'Наш продукт \"продукт 1\" помогает \"сегмент 1\", который хочет удовлетворить проблему \"напишите описание гипотезы проблемы сегмента\", избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, \"выгода\", в отличии от \"продукт\".', '2020-02-26', '2020-02-26 20:02:16', NULL, NULL, 0),
(4, 3, 'ГЦП 1', 'Продукт 1', 'выгода', 'продукт 2', 'Наш продукт \"продукт 1\" помогает \"сегмент 1\", который хочет удовлетворить проблему \"напишите описание гипотезы проблемы сегмента\", избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, \"выгода\", в отличии от \"продукт 2\".', '2020-02-27', '2020-02-27 00:55:48', '2020-02-27', '2020-02-27 00:56:39', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `generation_problem`
--

CREATE TABLE `generation_problem` (
  `id` int(11) UNSIGNED NOT NULL,
  `interview_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_gps` date NOT NULL,
  `date_confirm` date DEFAULT NULL,
  `date_time_confirm` datetime DEFAULT NULL,
  `exist_confirm` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `generation_problem`
--

INSERT INTO `generation_problem` (`id`, `interview_id`, `title`, `description`, `date_gps`, `date_confirm`, `date_time_confirm`, `exist_confirm`) VALUES
(1, 1, 'ГПС 1', 'Напишите описание гипотезы проблемы сегмента', '2020-02-23', '2020-02-23', '2020-02-23 19:47:33', 1),
(2, 1, 'ГПС 2', 'Напишите описание гипотезы проблемы сегмента', '2020-02-23', '2020-02-26', '2020-02-26 23:41:14', 1),
(3, 1, 'ГПС 3', 'Напишите описание гипотезы проблемы сегмента', '2020-02-23', NULL, NULL, 0),
(4, 1, 'ГПС 4', 'Напишите описание гипотезы проблемы сегмента', '2020-02-25', NULL, NULL, NULL),
(5, 2, 'ГПС 1', 'Напишите описание гипотезы проблемы сегмента', '2020-02-26', '2020-02-27', '2020-02-27 00:57:47', 1),
(6, 2, 'ГПС 2', 'Напишите описание гипотезы проблемы сегмента', '2020-02-26', NULL, NULL, NULL),
(7, 2, 'ГПС 3', 'Напишите описание гипотезы проблемы сегмента', '2020-02-26', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `interview`
--

CREATE TABLE `interview` (
  `id` int(11) UNSIGNED NOT NULL,
  `segment_id` int(11) NOT NULL,
  `count_respond` int(11) UNSIGNED NOT NULL,
  `count_positive` int(11) UNSIGNED NOT NULL,
  `greeting_interview` varchar(255) NOT NULL,
  `view_interview` varchar(255) NOT NULL,
  `reason_interview` varchar(255) NOT NULL,
  `question_1` enum('0','1') NOT NULL DEFAULT '1',
  `question_2` enum('0','1') NOT NULL DEFAULT '1',
  `question_3` enum('0','1') NOT NULL DEFAULT '1',
  `question_4` enum('0','1') NOT NULL DEFAULT '1',
  `question_5` enum('0','1') NOT NULL DEFAULT '1',
  `question_6` enum('0','1') NOT NULL DEFAULT '1',
  `question_7` enum('0','1') NOT NULL DEFAULT '1',
  `question_8` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `interview`
--

INSERT INTO `interview` (`id`, `segment_id`, `count_respond`, `count_positive`, `greeting_interview`, `view_interview`, `reason_interview`, `question_1`, `question_2`, `question_3`, `question_4`, `question_5`, `question_6`, `question_7`, `question_8`) VALUES
(1, 1, 5, 3, 'Приветствие в начале встречи', 'Представление интервьюера', 'Почему мне интересно', '1', '1', '1', '1', '1', '1', '1', '1'),
(2, 2, 5, 3, 'Приветствие в начале встречи', 'Представление интервьюера', 'Почему мне интересно', '1', '1', '1', '1', '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `mvp`
--

CREATE TABLE `mvp` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_gcp_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_create` date NOT NULL,
  `date_time_create` datetime DEFAULT NULL,
  `date_confirm` date DEFAULT NULL,
  `date_time_confirm` datetime DEFAULT NULL,
  `exist_confirm` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `mvp`
--

INSERT INTO `mvp` (`id`, `confirm_gcp_id`, `title`, `description`, `date_create`, `date_time_create`, `date_confirm`, `date_time_confirm`, `exist_confirm`) VALUES
(1, 1, 'ГMVP 1', 'Презентация', '2020-02-23', '2020-02-23 20:19:06', '2020-02-23', '2020-02-23 20:37:06', 1),
(2, 1, 'ГMVP 2', 'Макет', '2020-02-23', '2020-02-23 20:19:12', '2020-02-26', NULL, 0),
(3, 1, 'ГMVP 3', 'Видео', '2020-02-23', '2020-02-23 20:19:21', '2020-02-27', '2020-02-27 12:34:45', 1),
(4, 1, 'ГMVP 4', 'Опытный образец', '2020-02-26', '2020-02-26 00:24:20', NULL, NULL, NULL);

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
(53, 1, '200220Описание этапов для 1 стр_3.docx', 'LRpWirACYqulxZZ.docx'),
(54, 1, 'Бизнес требования для сервиса Акселератор.docx', 'f7MAXnDVFmJpFP8.docx'),
(57, 1, 'Методика проведения процедур на аппарате Армед.doc', 'oO9B26i9UV4EqIx.doc'),
(58, 2, 'Договор на 31.12.18..doc', 'qPk65fbSm62h0pE.doc'),
(59, 2, 'интернет маркетинг.doc', '8boznILsWxFjXgy.doc'),
(60, 2, 'Методика проведения процедур на аппарате Армед.doc', 'wmy5cxh_quf2eQ_.doc'),
(61, 2, 'песенки.doc', 'uoApFNOdNEXN4V9.doc');

-- --------------------------------------------------------

--
-- Структура таблицы `projects`
--

CREATE TABLE `projects` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `update_at` date NOT NULL,
  `project_fullname` varchar(255) DEFAULT NULL,
  `project_name` varchar(255) NOT NULL,
  `description` text,
  `rid` varchar(255) DEFAULT NULL,
  `patent_number` varchar(255) DEFAULT NULL,
  `patent_date` date DEFAULT NULL,
  `patent_name` text,
  `core_rid` text,
  `technology` varchar(255) DEFAULT NULL,
  `layout_technology` text,
  `register_name` varchar(255) DEFAULT NULL,
  `register_date` date DEFAULT NULL,
  `site` varchar(255) DEFAULT NULL,
  `invest_name` varchar(255) DEFAULT NULL,
  `invest_date` date DEFAULT NULL,
  `invest_amount` int(11) DEFAULT NULL,
  `date_of_announcement` date DEFAULT NULL,
  `announcement_event` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `created_at`, `update_at`, `project_fullname`, `project_name`, `description`, `rid`, `patent_number`, `patent_date`, `patent_name`, `core_rid`, `technology`, `layout_technology`, `register_name`, `register_date`, `site`, `invest_name`, `invest_date`, `invest_amount`, `date_of_announcement`, `announcement_event`) VALUES
(1, 1, '2020-02-23', '2020-03-03', 'Полное наименование проекта Полное наименование проекта Полное наименование проекта Полное наименование проекта', 'Проект 1', 'Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.', 'Результат интеллектуальной деятельности', 'Номер патента 1266349187230918', '2020-02-22', 'Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.', 'Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.', 'Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.', 'Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций. Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций. Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.', 'Зарегистрированное юр. лицо', '2020-02-22', 'Адрес сайта', 'Инвестор', '2020-02-22', 20000000, '2020-02-15', 'Мероприятие, на котором проект анонсирован впервые'),
(2, 1, '2020-03-03', '2020-03-03', 'Проект второй', 'Проект 2', 'Описание проекта', 'Результат интеллектуальной деятельности', 'Номер патента 1266349187230918', '2020-03-11', 'Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.', 'Суть результата интеллектуальной деятельности', 'Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.', 'Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций. Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций. Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций. Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.', 'Зарегистрированное юр. лицо', '2020-03-02', 'Адрес сайта', 'Инвестор', '2020-03-13', 50000000, '2020-03-01', 'Мероприятие, на котором проект анонсирован впервые'),
(3, 1, '2020-03-04', '2020-03-04', 'Проект 3', 'Проект 3', 'Описание проекта', 'Результат интеллектуальной деятельности', 'Номер патента 1266349187230918', '2020-03-05', 'Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.', 'Суть результата интеллектуальной деятельности', 'Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.', 'Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций. Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций. Это достаточно маленький текст, оптимально подходящий для карточек товаров в интернет-магазинах или для небольших информационных публикаций.', 'Зарегистрированное юр. лицо', '2020-03-03', 'Адрес сайта', 'Инвестор', '2020-03-12', 2147483647, '2020-03-01', 'Мероприятие, на котором проект анонсирован впервые');

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `id` int(11) UNSIGNED NOT NULL,
  `interview_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`id`, `interview_id`, `title`, `status`) VALUES
(1, 1, 'Как и посредством какого инструмента / процесса вы справляетесь с задачей?', '1'),
(2, 1, 'Что нравится / не нравится в текущем положении вещей?', '1'),
(3, 1, 'Вас беспокоит данная ситуация?', '1'),
(4, 1, 'Что вы пытались с этим сделать?', '1'),
(5, 1, 'Что вы делали с этим в последний раз, какие шаги предпринимали?', '1'),
(6, 1, 'Если ничего не делали, то почему?', '1'),
(7, 1, 'Сколько денег / времени на это тратится сейчас?', '1'),
(8, 1, 'Есть ли деньги на решение сложившейся ситуации сейчас?', '1'),
(9, 2, 'Как и посредством какого инструмента / процесса вы справляетесь с задачей?', '1'),
(10, 2, 'Что нравится / не нравится в текущем положении вещей?', '1'),
(11, 2, 'Вас беспокоит данная ситуация?', '1'),
(12, 2, 'Что вы пытались с этим сделать?', '1'),
(13, 2, 'Что вы делали с этим в последний раз, какие шаги предпринимали?', '1'),
(14, 2, 'Если ничего не делали, то почему?', '1'),
(15, 2, 'Сколько денег / времени на это тратится сейчас?', '1'),
(16, 2, 'Есть ли деньги на решение сложившейся ситуации сейчас?', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `responds`
--

CREATE TABLE `responds` (
  `id` int(11) UNSIGNED NOT NULL,
  `interview_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `info_respond` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `date_plan` date DEFAULT NULL,
  `place_interview` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `responds`
--

INSERT INTO `responds` (`id`, `interview_id`, `name`, `info_respond`, `email`, `date_plan`, `place_interview`) VALUES
(1, 1, 'Анютина Анна Николаевна', 'Данные респондента', 'anan@mail.ru', '2020-02-22', 'Место проведения Место проведения Место проведения'),
(2, 1, 'Антонов Антон Петрович', 'Данные респондента', 'antonov@mail.com', '2020-02-22', 'Место проведения Место проведения Место проведения'),
(3, 1, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru', '2020-02-14', 'Место проведения Место проведения Место проведения'),
(4, 1, 'Петров Петр Антонович', 'Данные респондента', 'petrov@mail.com', '2020-02-19', 'Место проведения Место проведения Место проведения'),
(5, 1, 'Попов Игорь Игоревич', 'Данные респондента', 'popov@mail.com', '2020-03-02', 'Место проведения Место проведения Место проведения'),
(6, 2, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru', '2020-02-27', 'Место проведения Место проведения Место проведения'),
(7, 2, 'Борисов Дмитрий Николаевич', 'Данные респондента', 'borisov@rambler.ru', '2020-02-19', 'Место проведения Место проведения Место проведения'),
(8, 2, 'Анютина Анна Николаевна', 'Данные респондента', 'anan@mail.ru', '2020-02-12', 'Место проведения Место проведения Место проведения'),
(9, 2, 'Респондент 4', '', '', NULL, ''),
(10, 2, 'Респондент 5', '', '', NULL, '');

-- --------------------------------------------------------

--
-- Структура таблицы `responds_confirm`
--

CREATE TABLE `responds_confirm` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_problem_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `info_respond` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `responds_confirm`
--

INSERT INTO `responds_confirm` (`id`, `confirm_problem_id`, `name`, `info_respond`, `email`) VALUES
(1, 1, 'Анютина Анна Николаевна', 'Данные респондента', 'anan@mail.ru'),
(2, 1, 'Антонов Антон Петрович', 'Данные респондента', 'antonov@mail.com'),
(3, 1, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru'),
(4, 2, 'Анютина Анна Николаевна', 'Данные респондента', 'anan@mail.ru'),
(5, 2, 'Антонов Антон Петрович', 'Данные респондента', 'antonov@mail.com'),
(6, 2, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru'),
(7, 3, 'Анютина Анна Николаевна', 'Данные респондента', 'anan@mail.ru'),
(8, 3, 'Антонов Антон Петрович', 'Данные респондента', 'antonov@mail.com'),
(9, 3, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru'),
(10, 4, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru'),
(11, 4, 'Борисов Дмитрий Николаевич', 'Данные респондента', 'borisov@rambler.ru'),
(12, 4, 'Анютина Анна Николаевна', 'Данные респондента', 'anan@mail.ru');

-- --------------------------------------------------------

--
-- Структура таблицы `responds_gcp`
--

CREATE TABLE `responds_gcp` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_gcp_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `info_respond` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `responds_gcp`
--

INSERT INTO `responds_gcp` (`id`, `confirm_gcp_id`, `name`, `info_respond`, `email`) VALUES
(1, 1, 'Анютина Анна Николаевна', 'Данные респондента', 'anan@mail.ru'),
(2, 1, 'Антонов Антон Петрович', 'Данные респондента', 'antonov@mail.com'),
(3, 1, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru'),
(4, 2, 'Анютина Анна Николаевна', 'Данные респондента', 'anan@mail.ru'),
(5, 2, 'Антонов Антон Петрович', 'Данные респондента', 'antonov@mail.com'),
(6, 2, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru'),
(7, 3, 'Анютина Анна Николаевна', 'Данные респондента', 'anan@mail.ru'),
(8, 3, 'Антонов Антон Петрович', 'Данные респондента', 'antonov@mail.com');

-- --------------------------------------------------------

--
-- Структура таблицы `responds_mvp`
--

CREATE TABLE `responds_mvp` (
  `id` int(11) UNSIGNED NOT NULL,
  `confirm_mvp_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `info_respond` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `responds_mvp`
--

INSERT INTO `responds_mvp` (`id`, `confirm_mvp_id`, `name`, `info_respond`, `email`) VALUES
(1, 1, 'Анютина Анна Николаевна', 'Данные респондента', 'anan@mail.ru'),
(2, 1, 'Антонов Антон Петрович', 'Данные респондента', 'antonov@mail.com'),
(3, 1, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru'),
(4, 2, 'Анютина Анна Николаевна', 'Данные респондента', 'anan@mail.ru'),
(5, 2, 'Антонов Антон Петрович', 'Данные респондента', 'antonov@mail.com'),
(6, 2, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru'),
(7, 3, 'Анютина Анна Николаевна', 'Данные респондента', 'anan@mail.ru'),
(8, 3, 'Антонов Антон Петрович', 'Данные респондента', 'antonov@mail.com'),
(9, 3, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru');

-- --------------------------------------------------------

--
-- Структура таблицы `segments`
--

CREATE TABLE `segments` (
  `id` int(11) UNSIGNED NOT NULL,
  `project_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `field_of_activity` varchar(255) DEFAULT NULL,
  `sort_of_activity` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `income` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `market_volume` int(11) DEFAULT NULL,
  `add_info` text,
  `creat_date` date DEFAULT NULL,
  `plan_gps` date DEFAULT NULL,
  `fact_gps` date DEFAULT NULL,
  `plan_ps` date DEFAULT NULL,
  `fact_ps` date DEFAULT NULL,
  `plan_dev_gcp` date DEFAULT NULL,
  `fact_dev_gcp` date DEFAULT NULL,
  `plan_gcp` date DEFAULT NULL,
  `fact_gcp` date DEFAULT NULL,
  `plan_dev_gmvp` date DEFAULT NULL,
  `fact_dev_gmvp` date DEFAULT NULL,
  `plan_gmvp` date DEFAULT NULL,
  `fact_gmvp` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `segments`
--

INSERT INTO `segments` (`id`, `project_id`, `name`, `field_of_activity`, `sort_of_activity`, `age`, `income`, `quantity`, `market_volume`, `add_info`, `creat_date`, `plan_gps`, `fact_gps`, `plan_ps`, `fact_ps`, `plan_dev_gcp`, `fact_dev_gcp`, `plan_gcp`, `fact_gcp`, `plan_dev_gmvp`, `fact_dev_gmvp`, `plan_gmvp`, `fact_gmvp`) VALUES
(1, 1, 'Сегмент 1', 'Сфера деятельности потребителя', 'Род деятельности потребителя', 34, 500000, 600000, 666333000, '', '2020-02-23', '2020-03-24', '2020-02-25', '2020-04-23', '2020-02-23', '2020-05-23', '2020-02-27', '2020-06-22', '2020-02-23', '2020-07-22', '2020-02-26', '2020-08-21', '2020-02-23'),
(2, 1, 'Сегмент 2', 'Сфера деятельности потребителя', 'Род деятельности потребителя', 45, 450000, 3500, 1000000000, '', '2020-02-26', '2020-03-27', '2020-02-26', '2020-04-26', '2020-02-27', '2020-05-26', NULL, '2020-06-25', NULL, '2020-07-25', NULL, '2020-08-24', NULL),
(3, 1, 'Сегмент 3', 'Сфера деятельности потребителя', 'Род деятельности потребителя', 56, 458888, 45645, 235245, '', '2020-02-27', '2020-03-28', NULL, '2020-04-27', NULL, '2020-05-27', NULL, '2020-06-26', NULL, '2020-07-26', NULL, '2020-08-25', NULL),
(4, 2, 'Сегмент 1', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 2, 'Сегмент 2', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 3, 'Сегмент 1', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
  `auth_key` varchar(255) DEFAULT NULL,
  `secret_key` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `status` smallint(6) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `second_name`, `first_name`, `middle_name`, `telephone`, `email`, `username`, `password_hash`, `auth_key`, `secret_key`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Иванов', 'Иван', 'Иванович', '+7(999)99-99-99', 'ivanov@mail.com', 'IvanoV', '$2y$13$AXM0v8mZAVuhZDJPLLJOWOMvwURNqrqDNRAAFF5p1eVmQdph2n7z6', '32hRL8j7MkkANX9mIy7vYPhsxBuO5JD8', NULL, 'user', 10, 1582408272, 1583353943),
(2, 'Карпов', 'Антон', 'Петрович', '+7(999)99-99-99', 'karpov@mail.com', 'KarpoV', '$2y$13$9wx5Aioj8NwWFowihANgGujgTou5tEXiZo9kbZFo3dEkUGc2SbHrS', '32bDlnhlVI0hQocLAZJn4oSKxUiliCp8', NULL, 'user', 10, 1582410889, 1582410889),
(3, 'Латухин', 'Алексей', 'Валерьевич', '+7(999)99-99-99', 'aleksejj.latukhin@rambler.ru', 'Latukhin', '$2y$13$x.E/ThKjk0nG/bsyp3zSy.rxf/x8nfRj7l0FZWHbWJFERdPY2AqRa', 'XB0l-XVyL4wgD8FlSXGTMQO6ctda7fVx', NULL, 'user', 10, 1582533894, 1582534277);

--
-- Индексы сохранённых таблиц
--

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
-- Индексы таблицы `desc_interview`
--
ALTER TABLE `desc_interview`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `desc_interview_confirm`
--
ALTER TABLE `desc_interview_confirm`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `desc_interview_gcp`
--
ALTER TABLE `desc_interview_gcp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `desc_interview_mvp`
--
ALTER TABLE `desc_interview_mvp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `feedback_expert`
--
ALTER TABLE `feedback_expert`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `feedback_expert_confirm`
--
ALTER TABLE `feedback_expert_confirm`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `feedback_expert_gcp`
--
ALTER TABLE `feedback_expert_gcp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `feedback_expert_mvp`
--
ALTER TABLE `feedback_expert_mvp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `gcp`
--
ALTER TABLE `gcp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `generation_problem`
--
ALTER TABLE `generation_problem`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `interview`
--
ALTER TABLE `interview`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mvp`
--
ALTER TABLE `mvp`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pre_files`
--
ALTER TABLE `pre_files`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `responds`
--
ALTER TABLE `responds`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `responds_confirm`
--
ALTER TABLE `responds_confirm`
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
-- Индексы таблицы `segments`
--
ALTER TABLE `segments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `business_model`
--
ALTER TABLE `business_model`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `confirm_gcp`
--
ALTER TABLE `confirm_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `confirm_mvp`
--
ALTER TABLE `confirm_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `confirm_problem`
--
ALTER TABLE `confirm_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `desc_interview`
--
ALTER TABLE `desc_interview`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `desc_interview_confirm`
--
ALTER TABLE `desc_interview_confirm`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `desc_interview_gcp`
--
ALTER TABLE `desc_interview_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `desc_interview_mvp`
--
ALTER TABLE `desc_interview_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `feedback_expert`
--
ALTER TABLE `feedback_expert`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `feedback_expert_confirm`
--
ALTER TABLE `feedback_expert_confirm`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `feedback_expert_gcp`
--
ALTER TABLE `feedback_expert_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `feedback_expert_mvp`
--
ALTER TABLE `feedback_expert_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `gcp`
--
ALTER TABLE `gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `generation_problem`
--
ALTER TABLE `generation_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `interview`
--
ALTER TABLE `interview`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `mvp`
--
ALTER TABLE `mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `pre_files`
--
ALTER TABLE `pre_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT для таблицы `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `responds`
--
ALTER TABLE `responds`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `responds_confirm`
--
ALTER TABLE `responds_confirm`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `responds_gcp`
--
ALTER TABLE `responds_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `responds_mvp`
--
ALTER TABLE `responds_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `segments`
--
ALTER TABLE `segments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
