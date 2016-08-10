<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\persons\models\Person;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\modules\persons\models\Person */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">


    <div class="col-sm-3"> 

    </div>
    <div class="col-sm-9">
        <?= $form->field($model, 'id_card')->textInput(['maxlength' => true]) ?>

        <div class="row">
            <div class="col-sm-2"> 
                <?= $form->field($model, 'prefix_id')->dropDownList(Person::getPrefixList(), ['prompt' => Yii::t('app', 'เลือก')]) ?>
            </div>
            <div class="col-sm-5">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-5">
                <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-5 col-sm-offset-2">
                <?= $form->field($model, 'name_en')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-sm-5">
                <?= $form->field($model, 'surname_en')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-3">
        <?=
        $form->field($model, 'birthday')->widget(DatePicker::classname(), [
            'language' => \Yii::$app->language,
            'value' => date('Y-m-d H:i:s'),
            'removeButton' => false,
            'pickerButton' => ['icon' => 'calendar'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ]
        ]);
        ?>
            </div>
            <div class="col-sm-3">                
        <?= $form->field($model, 'sex')->dropDownList(Person::getItemSex(), ['prompt' => Yii::t('app', 'เลือก')]) ?>
            </div>
        </div>
        <?= $form->field($model, 'address_id')->textInput() ?>

        <?= $form->field($model, 'contact_address_id')->textInput() ?>

        <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'home_phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'facebook')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'doc_delivery')->dropDownList([ 3 => '3', 2 => '2', 1 => '1',], ['prompt' => '']) ?>


    </div>


</div>
