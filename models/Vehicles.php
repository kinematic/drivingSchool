<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vehicles".
 *
 * @property int $id
 * @property int $name
 * @property int $fuel
 * @property int $transmission
 * @property string $description
 */
class Vehicles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vehicles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['fuel', 'transmission'], 'integer'],
            [['name', 'description'], 'string'],
            [['name'], 'unique'],
			[['description'], 'default', 'value' => null],
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
            'name' => 'имя',
            'fuel' => 'топливо',
            'transmission' => 'передачи',
			'fuelname' => 'топливо',
            'transmissionname' => 'передачи',
            'description' => 'описание',
        ];
    }

	public function getFuelname()
	{
		if(isset($this->fuel)) {
			if($this->fuel == 1) return 'бензин';
			if($this->fuel == 2) return 'дизель';
		} else return 'не известно';
	}

	public function getTransmissionname()
	{
		if(isset($this->transmission)) {
			if($this->transmission == 1) return 'механика';
			if($this->transmission == 2) return 'автомат';
		} else return 'не известно';
	}
}
