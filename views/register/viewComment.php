<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ikhlas\seller\models\RegisterSeller;
use backend\modules\customer\models\RegisterCustomerConsider;
use yii\widgets\MaskedInput;

//if (isset($model->staffMaterial_id) && $model->status > 1):



if ($model->registerSellerProgresses):

    foreach ($model->registerSellerProgresses as $considers):
        ?>
        <div class="box box-widget">
            <div class="box-header with-border">
                <?= $considers->createdBy->displaynameImg ?>
            </div>
            <div class="box-body">
                <?php
                //print_r($considers->data);
                if (isset($considers->data['doc_fully'])) {
                    echo Html::tag('label', $considers->getAttributeLabel('doc_fully'));
                    echo Html::beginTag('blockquote');
                    foreach (RegisterSeller::getItemDocFullyList() as $key => $val):
                        $check = $considers->data['doc_fully'] == $key;
                        echo Html::tag('p', '<i class="fa ' . ($check ? 'fa-check-circle' : 'fa-circle-o') . '"></i> ' . $val, ['class' => $check ? 'text-green' : '']);
                    endforeach;
                    if ($considers->data['doc_fully'] == '0'):
                        echo Html::tag('label', $considers->getAttributeLabel('doc_comment'));
                        echo $considers->data['doc_comment'];
                    endif;
                    echo Html::endTag('blockquote');
                    echo Html::tag('hr');
                }

                
                if (isset($considers->data['staff_receive'])) {
                    echo Html::tag('label', $considers->getAttributeLabel('should_receive'));
                    echo Html::beginTag('blockquote');
                    foreach (RegisterSeller::getItemStaffReceiveList() as $key => $val):
                        $check = $considers->data['staff_receive'] == $key;
                        echo Html::tag('p', '<i class="fa ' . ($check ? 'fa-check-circle' : 'fa-circle-o') . '"></i> ' . $val, ['class' => $check ? 'text-green' : '']);
                    endforeach;

                    if ($considers->data['staff_receive_because']) {
                        echo Html::tag('label', '<i class="fa fa-comment"></i> ' . $considers->getAttributeLabel('comment') . ' &nbsp;');
                        echo $considers->data['staff_receive_because'];
                    }
                    if (isset($considers->data['class'])) {
                        echo Html::tag('label', '<i class="fa fa-money"></i> ' . $model->getAttributeLabel('class') . ' &nbsp;');
                        echo $considers->data['class'] . ' &nbsp;';
                      
                    }

                    echo Html::endTag('blockquote');
                }
                ?>
            </div>


            <div class="box-footer box-comments">
                <div class="box-comment">
                    <div class="comment-text"> 
                        <span class="username">                            
                            <span class="text-muted pull-right"> 
                                <?= Html::tag('label', '<i class="fa fa-clock-o"></i> ' . $considers->getAttributeLabel('created_at')) ?>
                                <?= Yii::$app->formatter->asDatetime($considers->created_at) ?>
                            </span>
                        </span>
                    </div><!-- /.comment-text -->
                </div><!-- /.box-comment -->
            </div>

        </div>
        <?php
    endforeach;
endif;
?>


<?php
if (Yii::$app->user->can('staff')):
    $form = ActiveForm::begin();
    ?>
    <div class="box box-widget">
        <div class="box-header with-border">
            <?= common\models\User::getThisUser()->displaynameImg ?>
        </div>
        <div class="box-body">


            <?= Html::tag('b', 'ส่วนของเจ้าหน้าที่รับสมัคร'); ?>
            <?= Html::beginTag('blockquote'); ?>
            <?= $form->field($model, 'doc_fully')->radioList(RegisterSeller::getItemDocFullyList(), ['prompt' => '']) ?>                           
            <div class="doc_because">
                <?= $form->field($model, 'doc_because')->textarea(['rows' => 2]) ?>
            </div>
            <?= Html::endTag('blockquote'); ?>

            
            

            <?= Html::tag('b', 'ส่วนของเจ้าหน้าที่ผู้อนุมัติ'); ?>
            <?= Html::beginTag('blockquote'); ?>
            <?= $form->field($model, 'staff_receive')->radioList(RegisterSeller::getItemStaffReceiveList(), ['prompt' => '']) ?>                           
            <div class="receive receive1">
                <?= $form->field($model, 'class')->dropDownList(RegisterSeller::getItemClassList()) ?>
            </div>
            <div class="receive receive2">
                <?= $form->field($model, 'receive_because')->textarea(['rows' => 2]) ?>
            </div>
            <?= Html::endTag('blockquote'); ?>


            
            
            <?php /*= Html::tag('b', 'ส่วนของเจ้าหน้าที่งานทะเบียน'); ?>
            <?= Html::beginTag('blockquote'); ?>
            <?= $form->field($model, 'doc_fully')->radioList(ikhlas\seller\models\RegisterSeller::getItemDocFullyList(), ['prompt' => '']) ?>                           
            <div class="doc_because">
                <?= $form->field($model, 'doc_because')->textarea(['rows' => 6]) ?>
            </div>
            <?= Html::endTag('blockquote');*/ ?>




        </div>



        <div class="box-footer box-comments">
            <div class="box-comment">
                <div class="comment-text"> 
                    <div class="form-group">               
                        <?= Html::submitButton(Yii::t('system', 'บันทึก'), ['class' => 'btn btn-success btn_confirm', 'name' => 'btnConfirm']) ?> 

                        <?= Html::a(Yii::t('system', 'ยกเลิก'), ['create', 'id' => $model->id], ['class' => 'btn btn-link']) ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php
    ActiveForm::end();

    $this->registerCss(" 
        .staff_receive{
            display:none;
        }            
    ");

    $this->registerJs(" 
        $('.doc_because').hide();
        $('input[name=\"RegisterSeller[doc_fully]\"]').change(function(){
            var doc_fully =$(this).val();
            if(doc_fully==2){
                $('.doc_because').show();
            }else{
                $('.doc_because').hide();
            }
        });
        
        $('.receive').hide();
        $('input[name=\"RegisterSeller[staff_receive]\"]').change(function(){
            var receive =$(this).val();
            $('.receive').hide();
            $('.receive.receive'+receive).show();
        });

    ");

endif;
?>
