<?php

$this->title = 'Организации';
//$this->registerCssFile('@web/css/communication-settings-style.css');

?>


<?php foreach ($clients as $client) : ?>

    <div class="">
        <?= $client->name; ?>
    </div>

<?php endforeach; ?>
