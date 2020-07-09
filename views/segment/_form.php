<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Segment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="segment-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row" style="margin-bottom: 10px;">
        <?= $form->field($model, 'name', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-5">{input}</div><div class="col-md-12">{error}</div>'
        ])->textInput(['maxlength' => true]) ?>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <?= $form->field($model, 'field_of_activity', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 2]) ?>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <?= $form->field($model, 'sort_of_activity', [
                'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 2]) ?>
    </div>


    <script>

        $( function() {

            var minAge = document.getElementById('age_from').value;
            var maxAge = document.getElementById('age_to').value;

            if (minAge == 0 && maxAge == 0 || minAge != 0 && maxAge == 0){
                minAge = 0;
                maxAge = 100;
            }

            $( "#slider_age" ).slider({
                range: true,
                //orientation: 'vertical',
                step: 1,
                min: 0,
                max: 100,
                values: [ minAge, maxAge ],
                slide: function( event, ui ) {
                    $( "#age_from" ).val( ui.values[ 0 ] );
                    $( "#age_to" ).val( ui.values[ 1 ] );
                }
            });
            $( "#age_from" ).val( $( "#slider_age" ).slider( "values", 0 ) );
            $( "#age_to" ).val( $( "#slider_age" ).slider( "values", 1 ) );

            //Изменение местоположения ползунка при вводе данных в первый элемент Input
            $("input#age_from").change(function () {
                var value1 = $("input#age_from").val();
                var value2 = $("input#age_to").val();
                if (parseInt(value1) > parseInt(value2)){
                    value1 = value2;
                    $("input#age_from").val(value1);
                }
                $("#slider_age").slider("values", 0, value1);
            });

            //Изменение местоположения ползунка при вводе данных во второй элемент Input
            $("input#age_to").change(function () {
                var value1 = $("input#age_from").val();
                var value2 = $("input#age_to").val();
                if (parseInt(value1) > parseInt(value2)){
                    value2 = value1;
                    $("input#age_to").val(value2);
                }
                $("#slider_age").slider("values", 1, value2);
            });

        } );
    </script>


    <script>

        $( function() {

            var minIncome = document.getElementById('income_from').value;
            var maxIncome = document.getElementById('income_to').value;

            if (minIncome == 0 && maxIncome == 0 || minIncome != 0 && maxIncome == 0){
                minIncome = 0;
                maxIncome = 10000;
            }

            $( "#slider_income" ).slider({
                range: true,
                //orientation: 'vertical',
                step: 1,
                min: 0,
                max: 10000,
                values: [ minIncome, maxIncome ],
                slide: function( event, ui ) {
                    $( "#income_from" ).val( ui.values[ 0 ] );
                    $( "#income_to" ).val( ui.values[ 1 ] );
                }
            });
            $( "#income_from" ).val( $( "#slider_income" ).slider( "values", 0 ) );
            $( "#income_to" ).val( $( "#slider_income" ).slider( "values", 1 ) );

            //Изменение местоположения ползунка при вводе данных в первый элемент Input
            $("input#income_from").change(function () {
                var value1 = $("input#income_from").val();
                var value2 = $("input#income_to").val();
                if (parseInt(value1) > parseInt(value2)){
                    value1 = value2;
                    $("input#income_from").val(value1);
                }
                $("#slider_income").slider("values", 0, value1);
            });

            //Изменение местоположения ползунка при вводе данных во второй элемент Input
            $("input#income_to").change(function () {
                var value1 = $("input#income_from").val();
                var value2 = $("input#income_to").val();
                if (parseInt(value1) > parseInt(value2)){
                    value2 = value1;
                    $("input#income_to").val(value2);
                }
                $("#slider_income").slider("values", 1, value2);
            });

        } );
    </script>


    <div class="row">
        <?= $form->field($model, 'age_from', [
                'template' => '<div class="col-md-4" style="padding-top: 5px;margin-top: 15px;">{label}<div>{error}</div></div>
                <div class="col-md-2" style="margin-top: 15px;">{input}</div>'
        ])->label('<div>Возраст потребителя</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 0 до 100)</div>')->textInput(['type' => 'number', 'id' => 'age_from']);?>

        <?= $form->field($model, 'age_to', [
                'template' => '<div class="col-md-2">{input}</div>'
        ])->label(false)->textInput(['type' => 'number', 'id' => 'age_to']);?>
    </div>

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4" style=" margin-bottom: 10px;">
            <div class="" id="slider_age"></div>
        </div>
    </div>



    <div class="row">
        <?= $form->field($model, 'income_from', [
                'template' => '<div class="col-md-4" style="padding-top: 5px;margin-top: 15px;">{label}<div>{error}</div></div>
                <div class="col-md-2" style="margin-top: 15px;">{input}</div>'
        ])->label('<div>Доход потребителя (тыс. руб./мес.)</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 0 до 10 000)</div>')->textInput(['type' => 'number', 'id' => 'income_from']);?>

        <?= $form->field($model, 'income_to', [
                'template' => '<div class="col-md-2">{input}</div>'
        ])->label(false)->textInput(['type' => 'number', 'id' => 'income_to']);?>
    </div>

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4" style=" margin-bottom: 10px;">
            <div class="" id="slider_income"></div>
        </div>
    </div>


    <script>

        $( function() {

            var minQuantity = document.getElementById('quantity_from').value;
            var maxQuantity = document.getElementById('quantity_to').value;

            if (minQuantity == 0 && maxQuantity == 0 || minQuantity != 0 && maxQuantity == 0){
                minQuantity = 0;
                maxQuantity = 1000000;
            }

            $( "#slider_quantity" ).slider({
                range: true,
                //orientation: 'vertical',
                step: 1,
                min: 0,
                max: 1000000,
                values: [ minQuantity, maxQuantity ],
                slide: function( event, ui ) {
                    $( "#quantity_from" ).val( ui.values[ 0 ] );
                    $( "#quantity_to" ).val( ui.values[ 1 ] );
                }
            });
            $( "#quantity_from" ).val( $( "#slider_quantity" ).slider( "values", 0 ) );
            $( "#quantity_to" ).val( $( "#slider_quantity" ).slider( "values", 1 ) );

            //Изменение местоположения ползунка при вводе данных в первый элемент Input
            $("input#quantity_from").change(function () {
                var value1 = $("input#quantity_from").val();
                var value2 = $("input#quantity_to").val();
                if (parseInt(value1) > parseInt(value2)){
                    value1 = value2;
                    $("input#quantity_from").val(value1);
                }
                $("#slider_quantity").slider("values", 0, value1);
            });

            //Изменение местоположения ползунка при вводе данных во второй элемент Input
            $("input#quantity_to").change(function () {
                var value1 = $("input#quantity_from").val();
                var value2 = $("input#quantity_to").val();
                if (parseInt(value1) > parseInt(value2)){
                    value2 = value1;
                    $("input#quantity_to").val(value2);
                }
                $("#slider_quantity").slider("values", 1, value2);
            });

        } );
    </script>


    <script>

        $( function() {

            var minMarketVolume = document.getElementById('market_volume_from').value;
            var maxMarketVolume = document.getElementById('market_volume_to').value;

            if (minMarketVolume == 0 && maxMarketVolume == 0 || minMarketVolume != 0 && maxMarketVolume == 0){
                minMarketVolume = 0;
                maxMarketVolume = 100000;
            }

            $( "#slider_market_volume" ).slider({
                range: true,
                //orientation: 'vertical',
                step: 1,
                min: 0,
                max: 100000,
                values: [ minMarketVolume, maxMarketVolume ],
                slide: function( event, ui ) {
                    $( "#market_volume_from" ).val( ui.values[ 0 ] );
                    $( "#market_volume_to" ).val( ui.values[ 1 ] );
                }
            });
            $( "#market_volume_from" ).val( $( "#slider_market_volume" ).slider( "values", 0 ) );
            $( "#market_volume_to" ).val( $( "#slider_market_volume" ).slider( "values", 1 ) );

            //Изменение местоположения ползунка при вводе данных в первый элемент Input
            $("input#market_volume_from").change(function () {
                var value1 = $("input#market_volume_from").val();
                var value2 = $("input#market_volume_to").val();
                if (parseInt(value1) > parseInt(value2)){
                    value1 = value2;
                    $("input#market_volume_from").val(value1);
                }
                $("#slider_market_volume").slider("values", 0, value1);
            });

            //Изменение местоположения ползунка при вводе данных во второй элемент Input
            $("input#market_volume_to").change(function () {
                var value1 = $("input#market_volume_from").val();
                var value2 = $("input#market_volume_to").val();
                if (parseInt(value1) > parseInt(value2)){
                    value2 = value1;
                    $("input#market_volume_to").val(value2);
                }
                $("#slider_market_volume").slider("values", 1, value2);
            });

        } );
    </script>



    <div class="row">
        <?= $form->field($model, 'quantity_from', [
                'template' => '<div class="col-md-4" style="padding-top: 5px;margin-top: 15px;">{label}<div>{error}</div></div>
                <div class="col-md-2" style="margin-top: 15px;">{input}</div>'
        ])->label('<div>Потенциальное кол-во потребителей (тыс. чел.)</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 0 до 1 000 000)</div>')->textInput(['type' => 'number', 'id' => 'quantity_from']);?>

        <?= $form->field($model, 'quantity_to', [
                'template' => '<div class="col-md-2">{input}</div>'
        ])->label(false)->textInput(['type' => 'number', 'id' => 'quantity_to']);?>
    </div>

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4" style=" margin-bottom: 10px;">
            <div class="" id="slider_quantity"></div>
        </div>
    </div>


    <div class="row">
        <?= $form->field($model, 'market_volume_from', [
                'template' => '<div class="col-md-4" style="padding-top: 5px;margin-top: 15px;">{label}<div>{error}</div></div>
                <div class="col-md-2" style="margin-top: 15px;">{input}</div>'
        ])->label('<div>Объем рынка (млн. руб./год)</div><div style="font-weight: 400;font-size: 13px;">(укажите значения в диапазоне от 0 до 100 000)</div>')->textInput(['type' => 'number', 'id' => 'market_volume_from']);?>

        <?= $form->field($model, 'market_volume_to', [
                'template' => '<div class="col-md-2">{input}</div>'
        ])->label(false)->textInput(['type' => 'number', 'id' => 'market_volume_to']);?>
    </div>

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4" style=" margin-bottom: 20px;">
            <div class="" id="slider_market_volume"></div>
        </div>
    </div>


    <div class="row" style="margin-bottom: 10px;">
        <?= $form->field($model, 'add_info', [
            'template' => '<div class="col-md-12">{label}</div><div class="col-md-8">{input}</div>'
        ])->textarea(['rows' => 4]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
