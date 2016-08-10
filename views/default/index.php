<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel ikhlas\seller\models\SellerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('person', 'ตัวแทนจำหน่าย');
$this->params['breadcrumbs'][] = $this->title;

echo \ikhlas\seller\models\Seller::getLastId();
?>
<div class='box box-info'>
    <div class='box-header'>
      <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
    </div><!--box-header -->

    <div class='box-body pad'>


        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                [
                    'attribute' => 'person_id',
                    'value' => 'person.fullname',
                ],
                
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'filter' => \ikhlas\seller\models\Seller::getItemStatus(),
                    'value' => 'statusLabel',
                ],
                [
                    'attribute' => 'registerSeller.class',
                    'format' => 'html',
                    'filter' => \ikhlas\seller\models\RegisterSeller::getItemClassList(),
                    'value' => 'registerSeller.class',
                ],
                'created_at:datetime',
                //'register_seller_id',
                // 'receive_data:ntext',
                // 'staff_id',
               
                [
                            'content' => function($model) {
                                return ($model->user_id!=null) ?Html::a('<span class="glyphicon glyphicon-pencil"></span> ดูรายละเอียด', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']):Html::a('สร้างชื่อผู้ใช้', ['create', 'id' => $model->id],['class'=>'btn btn-warning']);
                            },
                                    'headerOptions' => ['class' => 'text-nowrap', 'width' => '160'],
                                ],
                            ],
                        ]);
                        ?>


    </div><!--box-body pad-->
</div><!--box box-info-->
