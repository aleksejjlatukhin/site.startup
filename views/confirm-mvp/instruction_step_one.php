<?php

use app\models\User;
use yii\helpers\Html;

$this->registerCssFile('@web/css/methodological-guide-style.css');

?>

<?php if (!User::isUserAdmin(Yii::$app->user->identity['username'])) : ?>

    <div class="methodological-guide">

        <div class="container-list">

            <h3><span class="bold">Шаг 1. Подготовка к тестированию</span></h3>

            <p>
                По сути это должна быть презентация вашего продукта, и она может быть проведена публично, когда вы собираете свою целевую аудиторию в одном месте одновременно.
                Обычно автор рассказывает о перспективном продукте в виде подробной презентации, и в завершение презентации демонстрируются работоспособность и уникальные ключевые
                качества перспективного продукта. Затем предоставляют слово представителям целевого рынка, которые задают дополнительные вопросы, делают уточнения.
            </p>

            <p>
                Автор задает интересующие его вопросы, насколько понятен материал, что понравилось, что не понравилось, чего не хватает в продукте, пожелания и т.д., а также интересуется
                мнением приглашенных о намерении купить данный продукт (даже представленный MVP) по предложенной цене. Если среди аудитории найдутся желающие купить продукт при определенных
                доработках, то это стоит оформить протоколом или соглашением о намерениях.
            </p>

            <div class="bold">Задача:</div>
            <div class="container-text">
                <ul>
                    <li class="pl-15">Подготовить сценарий проведения тестирования. </li>
                    <li class="pl-15">Подготовить презентацию MVP («минимально жизнеспособного продукта»).</li>
                    <li class="pl-15">Определить форму тестирования: интервью «один на один» или проведение презентации перед аудиторией.</li>
                    <li class="pl-15">Определить форму и содержание реакции представителей, соответствующей положительному тесту MVP.</li>
                </ul>
            </div>

            <div class="bold">Результат: <span class="normal">Заполненная форма Шаг 1, Этап 8. на платформе Spaccel.ru</span></div>
            <div class="container-text">
                <ol>
                    <li class="pl-15">Просмотрите еще раз информационный материал о подготовке сценария по проведению тестирования.</li>
                    <li class="pl-15">
                        Подготовьте небольшую презентацию, где необходимо отразить:
                        <div class="container-text">
                            <ol>
                                <li>Цель вашего исследования.</li>
                                <li>Целевая аудитория вашего исследования.</li>
                                <li>Какие проблемы вы нашли в процессе вашего исследования.</li>
                                <li>Какие проблемы являются, по вашему мнению, наиболее актуальные. И почему вы выбрали их для создания MVP.</li>
                                <li>Продемонстрируйте MVP/расскажите о нем - в чем его сильные стороны, на чем построено ваше решение.</li>
                            </ol>
                        </div>
                    </li>
                    <li class="pl-15">Выберете количество респондентов, достаточное для проведения теста.</li>
                    <li class="pl-15">Определите, какая реакция представителя сегмента может оцениваться как положительная на проведение теста.</li>
                </ol>
            </div>

            <div class="bold">
                ВАЖНО! На этом этапе, возможно, у вас уже появится проект устройства, метода решения проблемы. Если это так, то необходимо подписать
                NDA (соглашение о конфиденциальности) с каждым респондентом, кому собираетесь сообщить о вашем проекте.
            </div>

            <div class="bold">
                Помните, что после первого публичного раскрытия по законодательству РФ у вас есть только 6 (шесть) месяцев на подачу заявки для регистрации приоритета
                на объект интеллектуальной собственности. По истечении этого срока статус новизны вашего решения может быть утрачен.
            </div>

        </div>

    </div>

