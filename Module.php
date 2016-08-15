<?php

namespace backend\modules\contract;

/**
 * contract module definition class
 */
class Module extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\contract\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        $this->layout = 'left-menu.php';
        parent::init();

        // custom initialization code goes here
    }

}
