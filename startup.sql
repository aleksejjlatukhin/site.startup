-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 06 2020 г., 10:26
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
(1, 'Иванов', 'Иван', 'Иванович', '+7(999)99-99-99', 'ivanov@mail.com', 'IvanoV', '$2y$13$AXM0v8mZAVuhZDJPLLJOWOMvwURNqrqDNRAAFF5p1eVmQdph2n7z6', '32hRL8j7MkkANX9mIy7vYPhsxBuO5JD8', NULL, 'user', 10, 1582408272, 1583451134),
(2, 'Карпов', 'Антон', 'Петрович', '+7(999)99-99-99', 'karpov@mail.com', 'KarpoV', '$2y$13$9wx5Aioj8NwWFowihANgGujgTou5tEXiZo9kbZFo3dEkUGc2SbHrS', '32bDlnhlVI0hQocLAZJn4oSKxUiliCp8', NULL, 'user', 10, 1582410889, 1582410889),
(3, 'Латухин', 'Алексей', 'Валерьевич', '+7(999)99-99-99', 'aleksejj.latukhin@rambler.ru', 'Latukhin', '$2y$13$oXSkbt/1tnu5SJdi3i2P.OGjbpWgZaG14aME3SzS/WFQbG5irNZn2', 'XB0l-XVyL4wgD8FlSXGTMQO6ctda7fVx', NULL, 'user', 10, 1582533894, 1583428479);

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `business_model`
--
ALTER TABLE `business_model`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `confirm_gcp`
--
ALTER TABLE `confirm_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `confirm_mvp`
--
ALTER TABLE `confirm_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `confirm_problem`
--
ALTER TABLE `confirm_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `desc_interview`
--
ALTER TABLE `desc_interview`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `desc_interview_confirm`
--
ALTER TABLE `desc_interview_confirm`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `desc_interview_gcp`
--
ALTER TABLE `desc_interview_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `desc_interview_mvp`
--
ALTER TABLE `desc_interview_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `generation_problem`
--
ALTER TABLE `generation_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `interview`
--
ALTER TABLE `interview`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `mvp`
--
ALTER TABLE `mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `pre_files`
--
ALTER TABLE `pre_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `responds`
--
ALTER TABLE `responds`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `responds_confirm`
--
ALTER TABLE `responds_confirm`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `responds_gcp`
--
ALTER TABLE `responds_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `responds_mvp`
--
ALTER TABLE `responds_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `segments`
--
ALTER TABLE `segments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
