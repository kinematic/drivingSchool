<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Vehicles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vehicles-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
		    <?= $form->field($model, 'name')->textInput() ?>

		    <?= $form->field($model, 'fuel')->dropDownList(array('1' => 'бензин', '2' => 'дизель'), ['prompt' => '']) ?>

		    <?= $form->field($model, 'transmission')->dropDownList(array('1' => 'механика', '2' => 'автомат'), ['prompt' => '']) ?>
		</div>
		<div class="col-md-4">
		    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
		</div>
	 </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
