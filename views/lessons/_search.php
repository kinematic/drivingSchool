<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Customers;
use app\models\Vehicles;
use app\models\Instructors;

/* @var $this yii\web\View */
/* @var $model app\models\LessonsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lessons-search">


    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

	<div class="row">
		<div class="col-md-2">

		    <?= $form->field($model, 'dateBegin')->widget(
		        DatePicker::className(), [
		            'clientOptions' => [
		                'autoclose' => true,
		                'format' => 'yyyy-mm-dd'
		            ]
		    ])->hint('начало')->label(false); 
		    ?>

			<?= $form->field($model, 'dateEnd')->widget(
		        DatePicker::className(), [
		            'clientOptions' => [
		                'autoclose' => true,
		                'format' => 'yyyy-mm-dd'
		            ]
		    ])->hint('конец')->label(false); 
		    ?>

		</div>
		<div class="col-md-2">
			<?= $form->field($model, 'typeid')->dropDownList(array('1' => 'занятие', '2' => 'тест'), ['prompt' => ''])->hint('тип')->label(false) ?>
			<?= $form->field($model, 'vehicleid')->dropDownList(ArrayHelper::map(Vehicles::find()
				->orderBy('name')
				->all(), 'id', 'name'), ['prompt'=>''])->hint('машина')->label(false); ?>
		</div>
		<div class="col-md-3">
			<?= $form->field($model, 'instructorid')->dropDownList(ArrayHelper::map(Instructors::find()
				->orderBy('name')
				->all(), 'id', 'name'), ['prompt'=>''])->hint('инструктор')->label(false);?>

		    <?= $form->field($model, 'customerid')->dropDownList(ArrayHelper::map(Customers::find()
				->orderBy('name')
				->all(), 'id', 'name'), ['prompt'=>''])->label(false)->hint('клиент');?>
		</div>
		<div class="col-md-3">
			<div class="form-group">
		        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
		        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		    </div>
			</div>
		</div>
		    <?php // echo $form->field($model, 'serviceid') ?>

		    <?php // echo $form->field($model, 'comment') ?>

    

    <?php ActiveForm::end(); ?>

</div>
