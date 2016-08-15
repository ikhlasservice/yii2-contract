<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\modules\credit\models\Contract;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//print_r($model);

if ($listDataProvider) {
    ?>

    <div class="row">
        <div class="col-sm-12">
            <?=
            GridView::widget([
                'dataProvider' => $listDataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'credit_id',
                    'credit.customer_id',
                    'credit.fullname',
                    'credit.totalPay',
                    'credit.period',
                    [
                        'label' => '',
                        'format' => 'html',
                        'headerOptions' => ['width' => '100'],
                        'content' => function($model) {
                    return Html::button('เลือก', [
                                'class' => 'btn select-data btn-primary',
                                'disabled'=>(in_array($model->id,Yii::$app->params['listId'])?' disabled':''),
                                'value' => $model->id
                    ]);
                },
                    ],
                //'problem:text',                   
                ]
            ]);
        }
        ?>
    </div>
</div>

<?php
if (isset($ajax)) {
    $this->registerJs(' 
        var rowNo = ' . $rowNo . ';
$(".select-data").each(function(){
    var id = $(this).val();    
    $(this).click(function(){     
        //alert(id);
        $.getJSON( "' . Yii::$app->urlManager->createUrl('/credit/contract/load-data') . '",
            {
               "id":id,                    
           },
           function(data){   
                //console.log(data); 
                console.log(rowNo); 
                $(".dynamicform_wrapper .contract_id:eq("+rowNo+") input").val(data.id);          
                $(".dynamicform_wrapper .customer_id:eq("+rowNo+")").html(data.customer_id);      
                $(".dynamicform_wrapper .fullname:eq("+rowNo+")").text(data.fullname);       
                $(".dynamicform_wrapper .products:eq("+rowNo+")").text(data.products);       
                $(".dynamicform_wrapper .amount:eq("+rowNo+") input").val(data.totalPay);
                $("#modalHistory").modal("hide");
           }          
        );  


    });
});
');
}

    