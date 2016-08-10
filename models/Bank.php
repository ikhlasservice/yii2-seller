<?php

namespace ikhlas\seller\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bank".
 *
 * @property integer $id
 * @property string $title
 *
 * @property Seller[] $sellers
 */
class Bank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('seller', 'ID'),
            'title' => Yii::t('seller', 'ชื่อธนาคาร'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSellers()
    {
        return $this->hasMany(Seller::className(), ['bank_id' => 'id']);
    }
    
    public static function getList(){
        return ArrayHelper::map(self::find()->all(),'id','title');
    }
}
