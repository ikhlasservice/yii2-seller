<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\Customer */

$this->title = $model->person->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('seller', 'ตัวแทนทั้งหมด'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <div class='box-header'>
     <!-- <h3 class='box-title'><?= Html::encode($this->title) ?></h3>-->
    </div><!--box-header -->

    <div class='box-body pad'>

<!--    <p>
        <?= Html::a(Yii::t('system', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('system', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('system', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>-->
        <div class='row'>
            <div class='col-sm-3'>
                <?= Html::img($model->person->image, ['width' => '100%']) ?>
            </div>
            <div class='col-sm-9'>
                <?php
                echo DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table '],
                    'template' => '<tr><th width="100" class="text-right text-nowrap table-responsive">{label}</th><td>{value}</td></tr>',
                    'attributes' => [
                        'id',
                        [
                            'attribute' => 'person.fullname',
                            'value' => $model->person->fullname . " " . ($model->person->fullname_en?'('.$model->person->fullname_en.')':''),
                        ],                       
                        [
                            'attribute' => 'person.telephone',
                            'value' => implode(', ',[$model->person->telephone,$model->person->home_phone])
                        ], 
                        [
                            'attribute' => 'person.email',
                            'format' => 'email',
                            'value' => $model->person->email
                        ],
                        [
                            'attribute' => 'account_id',
                            //'format' => 'email',
                            'value' => $model->account_id.'('.$model->account_name.')'.$model->bank->title
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'html',
                            'value' => $model->statusLabel
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
        
        
        <div class='row'>
            <div class='col-sm-12'>
                <?php
                echo Tabs::widget([
                    'items' => [
                        [
                            'label' => 'ข้อมูลทั่วไป',
                            'content' => $this->render('view/person',['model'=>$model->person]),
                            'active' => true
                        ],
                        [
                            'label' => 'ข้อมูลการลูกค้า',
                        ],
                        [
                            'label' => 'ข้อมูลการชำระ',
                            //'content' => 'ข้อมูลการชำระ',
                            //'headerOptions' => 'Anim pariatur cliche...',
                            
                            //'options' => ['id' => 'myveryownID'],
                        ],
                    ]
                ]);
                ?>


            </div>
        </div>
    </div><!--box-body pad-->
</div><!--box box-info-->
