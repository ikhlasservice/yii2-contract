<?php

namespace backend\modules\contract\models;

use Yii;
use common\models\User;
use yii\helpers\ArrayHelper;
use backend\modules\payment\models\PaymentDetail;

/**
 * This is the model class for table "contract".
 *
 * @property integer $id
 * @property integer $credit_id
 * @property integer $status
 * @property string $total
 * @property integer $created_at
 * @property integer $staff_id
 *
 * @property Credit $credit
 * @property User $staff
 * @property PaymentDetail[] $paymentDetails
 * @property Payment[] $payments
 */
class Contract extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'contract';
    }

    public function behaviors() {
        return [
            [
                'class' => 'mdm\autonumber\Behavior',
                'attribute' => 'id', // required
                //'group' => $this->id_branch, // optional
                'value' => 'AR' . '?', // format auto number. '?' will be replaced with generated number
                'digit' => 5 // optional, default to null. 
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['credit_id', 'status', 'total'], 'required'],
            [['credit_id', 'status', 'created_at', 'staff_id'], 'integer'],
            [['total'], 'number'],
            [['id'], 'string','max'=>8],
            [['credit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Credit::className(), 'targetAttribute' => ['credit_id' => 'id']],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['staff_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('contract', 'เลขที่สัญญา'),
            'credit_id' => Yii::t('contract', 'เลขที่จองสินเชื่อ'),
            'status' => Yii::t('contract', 'สถานะ'),
            'total' => Yii::t('contract', 'ราคารวม'),
            'created_at' => Yii::t('contract', 'สร้างเมื่อ'),
            'staff_id' => Yii::t('contract', 'เจ้าหน้าที่'),
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
    public function getStaff() {
        return $this->hasOne(User::className(), ['id' => 'staff_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentDetails() {
        return $this->hasMany(PaymentDetail::className(), ['contract_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments() {
        return $this->hasMany(Payment::className(), ['id' => 'payment_id'])->viaTable('payment_detail', ['contract_id' => 'id']);
    }

    ##########################################################

    public static function itemsAlias($key) {
        $items = [
            'status' => [
                0 => Yii::t('app', 'หมดสัญญา'),
                1 => Yii::t('app', 'คงสัญญา'),
            ],
        ];
        return ArrayHelper::getValue($items, $key, []);
    }

    public function getStatusLabel() {
        $status = ArrayHelper::getValue($this->getItemStatus(), $this->status);
        $status = ($this->status === NULL) ? ArrayHelper::getValue($this->getItemStatus(), 0) : $status;
        switch ($this->status) {
            case '0' :
                $str = '<span class="label label-danger">' . $status . '</span>';
                break;
            case '1' :
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

    public static function chkList($id, $listId) {
        return $listId ? array_search($id, $listId) : NULL;
    }

    /*
     * หาค่างวดครั้งที่เท่าไร
     */
    public function getPeriodPay1() {
        $model = \backend\modules\payment\models\PaymentDetail::find()
                ->joinWith('contract')
                ->joinWith('payment')
                ->where([
                    'contract.id' => $this->id,
                    'payment.status' => 3
                ])
                ->andWhere(['!=', 'payment_detail.amount', 0])
                ->count();
        $model+=1;
        return $model;
    }

    /*
     * ยอดที่ต้องชำทั้งหมด
     */
    public function getTotalPayAll() {
        $credit = $this->credit;
        return $credit->totalPay * $credit->period;
    }

    /*
     * ยอดคงเหลือ
     */
    public function getBalances() {
        $periodCount = \backend\modules\payment\models\PaymentDetail::find()
                ->joinWith('contract')
                ->joinWith('payment')
                ->where([
                    'contract.id' => $this->id,
                    'payment.status' => 3
                ])
                ->sum('payment_detail.amount');
        return $this->totalPayAll - $periodCount;
    }

    /*
     * ปิดยอด
     */
    public function getOffBalance() {
        $periodPay = $this->credit->period - (($this->periodPay) - 1);
        $periodPay = ($periodPay * $this->credit->settlement) / 2;
        return $this->balances - $periodPay;
    }
    
    /**
     * รายละเอียดการชำระ
     */
    public function getPaymentAll() {
        $model = PaymentDetail::find()
                ->joinWith('contract')
                ->joinWith('payment')
                ->where([
                    'contract.id' => $this->id,
                    'payment.status' => 3
                ])->all();
        
        
        return $model;
    }

}
