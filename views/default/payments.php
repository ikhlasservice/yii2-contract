<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\modules\credit\models\Contract */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('credit', 'Contracts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <div class='box-header'>
     <!-- <h3 class='box-title'><?= Html::encode($this->title) ?></h3>-->
    </div><!--box-header -->

    <div class='box-body pad'>

<!--    <p>
<?= Html::a(Yii::t('credit', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('credit', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('credit', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>-->

        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'credit_id',
                'status',
                'total',
                'created_at:datetime',
                'staff_id',
            ],
        ])
        ?>

<?= Html::tag('h3', 'ประวัติการชำระ') ?>

        
        <div class="table-responsive dynamicform_wrapper" style="border: 1px solid #bbb;">
            
            <?=GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'payment_id',
                [
                    'attribute'=>'period',
                    'value'=>function($model){
                     return $model->period .'/'.$model->contract->credit->period;
                    }
                ],
                'old_balance:decimal',
                'amount:decimal',
                'balance:decimal',
                'payment.paid_at:datetime',
                'note:text'
            ]
            ]);
?>
            
            
           

        </div>


    </div><!--box-body pad-->
</div><!--box box-info-->
