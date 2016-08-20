<?php

namespace ikhlas\seller\models;

use Yii;
use ikhlas\persons\models\Person;
use common\models\User;
use yii\helpers\ArrayHelper;
use ikhlas\persons\models\PersonDetail;
use ikhlas\persons\models\Address;
use ikhlas\persons\models\ContactAddress;
use ikhlas\persons\models\PersonContact;
use ikhlas\persons\models\PersonCareer;

/**
 * This is the model class for table "register_seller".
 *
 * @property integer $id
 * @property integer $created_at
 * @property string $status
 * @property integer $person_id
 * @property string $data
 * @property string $doc
 * @property string $doc_fully
 * @property string $doc_because
 * @property string $score
 * @property integer $staff_id
 * @property string $staff_receive
 * @property string $staff_date
 * @property string $class
 * @property string $receive_because
 * @property integer $send_at
 *
 * @property Person $person
 * @property Staff $staff
 * @property Seller[] $sellers
 */
class RegisterSeller extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'register_seller';
    }
    

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'person_id'], 'required'],
            [['created_at', 'person_id', 'staff_id', 'send_at', 'status'], 'integer'],
            [['data', 'doc', 'doc_fully', 'doc_because', 'score', 'staff_receive', 'class', 'receive_because'], 'string'],
            [['staff_date'], 'safe'],
            [['status'], 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('seller', 'เลขที่ใบสมัคร'),
            'created_at' => Yii::t('seller', 'วันที่สร้าง'),
            'status' => Yii::t('seller', 'สถานะ'),
            'person_id' => Yii::t('seller', 'บุคคล'),
            'data' => Yii::t('seller', 'ข้อมูลทั้งหมด'),
            'doc' => Yii::t('seller', 'เอกสารประกอบ'),
            'doc_fully' => Yii::t('seller', 'ความครบถ้วนของเอกสาร'),
            'doc_because' => Yii::t('seller', 'ดังนี้'),
            'score' => Yii::t('seller', 'คะแนนการพิจารณา'),
            'staff_id' => Yii::t('seller', 'เจ้าหน้าที่'),
            'staff_receive' => Yii::t('seller', 'ควรรับเป็นสมาชิกหรือไม่'),
            'staff_date' => Yii::t('seller', 'วันที่อนุญาต'),
            'class' => Yii::t('seller', 'ระดับ'),
            'receive_because' => Yii::t('seller', 'สาเหตุ'),
            'send_at' => Yii::t('seller', 'ส่งเมื่อ'),
            'confirm' => Yii::t('seller', 'ข้าพเจ้าได้อ่านและเข้าใจกำหนดและเงื่อนไงการเป็นตัวแทนจำหน่ายกับบริษัทฯและยินยอมปฎิบัติตามเงื่อนไงข้อกำหนดดังกล่าวอย่างเคร่งครัดทุกประการ'),
            'fullname' => Yii::t('seller', 'ชื่อ-สกุล'),
        ];
    }

    public $confirm;

    const UPLOAD_FOLDER = 'seller';

    public function scenarios() {
        $scenarios = parent::scenarios();

        $scenarios['document'] = ['doc'];
        $scenarios['confirm'] = ['confirm'];

        return $scenarios;
    }

    public static function itemsAlias($key) {
        $items = [
            'status' => [
                0 => Yii::t('app', 'ร่าง'),
                1 => Yii::t('app', 'เสนอ'),
                2 => Yii::t('app', 'พิจารณา'),
                3 => Yii::t('app', 'อนุมัติ'),
                4 => Yii::t('app', 'ไม่อนุมัติ'),
                5 => Yii::t('app', 'ยกเลิก'),
            ],
            'doc_list' => [
                1 => 'สำเนาบัตรประชาชน หรือ บัตรข้าราชการ หรือ บัตรพนักงานรัฐวิสาหกิจ',
                2 => 'สำเนาทะเบียนบ้าน',
                3 => 'สำเนาบัญชีธนาคาร',
                4 => 'รูปถ่าย 1 นิ้ว..',
                5 => 'แผ่นที่บ้าน',
            ],
            'condition' => [
                1 => 'ตัวแทนขอสมัคร',
                2 => 'บริษัทฯจะจ่ายผลตอบแทน',
            ],
            'doc_fully_list' =>[
                1 => 'เอกสารถูกต้อง ครบถ้วน',
                2 => 'เอกสารไม่ถูกต้อง',
                3 => 'เอกสารได้รับการแก้ไข ถูกต้อง ครบถ้วน',
            ],
            'staff_receive_list' =>[
                1 => 'รับเป็นตัวแทน (IKHLAS Seller)',
                2 => 'ไม่รับเป็นตัวแทน (IKHLAS Seller)',
            ],
            'class_list' =>[
                'a' => 'A',
                'b' => 'B',
                'c' => 'C',
                'd' => 'D',
            ],
        ];
        return ArrayHelper::getValue($items, $key, []);
    }

    public function getStatusLabel() {
        $status = ArrayHelper::getValue($this->getItemStatus(), $this->status);
        $status = ($this->status === NULL) ? ArrayHelper::getValue($this->getItemStatus(), 0) : $status;
        switch ($this->status) {
            case 0 :
                $str = '<span class="label label-danger">' . $status . '</span>';
                break;
            case 1 :
                $str = '<span class="label label-primary">' . $status . '</span>';
                break;
            case 2 :
                $str = '<span class="label label-success">' . $status . '</span>';
                break;
            case 3 :
                $str = '<span class="label label-success">' . $status . '</span>';
                break;
            case 4 :
                $str = '<span class="label label-warning">' . $status . '</span>';
                break;
            default :
                $str = $status;
                break;
        }

        return $str;
    }

    public function getStatusLabelString() {
        return ArrayHelper::getValue($this->getItemStatus(), $this->status);
    }

    public static function getItemStatus() {
        return self::itemsAlias('status');
    }

    public static function getItemCondition() {
        return self::itemsAlias('condition');
    }

    public static function getItemDocList() {
        return self::itemsAlias('doc_list');
    }
    
    public static function getItemDocFullyList() {
        return self::itemsAlias('doc_fully_list');
    }
    
    public static function getItemStaffReceiveList() {
        return self::itemsAlias('staff_receive_list');
    }
    
    public static function getItemClassList() {
        return self::itemsAlias('class_list');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson() {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }

    public function getFullname() {
        return $this->person->fullname;
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
    public function getSellers() {
        return $this->hasMany(Seller::className(), ['register_seller_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegisterSellerProgresses() {
        return $this->hasMany(RegisterSellerProgress::className(), ['register_seller_id' => 'id']);
    }

    public static function getCoutRegis() {
        $countRegisSeller = "";
//if (Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'staff')) {
        $countRegisSeller = self::find()
                ->where(['IN', 'status', ['', '0']])
                ->orWhere(['IS', 'status', NULL])
                ->count();
        //echo $countRegisSeller;
        return $countRegisSeller ? '<small class="label pull-right bg-yellow">' . $countRegisSeller . '</small>' : '';
    }

    public static function getDetailRegister($id) {
        $model = self::findOne($id);
        $modelPerson = $model->person_id ? $model->person : new Person();
        $modelPersonDetail = $modelPerson->personDetail ? $modelPerson->personDetail : new PersonDetail();
        $modelAddress = $modelPerson->address_id ? $modelPerson->address : new Address();
        $modelContactAddress = $modelPerson->contact_address_id ? $modelPerson->contactAddress : new ContactAddress();
        $modelContactAddress->contactBy = ($modelPerson->address_id == $modelPerson->contact_address_id) ? 1 : 2;
        $modelPersonContact = $modelPerson->personContact ? $modelPerson->personContact : new PersonContact();
        $modelPersonCareer = $modelPerson->personCareer ? $modelPerson->personCareer : new PersonCareer();

        return compact(
                'model', 'modelPerson', 'modelPersonDetail', 'modelAddress', 'modelContactAddress', 'modelPersonContact', 'modelPersonCareer'
        );
    }

}
