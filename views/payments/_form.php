<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use app\models\Customers;
use app\models\Services;

/* @var $this yii\web\View */
/* @var $model app\models\Payments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payments-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<div class="row">
	    <div class="col-md-6">
		    <?= $form->field($model, 'customerid')->dropDownList(ArrayHelper::map(Customers::find()
				->orderBy('name')
				->all(), 'id', 'name'), ['prompt'=>''])->label('
					клиент ' . Html::a('<span class="glyphicon glyphicon-plus"></span>', 
					['customers/create', 'Customers[id]' => $model->id], 
					['title' => Yii::t('yii', 'добавить')])); ?>
			<div class="row">
				<div class="col-md-10">
				    <?= $form->field($model, 'serviceid')->dropDownList(ArrayHelper::map(Services::find()
						->orderBy('name')
						->all(), 
						'id',     
						function($model) {
					        return $model->name . ', $' . $model->price;
					    }), ['prompt'=>'']); ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>
				
				</div>
			</div>
			<div class="row">
			    <div class="col-md-8">
					<?= $form->field($model, 'date')->widget(
			            DatePicker::className(), [
			                'clientOptions' => [
			                    'autoclose' => true,
			                    'format' => 'yyyy-mm-dd'
			                ]
			        ]);  ?>
				</div>
				<div class="col-md-4">
					<?= $form->field($model, 'cost')->textInput(['maxlength' => true]) ?>
				</div>
			</div>

		</div>
		<div class="col-md-6">
		    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
		</div>
	</div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
