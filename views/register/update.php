<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ikhlas\seller\models\RegisterSeller */

$this->title = Yii::t('seller', 'Update {modelClass}: ', [
    'modelClass' => 'Register Seller',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('seller', 'Register Sellers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('seller', 'Update');
?>
<div class='box box-info'>
    <div class='box-header'>
        <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
    </div><!--box-header -->

    <div class='box-body pad'>
        <div class="register-seller-update">

            <!--<h1><?= Html::encode($this->title) ?></h1>-->
            
            <?= $this->render('_form', [
            'model' => $model,
            ]) ?>

        </div>
    </div><!--box-body pad-->
</div><!--box box-info-->
