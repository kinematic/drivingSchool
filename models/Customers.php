<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customers".
 *
 * @property int $id
 * @property string $name
 * @property int $driverslicense
 * @property string $dob
 * @property string $phonenumber
 * @property string $description
 */
class Customers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['driverslicense'], 'integer'],
            [['dob'], 'safe'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['phonenumber'], 'string', 'max' => 20],
            [['name'], 'unique'],
			[['description', 'dob', 'phonenumber'], 'default', 'value' => null],
			[['name'], 'filter', 'filter' => 'trim', 'skipOnArray' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'driverslicense' => '№ удостоверения',
            'dob' => 'ДР',
            'phonenumber' => '№ телефона',
            'description' => 'описание',
        ];
    }
    
    public function getPayments()
    {
        return $this->hasMany(Payments::className(), ['customerid' => 'id'])->orderBy('date');
    }
    
    public function getLessons()
    {
        return $this->hasMany(Lessons::className(), ['customerid' => 'id'])->orderBy('datetime');
    }
    
    public function getPaidfee()
    {
		$value = Payments::find()
			->where(
			[
				'serviceid' => 24,
				'customerid' => $this->id,
			])->one();
        return $value;
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
			if(isset($this->dob)) $this->dob = date_format(date_create_from_format('m/d/Y', $this->dob), 'Y-m-d');
            return parent::beforeSave($insert);
        } else {
            return false;
        }
    }
}
