
<!--Ответы респондентов на вопросы интервью-->
<?php foreach ($questions as $i => $question) : ?>

    <table>

        <tr>
            <td colspan="2" style="color: #ffffff; background: #707F99; font-size: 18px; margin: 2px 0; padding: 10px;">
                Вопрос <?= ($i+1); ?>: <?= $question->title; ?>
            </td>
        </tr>

        <?php foreach ($question->answers as $answer) : ?>

            <tr style="color: #4F4F4F; background: #F2F2F2;">
                <td style="width: 200px; font-size: 16px; padding: 10px;"><?= $answer->respond->name; ?></td>
                <td style="width: 480px; font-size: 13px; padding: 10px;"><?= $answer->answer; ?></td>
            </tr>

        <?php endforeach; ?>

    </table>

<?php endforeach; ?>