<?php else : ?>

    <div class="methodological-guide">

        <h3 class="header-text"><span>Этап 8. Подтверждение MVP</span></h3>

        <div class="container-list">

            <h3><span class="bold">Шаг 1. Подготовка к тестированию</span></h3>

            <div class="simple-block">
                <p>
                    <span>Задача:</span>
                    Проверить на соответствие рекомендациям заполненной формы форма <span>MVP.</span>
                </p>
                <p>
                    <span>Результат:</span>
                    Проектант получил необходимые рекомендации и точно понял требования методики spaccel.
                </p>
            </div>

            <div class="bold">Рекомендации и точки контроля:</div>
            <div class="container-text">
                <ul>
                    <li class="pl-15">Все поля должны быть заполнены;</li>
                    <li class="pl-15">Проверить, опираясь на здравый смысл, легенду представления интервьюера себя респонденту.</li>
                    <li class="pl-15">Количество респондентов (участников презентации), которые соответствуют сегменту, должно быть, по возможности, намного больше 5-6 человек. </li>
                    <li class="pl-15">
                        Количество респондентов, которые должны пройти интервью, желательно выбрать на 15-25% больше, чем требуемое количество «положительных» (с точки зрения принадлежности
                        к выбранному сегменту) респондентов, чтобы с большей вероятностью получить нужное количество целевых респондентов.
                    </li>
                </ul>
            </div>

            <h4><span class="bold"><u>Информация, полученная Проектантом:</u></span></h4>

            <p>
                По сути это должна быть презентация вашего продукта, и она может быть проведена публично, когда вы собираете свою целевую аудиторию в одном месте одновременно.
                Обычно автор рассказывает о перспективном продукте в виде подробной презентации, и в завершение презентации демонстрируются работоспособность и уникальные ключевые
                качества перспективного продукта. Затем предоставляют слово представителям целевого рынка, которые задают дополнительные вопросы, делают уточнения.
            </p>

            <p>
                Автор задает интересующие его вопросы, насколько понятен материал, что понравилось, что не понравилось, чего не хватает в продукте, пожелания и т.д., а также интересуется
                мнением приглашенных о намерении купить данный продукт (даже представленный MVP) по предложенной цене. Если среди аудитории найдутся желающие купить продукт при определенных
                доработках, то это стоит оформить протоколом или соглашением о намерениях.
            </p>

            <div class="bold">Задача:</div>
            <div class="container-text">
                <ul>
                    <li class="pl-15">Подготовить сценарий проведения тестирования. </li>
                    <li class="pl-15">Подготовить презентацию MVP («минимально жизнеспособного продукта»).</li>
                    <li class="pl-15">Определить форму тестирования: интервью «один на один» или проведение презентации перед аудиторией.</li>
                    <li class="pl-15">Определить форму и содержание реакции представителей, соответствующей положительному тесту MVP.</li>
                </ul>
            </div>

            <div class="bold">Результат: <span class="normal">Заполненная форма Шаг 1, Этап 8. на платформе Spaccel.ru</span></div>
            <div class="container-text">
                <ol>
                    <li class="pl-15">Просмотрите еще раз информационный материал о подготовке сценария по проведению тестирования.</li>
                    <li class="pl-15">
                        Подготовьте небольшую презентацию, где необходимо отразить:
                        <div class="container-text">
                            <ol>
                                <li>Цель вашего исследования.</li>
                                <li>Целевая аудитория вашего исследования.</li>
                                <li>Какие проблемы вы нашли в процессе вашего исследования.</li>
                                <li>Какие проблемы являются, по вашему мнению, наиболее актуальные. И почему вы выбрали их для создания MVP.</li>
                                <li>Продемонстрируйте MVP/расскажите о нем - в чем его сильные стороны, на чем построено ваше решение.</li>
                            </ol>
                        </div>
                    </li>
                    <li class="pl-15">Выберете количество респондентов, достаточное для проведения теста.</li>
                    <li class="pl-15">Определите, какая реакция представителя сегмента может оцениваться как положительная на проведение теста.</li>
                </ol>
            </div>

            <div class="bold">
                ВАЖНО! На этом этапе, возможно, у вас уже появится проект устройства, метода решения проблемы. Если это так, то необходимо подписать
                NDA (соглашение о конфиденциальности) с каждым респондентом, кому собираетесь сообщить о вашем проекте.
            </div>

            <div class="bold">
                Помните, что после первого публичного раскрытия по законодательству РФ у вас есть только 6 (шесть) месяцев на подачу заявки для регистрации приоритета
                на объект интеллектуальной собственности. По истечении этого срока статус новизны вашего решения может быть утрачен.
            </div>

        </div>

    </div>

<?php endif; ?>

<div class="row">
    <div class="col-md-12" style="display:flex;justify-content: center;">
        <?= Html::button('Закрыть', [
            'onclick' => 'return $(".modal_instruction_page").modal("hide");',
            'class' => 'btn btn-default',
            'style' => [
                'display' => 'flex',
                'align-items' => 'center',
                'justify-content' => 'center',
                'background' => '#F5A4A4',
                'color' => '#ffffff',
                'width' => '180px',
                'height' => '40px',
                'font-size' => '16px',
                'text-transform' => 'uppercase',
                'font-weight' => '700',
                'padding-top' => '9px',
                'border-radius' => '8px',
                'margin-top' => '28px'
            ]
        ]) ?>
    </div>
</div>
