<?php

/* @var $this yii\web\View */

use app\models\User;
use yii\helpers\Html;

$this->title = 'О сервисе';

?>
<div class="site-about">

    <div class="row">
        <div class="col-md-12 top_line_text_about_page">Мы рады приветствовать вас на портале АКСЕЛЕРАТОРА СТАРТАП-ПРОЕКТОВ!</div>
    </div>

    <div class="row">

        <div class="col-md-12 block_middle_content_about_page">
            
            <div class="text_block_about_page">

                <div>
                    <p style="font-weight: 700;">Для кого предназначен наш АКСЕЛЕРАТОР СТАРТАП-ПРОЕКТОВ:</p>
                    <p>- Вы слышали о развитии потребителя (Customer Development) и о бережливом стартапе (Lean Start up), но не знаете, как правильно начать беседу с вашим первым клиентом?</p>
                    <p>- Вы занимаетесь развитием бизнеса или продажами и хотите работать с большей результативностью в молодой компании, у которой еще нет бизнес-модели.</p>
                    <p>- Вы ментор, инвестируете в стартапы или оказываете им консультационную поддержку и хотите помочь им наладить более эффективное общение с клиентами?</p>
                    <p style="padding-bottom: 15px;">- Вам нравится новая бизнес-идея и вы хотите понять – есть ли у нее будущее, перед тем как сжечь мосты и бросить предыдущую работу, да и вообще, плотно заняться ею.</p>
                    <div style="float: left;">
                        <iframe style="padding: 0px 15px 0px 0;" src="https://www.youtube.com/embed/F66NhTNOlMA" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <div class="text-center">
                            <?= Html::a('Скачать презентацию', ['/site/download-presentation']); ?>
                        </div>
                    </div>
                    <p>- Вы привлекаете инвестиции, и инвесторам нужно больше доказательств того, что вы решаете реальную проблему.</p>
                    <p>- Все рабочие процессы кажутся вам громоздкими и неуклюжими, вы стремитесь упростить и усовершенствовать их?</p>
                    <p>- У вас появилось смутное предчувствие новых интересных возможностей и вы хотите понять, в чем они заключаются?</p>
                    <p>- Вы хотите получать результат от своей бизнес-идеи уже сегодня.</p>
                </div>

                <div>
                    <p>Любая бизнес-идея имеет шанс на реализацию, если ее потребность протестирована рынком.</p>
                    <p>Какая бы красивая бизнес-идея ни была, она не стоит ничего, если ее ценность не подтверждена рынком!</p>
                </div>

                <div>
                    <span style="font-weight: 700;">Тестирование бизнес идеи</span> – это технология (пошаговые действия по алгоритму), которая требует точного выполнения
                    последовательных определенный действий по взаимодействию с рынком. Неточность и формальное следование предложенному алгоритму приведут к ложным результатам
                    и пустой трате времени.
                </div>

                <div>
                    <span style="font-weight: 700;">АКСЕЛЕРАТОР СТАРТАП-ПРОЕКТОВ</span> предлагает воспользоваться технологией создания и управления процессом разработки
                    инновационного продукта, который использовали все известные вам бренды (Faсebook, VK, Google, Apple, и т.д.)
                </div>

                <div>
                    <span style="font-weight: 700;">АКСЕЛЕРАТОР СТАРТАП-ПРОЕКТОВ</span> – это пошаговое руководство к тому, как вашу идею вывести на рынок.
                    Каждый из этапов имеет свою обоснованную ценность и является неотъемлемой частью цикла разработки проекта.
                </div>

            </div>

            <?php echo Html::img('/images/elements/tech-meeting-flatlay.png', ['style' => ['min-width' => '360px']]);?>

        </div>

    </div>

    <div class="row">
        <div class="col-md-12 top_line_text_about_page">ПРИ работе с приложением АКСЕЛЕРАТОР СТАРТАП-ПРОЕКТОВ необходимо двигаться поступательно, чтобы получить нужный эффект</div>
    </div>


    <div class="row tab navigation_blocks">

        <div class="passive_navigation_block navigation_block tablinks" onclick="openCity(event, 'step_one')" id="defaultOpen">
            <div class="stage_number">1</div>
            <div>Генерация гипотез целевых сегментов</div>
        </div>


        <div class="passive_navigation_block navigation_block tablinks" onclick="openCity(event, 'step_two')">
            <div class="stage_number">2</div>
            <div>Подтверждение гипотез целевых сегментов</div>
        </div>

        <div class="passive_navigation_block navigation_block tablinks" onclick="openCity(event, 'step_three')">
            <div class="stage_number">3</div>
            <div>Генерация гипотез проблем сегментов</div>
        </div>

        <div class="passive_navigation_block navigation_block tablinks" onclick="openCity(event, 'step_four')">
            <div class="stage_number">4</div>
            <div>Подтверждение гипотез проблем сегментов</div>
        </div>

        <div class="passive_navigation_block navigation_block tablinks" onclick="openCity(event, 'step_five')">
            <div class="stage_number">5</div>
            <div>Разработка гипотез ценностных предложений</div>
        </div>

        <div class="passive_navigation_block navigation_block tablinks" onclick="openCity(event, 'step_six')">
            <div class="stage_number">6</div>
            <div>Подтверждение гипотез ценностных предложений</div>
        </div>

        <div class="passive_navigation_block navigation_block tablinks" onclick="openCity(event, 'step_seven')">
            <div class="stage_number">7</div>
            <div>Разработка MVP</div>
        </div>

        <div class="passive_navigation_block navigation_block tablinks" onclick="openCity(event, 'step_eight')">
            <div class="stage_number">8</div>
            <div>Подтверждение MVP</div>
        </div>

        <div class="passive_navigation_block navigation_block tablinks" onclick="openCity(event, 'step_nine')">
            <div class="stage_number">9</div>
            <div>Генерация бизнес-модели</div>
        </div>

    </div>


    <div id="step_one" class="tabcontent row">

        <p>Начиная работу над продуктом или технологией, следует определить,
            кто будет являться целевой аудиторией – потребителем вашего продукта или клиентом вашего сервиса.</p>

        <p>Процесс сегментации потребителей представляет собой выделение групп потенциальных потребителей
            в пределах определенного вами рынка.</p>

        <p>На данном этапе вам потребуется сформулировать гипотезу для каждого возможного по вашему мнению сегмента
            и занести информацию в предлагаемые формы.</p>

        <p>Сформулируйте гипотезу сегмента самостоятельно, представьте себе наиболее ярко выраженного представителя
            потенциально интересного вам сегмента как можно подробнее и опишите его параметры в соответствии с
            предложенным списком в данном приложении.</p>

    </div>

    <div id="step_two" class="tabcontent row">

        <p>Для выполнения этапа вам потребуется провести интервью и получить информацию от
            представителей сегмента, которая подтвердит или опровергнет вашу гипотезу относительно
            правильности определения целевого сегмента.</p>

        <p>Следуйте шагам и заносите собранные данные в предлагаемые формы.</p>

        <p>Первичная полученная от респондентов информация поможет определить количество целевых
            сегментов и предварительно оценить их рыночную емкость.</p>

        <p>Далее для каждого определенного сегмента необходимо будет разработать проблему сегмента,
            ценностное предложение и минимально жизнеспособный продукт.</p>

    </div>

    <div id="step_three" class="tabcontent row">

        <p>Этап формулирования гипотезы проблемы сегмента очень важен, т.к. ответы, полученные во
            время интервью, а также выводы, сделанные на их основе, будут играть определяющую роль в
            процессе поиска жизнеспособной бизнес-модели для вашего продукта или технологии.</p>

        <p>На основе ранее проведенных интервью вам предстоит определить проблемы, характерные для
            представителей сегмента.</p>

        <p>Проанализируйте полученную информацию и сформулируйте гипотезы проблем сегмента для
            дальнейшего подтверждения.</p>

    </div>

    <div id="step_four" class="tabcontent row">

        <p>На этапе подтверждения гипотезы проблемы сегмента необходимо удостовериться, что
            проведенные интервью были правильно интерпретированы, точка зрения потребителей не
            изменилась, и вы находитесь на правильном пути для дальнейшей реализации проекта.</p>

        <p>Вам необходимо решить – какое количество интервью и положительных ответов будет считаться
            исчерпывающим для получения максимально достоверного результата.</p>

        <p>При помощи краткой анкеты соберите ответы респондентов, проанализируйте данные и сделайте
            вывод, можно ли проблему сегмента считать подтвержденной - существующей и значимой для
            выбранного сегмента.</p>

    </div>

    <div id="step_five" class="tabcontent row">

        <p>Для разработки гипотезы ценностного предложения - краткого описания сути вашего проекта вы сможете
            воспользоваться следующей формулой:</p>

        <p>«Наш _______ (товар/услуга) помогает _______ (потребительскому сегменту), которые хотят_______ (выполнить задачу),
            так, что _______ (снижает, избавляет(проблему)) и _______ (увеличивает, позволяет (выгоду))
            в отличии от _______ (конкурирующее ЦП)»</p>

        <p>В результате прохождения этапа у вас появится емкое, четко изложенное ценностное предложение продукта или технологии,
            которое впоследствии можно доработать.</p>

        <p>На основании предложенной формулы дайте краткое определение ценностных предложений, которые,
            по вашему мнению, могли бы решить выявленные проблемы сегмента. Одна проблема – одно ценностное предложение.</p>

    </div>

    <div id="step_six" class="tabcontent row">

        <p>Этап подтверждения ГЦП необходим для того, чтобы удостовериться, что сформулированное ЦП отвечает потребностям
            представителей сегмента, и точка зрения потребителей у многих представителей сегмента совпадает, значит ваша
            гипотеза ЦП в практическом смысле интересна потребителю и востребована.</p>

        <p>Для выполнения этого этапа Вам необходимо решить какая выборка в количественном выражении для вас будет исчерпывающей
            для получения максимально достоверного результата. Также, необходимо установить какое количество положительных
            ответов должно быть, чтобы считать тест пройденным.</p>

        <p>По мере прохождения по маршруту дорожной карты база представителей сегмента постепенно растет. Соответственно, запросив
            всех имеющихся в вашей базе представителей сегмента, подтвердивших ГПС, о привлекательности ГЦП, вы получаете независимую
            оценку вашего предложения. Для прохождения этапа вам необходимо будет собрать ответы респондентов при помощи краткой анкеты.</p>

        <p>При помощи краткой анкеты соберите ответы респондентов, проанализируйте данные и сделайте вывод
            о подтверждении Гипотезы ценностного предложения.</p>

    </div>

    <div id="step_seven" class="tabcontent row">

        <p>Для тестирования вашего продукта или технологии на группе первых пользователей возможно создание «минимально
            жизнеспособного продукта» (Minimal viable product – MVP) - прототипа с минимальным требуемым функционалом.</p>

        <p>При помощи MVP можно получить необходимую обратную связь от пользователей о продукте для
            формирования дальнейшей траектории его развития.</p>

        <p>Создайте макет или прототип «минимально жизнеспособного продукта», который может быть реализован в виде слайдов,
            скетча интерфейса, целевой страницы (landing page), ролика с демо-версий продукта и т.д.</p>

    </div>

    <div id="step_eight" class="tabcontent row">

        <p>Этап подтверждения «минимально жизнеспособного продукта» является отправной точкой для старта продаж.</p>

        <p>Если выясняется, что существующая проблема целевой аудитории решается с помощью предложенного вами продукта,
            в нашем случае MVP, и потребители готовы его покупать, этап подтверждения считается пройденным.</p>

        <p>Если же предлагаемый продукт требует модификации или доработки, за данным этапом должна последовать итерация по уточнению продукта.</p>

        <p>Протестируйте созданный «минимально жизнеспособный продукт» на представителях выбранного целевого сегмента и на основании
            полученной обратной связи примите решение, можно ли выходить с продуктом на рынок или же он требует доработки.</p>

    </div>

    <div id="step_nine" class="tabcontent row">

        <p>Целью, а также заключительным этапом работы в рамках нашего Акселератора является генерация Бизнес-модели. Мы предлагаем
            использовать Канву бизнес-модели (англ. Business model canvas), разработанную авторами Александром Остервальдером и Ивом Пинье.</p>

        <p>Канва бизнес-модели состоит из 9 блоков, которые могут быть объединены в 4 группы, каждый из блоков описывает
            свою часть бизнес-модели организации, а именно: ключевые партнеры, ключевые активности, достоинства и предложения,
            отношения с заказчиком, пользовательские сегменты, ключевые ресурсы, каналы поставки, структура затрат и источники доходов.</p>

        <p>
            (Википедия - <?= Html::a('Канва бизнес-модели',
                'https://ru.wikipedia.org/wiki/%D0%9A%D0%B0%D0%BD%D0%B2%D0%B0_%D0%B1%D0%B8%D0%B7%D0%BD%D0%B5%D1%81-%D0%BC%D0%BE%D0%B4%D0%B5%D0%BB%D0%B8', ['target'=>'_blank'])?>)
        </p>

        <p>На основании ранее заполненных форм система автоматически сгенерирует вариант Бизнес-модели,
            который при необходимости вы сможете отредактировать.</p>

        <p>Наличие решения к цепочке «Клиент – Проблема – Ценностное предложение» означает, что Бизнес-модель найдена.
            Подтверждением актуальности Бизнес-модели может также служить первая произведенная продажа.</p>

    </div>


    <div class="row">

        <div class="col-md-12 text-center">

            <?php if (!User::isUserSimple(Yii::$app->user->identity['username'])) : ?>

                <?= Html::a('Создать проект', ['/'],[
                    'class' => 'btn btn-default',
                    'style' => [
                        'margin-top' => '40px',
                        'margin-bottom' => '30px',
                        'background' => '#E0E0E0',
                        'color' => '4F4F4F',
                        'border-radius' => '8px',
                        'width' => '220px',
                        'height' => '40px',
                        'font-size' => '16px',
                        'font-weight' => '700'
                    ]
                ]) ?>

            <?php else : ?>

                <?= Html::a('Создать проект', ['/projects/index', 'id' => Yii::$app->user->id],[
                    'class' => 'btn btn-default',
                    'style' => [
                        'margin-top' => '40px',
                        'margin-bottom' => '30px',
                        'background' => '#E0E0E0',
                        'color' => '4F4F4F',
                        'border-radius' => '8px',
                        'width' => '220px',
                        'height' => '40px',
                        'font-size' => '16px',
                        'font-weight' => '700'
                    ]
                ]) ?>

            <?php endif; ?>
        </div>

    </div>


</div>


<?php
$script = "
    
    //Установка Simple ScrollBar
    const simpleBar = new SimpleBar(document.getElementById('simplebar-shared-container'));
";
$position = \yii\web\View::POS_READY;
$this->registerJs($script, $position);
?>