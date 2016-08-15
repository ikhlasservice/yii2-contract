<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\credit\models\CreditSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="credit-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'reserved_at') ?>

    <?php // echo $form->field($model, 'seller_id') ?>

    <?php // echo $form->field($model, 'staff_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('customer', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('customer', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
