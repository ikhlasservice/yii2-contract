<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\credit\models\ContractSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'credit_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'total') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'staff_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('credit', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('credit', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
