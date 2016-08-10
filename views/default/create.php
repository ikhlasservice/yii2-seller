<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use nenad\passwordStrength\PasswordInput;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\Customer */

$this->title = Yii::t('seller', 'สร้างชื่อผู้ใช้ในระบบ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('seller', 'ตัวแทนทั้งหมด'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <div class='box-header'>
        <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
    </div><!--box-header -->

    <div class='box-body pad'>
        <div class='row'>
            <div class='col-sm-3'>
            </div>
            <div class='col-sm-9'>

                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'person.fullname',
                    ],
                ])
                ?>
                <?php $form = ActiveForm::begin(['id' => 'form-user']); ?> 



                <?= $form->field($modelUser, 'username') ?>

                <?= $form->field($modelUser, 'password')->textInput() ?>

                <?= $form->field($modelUser, 'email')->textInput() ?>

                <?= $form->field($modelUser, 'displayname')->textInput(); ?>

                <hr />
                <?=
                $form->field($model, 'account_id')->widget(MaskedInput::className(), [
                    'mask' => '999-9-99999-9'
                ])
                ?>

                <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'bank_id')->dropDownList(ikhlas\seller\models\Bank::getList()) ?>


                <div class="form-group">     
                    <?=
                    Html::submitButton($modelUser->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $modelUser->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
                    ?>

<?= Html::a(Yii::t('app', 'Cancel'), ['user/index'], ['class' => 'btn btn-default']) ?>
                </div>

<?php ActiveForm::end(); ?>


            </div>
        </div>
    </div><!--box-body pad-->
</div><!--box box-info-->