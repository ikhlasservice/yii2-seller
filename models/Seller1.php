<?php

namespace ikhlas\seller\models;

use Yii;

/**
 * This is the model class for table "seller".
 *
 * @property string $id
 * @property integer $created_at
 * @property integer $status
 * @property integer $register_seller_id
 * @property integer $person_id
 * @property string $receive_data
 * @property integer $account_id
 * @property string $account_name
 * @property integer $bank_id
 * @property integer $staff_id
 * @property integer $user_id
 *
 * @property RegisterSeller $registerSeller
 */
class Seller extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seller';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'register_seller_id', 'person_id'], 'required'],
            [['created_at', 'status', 'register_seller_id', 'person_id', 'account_id', 'bank_id', 'staff_id', 'user_id'], 'integer'],
            [['receive_data'], 'string'],
            [['id'], 'string', 'max' => 5],
            [['account_name'], 'string', 'max' => 100],
            [['register_seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegisterSeller::className(), 'targetAttribute' => ['register_seller_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('seller', 'รหัสสมาชิก'),
            'created_at' => Yii::t('seller', 'วันที่ออกรหัส'),
            'status' => Yii::t('seller', 'สถานะ'),
            'register_seller_id' => Yii::t('seller', 'รหัสใบสมัคร'),
            'person_id' => Yii::t('seller', 'บุคคล'),
            'receive_data' => Yii::t('seller', 'ข้อมูลในการออกรหัสตัวแทน'),
            'account_id' => Yii::t('seller', 'เลขที่บัญชี'),
            'account_name' => Yii::t('seller', 'ชื่อบัญชี'),
            'bank_id' => Yii::t('seller', 'ธนาคาร'),
            'staff_id' => Yii::t('seller', 'เจ้าหน้าที่'),
            'user_id' => Yii::t('seller', 'ผู้ใช้'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegisterSeller()
    {
        return $this->hasOne(RegisterSeller::className(), ['id' => 'register_seller_id']);
    }
}
