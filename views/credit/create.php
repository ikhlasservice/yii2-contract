<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\credit\models\Credit */

$this->title = Yii::t('customer', 'แบบฟอร์มยื่นจองสินเชื่อ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('contract', 'สินเชื่อ&สัญญา'), 'url' => ['/contract']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('contract', 'สินเชื่อ'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <!--    <div class='box-header'>
            <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
        </div>box-header -->

    <div class='box-body pad'>
        <div class="row"> 
            <div class="col-xs-6 col-sm-5 "> 
                <?= Html::img(Yii::$app->img->getUploadUrl() . "logo_form.png", ['width' => '100%']) ?>
            </div>
            <div class="col-xs-6 col-sm-5 col-sm-offset-2"> 
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-12">&nbsp;</div>
                    
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
        <h3 class='box-title text-center'><?= Yii::t('customer', 'แบบฟอร์มยืนขอสินเชื่อ') ?></h3>
        <?=
        $this->render('_form', [
            'model' => $model,
            'modelDetail' => $modelDetail,
            'modelCustomer' => $modelCustomer,
        ])
        ?>

    </div><!--box-body pad-->
</div><!--box box-info-->
