<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use ikhlas\persons\models\Person;
?>

<div class="credit-form">

    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'id' => 'dynamic-form'
    ]]);
    ?>
    <?= $form->field($model, 'customer_id')->hiddenInput()->label(false) ?>

    <div class="row"> 
        <div class="col-xs-4 col-xs-offset-8 col-sm-3 col-sm-offset-9">
            <label><?= Yii::t('credit', 'วันที่') ?></label>
            <?= Yii::$app->formatter->asDate($model->created_at, 'php:d M Y') ?>
        </div>
    </div><!-- /.row -->

    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $model->getAttributeLabel('customer_id') ?></label>
        </div><!-- /.col-lg-3 -->
        <div class="col-xs-9 col-sm-6 customer_id">
            <?= $model->customer_id ? $model->customer_id : '' ?>
        </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->

    <div class="row">
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= (new Person())->getAttributeLabel('fullname') ?></label>
        </div><!-- /.col-lg-3 -->
        <div class="col-xs-9 col-sm-10 fullname">
            <?=Html::a(($model->customer_id ? $model->customer->person->fullname : ''),['/customer/default/view/','id'=>$model->customer_id]) ?>
        </div><!-- /.col-lg-3 -->
    </div><!-- /.row -->


    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $model->customer->person->getAttributeLabel('phone') ?></label>
        </div>
        <div class="col-xs-9 col-sm-10">
            <?= $model->customer->person->phone ?>
        </div>
    </div><!-- /.row -->

    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $model->customer->person->getAttributeLabel('address_contact') ?></label>
        </div>
        <div class="col-xs-5 col-sm-4">
            <?= $model->customer->person->addressContact ?>
        </div>
    </div><!-- /.row -->

    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $model->customer->getAttributeLabel('profit_id') ?></label>
        </div>
        <div class="col-xs-5 col-sm-4">
             <?= $model->customer->profitPercent ?>
        </div>
    </div><!-- /.row -->
    
    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $model->customer->getAttributeLabel('financial_amount') ?></label>
        </div>
        <div class="col-xs-5 col-sm-4 financial_amount">
             <?= Yii::$app->formatter->asDecimal($model->customer->financial_amount,2).' '.Yii::t('app','฿') ?> 
        </div>
    </div><!-- /.row -->
    
    <div class="row"> 
        <div class="col-xs-3 col-sm-2 text-right">
            <label><?= $model->getAttributeLabel('period') ?></label>
        </div>
        <div class="col-xs-5 col-sm-4">
            <?= $form->field($model, "period")->label(false)->dropDownList(\backend\modules\credit\models\Credit::getItemPeriod()) ?>
        </div>
    </div><!-- /.row -->



    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <br />
            <div class="table-responsive no-padding">
                <?=
                $this->render('_form_detail_prduct', [
                    'form' => $form,
                    'modelDetail' => $modelDetail,
                    'modelCustomer' => $modelCustomer,
                ])
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-5 col-sm-offset-7">
            <br/>
            <?= $model->seller->license; ?>
        </div>

    </div>


    <div class="row">
        <div class="col-sm-11 col-sm-offset-1">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('customer', 'บันทึก'), ['class' => 'btn btn-primary btn-check', 'name' => 'save']) ?>
                <?= Html::submitButton(Yii::t('customer', 'ยื่นจอง'), ['class' => 'btn btn-success btn-check', 'name' => 'send']) ?>        
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

