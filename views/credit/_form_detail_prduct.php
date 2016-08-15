<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\widgets\Typeahead;
use yii\widgets\MaskedInput;
use wbraganca\dynamicform\DynamicFormWidget;
use backend\modules\credit\models\CreditDetail;

$template = '<div>' .
        '<p class="repo-language"><i class="fa fa-info-circle"></i> {{value}}</p>' .
        '<p class="repo-name">{{display}} <i>{{brand}}</i></p><hr style="margin:5px 0" />' .
        '</div>';
?>

<div id="panel-option-values" class="panel panel-default">

    <?php
    DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.form-options-body',
        'widgetItem' => '.form-options-item',
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.delete-item',
        'model' => $modelDetail[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'product_id',
            'amount',
            'period',
            'product_detail',
        ],
    ]);
    ?>

    <table class="table table-bordered table-striped margin-b-none table-responsive">
        <thead>
            <tr>
                <th style="text-align: center" width="70" nowrap >
                    <?= Yii::t('credit', 'ลำดับที่'); ?>
                </th>
                <th class="required">
                    <?= Yii::t('credit', 'สินค้า/รายละเอียดสินค้า'); ?>
                </th>
                <th class="required text-right text-nowrap" style="width: 150px;">
                    <?= Yii::t('credit', 'จำนวนเงิน'); ?>
                </th>
                <th class="required text-right text-nowrap" style="width: 50px;">
                    <?= Yii::t('credit', 'จำนวนชิ้น'); ?>
                </th>
                <th class="text-right text-nowrap" style="width: 50px;">
                    <?= Yii::t('credit', 'รวมต่อเดือน'); ?>
                </th>
                <th class="text-right text-nowrap" style="width: 50px;">
                    <?= Yii::t('credit', 'กำไร'); ?>
                </th>
                <th class="text-right text-nowrap" style="width: 50px;">
                    <?= Yii::t('credit', 'ค่าคอม'); ?>
                </th>
                <th class="text-center text-nowrap" style="width: 30px;"></th>
            </tr>

        </thead>
        <tbody class="form-options-body">
            <?php 
//            echo "<pre>";
//            print_r($modelDetail);
            foreach ($modelDetail as $index => $modelOptionValue): ?>
                <tr class="form-options-item">
                    <td class="text-right number" >
                        <?= ($index + 1) ?>
                    </td>
                    <td class="vcenter title">
                        <?=
                        $form->field($modelOptionValue, "[{$index}]title")->label(false)->widget(Typeahead::classname(), [
                            'options' => [
                                //'id'=>'title',
                                'placeholder' => $modelOptionValue->getAttributeLabel('product.title'),
                                'value' => $modelOptionValue->titleLabel
                            ],
                            'pluginOptions' => [
                                'highlight' => true,
                            //'allowClear' => true
                            ],
                            'dataset' => [
                                [
                                    //'local' => $data,
                                    //'limit' => 10,
                                    //'value'=>$modelOptionValue->titleLabel,
                                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('title')",
                                    'display' => 'value',
                                    'remote' => [
                                        'url' => Url::to(['/product/default/list']) . '?q=%QUERY',
                                        'wildcard' => '%QUERY'
                                    ],
                                    'templates' => [
                                        //'notFound' => '<div class="text-danger" style="padding:0 8px">Unable to find repositories for selected query.</div>',
                                        'suggestion' => new JsExpression("Handlebars.compile('{$template}')")
                                    ]
                                ]
                            ]
                        ]);
                        ?>
                        <?=
                        $form->field($modelOptionValue, "[{$index}]product_detail")->label(false)->textarea(['placeholder'=>$modelOptionValue->getAttributeLabel('product_detail')]);
                        ?>

                    </td>
                    <td class="price">
                        <?=
                                $form->field($modelOptionValue, "[{$index}]price")->label(false)
                                ->widget(MaskedInput::className(), [
                                    'name' => 'price' . $index,
                                    'clientOptions' => [
                                        'alias' => 'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                        ]);
                        ?>             
                    </td>
                    <td class="amount">
                        <?=
                                $form->field($modelOptionValue, "[{$index}]amount")->label(false)
                                ->widget(MaskedInput::className(), [
                                    'name' => 'amount' . $index,
                                    'mask' => '9{1,4}'
                        ]);
                        ?>                    
                    </td>
    <!--                    <td class="period">
                    <?= $form->field($modelOptionValue, "[{$index}]period")->label(false)->dropDownList(CreditDetail::getItemPeriod()) ?>

                    </td>-->
                    <td class="text-right totalRow tdDecimal">                      

                    </td>
                    <td class="text-right profitRow tdDecimal">                      

                    </td>
                    <td class="text-right comRow tdDecimal">                      

                    </td>
                    <td class="text-center vcenter">
                        <button type="button" class="delete-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>

                <th colspan="2" class="text-right text-nowrap tdDecimal" >
                    <?= Yii::t('credit', 'รวม'); ?>
                </th>
                <th class="totalPrice text-right tdDecimal"></th>
                <th class="totalAmount text-right tdDecimal"></th>
                <th class="totalAll text-right tdDecimal" >&nbsp;</th>
                <th class="profitAll text-right tdDecimal" >&nbsp;</th>
                <th class="comAll text-right tdDecimal" >&nbsp;</th>
                <th class="tdDecimal"><button type="button" class="add-item btn btn-success btn-sm"><span class="fa fa-plus"></span> New</button></th>
            </tr>
        </tfoot>
    </table>
    <?php DynamicFormWidget::end(); ?>
</div>


<?php
$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .number").each(function(index) {
        bindDynamicform();
    });
    
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .number").each(function(index) {
        bindDynamicform();
    });
});
bindDynamicform();

