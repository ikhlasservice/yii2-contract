<?php

use yii\helpers\Html;
use yii\helpers\BaseStringHelper;
use yii\bootstrap\Modal;

/* @var $this \yii\web\View */
/* @var $content string */

$controller = $this->context;
//$menus = $controller->module->menus;
//$route = $controller->route;
?>
<?php $this->beginContent('@app/views/layouts/main.php') ?>

<div class="row">
    <div class="col-md-3 hidden-print">

        <?php if (Yii::$app->user->can('seller')): ?>
            <?= Html::a('<i class="fa fa-plus-circle"></i> ' . Yii::t('credit', 'ขอจองสินเชื่อ'), ['/contract/credit/create'], ['class' => 'btn btn-success btn-block margin-bottom create-credit']) ?>
        <?php endif; ?>


        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">
                    ระบบจัดการสัญญา
                </h3>

                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">

                <?php
                $nav = new common\models\Navigate();
                echo dmstr\widgets\Menu::widget([
                    'options' => ['class' => 'nav nav-pills nav-stacked'],
                    //'linkTemplate' =>'<a href="{url}">{icon} {label} {badge}</a>',
                    'items' => $nav->menu(11),
                ])
                ?>                 

            </div>
            <!-- /.box-body -->
        </div>
        <!-- /. box -->

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">
                    ระบบจัดการสินเชื่อ
                </h3>

                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">

                <?php
                $nav = new common\models\Navigate();
                echo dmstr\widgets\Menu::widget([
                    'options' => ['class' => 'nav nav-pills nav-stacked'],
                    //'linkTemplate' =>'<a href="{url}">{icon} {label} {badge}</a>',
                    'items' => $nav->menu(6),
                ])
                ?>                 

            </div>
            <!-- /.box-body -->
        </div>





    </div>
    <!-- /.col -->


    <div class="col-md-9">
        <?= $content ?>
        <!-- /. box -->
    </div>
    <!-- /.col -->


</div>

<?php
Modal::begin([
    'header' => Html::tag('h4', 'รายชื่อลูกค้าในสังกัด'),
    'id' => 'modalHistory',
    'size' => 'modal-lg'
]);
echo Html::tag('div', '', ['id' => 'modalContent']);

Modal::end();

$this->registerJs('
    function loadCustomer(){
    $.get( "' . Yii::$app->urlManager->createUrl('/customer/default/list') . '",
            {
               "id":$("#repair-material_id").val(),                    
           },
           function(data){   
           $("#modalHistory").find("#modalContent").html(data);
           $("#modalHistory").modal("show");
          // console.log(data);
           }
        );  
    }
    $(".create-credit").click(function(){
        loadCustomer();
        return false;
    });
    
    

');
?>
<?php $this->endContent(); ?>
