<?php

namespace ikhlas\contract\models;

use Yii;
use yii\helpers\ArrayHelper;
use ikhlas\product\models\Product;

/**
 * This is the model class for table "credit_detail".
 *
 * @property integer $id
 * @property integer $credit_id
 * @property integer $product_id
 * @property string $price
 * @property integer $amount
 * @property integer $period
 *
 * @property Credit $credit
 * @property Product $product
 */
class CreditDetail extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'credit_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'amount'], 'required'],
            [['credit_id', 'product_id', 'amount', 'period'], 'integer'],
            [['price', 'product_detail'], 'string'],
            [['credit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Credit::className(), 'targetAttribute' => ['credit_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('contract', 'ID'),
            'credit_id' => Yii::t('contract', 'รหัสจองสินเชื่อ'),
            'product_id' => Yii::t('contract', 'รหัสสินค้า'),
            'product_detail' => Yii::t('contract', 'รายละเอียดสินค้า'),
            'price' => Yii::t('contract', 'ราคา'),
            'amount' => Yii::t('contract', 'จำนวน'),
            'period' => Yii::t('contract', 'งวด'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredit() {
        return $this->hasOne(Credit::className(), ['id' => 'credit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    ###########################

    public $title;

    public function getTitleLabel() {
        return $this->product_id ? $this->product->title : NULL;
    }

    public static function deleteByIDs($id) {
        //print_r($id);
        $model = self::deleteAll(['id' => $id]);
        //return $model->deleteAll();
    }
    
    /**
     * @return double กำไรต่อรายการ
     */
    public function getProfitOf(){
        return $this->price * ($this->credit->period * $this->credit->customer->profit / 100);
       
    }
    
    /**
     * @return double ยอดรวมต่อรายการ
     */
    public function getPriceOf(){
         return ($this->profitOf + $this->price);
    }
    
    /**
     * @return double ค่างวดต่อรายการ
     */
    public function getPeriodOf(){
         return $this->priceOf  / $this->credit->period;
    }
    
    /**
     * @return double ค่าคอมต่อรายการ
     */
    public function getComOf(){
         return ($this->profitOf  * 20 / 100);
    }   
    

}
