<?php

namespace backend\modules\contract\models;

use Yii;
use yii\helpers\ArrayHelper;
use backend\modules\customer\models\Customer;
use common\models\User;

/**
 * This is the model class for table "credit".
 *
 * @property integer $id
 * @property string $customer_id
 * @property integer $status
 * @property integer $period
 * @property integer $created_at
 * @property integer $reserved_at
 * @property integer $seller_id
 * @property integer $staff_id
 *
 * @property Contract[] $contracts
 * @property Customer $customer
 * @property User $seller
 * @property User $staff
 * @property CreditConsider[] $creditConsiders
 * @property CreditDetail[] $creditDetails
 */
class Credit extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'credit';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['customer_id'], 'required'],
            [['customer_id', 'status', 'period', 'created_at', 'reserved_at', 'seller_id', 'staff_id'], 'integer'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['seller_id' => 'id']],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['staff_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('contract', 'เลขที่สินเชื่อ'),
            'customer_id' => Yii::t('contract', 'รหัสลูกค้า'),
            'status' => Yii::t('contract', 'สถานะ'),
            'period' => Yii::t('contract', 'จำนวนงวด'),
            'created_at' => Yii::t('contract', 'สร้างเมื่อ'),
            'reserved_at' => Yii::t('contract', 'จองเมื่อ'),
            'seller_id' => Yii::t('contract', 'จองโดย'),
            'staff_id' => Yii::t('contract', 'เจ้าหน้าที่'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContracts() {
        return $this->hasMany(Contract::className(), ['credit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer() {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller() {
        return $this->hasOne(User::className(), ['id' => 'seller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff() {
        return $this->hasOne(User::className(), ['id' => 'staff_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditConsiders() {
        return $this->hasMany(CreditConsider::className(), ['credit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditDetails() {
        return $this->hasMany(CreditDetail::className(), ['credit_id' => 'id']);
    }

    #######################################################

    public static function itemsAlias($key) {
        $items = [
            'status' => [
                0 => Yii::t('app', 'ร่าง'),
                1 => Yii::t('app', 'เสนอ'),
                2 => Yii::t('app', 'พิจารณา'),
                3 => Yii::t('app', 'อนุมัติ'),
                4 => Yii::t('app', 'ปรับแก้'),
                5 => Yii::t('app', 'ไม่อนุมัติ'),
                6 => Yii::t('app', 'ยกเลิก'),
                7 => Yii::t('app', 'รับเรื่องแล้ว'),
            ],
            'condition' => [
                1 => 'ตัวแทนขอสมัคร',
                2 => 'บริษัทฯจะจ่ายผลตอบแทน',
            ],
            'period' => array_combine(range(6, 36), range(6, 36)),
        ];
        return ArrayHelper::getValue($items, $key, []);
    }

    public function getStatusLabel() {
        $status = ArrayHelper::getValue($this->getItemStatus(), $this->status);
        $status = ($this->status === NULL) ? ArrayHelper::getValue($this->getItemStatus(), 0) : $status;
        switch ($this->status) {
            case '0' :
            case '4' :
            case '5' :
            case NULL :
                $str = '<span class="label label-danger">' . $status . '</span>';
                break;
            case '1' :
            case '2' :
                $str = '<span class="label label-primary">' . $status . '</span>';
                break;
            case '3' :
                $str = '<span class="label label-success">' . $status . '</span>';
                break;
            default :
                $str = $status;
                break;
        }

        return $str;
    }

    public static function getItemStatus() {
        return self::itemsAlias('status');
    }

    public function getPeriodLabel() {
        return ArrayHelper::getValue($this->getItemPeriod(), $this->period);
    }

    public static function getItemPeriod() {
        return self::itemsAlias('period');
    }

    public function getFullname() {
        return $this->customer->person->fullname;
    }

    /*
     * คำนวนหาจำนวนเงินที่ต้องชำระต่องวด
     */

    public static function calProfit($price, $period, $profit) {
        return ($price * ($period * $profit) / 100);
    }

    /*
     * คำนวนหาจำนวนเงินที่ต้องชำระต่องวด
     */
    public static function calPayPeriod($price, $period, $profit) {
        return (self::calProfit($price, $period, $profit) + $price) / $period;
    }

    /*
     * แสดงผลรวมที่ต้องชำระต่องวด
     */

    //public $totalPay;
    public function getTotalPay() {
        $model = $this->creditDetails;
        $pay = 0;
        $period = $this->period;
        $profit = $this->customer->profit;
        if ($model) {
            foreach ($model as $detail) {
                $price = $detail->price;
                $pay += self::calPayPeriod($price, $period, $profit);
            }
        }

        return $pay;
    }

    public function getTotalPayLabel() {
        return Yii::$app->formatter->asDecimal($this->totalPayLabel);
    }

    public function getProductsLabel() {
        $model = $this->creditDetails;
        $product = [];
        if ($model) {
            foreach ($model as $detail) {
                $product[] = $detail->product->title;
            }
        }

        return $product ? implode(', ', $product) : NULL;
    }

    /*
     * หาค่างวดครั้งที่เท่าไร
     */

    public function getPeriodPay() {
        $model = \backend\modules\payment\models\PaymentDetail::find()
                ->joinWith('contract')
                ->joinWith('payment')
                ->where(['contract.id' => $this->contract_id, 'payment.status' => 2])
                ->count();
        $model+=1;
        return $model;
    }

    /*
     * หาราคารวมทั้งหมด
     */

    public function getTotalPrice() {
        return $this->hasMany(CreditDetail::className(), ['credit_id' => 'id'])->sum('price');
    }

    /*
     * หาค่ายอดกำไรทั้งหมด
     */

    public function getTotalProfit() {
        return self::calProfit($this->totalPrice, $this->period, $this->customer->profit);
    }

    /*
     * ค่าคอม
     */

    public function getTotalCom() {
        return ($this->totalProfit * 20) / 100;
    }

    /*
     * ส่วนต่าง (กำไร-ค่าคอม)/งวดทั้วหมด
     */

    public function getSettlement() {
        return ($this->totalProfit - $this->totalCom) / $this->period;
    }    
    
    
}
