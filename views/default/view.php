<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\persons\models\Person;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\credit\models\Credit */

$this->title = Yii::t('contract', 'เลขที่จองสินเชื่อ ') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('contract', 'Credits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>

    <div class='box-body pad'> 
        <div class="row">             
            <div class="col-xs-6 col-sm-5 "> 
                <?= Html::img(Yii::$app->img->getUploadUrl() . "logo_form.png", ['width' => '100%']) ?>
            </div>

            <div class="col-xs-6 col-sm-5 col-sm-offset-2"> 
                <div class="row">

                    <div class="col-xs-9 col-sm-8 text-right">
                        <?= Html::tag('label', $model->getAttributeLabel('id')) ?>                 
                    </div>
                    <div class="col-xs-3 col-sm-4" style="padding-left: 0px;">    
                        <?= Html::tag('span', '&nbsp;' . $model->id . '&nbsp;', ['class' => 'border-bottom-dotted']) ?>
                    </div>

                    <!--                    <div class="col-xs-9 col-sm-8 text-right">
                    <?= Html::tag('label', $model->credit->getAttributeLabel('id')) ?>                    
                                        </div>
                                        <div class="col-xs-3 col-sm-4" style="padding-left: 0px;">
                    <?= $model->credit->id ?>
                                        </div>-->

                    <div class="col-xs-9 col-sm-8 text-right">
                        <?= Html::tag('label', $model->credit->customer->getAttributeLabel('id')) ?>                    
                    </div>
                    <div class="col-xs-3 col-sm-4" style="padding-left: 0px;">
                        <?= $model->credit->customer->id ?>
                    </div>

                    <div class="col-xs-9 col-sm-8 text-right">
                        <?= Html::tag('label', $model->credit->seller->seller->getAttributeLabel('id')) ?>                    
                    </div>
                    <div class="col-xs-3 col-sm-4" style="padding-left: 0px;">
                        <?= $model->credit->seller->seller->id ?>
                    </div>

                    <div class="col-xs-9 col-sm-8 text-right">
                        <?= Html::tag('label', $model->getAttributeLabel('status')) ?>                    
                    </div>
                    <div class="col-xs-3 col-sm-4" style="padding-left: 0px;">
                        <?= $model->statusLabel ?>
                    </div>

                </div>
            </div>
        </div>


        <h3 class='box-title text-center'><?= Yii::t('contract', 'สัญญาณซื้อขาย') ?></h3>

        <div class="row"> 
            <div class="col-xs-4 col-xs-offset-5 col-sm-3 col-sm-offset-9">
                <label><?= Yii::t('credit', 'วันที่') ?></label>
                <?= Yii::$app->formatter->asDate($model->created_at, 'php:d M Y') ?>
            </div>
        </div><!-- /.row -->


        <div class="row"> 
            <div class="col-xs-4 col-sm-10 col-sm-offset-1">
                <label><?= Yii::t('credit', 'เขียนที่') ?></label>
                <label><?= Yii::t('credit', 'บริษัท อิคลาส เซอร์วิส จำกัด') ?></label>

            </div>
        </div><!-- /.row -->




        <div class="row"> 
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <p style="text-indent: 15%;">
                    สัญญาฉบับนี้ทำขึ้นระหว่าง
                    <span style="border-bottom: 1px solid #aaa;">&nbsp;&nbsp;&nbsp;
                        <?= $model->credit->customer_id ? $model->credit->customer->person->fullname : '' ?>
                        &nbsp;&nbsp;&nbsp;</span>

                    <?= (new Person())->getAttributeLabel('id_card') ?>
                    <span style="border-bottom: 1px solid #aaa;">&nbsp;&nbsp;&nbsp;
                        <?= $model->credit->customer->person->id_card; ?>
                        &nbsp;&nbsp;&nbsp;</span>

                    <?= $model->credit->customer->person->getAttributeLabel('address_contact') ?>
                    <span style="border-bottom: 1px solid #aaa;">&nbsp;&nbsp;&nbsp;
                        <?= $model->credit->customer->person->addressContact ?>
                        &nbsp;&nbsp;&nbsp;</span>

                    <?= $model->credit->customer->person->getAttributeLabel('address_contact') ?>
                    <span style="border-bottom: 1px solid #aaa;">&nbsp;&nbsp;&nbsp;
                        <?= $model->credit->customer->person->addressContact ?>
                        &nbsp;&nbsp;&nbsp;</span>

                    ซึ่งต่อไปในสัญญานี้จะเรียกว่า “ผู้ซื้อ” ฝ่ายหนึ่ง กับ บริษัท อิคลาส เซอร์วิส จำกัด
                </p>
                <p >                    
                    โดย <span style="border-bottom: 1px solid #aaa;">&nbsp;&nbsp;&nbsp;
                        นายอัสอารี  ลาเต๊ะ
                        &nbsp;&nbsp;&nbsp;</span>ผู้รับมอบอำนาจตามหนังสือมอบอำนาจ
                    สำนักงานตั้งอยู่เลขที่ 3/3 ม.2 ต.บุดี อ.เมืองยะลา จ.ยะลา 95000 ซึ่งต่อไปในสัญญานี้ จะเรียกว่า “ผู้ขาย” อีกฝ่ายหนึ่ง

                </p>

                <p style="text-indent: 15%;">
                    ทั้งสองฝ่ายตกลงทำสัญญากันดังมีข้อความต่อไปนี้
                </p>
                <p style="text-indent: 15%;">
                    <b>ข้อ 1.</b>  ทรัพย์สินที่ซื้อขาย
                </p>
                <p style="text-indent: 15%;">
                    ผู้ซื้อตกลงซื้อและผู้ขายตกลงขาย และได้รับมอบทรัพย์สินที่ซื้อไปจากผู้ขายแล้วในวันทำสัญญานี้ในสภาพเรียบร้อย ถูกต้องครบถ้วน และได้ตรวจดูเป็นที่พอใจแล้ว  คือ
                </p>



                <?php
                foreach ($model->credit->creditDetails as $key => $modelDetails):
                    echo Html::tag('label', "สินค้าตัวที่ " . ($key + 1));
                    echo DetailView::widget([
                        'model' => $modelDetails,
                        'attributes' => [
                            'product.title',
                            'product_detail',
                            [
                                'attribute' => 'price',
                                'value' => $modelDetails->price . " (" . Yii::$app->number->wordThai($modelDetails->price) . ")"
                            ]
                        ],
                    ]);
                endforeach;
                ?>


                <p style="text-indent: 15%;">
                    <b>ข้อ 2.</b> การชำระเงินค่าทรัพย์สินและการวางเงินล่วงหน้า
                </p>

                <p style="text-indent: 15%;">
                    ทรัพย์สินที่ซื้อตามข้อ 1.  ผู้ซื้อสัญญาว่าจะชำระค่าทรัพย์สินให้ผู้ขายในวันทำสัญญานี้เป็นเงิน…………………………….บาท (…………………………………….……………….) และต่อไปจะนำเงินงวดไปชำระ
                    ณ สถานที่ซึ่งเป็นภูมิลำเนาของผู้ขาย  ในกรณีผู้ขายส่งตัวแทนไปเรียกชำระก็ให้ถือว่าเป็นเพียงการให้ความสะดวกแก่ผู้ซื้อเท่านั้น  มิใช่เป็นการเปลี่ยนแปลงสถานที่ชำระเงิน

                </p>
                <p style="text-indent: 15%;">
                    จำนวนเงินงวดที่จะต้องชำระต่อไป ผู้ซื้อจะชำระ 
                </p>
                <p >งวดละ<span style="border-bottom: 1px solid #aaa;">&nbsp;&nbsp;&nbsp;<?=Yii::$app->formatter->asDecimal($model->credit->totalPay,2) ?>&nbsp;&nbsp; </span>  บาท (<span style="border-bottom: 1px solid #aaa;">&nbsp;&nbsp;&nbsp;<?=Yii::$app->number->wordThai(Yii::$app->formatter->asDecimal($model->credit->totalPay,2)) ?>&nbsp;&nbsp; </span>  )รวม <span style="border-bottom: 1px solid #aaa;">&nbsp;&nbsp;&nbsp;<?=$model->credit->period?>&nbsp;&nbsp; </span> งวด
                
                </p>
                <p >
                    <?php $total = $model->credit->totalPay*$model->credit->period?>
                เป็นเงิน<span style="border-bottom: 1px solid #aaa;">&nbsp;&nbsp;&nbsp;<?=Yii::$app->formatter->asDecimal($total)?>&nbsp;&nbsp; </span>บาท (<span style="border-bottom: 1px solid #aaa;">&nbsp;&nbsp;&nbsp;<?=Yii::$app->number->wordThai(Yii::$app->formatter->asDecimal($total,2)) ?>&nbsp;&nbsp; </span>)
                </p>
                <p >เริ่มชำระงวดแรกวันที่……………………………….. งวดต่อไปชำระทุกวันที่ 5 ของเดือนจนกว่าจะครบตามรายการข้างต้น
                    กำหนดชำระให้แล้วเสร็จภายในวันที่…………………………………………………………
                </p>
                <?= $this->render('viewOther') ?>

                <br />
                <div class="row">
                    <div class="col-sm-6 text-center">
                        ลงชื่อ……………………………………….. ผู้ซื้อ<br/>
                        (<span style="border-bottom: 1px dotted #aaa;">&nbsp;&nbsp;&nbsp;<?=$model->credit->customer->person->fullname?>&nbsp;&nbsp;&nbsp;</span>)	
                    </div>
                    <div class="col-sm-6 text-center">
                        ลงชื่อ……………………………………….. ผู้ขาย<br/>
                        (…………………………………………)
                    </div>

                </div>
                <p>&nbsp;</p>
                <div class="row">
                    <div class="col-sm-6 text-center">
                        ลงชื่อ……………………………………….. ผู้พยาน<br/>
                        (…………………………………………)	
                    </div>
                    <div class="col-sm-6 text-center">
                        ลงชื่อ……………………………………….. ผู้พยาน<br/>
                        (…………………………………………)
                    </div>

                </div>


                <p>&nbsp;</p>


            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->