-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 15 2020 г., 01:32
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
  `position` varchar(255) NOT NULL,
  `feedback_file` varchar(255) NOT NULL,
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
  `file_name` varchar(255) NOT NULL
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
  `fio` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `auth_key` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `fio`, `telephone`, `email`, `username`, `password`, `auth_key`, `role`) VALUES
(2, 'Петров Петр Петрович', '22222222', 'Petrov@mail.com', 'Petrov', '$2y$13$..03.ntfn22PYJL8AwxP0uGZiVm51GqdUf9HGEKHBZkFpPo7kIuEe', 'D0ghG21D2hbH9iGUl0G8GlT8oaWvNA-C', 'user'),
(5, 'Иванов Иван Иванович', '2202020', 'ivanov@mail.com', 'Ivanov', '$2y$13$CxAHKuqoYDsF2J0wzHtlAuh17SI.RU5.BrT/DviYKzvlcJAPCzIh6', 'LDQEwcJPcv9XfbtoLMjveprD61ipkU_T', 'user'),
(7, 'Васечкин', '333', 'Vasya@mail.com', 'Vasya', '$2y$13$7Cs/RM56jaEgRzq8n2RywO1078WeKOOci5a/qg/OAzGqy09qcSSTi', 'sgaNt8xtlReqzE19U8aaV8yZgACQ3Bkg', 'user'),
(8, 'Сидоров', '243243534', 'Sidorov@mail.com', 'Sidorov', '$2y$13$DOrU/gG3krdQg.JydZ489eMLDd/7kmAmHAVoZ83XCNm3qQiRCEDIC', '-FbXSF0QvmGt5BpsFp3f3aOUiVr-unWG', 'user'),
(9, 'Пронин Иван Петрович', '222222', 'Pronin@mai.com', 'Pronin', '$2y$13$FIsjZQJB70z0KcsqxNHKwOwhlSdfQS9NcAuSOtYrYxG.wevlT3Lqu', 'qMmUVxZVHjUQ7mGd7ESg2NkKbh7ztbGW', 'user'),
(10, 'Федотов', '444444', 'mihailionf@gmail.com', 'Mike', '$2y$13$/HjEEjWYWBGHA5VJQ/09heLFVk/HP263MSjs8qV..uphjU06oCvJa', 'aBz7QuJuiVkYSE2M5IXFHOiTFdA887YZ', 'user'),
(11, 'Никитин Алексей Васильевич', '2202020', 'Nikitin@mail.ru', 'Nikitin', '$2y$13$4R0CIekfOWchpW8Wn9mf4.Fmx9SCc9jE3rI9lzhXN5PYtq6SuVLiK', '2VSUWLOporCd1q15B0T2qk1vbvE-cQZd', 'user');

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT для таблицы `business_model`
--
ALTER TABLE `business_model`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `confirm_gcp`
--
ALTER TABLE `confirm_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `confirm_mvp`
--
ALTER TABLE `confirm_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `confirm_problem`
--
ALTER TABLE `confirm_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `desc_interview`
--
ALTER TABLE `desc_interview`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT для таблицы `desc_interview_confirm`
--
ALTER TABLE `desc_interview_confirm`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT для таблицы `desc_interview_gcp`
--
ALTER TABLE `desc_interview_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT для таблицы `desc_interview_mvp`
--
ALTER TABLE `desc_interview_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT для таблицы `feedback_expert`
--
ALTER TABLE `feedback_expert`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `feedback_expert_confirm`
--
ALTER TABLE `feedback_expert_confirm`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `feedback_expert_gcp`
--
ALTER TABLE `feedback_expert_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `feedback_expert_mvp`
--
ALTER TABLE `feedback_expert_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `gcp`
--
ALTER TABLE `gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `generation_problem`
--
ALTER TABLE `generation_problem`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT для таблицы `interview`
--
ALTER TABLE `interview`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `mvp`
--
ALTER TABLE `mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT для таблицы `pre_files`
--
ALTER TABLE `pre_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT для таблицы `responds`
--
ALTER TABLE `responds`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT для таблицы `responds_confirm`
--
ALTER TABLE `responds_confirm`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT для таблицы `responds_gcp`
--
ALTER TABLE `responds_gcp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT для таблицы `responds_mvp`
--
ALTER TABLE `responds_mvp`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT для таблицы `segments`
--
ALTER TABLE `segments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
