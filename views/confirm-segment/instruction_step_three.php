<?php

use app\models\User;

$this->registerCssFile('@web/css/methodological-guide-style.css');

?>

<?php if (!User::isUserAdmin(Yii::$app->user->identity['username'])) : ?>

    <div class="methodological-guide">

        <h3 class="header-text"><span>Этап 2. Подтверждение гипотез целевых сегментов</span></h3>

        <div class="container-list">

            <div class="bold">Задача:</div>
            <div class="container-text">
                <ul>
                    <li class="pl-15">Подтвердить верность гипотез целевых сегментов, выбранных на предыдущем этапе.</li>
                    <li class="pl-15">Получить как можно больше информации о выбранном рынке, чтобы сделать более точное описание целевого сегмента.</li>
                    <li class="pl-15">Сделать выводы о гипотезах проблем целевых сегментов.</li>
                </ul>
            </div>

            <div class="simple-block">
                <p>
                    <span>Результат:</span>
                    Заполненная форма (шаг 3, Этап 2) на платформе Spaccel.ru.
                </p>
            </div>

            <br>

            <p class="bold blue">
                ВАЖНО! Необходимо очень качественно выполнить все три шага этого этапа! Следование рекомендациям, которые вы найдете в
                начале каждого шага, значительно поможет достичь цели этапа и открыть уникальные аспекты рынка, на основе которых можно
                построить эффективный бизнес или создать социальную услугу. Будьте уверены, формальное выполнение заданий в каждом этапе,
                каждом шаге приведут к безрезультатной трате времени.
            </p>

            <p class="bold blue">
                Готовьте каждое интервью тщательно, анализируйте результаты собеседования с каждым респондентом.
                Не проводите интервью с родственниками, знакомыми и друзьями.
            </p>

            <br>

            <p>При проверке гипотез о верности выбранных целевых сегментов предлагается использовать метод глубинного интервью.</p>
            <div>
                <span class="bold">Глубинное интервью</span>
                (ГИ) &#8212; один из качественных методов исследования пользователей, он применяется в продуктовом анализе. Предположим,
                вы готовите новый продукт, новую версию продукта или хотите добавить в него новые функции или хотите выйти на новый
                сегмент. Чтобы не ошибиться, вам нужно больше узнать о пользователях, их ожиданиях и потребностях. Для этого нужно
                поставить цели — понять, что именно вы хотите узнать, выбрать участников и поговорить лично с каждым из них.
            </div>
            <div>
                <span class="bold">Глубинное</span> &#8212;
                потому что помогает докопаться до скрытой информации, которую вряд ли удастся получить, задавая прямые вопросы.
                ГИ поможет узнать не только о том, как пользователь относится к продукту сейчас, но и о его прошлом опыте: выяснить,
                чем он пользовался раньше, что пошло не так, почему он решил что-то менять. Всё это поможет понять его мотивацию.
            </div>

        </div>

        <h3 class="header-text"><span>Шаг 3. Проведение интервью</span></h3>

        <div class="container-list">

            <div class="bold">Задача:</div>
            <div class="container-text">
                <ul>
                    <li class="pl-15">Найти представителей сегмента в количестве, заданном в Шаге 1, Этапа 2, договориться на интервью с ними.</li>
                    <li class="pl-15">Провести интервью, используя вопросы, сформулированные в Шаге 2, Этапа 2, в соответствии с выбранным графиком.</li>
                    <li class="pl-15">Занести результаты интервью в формы Spaccel.ru на каждого респондента.</li>
                    <li class="pl-15">Провести анализ скрипта ответов и сделать выводы о возможных проблемах, с которыми сталкивается респондент, и внести их в поле «Варианты проблем» .</li>
                </ul>
            </div>

            <div class="simple-block">
                <p>
                    <span>Результат:</span>
                    Заполненная форма (шаг 3, Этап 2) на платформе Spaccel.ru.
                </p>
            </div>

            <br>

            <div class="bold">Где найти клиентов для интервью:</div>

            <div class="container-text">
                <ol>
                    <li><span class="pl-15">Спросить у знакомых.</span></li>
                    <li><span class="pl-15">Социальные сети: Linked in, Free-lance, YouDo.com.</span></li>
                    <li><span class="pl-15">Использовать различные университетские мероприятия.</span></li>
                    <li><span class="pl-15">Индустриальные партнеры университетов и др. организаций.</span></li>
                    <li><span class="pl-15">Взаимодействие с региональными ТПП, опора России и др. организациями.</span></li>
                    <li><span class="pl-15">Холодные знакомства в местах нахождения потенциальных представителей сегмента.</span></li>
                </ol>
            </div>

            <div class="bold">Разница b&b и b&c</div>

            <p>
                b&b респондентов мало, они на вес золота. Здесь лучше получить рекомендацию. Друзья и родственники – худшие респонденты.
                Но они хорошие респонденты, чтобы отсеять на первом этапе те вопросы, которые могут быть глупыми, не уместными либо
                слишком широкими. b&c респондентов много.
            </p>

            <p>
                Когда мы проводим интервью, то мы являемся информационными вампирами. Мы берем информацию, но взамен ничего не даем.
                Можно предлагать взамен кофе и что-то типа этого. Можно пообещать сделать и прислать небольшой отчет.
            </p>

            <p>
                Чтобы избежать ситуации, когда вы попали на не того человека, который принимает решение нужно задать несколько
                квалификационных вопросов. Если, например, вы хотите сделать сервис по покупке билетов в кино, то сначала вы должны
                задать вопрос - как часто человек ходит в кино, и где он покупает билеты. Если он не ходит в кино (качает торенты),
                то это бесполезный человек для вас.
            </p>

            <p>Есть опасность, что вы можете не пройти по всей цепочке людей, которые принимают решения. Ошибкой может быть – пройдена не вся цепочка ценности.</p>

            <p>Ролевые статусы  b&b: для каждого статуса должен быть интерес. Следует не бояться отходить от сценария, если респондент вас туда (разумно) тянет.</p>

            <p class="bold blue">
                Важно! Вопросы, предлагаемые для интервью, нужны для того, чтобы разговорить респондента. Но при этом разговор нельзя пускать на
                самотек. Необходимо отмечать, когда собеседник начинает говорить о том, какие цели или задачи ему необходимо решить – это потребности,
                которые у него есть. Как он эти потребности удовлетворяет? Если вы видите (из разговора), что удовлетворение каких-то потребностей
                для него важно, но дается это не всегда легко, то здесь и есть проблема(!), которую нужно попытаться решить.
            </p>

            <p class="bold">Нужно больше слушать, а не говорить!</p>

            <div class="bold">И сколько времени займёт интервью? У нас с командой остался один день до запуска, ещё не поздно?</div>

            <p>
                Поздно. Одно интервью занимает примерно час-полтора, но вам для исследования нужно провести минимум пять.
                А перед этим найти участников и договориться с ними, а ещё —подготовить сценарий интервью. Один день до запуска — это
                не время для исследований, особенно, таких масштабных, как ГИ.
            </p>

            <div class="bold">Мы уже использовали фокус-группы и теневое копирование. Обязательно ли после них проводить интервью?</div>

            <p>
                Не обязательно, если вам достаточно полученной информации. Если мало, то можно использовать интервью как дополнительный
                метод исследования. Информацию о пользователях можно получить разными способами, их выбор и сочетание всегда остаются за вами.
            </p>

            <div class="bold">Я хочу сэкономить время. Можно я соберу всех участников вместе и буду по очереди задавать им вопросы?</div>

            <p>
                Нельзя. Если собрать всех участников вместе и разговаривать с ними по очереди, личной беседы не получится.
                Собеседники будут слышать друг друга, и это может их запутать, когда до них дойдёт очередь отвечать.
                А кто-то просто не захочет ничего рассказывать, если вокруг много незнакомых людей.
            </p>

            <p>
                <span class="bold">Глубинное интервью</span>
                &#8212; это беседа один на один, доверие, внимание к деталям и открытые вопросы. Если у вас нет времени
                или возможности пообщаться лично, лучше подберите другой метод исследования, например, фокус-группы, где можно собрать
                вместе сразу несколько человек.
            </p>

            <div class="bold">А участников можно тех же пригласить?</div>

            <p>
                Можно и тех же, если к ним остались вопросы. Или можно найти для каждого метода отдельную группу участников,
                чтобы охватить большую аудиторию. Когда нужно собрать разную информацию и времени много, можно использовать несколько
                методов исследования. А если команда большая, то можно разделиться и работать одновременно: например, пока один
                проводит глубинное интервью, другой модерирует фокус-группы, а кто-то наблюдает за пользователями.
            </p>

            <p>
                Таким образом, нужно последовательно провести интервью с каждым из выбранных респондентов. Если у вас есть команда
                из нескольких человек, то поделив весь список намеченных респондентов, выполнить Шаг 3 можно быстрее. Но предварительно
                все интервьюеры должны быть проинструктированы как проводить интервью: задание, зафиксированное в Шагах 1 и 2.
            </p>

            <p>Запишите, по возможности, то, что отвечали респонденты как можно более дословно и занесите результат в соответствующую форму Spaccel.ru.</p>

            <p>
                После каждого интервью внимательно прочитайте полученную от респондентов информацию и впишите
                в поле «Варианты проблем» возможные проблемы, с которыми, на ваш взгляд, сталкивается респондент в своей жизни.
            </p>

        </div>

    </div>

