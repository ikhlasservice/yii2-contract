<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use ikhlas\persons\models\Person;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\credit\models\Credit */

$this->title = Yii::t('customer', 'เลขที่จองสินเชื่อ ') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('customer', 'Credits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <!-- <div class='box-header'>
     <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
    </div>--><!--box-header -->

    <div class='box-body pad'> 
        <div class="row">             
            <div class="col-xs-6 col-sm-5 "> 
                <?= Html::img(Yii::$app->img->getUploadUrl() . "logo_form.png", ['width' => '100%']) ?>
            </div>
            <div class="col-xs-6 col-sm-5 col-sm-offset-2"> 
                <div class="row">
                    <div class="hidden-xs col-sm-12">&nbsp;</div>

                    <div class="col-xs-9 col-sm-8 text-right">
                        <?= Html::tag('label', $model->getAttributeLabel('id')) ?>                 
                    </div>
                    <div class="col-xs-3 col-sm-4" style="padding-left: 0px;">    
                        <?= Html::tag('span', '&nbsp;' . $model->id . '&nbsp;', ['class' => 'border-bottom-dotted']) ?>
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
        <hr />
        <h3 class='box-title text-center'>
        <?= Yii::t('customer', 'แบบฟอร์มยืนขอสินเชื่อ') ?>
        </h3>

        <div class="row"> 
            <div class="col-xs-4 col-xs-offset-8 col-sm-3 col-sm-offset-9">
                <label><?= Yii::t('credit', 'วันที่') ?></label>
                <?= Yii::$app->formatter->asDate($model->created_at, 'php:d M Y') ?>
            </div>
        </div><!-- /.row -->

        <div class="row"> 
            <div class="col-xs-2 col-sm-2 text-right">
                <label><?= $model->getAttributeLabel('customer_id') ?></label>
            </div><!-- /.col-lg-3 -->
            <div class="col-xs-9 col-sm-6 customer_id">
                <?= $model->customer_id ? $model->customer_id : '' ?>
            </div><!-- /.col-lg-6 -->
        </div><!-- /.row -->

        <div class="row">
            <div class="col-xs-2 col-sm-2 text-right">
                <label><?= (new Person())->getAttributeLabel('fullname') ?></label>
            </div><!-- /.col-lg-3 -->
            <div class="col-xs-9 col-sm-10 fullname">
                <?= $model->customer_id ? $model->customer->person->fullname : '' ?>
            </div><!-- /.col-lg-3 -->
        </div><!-- /.row -->


        <div class="row"> 
            <div class="col-xs-2 col-sm-2 text-right">
                <label><?= $model->customer->person->getAttributeLabel('phone') ?></label>
            </div>
            <div class="col-xs-9 col-sm-10">
                <?= $model->customer->person->phone ?>
            </div>
        </div><!-- /.row -->

        <div class="row"> 
            <div class="col-xs-2 col-sm-2 text-right">
                <label><?= $model->customer->person->getAttributeLabel('address_contact') ?></label>
            </div>
            <div class="col-xs-5 col-sm-4">
                <?= $model->customer->person->addressContact ?>
            </div>
        </div><!-- /.row -->


        <div class="row"> 
            <div class="col-xs-2 col-sm-2 text-right">
                <label><?= $model->customer->getAttributeLabel('profit_id') ?></label>
            </div>
            <div class="col-xs-5 col-sm-4">
                <?= $model->customer->profitPercent ?>
            </div>
        </div><!-- /.row -->

        <div class="row"> 
            <div class="col-xs-2 col-sm-2 text-right">
                <label><?= $model->customer->getAttributeLabel('financial_amount') ?></label>
            </div>
            <div class="col-xs-5 col-sm-4 financial_amount">
                <?= Yii::$app->formatter->asDecimal($model->customer->financial_amount, 2) . ' ' . Yii::t('app', '฿') ?> 
            </div>
        </div><!-- /.row -->

        <div class="row"> 
            <div class="col-xs-2 col-sm-2 text-right">
                <label><?= $model->getAttributeLabel('period') ?></label>
            </div>
            <div class="col-xs-5 col-sm-4">                
                <?= $model->period; ?> งวด
            </div>
        </div><!-- /.row -->



        <br/>
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <div class="table-responsive no-padding">

                    <table class="table table-bordered table-striped margin-b-none table-responsive">
                        <thead>
                            <tr>
                                <th style="text-align: center" width="70" nowrap >
                                    <?= Yii::t('credit', 'ลำดับที่'); ?>
                                </th>
                                <th class="required">
                                    <?= Yii::t('credit', 'สินค้า'); ?>
                                </th>
                                <th class="text-right text-nowrap" style="width: 80px;">
                                    <?= Yii::t('credit', 'จำนวนเงิน'); ?>
                                </th>
                                <th class="text-right text-nowrap" style="width: 80px;">
                                    <?= Yii::t('credit', 'จำนวนชิ้น'); ?>
                                </th>
                                <th class="text-right text-nowrap" style="width: 100px;">
                                    <?= Yii::t('credit', 'รวมต่อเดือน'); ?>
                                </th>
                                <th class="text-right text-nowrap" style="width: 50px;">
                                    <?= Yii::t('credit', 'กำไร'); ?>
                                </th>
                                <th class="text-right text-nowrap" style="width: 50px;">
                                    <?= Yii::t('credit', 'ค่าคอม'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="form-options-body">
                            <?php
                            $totalPriceAll = 0;
                            $totalAmountAll = 0;
                            $totalPeriodOfAll = 0;
                            $totalProfitAll = 0;
                            $totalComAll = 0;
                            foreach ($modelDetails as $index => $modelDetail):
                                $totalPriceAll += $modelDetail->price;
                                $totalAmountAll += $modelDetail->amount;
                                $totalPeriodOfAll += $modelDetail->periodOf;
                                $totalProfitAll += $modelDetail->profitOf;
                                $totalComAll += $modelDetail->comOf;
                                ?>
                                <tr class="form-options-item">
                                    <td class="text-right number" >
                                        <?= ($index + 1) ?>
                                    </td>
                                    <td class="vcenter">
                                        <?= Html::tag('b',$modelDetail->product->title) ?>
                                        <?=Html::tag('p',$modelDetail->product_detail) ?>
                                    </td>
                                    <td class="price text-right">
                                        <?= Yii::$app->formatter->asDecimal($modelDetail->price) ?>                       
                                    </td>
                                    <td class="amount text-right">
                                        <?= $modelDetail->amount ?>                    
                                    </td>
        <!--                                <td class="period text-right">
                                    <?= $modelDetail->credit->periodLabel ?>
                                    </td>-->
                                    
                                    <td class="text-right">
                                        <!--งวดละ-->
                                        <?= Yii::$app->formatter->asDecimal($modelDetail->periodOf, 2) ?>
                                    </td>
                                    <td class="text-right">
                                        <!--กำไร-->
                                        <?= Yii::$app->formatter->asDecimal($modelDetail->profitOf, 2) ?>
                                    </td>
                                    <td class="text-right">
                                        <!--ค่าคอม-->
                                        <?= Yii::$app->formatter->asDecimal($modelDetail->comOf, 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">รวม</th>
                                <th class="totalPrice text-right">
                                    <?= Yii::$app->formatter->asDecimal($totalPriceAll) ?>
                                </th>
                                <th class="totalAmount text-right"> 
                                    <?= $totalAmountAll ?>
                                </th>
                                <th class="text-right"> 
                                    <?= Yii::$app->formatter->asDecimal($totalPeriodOfAll, 2) ?>
                                </th>
                                <th class="text-right"> 
                                    <?= Yii::$app->formatter->asDecimal($totalProfitAll, 2) ?>
                                </th>
                                <th class="text-right"> 
                                    <?= Yii::$app->formatter->asDecimal($totalComAll, 2) ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-5 col-sm-offset-7 col-xs-5 col-xs-offset-7">
                <br/>
                <?= $model->seller->license; ?>
            </div>

        </div>


    </div><!--box-body pad-->



</div><!--box box-info-->


<?=
$this->render('viewComment', [
    'model' => $model
]);
?>