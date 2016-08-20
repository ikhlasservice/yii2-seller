<?php

namespace ikhlas\seller\models;

use Yii;
use ikhlas\persons\models\Person;
use yii\helpers\ArrayHelper;
use common\models\User;

/**
 * This is the model class for table "seller".
 *
 * @property integer $id
 * @property integer $created_at
 * @property string $status
 * @property integer $register_seller_id
 * @property integer $person_id
 * @property string $receive_data
 * @property integer $staff_id
 * @property integer $user_id
 *
 * @property Person $person
 * @property RegisterSeller $registerSeller
 * @property User $user
 * @property Staff $staff
 */
class Seller extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'seller';
    }
    
    public function behaviors() {
        return [
            [
                'class' => 'mdm\autonumber\Behavior',
                'attribute' => 'id', // required
                //'group' => $this->id_branch, // optional
                'value' => 'S' . '?', // format auto number. '?' will be replaced with generated number
                'digit' => 4 // optional, default to null. 
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'created_at', 'register_seller_id', 'person_id'], 'required'],
            [['created_at', 'status', 'register_seller_id', 'person_id', 'bank_id', 'staff_id', 'user_id'], 'integer'],
            [['status', 'receive_data'], 'string'],
            [['id'], 'string', 'max' => 5],
            [['account_name'], 'string', 'max' => 100],
            [['status', 'receive_data'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('person', 'รหัสตัวแทน'),
            'created_at' => Yii::t('person', 'วันที่ออกรหัส'),
            'status' => Yii::t('person', 'สถานะ'),
            'register_seller_id' => Yii::t('person', 'รหัสใบสมัคร'),
            'person_id' => Yii::t('person', 'บุคคล'),
            'receive_data' => Yii::t('person', 'ข้อมูลในการออกรหัสตัวแทน'),
            'account_id' => Yii::t('seller', 'เลขที่บัญชี'),
            'account_name' => Yii::t('seller', 'ชื่อบัญชี'),
            'bank_id' => Yii::t('seller', 'ธนาคาร'),
            'staff_id' => Yii::t('person', 'เจ้าหน้าที่'),
            'user_id' => Yii::t('person', 'ผู้ใช้'),
        ];
    }

    public function scenarios() {
        $scenarios = parent::scenarios();

        $scenarios['create'] = [];
        return $scenarios;
    }

    public static function itemsAlias($key) {
        $items = [
            'status' => [
                0 => Yii::t('app', 'ระงับ'),
                1 => Yii::t('app', 'ปกติ'),
                2 => Yii::t('app', 'แจ้งลบ'),
            ],
            'condition' => [
                1 => 'ตัวแทนขอสมัคร',
                2 => 'บริษัทฯจะจ่ายผลตอบแทน',
            ]
        ];
        return ArrayHelper::getValue($items, $key, []);
    }

    public function getStatusLabel() {
        $status = ArrayHelper::getValue($this->getItemStatus(), $this->status);
        $status = ($this->status === NULL) ? ArrayHelper::getValue($this->getItemStatus(), 0) : $status;
        switch ($this->status) {
            case '0' :
            case NULL :
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson() {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegisterSeller() {
        return $this->hasOne(RegisterSeller::className(), ['id' => 'register_seller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
    public function getBank() {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id']);
    }

    public static function getLastId() {
          //$id = self::find()->select(["concat('s'+ max(CAST((substring(id,2)) AS DECIMAL(5,0))))"])->scalar(); // cool, huh?;
        
        $id = self::find()->select(["MAX(CAST(SUBSTRING(`id`, 2) AS DECIMAL(5,0)))"])->scalar(); // cool, huh?;
        $id+=1;
        $id='s'.str_pad($id, 4, '0', STR_PAD_LEFT);        
        return $id;
    }

}
