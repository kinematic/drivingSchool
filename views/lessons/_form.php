<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Customers;
use app\models\Vehicles;
use app\models\Instructors;
use app\models\Payments;

/* @var $this yii\web\View */
/* @var $model app\models\Lessons */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lessons-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<div class="row">
	    <div class="col-md-6">
			<?= $form->field($model, 'paymentid')->dropDownList(ArrayHelper::map(Payments::find()
				->where(['customerid' => $model->customerid])
				->andWhere('serviceid <> 24')
				->orderBy('date DESC')
				->all(), 'id', function($model) {
					        return $model->date . ' ' . $model->service->name . ': ' . $model->lessonscount . ' из ' . $model->lessonsquantity;
					    })); ?>
		</div>
	</div>
	<div class="row">
	    <div class="col-md-3">
            <?= $form->field($model, 'datetime')->widget(DateTimePicker::className(), [
                'language' => 'ru',
                'size' => 'ms',
                'template' => '{input}',
                'pickButtonIcon' => 'glyphicon glyphicon-time',
                'inline' => false,
                'clientOptions' => [
//                     'startView' => 2,
                    'minView' => 0,
        //             'maxView' => 1,
                    'autoclose' => true,
        //             'linkFormat' => 'HH:ii P', // if inline = true
                    'linkFormat' => 'yyyy-mm-dd hh:ii', // if inline = true
                    'format' => 'yyyy-mm-dd hh:ii', // if inline = false
// 					'format' => 'yyyy-mm-dd H:i p', // if inline = false
                    'todayBtn' => true,
                    'minuteStep' => 15
                ]
            ]);
            ?>
        </div>
        <div class="col-md-1">
            <?= $form->field($model, 'duration')->dropDownList([
				'1' => 1, 
				'2' => 2, 
				'3' => 3,
				'4' => 4,
				'5' => 5,
				'6' => 6,
				'7' => 7,
				'8' => 8,
				'9' => 9,
				'10' => 10
			])->label('часов') ?>
        </div>
		<div class="col-md-2">
            <?= $form->field($model, 'typeid')->dropDownList(['1' => 'занятие', '2' => 'тест']) ?>
        </div>
	</div>
    <div class="row">
		<div class="col-md-3">
	        <?= $form->field($model, 'customerid')->dropDownList(ArrayHelper::map(Customers::find()
				->orderBy('name')
				->all(), 'id', 'name'), ['prompt'=>''])->label('
					клиент ' . Html::a('<span class="glyphicon glyphicon-plus"></span>', 
					['customers/create', 'Customers[id]' => $model->id], 
					['title' => Yii::t('yii', 'добавить')])); ?>
		</div>
        <div class="col-md-3">
            <?= $form->field($model, 'instructorid')->dropDownList(ArrayHelper::map(Instructors::find()
				->orderBy('name')
				->all(), 'id', 'name'), ['prompt'=>''])->label('
					инструктор ' . Html::a('<span class="glyphicon glyphicon-plus"></span>', 
					['instructors/create', 'Instructors[id]' => $model->id], 
					['title' => Yii::t('yii', 'добавить')])); ?>

            
        </div>
	</div>

	<div class="row">
		<div class="col-md-5">
	        <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
		</div>
    </div>
    

    

    

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
// Yii::warning($model->restoflessons);
// Yii::warning($model->payment->lessonscount);

?>
