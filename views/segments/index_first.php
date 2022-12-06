<?php

use app\models\Projects;
use yii\helpers\Html;
use app\models\User;
use yii\helpers\Url;

$this->title = 'Генерация гипотез целевых сегментов';
$this->registerCssFile('@web/css/segments-index-style.css');
$this->registerCssFile('@web/css/methodological-guide-style.css');

/**
 * @var Projects $project
 */

?>

<div class="segments-index">

    <?php if (!User::isUserAdmin(Yii::$app->user->identity['username'])) : ?>

        <div class="methodological-guide">

            <div class="header_hypothesis_first_index">Генерация гипотез целевых сегментов</div>

            <div class="header-title-index-mobile">
                <div style="overflow: hidden; max-width: 70%;">Проект: <?= $project->getProjectName() ?></div>
                <div class="buttons-project-menu-mobile" style="position: absolute; right: 20px; top: 5px;">
                    <?= Html::img('@web/images/icons/icon-four-white-squares.png', ['class' => 'open-project-menu-mobile', 'style' => ['width' => '30px']]) ?>
                    <?= Html::img('@web/images/icons/icon-white-cross.png', ['class' => 'close-project-menu-mobile', 'style' => ['width' => '30px', 'display' => 'none']]) ?>
                </div>
            </div>

            <div class="project-menu-mobile">
                <div class="project_buttons_mobile">

                    <?= Html::a('Сводная таблица', ['/projects/result-mobile', 'id' => $project->getId()], [
                        'class' => 'btn btn-default',
                        'style' => [
                            'display' => 'flex',
                            'width' => '47%',
                            'height' => '36px',
                            'background' => '#7F9FC5',
                            'color' => '#FFFFFF',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'border-radius' => '0',
                            'border' => '1px solid #ffffff',
                            'font-size' => '18px',
                            'margin' => '10px 1% 0 2%',
                        ],
                    ]) ?>

                    <?= Html::a('Трэкшн карта', ['/projects/roadmap-mobile', 'id' => $project->getId()], [
                        'class' => 'btn btn-default',
                        'style' => [
                            'display' => 'flex',
                            'width' => '47%',
                            'height' => '36px',
                            'background' => '#7F9FC5',
                            'color' => '#FFFFFF',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'border-radius' => '0',
                            'border' => '1px solid #ffffff',
                            'font-size' => '18px',
                            'margin' => '10px 2% 0 1%',
                        ],
                    ]) ?>

                </div>

                <div class="project_buttons_mobile">

                    <?= Html::a('Протокол', ['/projects/report-mobile', 'id' => $project->getId()], [
                        'class' => 'btn btn-default',
                        'style' => [
                            'display' => 'flex',
                            'width' => '47%',
                            'height' => '36px',
                            'background' => '#7F9FC5',
                            'color' => '#FFFFFF',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'border-radius' => '0',
                            'border' => '1px solid #ffffff',
                            'font-size' => '18px',
                            'margin' => '10px 1% 10px 2%',
                        ],
                    ]) ?>

                    <?= Html::a('Презентация', ['/projects/presentation-mobile', 'id' => $project->getId()], [
                        'class' => 'btn btn-default',
                        'style' => [
                            'display' => 'flex',
                            'width' => '47%',
                            'height' => '36px',
                            'background' => '#7F9FC5',
                            'color' => '#FFFFFF',
                            'align-items' => 'center',
                            'justify-content' => 'center',
                            'border-radius' => '0',
                            'border' => '1px solid #ffffff',
                            'font-size' => '18px',
                            'margin' => '10px 2% 10px 1%',
                        ],
                    ]) ?>

                </div>
            </div>

            <div class="arrow_stages_project_mobile">
                <div class="item-stage active"></div>
                <div class="item-stage passive"></div>
                <div class="item-stage passive"></div>
                <div class="item-stage passive"></div>
                <div class="item-stage passive"></div>
                <div class="item-stage passive"></div>
                <div class="item-stage passive"></div>
                <div class="item-stage passive"></div>
                <div class="item-stage passive"></div>
            </div>

            <div class="arrow_links_router_mobile">
                <div class="arrow_link_router_mobile_left">
                    <?= Html::a(Html::img('@web/images/icons/arrow_left_active.png'),
                        Url::to(['/projects/index', 'id' => $project->getUserId()])) ?>
                </div>
                <div class="text-stage">1/9. Генерация гипотез целевых сегментов</div>
                <div class="arrow_link_router_mobile_right">
                    <?= Html::img('@web/images/icons/arrow_left_passive.png') ?>
                </div>
            </div>

            <div class="row desktop-pl-15 desktop-pr-15">
                <div class="col-md-12">
                    <?php if (User::isUserSimple(Yii::$app->user->identity['username'])) : ?>
                        <?=  Html::a( '<div class="new_hypothesis_link_block"><div>' . Html::img(['@web/images/icons/add_vector.png'], ['style' => ['width' => '35px']]) . '</div><div style="padding-left: 20px;">Добавить сегмент</div></div>', ['/segments/get-hypothesis-to-create', 'id' => $project->getId()],
                            ['id' => 'showHypothesisToCreate', 'class' => 'new_hypothesis_link_plus pull-left']
                        ) ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="container-list">

                <div class="bold">Этап 1.</div>
                <div class="bold">Генерация гипотез целевых сегментов</div>

                <div class="simple-block">
                    <p>
                        <span>Задача:</span>
                        Сформулировать несколько гипотез сегментов.
                    </p>
                    <p>
                        <span>Результат:</span>
                        Заполненная форма на платформе Spaccel.ru.
                    </p>
                </div>

                <p>
                    <span class="bold">Формулировка сегментов</span><br>
                    <span>
                    Представьте, что Вы продаёте шампунь. Конечно, он нужен всем, у кого есть волосы. Или ещё шире – тем, у кого есть голова,
                    так о какой тогда сегментации целевой аудитории можно говорить? Дело в том, что у кого-то сухие волосы, у кого-то жирные,
                    кто-то хочет придать им объём, кто-то вылечить секущиеся кончики. Важно предложить каждому подходящий именно ему продукт.
                    Вот для этого и нужно делать сегментирование целевой аудитории.
                </span>
                </p>

                <p>
                    <span class="bold">Сегментация</span>
                    &#8212; это разделение целевой аудитории или всего рынка на части по какому-либо критерию или процесс выявления определенных
                    групп потребителей, которые обладают близкими потребностями, похожим покупательским поведением и отличительными характеристиками.
                </p>

                <p>Например, описанием сегмента может быть такой текст: Блондинка, незамужняя, 35 лет, работающая секретарем, что-то еще.</p>
                <p>Сегментация начинается с выбора критериев, на основании которых будут выделяться сегменты рынка.</p>

                <br>

                <p class="bold">Рынок – B2C. Группы критериев сегментации</p>

                <ol>
                    <li><span>Географические критерии (территория, с которой компания планирует работать (город, регион, страна).</span></li>
                    <li><span>Социально-демографические критерии (возраст, социальный статус, уровень дохода, образование, пол и т.д.)</span></li>
                    <li><span>Поведенческие критерии (определенные ценности, образ жизни, потребности, интересы, критерии выбора, использование продукта и т.д.)</span></li>
                    <li><span>Ключевые признаки продукта.</span></li>
                </ol>

                <br>

                <p class="bold">Рынок – B2B. Группы критериев сегментации</p>

                <ol>
                    <li><span>Географические. Тот же набор критериев. </span></li>
                    <li><span>Описательные критерии (размер бизнеса, отрасль, количество сотрудников, позиционирование, финансовое положение и т.д.)</span></li>
                    <li><span>Поведенческие критерии (частота приобретения, собственность или аренда, кто является потребителем внутри компании, опыт использования продукта и т.д.)</span></li>
                </ol>

                <br>

                <p class="bold">Возможные подходы к сегментации рынка</p>

                <div class="container-text">
                    <div class="bold"><span>A.</span>Вариант 1. Сегментация по продукту.</div>
                    <div class="pl-25">Пример: рынок телевизоров.</div>
                </div>

                <div class="container-text">
                    <div class="bold"><span>B.</span>Вариант 2. Сегментация по преимуществам продукта.</div>
                    <div class="pl-25">Пример: рынок телевизоров с разрешением 8К и вогнутым экраном.</div>
                </div>

                <div class="container-text">
                    <div class="bold"><span>C.</span>Вариант 3. Сегментация по потребности.</div>
                    <div class="pl-25">Пример: болельщики футбольной команды, которые ходят на стадион.</div>
                </div>

                <br>

                <div class="bold pl-15">Примеры характеристик: Магазин, как продукт.</div>

                <table>
                    <tr>
                        <th>Потребительские характеристики</th>
                        <th>Технические характеристики</th>
                    </tr>
                    <tr>
                        <td>Магазин работает с утра до вечера</td>
                        <td>Время работы 7.00-23.00</td>
                    </tr>
                    <tr>
                        <td>Не бывает очередей в кассу</td>
                        <td>Очередь в кассу не более 5 минут</td>
                    </tr>
                    <tr>
                        <td>Всегда можно получить справочную информацию о товаре</td>
                        <td>Не менее 10 продавцов-консультантов всегда в торговом зале</td>
                    </tr>
                    <tr>
                        <td>Всегда свободная собственная автомобильная парковка у магазина</td>
                        <td>Парковка на 100 машиномест</td>
                    </tr>
                </table>

                <div class="container-text">

                    <p><span class="bold">Важно!</span> Характеристика – это всегда факт. Это или есть, или нет</p><br>
                    <p class="bold">Рекомендации по заполнению информации по сегментам – генерация сегментов.</p>
                    <p class="bold">
                        Старайтесь выбирать сначала не больше трех сегментов, которые вас интересуют с точки зрения платежеспособности,
                        возможной (по вашему мнению) востребованности будущих или уже имеющихся продуктов, разработанных с использованием
                        базовой технологии. В процессе выполнения следующего этапа - Этап 2 «Подтверждение гипотез целевых сегментов»,
                        вы сможете уточнить параметры сегментов и сгенерировать более точное описание целевых сегментов, которые вы будете
                        разрабатывать далее.
                    </p>

                    <div class="container-text">
                        <div class="flex">
                            <div>a.</div>
                            <div class="pl-15">
                                О рынке имеется скудная или не структурированная информация: когда вы только приступаете к разработке сегмента и,
                                возможно, у вас подобрана только предварительная рыночная информация о потребителях.
                            </div>
                        </div>
                    </div>

                    <p>
                        Действие: предварительно опишите гипотезу сегмента (файл «Данные сегмента») при помощи информации, которой вы
                        располагаете на текущий момент. Это будет ваше исходное описание гипотезы целевого сегмента. По результатам глубинного
                        интервью (или опроса фокус групп - Этап 2 «Подтверждение гипотез целевых сегментов») вы получаете более подробную
                        информацию о рынке и делаете вывод: правильно ли вы сформировали сегмент. Если по результатам получается, что между
                        респондентами мало что общего, то необходимо сформировать сегмент вновь. Новый сегмент добавьте в список сегментов.
                    </p>

                    <div class="container-text">
                        <div class="flex">
                            <div>b.</div>
                            <div class="pl-15">Рынок вам хорошо известен, и вы располагаете информацией о целевых сегментах в структурированном виде.</div>
                        </div>
                    </div>

                    <p>
                        В этом случае вы легко формулируете сегменты. По результатам глубинного интервью (или опроса фокус групп - Этап 2
                        «Подтверждение гипотез целевых сегментов») делаете уточнения и выделяете те детали рынка, проблемы, которые, на ваш взгляд,
                        интересные. И, соответственно, по ним продолжаете разработку сегментов и продукты.
                    </p>

                    <div class="container-text">
                        <div class="flex">
                            <div>c.</div>
                            <div class="pl-15">Получен твердый заказ от конкретного лица (физического или юридического).</div>
                        </div>
                    </div>

                    <p>
                        Это тот случай, когда вы «перепрыгиваете» этапы: определения сегмента, подтверждение, поиск проблемы, разработка ЦП и
                        его подтверждения. По каким-то причинам, признавая в вас или в вашей компании эксперта, специалиста в определенной сфере,
                        вы получили заказ от конкретного заказчика. Поэтому вы сразу приступаете к подготовке MVP или опытного образца,
                        или промышленного образца.
                    </p>

                    <p class="bold">
                        Таким образом, для того, чтобы найти свой рынок, независимо от: отношения к ИС, полноты информации о рынке необходимо
                        первоначальное (стартовое) описание сегмента, корректность описания которого вы будете проверять на последующем этапе.
                    </p>

                </div>

            </div>

        </div>

        <!--Модальные окна-->
        <?= $this->render('modal') ?>

    <?php else : ?>

        <div class="methodological-guide">

            <h3 class="header-text"><span>Генерация гипотез целевых сегментов</span></h3>

            <div class="container-list">

                <div class="simple-block">
                    <p>
                        <span>Задача:</span>
                        Проверить на соответствие рекомендациям заполненную форму <span>Сегмент.</span>
                    </p>
                    <p>
                        <span>Результат:</span>
                        Проектант получил необходимые рекомендации и точно понял требования методики spaccel.
                    </p>
                </div>

                <div class="bold">Рекомендовать проектантам:</div>
                <div class="container-text">
                    <ul>
                        <li class="pl-15">
                            Создавать собственную структуру описания сегментов, как они их себе представляют
                            на этот момент, и записать это в поле <span class="bold">Дополнительная информация.</span>
                        </li>
                        <li class="pl-15">
                            При описании покупательской способности избегать детализации цифр. Если при арифметическом
                            вычислении получается, например, цифра 5000003 руб. или 5989002 руб., то стоит округлять до
                            ближайшего порядка, например, 5000000 или 6000000 руб.
                        </li>
                        <li class="pl-15">
                            Использовать доступную и имеющуюся форсайтинговую информацию, т.е.
                            запросы предприятий, которых интересуют наработки в конкретных сферах деятельности.
                        </li>
                        <li class="pl-15">
                            Выбраны (формально) сегменты с небольшим количеством представителей и невысокая покупательская способность.
                            Например, общая покупательская способность 10 млн руб/год. Предложить рассмотреть другие, более масштабные сегменты.
                        </li>
                        <li class="pl-15">
                            Сгенерировано слишком много сегментов (больше 10) или сегменты незначительно отличаются друг от друга.
                            Предложить выбрать меньшее количество, а остальные удалить.
                        </li>
                    </ul>
                </div>

                <h4><span class="bold"><u>Информация, полученная Проектантом:</u></span></h4>

                <div class="simple-block">
                    <p>
                        <span>Задача:</span>
                        Сформулировать несколько гипотез сегментов.
                    </p>
                    <p>
                        <span>Результат:</span>
                        Заполненная форма на платформе Spaccel.ru.
                    </p>
                </div>

                <p>
                    <span class="bold">Формулировка сегментов</span><br>
                    <span>
                    Представьте, что Вы продаёте шампунь. Конечно, он нужен всем, у кого есть волосы. Или ещё шире – тем, у кого есть голова,
                    так о какой тогда сегментации целевой аудитории можно говорить? Дело в том, что у кого-то сухие волосы, у кого-то жирные,
                    кто-то хочет придать им объём, кто-то вылечить секущиеся кончики. Важно предложить каждому подходящий именно ему продукт.
                    Вот для этого и нужно делать сегментирование целевой аудитории.
                </span>
                </p>

                <p>
                    <span class="bold">Сегментация</span>
                    &#8212; это разделение целевой аудитории или всего рынка на части по какому-либо критерию или процесс выявления определенных
                    групп потребителей, которые обладают близкими потребностями, похожим покупательским поведением и отличительными характеристиками.
                </p>

                <p>Например, описанием сегмента может быть такой текст: Блондинка, незамужняя, 35 лет, работающая секретарем, что-то еще.</p>
                <p>Сегментация начинается с выбора критериев, на основании которых будут выделяться сегменты рынка.</p>

                <br>

                <p class="bold">Рынок – B2C. Группы критериев сегментации</p>

                <ol>
                    <li><span>Географические критерии (территория, с которой компания планирует работать (город, регион, страна).</span></li>
                    <li><span>Социально-демографические критерии (возраст, социальный статус, уровень дохода, образование, пол и т.д.)</span></li>
                    <li><span>Поведенческие критерии (определенные ценности, образ жизни, потребности, интересы, критерии выбора, использование продукта и т.д.)</span></li>
                    <li><span>Ключевые признаки продукта.</span></li>
                </ol>

                <br>

                <p class="bold">Рынок – B2B. Группы критериев сегментации</p>

                <ol>
                    <li><span>Географические. Тот же набор критериев. </span></li>
                    <li><span>Описательные критерии (размер бизнеса, отрасль, количество сотрудников, позиционирование, финансовое положение и т.д.)</span></li>
                    <li><span>Поведенческие критерии (частота приобретения, собственность или аренда, кто является потребителем внутри компании, опыт использования продукта и т.д.)</span></li>
                </ol>

                <br>

                <p class="bold">Возможные подходы к сегментации рынка</p>

                <div class="container-text">
                    <div class="bold"><span>A.</span>Вариант 1. Сегментация по продукту.</div>
                    <div class="pl-25">Пример: рынок телевизоров.</div>
                </div>

                <div class="container-text">
                    <div class="bold"><span>B.</span>Вариант 2. Сегментация по преимуществам продукта.</div>
                    <div class="pl-25">Пример: рынок телевизоров с разрешением 8К и вогнутым экраном.</div>
                </div>

                <div class="container-text">
                    <div class="bold"><span>C.</span>Вариант 3. Сегментация по потребности.</div>
                    <div class="pl-25">Пример: болельщики футбольной команды, которые ходят на стадион.</div>
                </div>

                <br>

                <div class="bold pl-15">Примеры характеристик: Магазин, как продукт.</div>

                <table>
                    <tr>
                        <th>Потребительские характеристики</th>
                        <th>Технические характеристики</th>
                    </tr>
                    <tr>
                        <td>Магазин работает с утра до вечера</td>
                        <td>Время работы 7.00-23.00</td>
                    </tr>
                    <tr>
                        <td>Не бывает очередей в кассу</td>
                        <td>Очередь в кассу не более 5 минут</td>
                    </tr>
                    <tr>
                        <td>Всегда можно получить справочную информацию о товаре</td>
                        <td>Не менее 10 продавцов-консультантов всегда в торговом зале</td>
                    </tr>
                    <tr>
                        <td>Всегда свободная собственная автомобильная парковка у магазина</td>
                        <td>Парковка на 100 машиномест</td>
                    </tr>
                </table>

                <div class="container-text">

                    <p><span class="bold">Важно!</span> Характеристика – это всегда факт. Это или есть, или нет</p><br>
                    <p class="bold">Рекомендации по заполнению информации по сегментам – генерация сегментов.</p>
                    <p class="bold">
                        Старайтесь выбирать сначала не больше трех сегментов, которые вас интересуют с точки зрения платежеспособности,
                        возможной (по вашему мнению) востребованности будущих или уже имеющихся продуктов, разработанных с использованием
                        базовой технологии. В процессе выполнения следующего этапа - Этап 2 «Подтверждение гипотез целевых сегментов»,
                        вы сможете уточнить параметры сегментов и сгенерировать более точное описание целевых сегментов, которые вы будете
                        разрабатывать далее.
                    </p>

                    <div class="container-text">
                        <div class="flex">
                            <div>a.</div>
                            <div class="pl-15">
                                О рынке имеется скудная или не структурированная информация: когда вы только приступаете к разработке сегмента и,
                                возможно, у вас подобрана только предварительная рыночная информация о потребителях.
                            </div>
                        </div>
                    </div>

                    <p>
                        Действие: предварительно опишите гипотезу сегмента (файл «Данные сегмента») при помощи информации, которой вы
                        располагаете на текущий момент. Это будет ваше исходное описание гипотезы целевого сегмента. По результатам глубинного
                        интервью (или опроса фокус групп - Этап 2 «Подтверждение гипотез целевых сегментов») вы получаете более подробную
                        информацию о рынке и делаете вывод: правильно ли вы сформировали сегмент. Если по результатам получается, что между
                        респондентами мало что общего, то необходимо сформировать сегмент вновь. Новый сегмент добавьте в список сегментов.
                    </p>

                    <div class="container-text">
                        <div class="flex">
                            <div>b.</div>
                            <div class="pl-15">Рынок вам хорошо известен, и вы располагаете информацией о целевых сегментах в структурированном виде.</div>
                        </div>
                    </div>

                    <p>
                        В этом случае вы легко формулируете сегменты. По результатам глубинного интервью (или опроса фокус групп - Этап 2
                        «Подтверждение гипотез целевых сегментов») делаете уточнения и выделяете те детали рынка, проблемы, которые, на ваш взгляд,
                        интересные. И, соответственно, по ним продолжаете разработку сегментов и продукты.
                    </p>

                    <div class="container-text">
                        <div class="flex">
                            <div>c.</div>
                            <div class="pl-15">Получен твердый заказ от конкретного лица (физического или юридического).</div>
                        </div>
                    </div>

                    <p>
                        Это тот случай, когда вы «перепрыгиваете» этапы: определения сегмента, подтверждение, поиск проблемы, разработка ЦП и
                        его подтверждения. По каким-то причинам, признавая в вас или в вашей компании эксперта, специалиста в определенной сфере,
                        вы получили заказ от конкретного заказчика. Поэтому вы сразу приступаете к подготовке MVP или опытного образца,
                        или промышленного образца.
                    </p>

                    <p class="bold">
                        Таким образом, для того, чтобы найти свой рынок, независимо от: отношения к ИС, полноты информации о рынке необходимо
                        первоначальное (стартовое) описание сегмента, корректность описания которого вы будете проверять на последующем этапе.
                    </p>

                </div>

            </div>

        </div>

    <?php endif; ?>

</div>

<!--Подключение скриптов-->
<?php $this->registerJsFile('@web/js/hypothesis_segment_index.js'); ?>
