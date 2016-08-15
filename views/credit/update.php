<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\credit\models\Credit */

$this->title = Yii::t('customer', 'แบบฟอร์มยื่อจองสินเชื่อ {modelClass}: ', [
            'modelClass' => 'Credit',
        ]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('customer', 'Credits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('customer', 'แก้ไข');
?>
<div class='box box-info'>
    <div class='box-header'>
     <!-- <h3 class='box-title'><?= Html::encode($this->title) ?></h3>-->
    </div><!--box-header -->

    <div class='box-body pad'>
        <h3 class='box-title text-center'><?= Yii::t('customer', 'แบบฟอร์มยืนขอสินเชื่อ') ?></h3>
        <?=
        $this->render('_form', [
            'model' => $model,
            'modelDetail' => $modelDetail,
        ])
        ?>


    </div><!--box-body pad-->
</div><!--box box-info-->
