<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property int $id
 * @property int $customerid
 * @property int $serviceid
 * @property int $date
 * @property int $quantity
 * @property string $cost
 * @property string $comment
 */
class Payments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customerid', 'serviceid', 'date', 'cost', 'quantity'], 'required'],
            [['customerid', 'serviceid', 'quantity'], 'integer'],
            [['cost'], 'number'],
			[['date'], 'safe'],
            [['comment'], 'string'],
			[['comment'], 'default', 'value' => null],
			[['quantity'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customerid' => 'клиент',
            'serviceid' => 'услуга',
            'date' => 'дата',
            'cost' => 'оплата, $',
			'quantity' => 'к-во',
            'comment' => 'примечание',
			'lessonsquantity' => 'опл.зан.',
        ];
    }

	public function getCustomer()
	{
		return $this->hasOne(Customers::className(), ['id' => 'customerid']);
	}

	public function getService()
	{
		return $this->hasOne(Services::className(), ['id' => 'serviceid']);
	}

	public function getLessonsquantity(){
		if($this->quantity == 1) {
			if($this->service->price == $this->cost) {
				$lessonsQuantity = $this->service->duration;
			} else {
				$oneLessonPrice = $this->service->price / $this->service->duration;
				$lessonsQuantity = ROUND($this->cost / $oneLessonPrice);
			}
		} else $lessonsQuantity = $this->service->duration * $this->quantity;
        return $lessonsQuantity;
	}
}
