<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lessons".
 *
 * @property int $id
 * @property string $datetime
 * @property string $duration
 * @property int $instructorid
 * @property int $customerid
 * @property int $serviceid
 * @property string $payment
 * @property int $status
 * @property string $comment
 */
class Lessons extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lessons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datetime', 'paymentid', 'instructorid', 'customerid'], 'required'],
            [['datetime'], 'safe'],
            [['duration'], 'number'],
            [['instructorid', 'paymentid', 'customerid', 'vehicleid', 'typeid'], 'integer'],
            [['comment'], 'string'],
			[['comment'], 'default', 'value' => null],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'datetime' => 'дата и время',
            'duration' => 'длительность, ч',
            'instructor.name' => 'инструктор',
            'customer.name' => 'клиент',
            'vehicleid' => 'автомобиль',
            'vehicle.name' => 'автомобиль',
            'comment' => 'примечание',
//             'balance' => 'баланс, ч',
			'typeid' => 'тип занятия',
			'type' => 'тип',
			'paymentid' => 'платеж',
        ];
    }

	public function getCustomer()
	{
		return $this->hasOne(Customers::className(), ['id' => 'customerid']);
	}

	public function getPayment()
	{
		return $this->hasOne(Payments::className(), ['id' => 'paymentid']);
	}

	public function getInstructor()
	{
		return $this->hasOne(Instructors::className(), ['id' => 'instructorid']);
	}

	public function getVehicle()
	{
		return $this->hasOne(Vehicles::className(), ['id' => 'vehicleid']);
	}
		
	public function getRestoflessons()
	{
        return $this->payment->service->duration;
        
	}

	public function getType()
	{
		if($this->typeid == 1) return 'занятие';
		if($this->typeid == 2) return 'тест';
	}
}
