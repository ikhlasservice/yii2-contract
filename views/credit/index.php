<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\credit\models\CreditSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('customer', 'รายการยืนจองสินเชื่อ');
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
                    'headerOptions' => ['class' => 'text-nowrap', 'width' => '50'],
                    'contentOptions' => ['class' => 'text-nowrap text-right']
                ],
                [
                    'attribute' => 'customer_id',
                    'headerOptions' => ['class' => 'text-nowrap', 'width' => '50'],
                    'contentOptions' => ['class' => 'text-nowrap text-right']
                ],
                [
                    'attribute' => 'fullname',
                    'format' => 'html',
                    'value' => function($model) {
                        return Html::a($model->fullname, ['/credit/default/view', 'id' => $model->id]);
                    }
                        ],
                        [
                            'attribute' => 'totalPriceOfAllLabel',
                            'contentOptions' => ['class' => 'text-right', 'width' => '100']
                        ],
                        [
                            'attribute' => 'period',
                            'contentOptions' => ['class' => 'text-right', 'width' => '100']
                        ],
                        [
                            'attribute' => 'totalPeriodOfAllLabel',
                            'contentOptions' => ['class' => 'text-right', 'width' => '100']
                        ],
                        [
                            'attribute' => 'status',
                            'filter' => \backend\modules\credit\models\Credit::getItemStatus(),
                            'format' => 'html',
                            'value' => 'statusLabel',
                            'headerOptions' => ['class' => 'text-nowrap', 'width' => '80'],
                        ],
                        [
                            'attribute' => 'staff_id',
                            'value' => 'staff.displayname',
                            'visible' => Yii::$app->user->can('seller')
                        ],
                        [
                            'attribute' => 'seller_id',
                            'value' => 'seller.displayname',
                            'visible' => Yii::$app->user->can('staff')
                        ],
                        [
                            'attribute' => 'reserved_at',
                            'format' => 'datetime',
                            'headerOptions' => ['class' => 'text-nowrap', 'width' => '100'],
                            'options' => ['class' => 'text-nowrap', 'width' => '100']
                        ],
                        [
                            'content' => function($model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span> ดูรายละเอียด', ['/contract/credit/view', 'id' => $model->id], ['class' => 'btn btn-primary btn-xs']);
                            },
                                    //'visible' => Yii::$app->user->can('seller'),
                                    'headerOptions' => ['class' => 'text-nowrap',],
                                ],
                            ],
                        ]);
                        ?>


    </div><!--box-body pad-->
</div><!--box box-info-->


