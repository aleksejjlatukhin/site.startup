
<!--Ответы респондентов на вопросы анкеты-->
<div class="container-questions-and-answers">

<?php foreach ($questions as $i => $question) : ?>

    <div class="row container-fluid question-container">
        <div class="col-md-12">Вопрос <?= ($i+1);?>: <span><?= $question->title; ?></span></div>
    </div>

    <?php foreach ($question->answers as $answer) : ?>

    <div class="row container-fluid answer-container">
        <div class="col-md-4 col-lg-3 respond-block"><?= $answer->respond->name; ?></div>
        <div class="col-md-8 col-lg-9"><?= $answer->answer; ?></div>
    </div>
    <?php endforeach; ?>

<?php endforeach; ?>

</div>