<?php else : ?>

    <div class="methodological-guide">

        <h3 class="header-text"><span>Этап 2. Подтверждение гипотез целевых сегментов</span></h3>

        <div class="container-list">

            <h3><span class="bold blue">Шаг 3. Проведение интервью</span></h3>

            <div class="simple-block">
                <p>
                    <span>Задача:</span>
                    Проверить на соответствие рекомендациям и формату заполненную форму <span>Информация о респондентах и интервью.</span>
                </p>
                <p>
                    <span>Результат:</span>
                    Информация проверена. При необходимости сформированы замечания о необходимости произвести корректировки.
                </p>
            </div>

            <div class="bold">Рекомендации:</div>
            <div class="container-text">
                <ul>
                    <li class="pl-15">
                        По возможности выяснить являются ли представители списка интервьюируемых
                        представителей сегмента таковыми;
                    </li>
                    <li class="pl-15">
                        Опираясь на здравый смысл проверить наличие одинаковых ответов (слово в слово) и указать на формальность
                        или фиктивность проведенного интервью;
                    </li>
                    <li class="pl-15">
                        Предложить представить полный ответ, который был дан респондентом, т.к. на этой стадии важны детали, а не короткий формальный ответ.
                    </li>
                    <li class="pl-15">
                        Проверить наличие вывода (возможная распространенная проблема) при внесении информации в форму Результаты интервью.
                    </li>
                    <li class="pl-15">
                        Если в ответах респондентов, принадлежащих целевому сегменту, не обнаружена связка потребность-проблема,
                        то предложить еще раз провести интервью с новым респондентом или со старым для выявления потребности,
                        которая имеется у респондента, и он не может ее удовлетворить по каким-то причинам, хотя и были зафиксированы
                        попытки (затрачены деньги, усилия) решить эту проблему.
                    </li>
                </ul>
            </div>

            <h4><span class="bold blue"><u>Информация, полученная Проектантом:</u></span></h4>

            <div class="bold">Задача:</div>
            <div class="container-text">
                <ul>
                    <li class="pl-15">Найти представителей сегмента в количестве, заданном в Шаге 1, Этапа 2, договориться на интервью с ними.</li>
                    <li class="pl-15">Провести интервью, используя вопросы, сформулированные в Шаге 2, Этапа 2, в соответствии с выбранным графиком.</li>
                    <li class="pl-15">Занести результаты интервью в формы Spaccel.ru на каждого респондента.</li>
                    <li class="pl-15">Провести анализ скрипта ответов и сделать выводы о возможных проблемах, с которыми сталкивается респондент, и внести их в поле «Варианты проблем» .</li>
                </ul>
            </div>

            <div class="simple-block">
                <p>
                    <span>Результат:</span>
                    Заполненная форма (шаг 3, Этап 2) на платформе Spaccel.ru.
                </p>
            </div>

            <br>

            <div class="bold">Где найти клиентов для интервью:</div>

            <div class="container-text">
                <ol>
                    <li><span class="pl-15">Спросить у знакомых.</span></li>
                    <li><span class="pl-15">Социальные сети: Linked in, Free-lance, YouDo.com.</span></li>
                    <li><span class="pl-15">Использовать различные университетские мероприятия.</span></li>
                    <li><span class="pl-15">Индустриальные партнеры университетов и др. организаций.</span></li>
                    <li><span class="pl-15">Взаимодействие с региональными ТПП, опора России и др. организациями.</span></li>
                    <li><span class="pl-15">Холодные знакомства в местах нахождения потенциальных представителей сегмента.</span></li>
                </ol>
            </div>

            <div class="bold">Разница b&b и b&c</div>

            <p>
                b&b респондентов мало, они на вес золота. Здесь лучше получить рекомендацию. Друзья и родственники – худшие респонденты.
                Но они хорошие респонденты, чтобы отсеять на первом этапе те вопросы, которые могут быть глупыми, не уместными либо
                слишком широкими. b&c респондентов много.
            </p>

            <p>
                Когда мы проводим интервью, то мы являемся информационными вампирами. Мы берем информацию, но взамен ничего не даем.
                Можно предлагать взамен кофе и что-то типа этого. Можно пообещать сделать и прислать небольшой отчет.
            </p>

            <p>
                Чтобы избежать ситуации, когда вы попали на не того человека, который принимает решение нужно задать несколько
                квалификационных вопросов. Если, например, вы хотите сделать сервис по покупке билетов в кино, то сначала вы должны
                задать вопрос - как часто человек ходит в кино, и где он покупает билеты. Если он не ходит в кино (качает торенты),
                то это бесполезный человек для вас.
            </p>

            <p>Есть опасность, что вы можете не пройти по всей цепочке людей, которые принимают решения. Ошибкой может быть – пройдена не вся цепочка ценности.</p>

            <p>Ролевые статусы  b&b: для каждого статуса должен быть интерес. Следует не бояться отходить от сценария, если респондент вас туда (разумно) тянет.</p>

            <p class="bold blue">
                Важно! Вопросы, предлагаемые для интервью, нужны для того, чтобы разговорить респондента. Но при этом разговор нельзя пускать на
                самотек. Необходимо отмечать, когда собеседник начинает говорить о том, какие цели или задачи ему необходимо решить – это потребности,
                которые у него есть. Как он эти потребности удовлетворяет? Если вы видите (из разговора), что удовлетворение каких-то потребностей
                для него важно, но дается это не всегда легко, то здесь и есть проблема(!), которую нужно попытаться решить.
            </p>

            <p class="bold">Нужно больше слушать, а не говорить!</p>

            <div class="bold">И сколько времени займёт интервью? У нас с командой остался один день до запуска, ещё не поздно?</div>

            <p>
                Поздно. Одно интервью занимает примерно час-полтора, но вам для исследования нужно провести минимум пять.
                А перед этим найти участников и договориться с ними, а ещё —подготовить сценарий интервью. Один день до запуска — это
                не время для исследований, особенно, таких масштабных, как ГИ.
            </p>

            <div class="bold">Мы уже использовали фокус-группы и теневое копирование. Обязательно ли после них проводить интервью?</div>

            <p>
                Не обязательно, если вам достаточно полученной информации. Если мало, то можно использовать интервью как дополнительный
                метод исследования. Информацию о пользователях можно получить разными способами, их выбор и сочетание всегда остаются за вами.
            </p>

            <div class="bold">Я хочу сэкономить время. Можно я соберу всех участников вместе и буду по очереди задавать им вопросы?</div>

            <p>
                Нельзя. Если собрать всех участников вместе и разговаривать с ними по очереди, личной беседы не получится.
                Собеседники будут слышать друг друга, и это может их запутать, когда до них дойдёт очередь отвечать.
                А кто-то просто не захочет ничего рассказывать, если вокруг много незнакомых людей.
            </p>

            <p>
                <span class="bold">Глубинное интервью</span>
                &#8212; это беседа один на один, доверие, внимание к деталям и открытые вопросы. Если у вас нет времени
                или возможности пообщаться лично, лучше подберите другой метод исследования, например, фокус-группы, где можно собрать
                вместе сразу несколько человек.
            </p>

            <div class="bold">А участников можно тех же пригласить?</div>

            <p>
                Можно и тех же, если к ним остались вопросы. Или можно найти для каждого метода отдельную группу участников,
                чтобы охватить большую аудиторию. Когда нужно собрать разную информацию и времени много, можно использовать несколько
                методов исследования. А если команда большая, то можно разделиться и работать одновременно: например, пока один
                проводит глубинное интервью, другой модерирует фокус-группы, а кто-то наблюдает за пользователями.
            </p>

            <p>
                Таким образом, нужно последовательно провести интервью с каждым из выбранных респондентов. Если у вас есть команда
                из нескольких человек, то поделив весь список намеченных респондентов, выполнить Шаг 3 можно быстрее. Но предварительно
                все интервьюеры должны быть проинструктированы как проводить интервью: задание, зафиксированное в Шагах 1 и 2.
            </p>

            <p>Запишите, по возможности, то, что отвечали респонденты как можно более дословно и занесите результат в соответствующую форму Spaccel.ru.</p>

            <p>
                После каждого интервью внимательно прочитайте полученную от респондентов информацию и впишите
                в поле «Варианты проблем» возможные проблемы, с которыми, на ваш взгляд, сталкивается респондент в своей жизни.
            </p>

        </div>

    </div>

<?php endif; ?>
