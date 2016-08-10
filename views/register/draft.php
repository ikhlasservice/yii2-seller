<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel ikhlas\seller\models\RegisterSellerDraftSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('seller', 'ร่างใบสมัคร');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class='box box-info'>
    <div class='box-header'>
        <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
    </div><!--box-header -->

    <div class='box-body pad'>
        <div class="register-seller-index">


            <?php Pjax::begin(); ?>                            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    [
                        'attribute' => 'person_id',
                        //'format'=>'html',
                        //'filter'=>  \ikhlas\seller\models\Seller::getItemStatus(),
                        'value' => 'person.fullname',
                    ],
                    [
                        'attribute' => 'staff_id',
                        'value' => 'staff.displayname',
                    ],
                    'created_at:datetime',
                    [
                        //'label'=>'',
                        'content' => function($model) {
                            return \backend\widgets\BtnGroup::widget([
                                        'header' => [
                                            'label' => '<span class="glyphicon glyphicon-pencil"></span> แก้ไข',
                                            'router' => ['create', 'id' => $model->id],
                                            'options' => ['class' => 'btn btn-danger'],
                                        ],
                                        'button' => [
                                            'options' => [
                                                'class' => 'btn btn-danger dropdown-toggle'
                                            ]
                                        ],
                                        'sub' => [
                                            [
                                                'label' => '<span class="glyphicon glyphicon-eye-open"></span> ดู',
                                                'router' => ['view', 'id' => $model->id]
                                            ],
                                            [
                                                'label' => '<span class="glyphicon glyphicon-trash"></span> ลบ',
                                                'router' => ['delete', 'id' => $model->id],
                                                'options' => [
                                                    'title' => "Delete",
                                                    'aria-label' => "Delete",
                                                    'data-confirm' => "Are you sure you want to delete this item?",
                                                    'data-method' => "post"
                                                ]
                                            ],
                                        ]
                            ]);
                        },
                                'visible' => Yii::$app->user->can('staff'),
                            ],
                        ],
                    ]);
                    ?>
                    <?php Pjax::end(); ?>        </div>
    </div><!--box-body pad-->
</div><!--box box-info-->
