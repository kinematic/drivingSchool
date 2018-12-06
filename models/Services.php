<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string $name
 * @property string $decription
 * @property string $price
 */
class Services extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'duration'], 'required'],
            [['decription'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique'],
			[['duration', 'price'], 'integer'],
// 			[['description'], 'default', 'value' => null],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'название',
			'duration' => 'длительность, ч',
            'decription' => 'описание',
            'price' => 'цена, $',
        ];
    }
}
