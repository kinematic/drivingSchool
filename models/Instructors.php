<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "instructors".
 *
 * @property int $id
 * @property string $name
 * @property int $carid
 * @property string $description
 */
class Instructors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instructors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['carid'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100],
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
            'carid' => 'машина',
            'description' => 'описание',
        ];
    }

    public function getLessons()
    {
        return $this->hasMany(Lessons::className(), ['instructorid' => 'id']);
    }
}