var btn_check = $(".btn-check");
var financial_amount = $(".financial_amount").text();
financial_amount = financial_amount.replace(",", "");
financial_amount = (1*financial_amount);

function bindDynamicform(){
    bindNumber();
    bindSum();
    bindAmount();
    bindPeriod();
    bindTotalRow();
    
    jQuery(".dynamicform_wrapper .price input").each(function(index) {
        jQuery(this).on("keyup",function(e){
            //console.log(jQuery(this).val());
            if(!bindSum()){
                e.preventDefault;
            }
            bindTotalRow();            
        });
    });
    jQuery(".dynamicform_wrapper .amount input").each(function(index) {
        jQuery(this).on("keyup",function(){
            console.log(jQuery(this).val());
            bindSum();
            bindAmount();
            bindTotalRow();
        });
    });
    
    jQuery("select[name=\"Credit[period]\"]").change(function(){
        //console.log(jQuery(this).val());
        bindSum();
        //bindPeriod();
        bindTotalRow();
    });
    
    
}

function bindNumber(){
    jQuery(".dynamicform_wrapper .number").each(function(index) {
        jQuery(this).html((index + 1))
    });
 }

var arrPrice = [];
var arrAmount = [];
var arrPeriod = [];


function bindSum(input){ 
    var sumPrice=0;   
    var price =0;
    arrPrice = [];
    jQuery(".dynamicform_wrapper .price input").each(function(index) {
        price = jQuery(this).val();
        price = price.replace(",", "");
        //console.log("price:"+price);
        sumPrice += (1*price);        
        //sumPrice = parseFloat(sumPrice).toFixed(2); 
        //console.log(sumPrice);
        arrPrice.push(price);
    });   
    //console.log("arrPrice:"+arrPrice);
    $(btn_check).attr("disabled",false);
    if(financial_amount<sumPrice){
        alert("เกินวงเงิน"+financial_amount+" "+sumPrice);  
        $(btn_check).attr("disabled",true);
    }
    jQuery(".totalPrice").text(numberWithCommas(sumPrice));
}

function bindAmount(){
    var sumPrice=0;
    var amount = 0;
    arrAmount = [];
    jQuery(".dynamicform_wrapper .amount input").each(function(index) {
        amount = jQuery(this).val();
        sumPrice += (1*amount);       
    arrAmount.push(amount);
    });   
    //console.log("arrAmount:"+arrAmount);
    jQuery(".totalAmount").text(sumPrice);
}
function bindPeriod(){
    var sumPeriod=0;
    var period = 0;
    arrPeriod = [];
    /*
    jQuery(".dynamicform_wrapper .period select").each(function(index) {
        period = jQuery(this).val();
        period = (1*period)+6;
        sumPeriod += (1*period);       
        arrPeriod.push(period);
    });   
    console.log("arrPeriod:"+arrPeriod);
    jQuery(".totalPeriod").text(sumPeriod); */   
}

function bindTotalRow(){
    var sum=0;
    var profitSum = 0;
    var comSum = 0;
    var totalAll=0;
    var profitAll=0;
    var comAll=0;
    var period =$("select[name=\"Credit[period]\"] option:selected").val();
    period =parseInt(period);
    //period=period?period:6;
    
    $.each(arrPrice,function(index){
        var price =parseInt(arrPrice[index]);
        var amount =parseInt(arrAmount[index]);
        amount = amount?amount:1;
        price = price*amount;
        //var period =parseInt(arrPeriod[index]);

        var profit =' . $modelCustomer->profit->val . ';
        console.log("price:"+price+" period:"+period+" profit:"+profit);
        if(price && period && profit){
            profitSum = price*((period*profit)/100);
            sum = (profitSum+price)/period;            
            comSum = profitSum*20/100;
            console.log("totalRow:"+sum);
            jQuery(".totalRow:eq("+index+")").text(sum.toFixed(2));
            jQuery(".profitRow:eq("+index+")").text(profitSum.toFixed(2));
            jQuery(".comRow:eq("+index+")").text(comSum.toFixed(2));
        }
        totalAll+=sum;
        profitAll+=profitSum;
        comAll+=comSum;
    });
    jQuery(".totalAll").text(totalAll.toFixed(2));
    jQuery(".profitAll").text(profitAll.toFixed(2));
    jQuery(".comAll").text(comAll.toFixed(2));
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",");
}

';

$this->registerJs($js);
?>