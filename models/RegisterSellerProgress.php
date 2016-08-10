<?php

namespace ikhlas\seller\models;

use Yii;
use common\models\User;
use wowkaster\serializeAttributes\SerializeAttributesBehavior;
/**
 * This is the model class for table "register_seller_progress".
 *
 * @property integer $id
 * @property integer $register_seller_id
 * @property integer $status
 * @property string $comment
 * @property string $data
 * @property integer $created_by
 * @property integer $created_at
 *
 * @property User $createdBy
 * @property RegisterSeller $registerSeller
 */
class RegisterSellerProgress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'register_seller_progress';
    }
    
    public function behaviors() {
        return [
            [
                'class' => SerializeAttributesBehavior::className(),
                'convertAttr' => ['data' => 'serialize']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['register_seller_id', 'status', 'created_by', 'created_at'], 'integer'],
            [['comment'], 'required'],
            [['comment', 'data'], 'string'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['register_seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegisterSeller::className(), 'targetAttribute' => ['register_seller_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('seller', 'ID'),
            'register_seller_id' => Yii::t('seller', 'เลขที่ใบสมัคร'),
            'status' => Yii::t('seller', 'สถานะ'),
            'comment' => Yii::t('seller', 'ความคิดเห็น'),
            'data' => Yii::t('seller', 'ข้อมูล'),
            'created_by' => Yii::t('seller', 'บันทึกโดย'),
            'created_at' => Yii::t('seller', 'บันทึกเมื่อ'),
            'doc_fully' => Yii::t('seller', 'ความถูกต้องของเอกสาร'),
            'should_receive' => Yii::t('seller', 'ควรรับเป็นสมาชิกหรือไม่'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegisterSeller()
    {
        return $this->hasOne(RegisterSeller::className(), ['id' => 'register_seller_id']);
    }
}
