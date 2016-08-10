<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\Customer */
/* @var $form yii\widgets\ActiveForm */
$asset = backend\assets\AppAsset::register($this);

$this->title = Yii::t('system', 'ยืนยืน');
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <div class='box-header'>
     <!-- <h3 class='box-title'><?= Html::encode($this->title) ?></h3>-->
    </div><!--box-header -->

    <div class='box-body pad'>


<?php $form = ActiveForm::begin(); ?>

<div class="row"> 
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        <div class="row"> 
            <div class="col-sm-5"> 
                <?= Html::img($asset->baseUrl . "/images/banner.png", ['width' => '100%']) ?>
            </div>
            <div class="col-sm-3 col-sm-offset-4"> 
                <?= $form->field($model, 'id')->textInput(['readonly' => TRUE]) ?>
            </div>
        </div>
        <hr />
        <?= Html::tag('h2', 'ใบสมัครตัวแทนจำหน่าย(IKHLAS Seller)', ['class' => 'text-center']) ?>

        <div class="row">     
            <div class="col-sm-2 col-sm-offset-9 text-right"> 
                <?= Yii::$app->formatter->asDate($model->created_at) ?>
            </div>
        </div>
        <p>&nbsp;</p>

        <div class="row"> 
            <div class="col-sm-12"> 
                <?php                
                echo $this->render('confirm/person', [
                    'form' => $form,
                    //'model' => $model,
                    'modelPerson' => $modelPerson,
                    'modelPersonDetail' => $modelPersonDetail,
                    'modelAddress' => $modelAddress,
                ]);

                echo $this->render('confirm/contact', [
                    'form' => $form,
                    //'model' => $model,
                    'modelPerson' => $modelPerson,
                    'modelPersonContact' => $modelPersonContact,
                    'modelContactAddress' => $modelContactAddress,
                ]);
                
                echo $this->render('confirm/career', [
                    'form' => $form,
                    //'model' => $model,
                    'modelPerson' => $modelPerson,
                    'modelPersonCareer' => $modelPersonCareer,
                ]);
                
                echo $this->render('confirm/document', [
                    'form' => $form,
                    'model' => $model,
                    'modelPerson' => $modelPerson,
                ]);
                
                echo $this->render('confirm/confirm', [
                    'form' => $form,
                    'model' => $model,
                ]);
                ?>


               

<!--               <div class="form-group">
                    <?= Html::a( Yii::t('system', 'แก้ไข') ,['create','id'=>$model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::submitButton(Yii::t('system', 'ส่งใบสมัคร'), ['class' => 'btn btn-success btn_confirm','disabled'=>'disabled' ,'name'=>'btnConfirm']) ?>
                </div>-->



            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

        </div><!--box-body pad-->
</div><!--box box-info-->

<?=
$this->render('viewComment', [
    'model' => $model,
    'modelConsider' => $modelConsider
]);?>