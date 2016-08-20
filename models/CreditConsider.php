<?php

namespace ikhlas\contract\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\models\User;


/**
 * This is the model class for table "credit_consider".
 *
 * @property integer $id
 * @property integer $credit_id
 * @property integer $status
 * @property string $comment
 * @property string $data
 * @property integer $created_by
 * @property integer $created_at
 *
 * @property Credit $credit
 * @property User $createdBy
 */
class CreditConsider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'credit_consider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['credit_id', 'comment'], 'required'],
            [['credit_id', 'status', 'created_by', 'created_at'], 'integer'],
            [['comment', 'data'], 'string'],
            [['credit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Credit::className(), 'targetAttribute' => ['credit_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('contract', 'ID'),
            'credit_id' => Yii::t('contract', 'เลขสินเชื่อ'),
            'status' => Yii::t('contract', 'สถานะ'),
            'comment' => Yii::t('contract', 'ความคิดเห็น/เหตุผล'),
            'data' => Yii::t('contract', 'ข้อมูล'),
            'created_by' => Yii::t('contract', 'บันทึกโดย'),
            'created_at' => Yii::t('contract', 'บันทึกเมื่อ'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredit()
    {
        return $this->hasOne(Credit::className(), ['id' => 'credit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
    
    #######################################################
    
     public static function itemsAlias($key) {
        $items = [
            'status' => [
                1 => Yii::t('app', 'อนุมัติ'),
                0 => Yii::t('app', 'ไม่อนุมัติ'),
                2 => Yii::t('app', 'ควรปรับแก้'),
            ],
        ];
        return ArrayHelper::getValue($items, $key, []);
    }

    public function getStatusLabel() {
        $status = ArrayHelper::getValue($this->getItemStatus(), $this->status);
        //$status = ($this->status === NULL) ? ArrayHelper::getValue($this->getItemStatus(), 0) : $status;
        switch ($this->status) {
            case '0' :
                $str = '<span class="label label-warning">' . $status . '</span>';
                break;
            case '1' :
                $str = '<span class="label label-success">' . $status . '</span>';
                break;
            case '2' :
                $str = '<span class="label label-danger">' . $status . '</span>';
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

}
