<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use backend\modules\persons\models\Person;
use ikhlas\seller\models\RegisterSeller;
use yii\bootstrap\Modal;
?>

<hr />

<?= Html::tag('h4', '5. ' . Yii::t('person', 'สถานที่ที่สะดวกให้จัดส่งเอกสาร')); ?>

<div class="row">
    <div class="col-sm-11 col-sm-offset-1">    
       <?= Html::tag('label', $modelPerson->getAttributeLabel('doc_delivery')) ?>
        <?= $modelPerson->docDeliveryLabel ?>
    </div>  
</div>


<hr />
<?= Html::tag('h4', '6. ' . Yii::t('person', 'เอกสารประกอบการสมัคร')); ?>

<?= Html::tag('span', 'เพื่อให้ใบสมัครของท่านได้รับการพิจารณาอย่างรวดเร็ว กรุณากรอกใบสมัครให้ครบถ้วน และเตรียมเอกสารตามรายการต่อไปนี้ พร้อมร้บรองสำเนาถูกต้องทุกฉบับ') ?>
<?php /* = $form->field($model, 'doc')->textInput() */ ?>
<p>
<?php 

foreach ($model->getItemDocList() as $key => $val):
    echo ($key)." ".$val."<br/>";
endforeach;

?>
    </p>
<div class="row">
    <div class="col-sm-11 col-sm-offset-1">    
       
    </div>  
</div>

 <?=Html::tag('h4',Yii::t('person', 'ข้อกำหนดแลเงื่อนไงการเป็นตัวแทนของบริษัท อิคลาส เซอร์วิสจำกัด'),['class'=>'text-center'])?>
                <div class="row">
                    <div class="col-sm-12">
                        <div style="padding: 10px 10px">
                    <?php
                    foreach ($model->getItemCondition() as $key => $val) {
                        echo $key . ". " . $val . '<br/>';
                    }
                    ?>
                        </div>
                   
                    </div>
                </div>




<?php
if (!$model->isNewRecord):
   
    $this->registerJs(' 
    
    
    $("input[name=\'Image[name_file]\']").on("fileuploaded", function(event, data, previewId, index) {
    //alert(55);
    var form = data.form, files = data.files, extra = data.extra,
        response = data.response.files, reader = data.reader;
    
        response = data.response.files
        console.log("1"+form+"2"+files+"3"+extra+"4"+response+"5"+reader);
        console.log("File batch upload complete"+files);
        loadImg(data.response.path,data.response.files);
        $("#modal-img").modal("hide");
    });

var loadImg = function(path,id){
    $("#slide-img_id").val(id);
    $(".img_id").css("background","url("+path+id+")");
}


');

endif;
?>