<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ikhlas\contract\models\CreditConsider;

//if (isset($model->staffMaterial_id) && $model->status > 1):



if ($model->creditConsiders):

    foreach ($model->creditConsiders as $considers):
        ?>
        <div class="box box-widget">
            <div class="box-header with-border">
                <?= $considers->createdBy->displaynameImg ?>
            </div>
            <div class="box-body">
                <?php
                if (isset($considers->status)){
                    echo Html::tag('p',Html::tag('label', $considers->getAttributeLabel('status') . '&nbsp; ').$considers->statusLabel);
                }

                if (isset($considers->comment)) {
                    echo Html::tag('p',Html::tag('label', '<i class="fa fa-comment"></i> ' . $considers->getAttributeLabel('comment') . ' ').$considers->comment);
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
##############################

if (Yii::$app->user->can('staff')&& in_array($model->status, [1,2])):
    $form = ActiveForm::begin();
    echo $form->field($model, "id")->label(false)->hiddenInput();
    ?>
    <div class="box box-widget">
        <div class="box-header with-border">
    <?= common\models\User::getThisUser()->displaynameImg ?>
        </div>
        <div class="box-body">
    <?= Html::tag('b', 'ส่วนของเจ้าที่หน้าที่ผู้อนุมัติ'); ?>
            <?= Html::beginTag('blockquote'); ?>
            <?= $form->field(new CreditConsider, 'status')->radioList(CreditConsider::getItemStatus(), ['prompt' => '']) ?>                          
            <?= $form->field(new CreditConsider, 'comment')->textarea() ?>                          


    <?= Html::endTag('blockquote'); ?>
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


endif;
?>
