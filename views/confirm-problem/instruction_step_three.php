<?php

use yii\helpers\Html;

$this->registerCssFile('@web/css/methodological-guide-style.css');

?>

<div class="methodological-guide">

    <!--5. Этап 4. Подтверждение гипотез проблем сегментов-->
    <h3 class="header-text" id="confirmation_of_segment_problem_hypotheses"><span>Этап 4. Подтверждение гипотез проблем сегментов</span></h3>

    <div class="container-list">

        <div class="simple-block">
            <p>
                <span>Задача:</span>
                Подтвердить или опровергнуть выдвинутые гипотезы проблем сегментов.
            </p>
            <p>
                <span>Результат:</span>
                Получение результата о подтверждении выдвинутых гипотез, внесенного в форму на платформе Spaccel.ru.
            </p>
        </div>

        <br>

        <p class="bold blue">Общий материал перед этапом.</p>

        <div class="bold">Подтверждение гипотез проблем сегментов выполняется с помощью проблемного интервью (ПИ).</div>

        <p>
            <span class="bold">Цель ПИ</span>
            &#8212; подтвердить или опровергнуть гипотезы, которые важны для дальнейших действий по продукту. Ниже для лучшего восприятия приведена таблица с примерами гипотез для проверки:
        </p>

        <div style="width: 100%; overflow: auto;">
            <table>
                <tr>
                    <th>Предположение</th>
                    <th>Действие для проверки</th>
                    <th>Метрика результата</th>
                </tr>
                <tr>
                    <td></td>
                    <td>Что нужно сделать, чтобы проверить гипотезу?</td>
                    <td>Какой результат покажет, что гипотеза верна?</td>
                </tr>
                <tr>
                    <td>Средний класс и люди с высоким достатком хотят питаться свежими овощами, но не доверяют качеству продуктов в магазинах.</td>
                    <td>Задать вопросы: из чего состоит рацион, где они покупают овощи, как они их выбирают, устраивает ли качество</td>
                    <td>Больше 70% опрошенных выразит неудовлетворенность качеством продуктов в магазинах</td>
                </tr>
                <tr>
                    <td>Пользователям сложно определиться с выбором, когда по заданным параметрам они получают слишком много предложений</td>
                    <td>Задать вопросы о последнем опыте подбора жилья, каким образом они это делали, как проходил процесс подбора, из какого количества вариантов выбирали, сколько времени это заняло, что было важно при выборе, и какие трудности возникли в процессе.</td>
                    <td>Больше 30% опрошенных потратили на выбор жилья больше трех часов</td>
                </tr>
                <tr>
                    <td>В позиционировании приложения для учета финансов нужно делать упор на гибкость настройки</td>
                    <td>Задать вопросы о том, как пользователи ведут личный бюджет: каким инструментом пользуются сейчас и какие инструменты пробовали, по каким критериям подбирали инструмент, как проходит процесс учета финансов, какую информацию анализируют.</td>
                    <td>Больше 80% опрошенных ответят, что гибкость настройки для них является ключевым фактором выбора инструмента.</td>
                </tr>
            </table>
        </div>

        <p class="mt-10">Их подтверждение или опровержение будет результатом проблемных интервью.</p>

    </div>

    <!--5.3. Шаг 3. Проведение непосредственно интервью-->
    <h3 class="header-text" id="stage_4_step_3"><span>Шаг 3. Проведение непосредственно интервью</span></h3>

    <div class="container-list">

        <div class="bold">Задача:</div>
        <div class="container-text">
            <ul>
                <li class="pl-15">Найти представителей сегмента в количестве, заданном в Шаге 3, Этапа 4, договориться на интервью с ними.</li>
                <li class="pl-15">Провести интервью, используя вопросы, сформулированные в Шаге 2, Этапа 4 в соответствии с выбранным графиком.</li>
                <li class="pl-15">Занести результаты интервью в формы Spaccel.ru на каждого респондента.</li>
                <li class="pl-15">Провести анализ скриптов ответов и сделать выводы о прохождении или провале теста на подтверждение проблем, с которыми сталкивается респондент и внести/отобразить выводы в советующих формах.</li>
            </ul>
        </div>

        <div class="simple-block">
            <p>
                <span>Результат:</span>
                Заполненная форма (шаг 3, Этап 4) на платформе Spaccel.ru.
            </p>
        </div>

        <p>
            Задавайте больше открытых вопросов. Ответы «да», «нет», «иногда» и подобные дают очень мало информации. Чтобы получить больше
            информации, задавайте открытые вопросы – такие, на которые собеседнику придется давать развернутый ответ.
        </p>

        <p>
            <span class="bold">Пример</span> проблемного интервью, в котором следует обратить внимание на то, как составлялись вопросы, какие фиксировались
            ответы: <?= Html::a('https://vc.ru/growth/102500-problemnye-polzovatelskie-intervyu-kak-ne-nado-delat', 'https://vc.ru/growth/102500-problemnye-polzovatelskie-intervyu-kak-ne-nado-delat', ['target' => '_blank']);?>
        </p>

        <div class="bold">Анализ полученных анкет.</div>
        <div>Не нужно заигрываться. Когда же остановиться?</div>

        <div class="container-text">
            <ol>
                <li><span class="pl-15">Когда респондент вам дает предсказуемый ответ.</span></li>
                <li><span class="pl-15">50 интервью – это часто максимум. Больше бессмысленно.</span></li>
                <li><span class="pl-15">Бывает, все отвечают одинаково - это неплохо. Но бывает, что все отвечают по-разному. Это означает, что вы задаете не те вопросы или спрашиваете не тех людей. В этом случае лучше поменять или сузить сегмент, тогда возможно попадете в общие проблемы. Необходимо конкретизировать вопросы, сконцентрироваться на проблеме, которая, как вы знаете, у представителя сегмента должна быть. Тогда вы получите, возможно, более интересные ответы. Либо сделать вывод, что здесь нет проблемы и отказаться от разработки продукта для этого сегмента.</span></li>
                <li><span class="pl-15">Самая большая ошибка, что люди подсознательно подгоняют ответы под те, которые у них уже в голове есть.</span></li>
            </ol>
        </div>

        <div class="bold">Как избежать этой ошибки:</div>

        <div class="container-text">
            <ol>
                <li><span class="pl-15">Критически посмотреть на ответы, которые вам дали.</span></li>
                <li><span class="pl-15">Пусть посторонний человек посмотрит на эти ответы. Это может быть эксперт, который может увидеть, где подгоняли.</span></li>
            </ol>
        </div>

        <p class="bold blue">
            Проанализируйте еще раз полученные данные респондентов. Если результаты ответов отвечают требованиям, заданным вами в начале
            этапа, значит, вы нашли и подтвердили значимость проблемы и можете переходить к следующему этапу. Если нет, то переформулируйте
            гипотезу снова (pivot) и проделайте процедуру подтверждения еще раз.
        </p>

    </div>

</div>
