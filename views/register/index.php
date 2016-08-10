<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel ikhlas\seller\models\RegisterSellerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('seller', 'รายการใบสมัครทั้งหมด');
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
                        'attribute' => 'status',
                        'format' => 'html',
                        'filter' => \ikhlas\seller\models\RegisterSeller::getItemStatus(),
                        'value' => 'statusLabel',
                    ],
                    [
                        'attribute' => 'staff_id',
                        'value' => 'staff.displayname',
                    ],
                    'created_at:datetime',
                    // 'doc:ntext',
                    // 'doc_fully',
                    // 'doc_because:ntext',
                    // 'score:ntext',
                    // 'staff_id',
                    // 'staff_receive',
                    // 'staff_date',
                    // 'class',
                    // 'receive_because:ntext',
                    // 'send_at',
                    [
                    'content' => function($model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span> ดูรายละเอียด', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    },
                            
                            'headerOptions' => ['class' => 'text-nowrap', 'width' => '160'],
                        ],
                ],
            ]);
            ?>
<?php Pjax::end(); ?>        </div>
    </div><!--box-body pad-->
</div><!--box box-info-->
