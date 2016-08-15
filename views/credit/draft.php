<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\credit\models\CreditDraftSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('credit', 'ร่างสินเชื่อ');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box box-info'>
    <div class='box-header'>
     <!-- <h3 class='box-title'><?= Html::encode($this->title) ?></h3>-->
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
                    'contentOptions' => ['class' => 'text-nowrap']
                ],
                [
                    'attribute' => 'fullname',
                    'format' => 'html',
                    'value' => function($model) {
                        return Html::a($model->fullname, ['/credit/default/view', 'id' => $model->id]);
                    }
                        ],
                        [
                            'attribute' => 'status',
                            'filter' => \backend\modules\credit\models\Credit::getItemStatus(),
                            'format' => 'html',
                            'value' => 'statusLabel',
                            'headerOptions' => ['class' => 'text-nowrap', 'width' => '80'],
                            'contentOptions' => ['class' => 'text-nowrap']
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => 'datetime',
                            'visible' => Yii::$app->user->can('seller'),
                            'headerOptions' => ['class' => 'text-nowrap', 'width' => '100'],
                            'contentOptions' => ['class' => 'text-nowrap']
                        ],
                        [
                            //'label'=>'',
                            'content' => function($model) {
                                return  Html::a('<span class="glyphicon glyphicon-pencil"></span> แก้ไข', ['/contract/credit/create', 'id' => $model->id], ['class' => 'btn btn-danger']);
                            },
                                    'visible' => Yii::$app->user->can('seller'),
                                ],
                            ],
                        ]);
                        ?>


    </div><!--box-body pad-->
</div><!--box box-info-->
