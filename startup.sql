-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 13 2020 г., 13:39
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
(1, 1, 'Иванов Иван Иванович', 'Главный', '');

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
(1, 1, 4643634, 'Род деятельности потребителя', 'Взаимоотношения с клиентами', 'Ключевые партнеры', 'Ключевые партнеры', 'Ключевые партнеры', 'Ключевые партнеры', 'Ключевые партнеры');

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
(1, 1, 2, 2),
(2, 3, 1, 1),
(3, 4, 1, 1);

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
(1, 1, 2, 2);

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
(1, 1, 2, 2),
(2, 2, 2, 2),
(3, 3, 2, 1);

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
(1, 1, '2020-03-06', 'Материалы полученные во время интервью', NULL, NULL, 'Вывод', '1'),
(2, 2, '2020-03-06', 'Материалы полученные во время интервью', NULL, NULL, 'Вывод', '1');

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
(1, 1, '2020-03-06', '1'),
(2, 2, '2020-03-06', '1'),
(3, 5, '2020-03-07', '1');

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
(1, 1, '2020-03-06', '1'),
(2, 2, '2020-03-06', '1'),
(3, 4, '2020-03-07', '1');

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
(1, 1, '2020-03-06', 2),
(2, 2, '2020-03-06', 2);

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
(1, 1, 'ГЦП 1', 'Продукт 1', 'выгода', 'продукт 2', 'Наш продукт \"продукт 1\" помогает \"сегмент 1\", который хочет удовлетворить проблему \"напишите описание гипотезы проблемы сегмента\", избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, \"выгода\", в отличии от \"продукт 2\".', '2020-03-06', '2020-03-06 12:29:20', '2020-03-06', '2020-03-06 12:30:11', 1),
(2, 1, 'ГЦП 2', 'Interbink', 'выгода', 'продукт2', 'Наш продукт \"interbink\" помогает \"сегмент 1\", который хочет удовлетворить проблему \"напишите описание гипотезы проблемы сегмента\", избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, \"выгода\", в отличии от \"продукт2\".', '2020-03-06', '2020-03-06 12:29:37', NULL, NULL, NULL),
(3, 3, 'ГЦП 1', 'Продукт 1', 'выгода', 'продукт 2', 'Наш продукт \"продукт 1\" помогает \"сегмент 1\", который хочет удовлетворить проблему \"напишите описание гипотезы проблемы сегмента\", избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, \"выгода\", в отличии от \"продукт 2\".', '2020-03-07', '2020-03-07 17:16:33', NULL, NULL, 0),
(4, 3, 'ГЦП 2', 'Продукт 1', 'выгода', 'продукт 2', 'Наш продукт \"продукт 1\" помогает \"сегмент 1\", который хочет удовлетворить проблему \"напишите описание гипотезы проблемы сегмента\", избавиться от проблемы(или снизить её) и позволяет получить выгоду в виде, \"выгода\", в отличии от \"продукт 2\".', '2020-03-07', '2020-03-07 17:16:57', '2020-03-07', '2020-03-07 17:18:20', 1);

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
(1, 1, 'ГПС 1', 'Напишите описание гипотезы проблемы сегмента', '2020-03-06', '2020-03-06', '2020-03-06 12:29:02', 1),
(2, 1, 'ГПС 2', 'Напишите описание гипотезы проблемы сегмента', '2020-03-06', NULL, NULL, 0),
(3, 1, 'ГПС 3', 'Напишите описание гипотезы проблемы сегмента', '2020-03-07', '2020-03-07', '2020-03-07 17:15:27', 1);

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
(1, 1, 4, 2, 'Приветствие в начале встречи', 'Представление интервьюера', 'Почему мне интересно', '1', '1', '1', '1', '1', '1', '1', '1');

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
(1, 1, 'ГMVP 1', 'Макет', '2020-03-06', '2020-03-06 12:30:18', '2020-03-06', '2020-03-06 12:31:06', 1),
(2, 1, 'ГMVP 2', 'Презентация', '2020-03-06', '2020-03-06 12:30:26', NULL, NULL, NULL),
(3, 3, 'ГMVP 1', 'Макет', '2020-03-07', '2020-03-07 17:18:27', NULL, NULL, NULL),
(4, 3, 'ГMVP 2', 'презентация', '2020-03-07', '2020-03-07 17:18:34', NULL, NULL, NULL);

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
(1, 1, '200220Описание этапов для 1 стр_3.docx', 'M2wOJGQvSPzXYTG.docx'),
(2, 1, 'Бизнес требования для сервиса Акселератор.docx', 'iTqi3FHf5E7kbvK.docx'),
(3, 1, 'dogovor_gruppy 2020.doc', '5ACvxtCtTKIe-bd.doc');

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
(1, 1, '2020-03-06', '2020-03-07', 'Проект 1', 'Проект 1', '', '', '', NULL, '', '', '', '', '', NULL, '', '', NULL, NULL, NULL, '');

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
(8, 1, 'Есть ли деньги на решение сложившейся ситуации сейчас?', '1');

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
(1, 1, 'Попов Игорь Игоревич', 'Данные респондента', 'popov@mail.com', '2020-03-04', 'Место проведения Место проведения Место проведения'),
(2, 1, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru', '2020-03-05', 'Место проведения Место проведения Место проведения'),
(3, 1, 'Респондент 3', '', '', NULL, ''),
(4, 1, 'Респондент 4', '', '', NULL, '');

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
(1, 1, 'Попов Игорь Игоревич', 'Данные респондента', 'popov@mail.com'),
(2, 1, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru'),
(3, 2, 'Попов Игорь Игоревич', 'Данные респондента', 'popov@mail.com'),
(4, 2, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru'),
(5, 3, 'Попов Игорь Игоревич', 'Данные респондента', 'popov@mail.com'),
(6, 3, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru');

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
(1, 1, 'Попов Игорь Игоревич', 'Данные респондента', 'popov@mail.com'),
(2, 1, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru'),
(3, 2, 'Попов Игорь Игоревич', 'Данные респондента', 'popov@mail.com'),
(4, 3, 'Попов Игорь Игоревич', 'Данные респондента', 'popov@mail.com');

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
(1, 1, 'Попов Игорь Игоревич', 'Данные респондента', 'popov@mail.com'),
(2, 1, 'Карлов Виктор Иванович', 'Данные респондента', 'karlov@mail.ru');

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
(1, 1, 'Сегмент 1', 'Сфера деятельности потребителя', 'Род деятельности потребителя', 56, 46464, 4643634, 346346, '', '2020-03-06', '2020-04-05', '2020-03-07', '2020-05-05', '2020-03-06', '2020-06-04', '2020-03-07', '2020-07-04', '2020-03-06', '2020-08-03', '2020-03-07', '2020-09-02', '2020-03-06'),
(2, 1, 'Сегмент 2', '', '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
  `avatar_image` varchar(255) NOT NULL,
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

INSERT INTO `user` (`id`, `second_name`, `first_name`, `middle_name`, `telephone`, `email`, `username`, `password_hash`, `avatar_image`, `auth_key`, `secret_key`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Иванов', 'Иван', 'Иванович', '+7(999)99-99-99', 'ivanov@mail.com', 'IvanoV', '$2y$13$zndk8lQ0OmizfSRa1xkUwuAikBaPngYMVt0cIypNdMxechCXJBLYK', '/images/avatar/default.jpg', '32hRL8j7MkkANX9mIy7vYPhsxBuO5JD8', NULL, 'user', 10, 1582408272, 1583960676),
(8, 'Латухин', 'Алексей', 'Валерьевич', '+7(999)99-99-99', 'aleksejj.latukhin@rambler.ru', 'Latukhin', '$2y$13$tgy4EYw0.LGFXJfVFZYyvexkY0kPmfWcWdn9OPfCG9tgRW5Vd8rsS', '/images/avatar/default.jpg', 'IJn3g83GXf9aCWjZFelkBj3DaVP8Ixcf', NULL, 'user', 10, 1583760631, 1583760631),
(9, 'Карпов', 'Антон', 'Петрович', '+7(999)99-99-99', 'karpov@mail.com', 'karpov', '$2y$13$aDvsycNgzvtoTq7.fLGI7ekA.HLbR93pCudDUsidh.qEl.fwf7xBG', '/images/avatar/default.jpg', '8ttyGMNTTQgVTM-5vPftdvK1Y7LVLkM1', NULL, 'user', 10, 1583859778, 1583859778);

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `confirm_problem`
--
ALTER TABLE `confirm_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `desc_interview`
--
ALTER TABLE `desc_interview`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `desc_interview_confirm`
--
ALTER TABLE `desc_interview_confirm`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `desc_interview_gcp`
--
ALTER TABLE `desc_interview_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `desc_interview_mvp`
--
ALTER TABLE `desc_interview_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `feedback_expert`
--
ALTER TABLE `feedback_expert`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `feedback_expert_confirm`
--
ALTER TABLE `feedback_expert_confirm`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `feedback_expert_gcp`
--
ALTER TABLE `feedback_expert_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `feedback_expert_mvp`
--
ALTER TABLE `feedback_expert_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `gcp`
--
ALTER TABLE `gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `generation_problem`
--
ALTER TABLE `generation_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `interview`
--
ALTER TABLE `interview`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `mvp`
--
ALTER TABLE `mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `pre_files`
--
ALTER TABLE `pre_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `responds`
--
ALTER TABLE `responds`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `responds_confirm`
--
ALTER TABLE `responds_confirm`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `responds_gcp`
--
ALTER TABLE `responds_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `responds_mvp`
--
ALTER TABLE `responds_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `segments`
--
ALTER TABLE `segments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
