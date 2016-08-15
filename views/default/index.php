<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\credit\models\ContractSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('credit', 'สัญญาทั้งหมด');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <div class='box-header'>
        <h3 class='box-title'><?= Html::encode($this->title) ?></h3>
    </div><!--box-header -->

    <div class='box-body pad'>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'id',
                    'format' => 'html',
                    'value' => function($model) {
                        return Html::a($model->id, ['view', 'id' => $model->id]);
                    }
                        ,
                        ],
                        [
                            'attribute' => 'credit_id',
                            'format' => 'html',
                            'value' => function($model) {
                                return Html::a($model->credit_id, ['/credit/default/view', 'id' => $model->credit_id]);
                            }
                                ],
                                [
                                    'label' => 'ลูกค้า',
                                    'attribute' => 'credit.fullname',
                                ],
                                [
                                    'attribute' => 'credit.totalPriceOfAllLabel',
                                    'contentOptions' => ['class' => 'text-right', 'width' => '100']
                                ],
                                [
                                    'attribute' => 'credit.period',
                                    'contentOptions' => ['class' => 'text-right', 'width' => '100']
                                ],
                                [
                                    'attribute' => 'credit.totalPeriodOfAllLabel',
                                    'contentOptions' => ['class' => 'text-right', 'width' => '100']
                                ],
                                [
                                    'attribute' => 'status',
                                    'format' => 'html',
                                    'value' => 'statusLabel',
                                ],
                                'created_at:datetime',
                                // 'staff_id',
                                [
                                    'content' => function($model) {
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span> ดูรายละเอียด', ['view', 'id' => $model->id], ['class' => 'btn btn-primary btn-xs']);
                                    },
                                        ],
                                    ],
                                ]);
                                ?>


    </div><!--box-body pad-->
</div><!--box box-info-->
