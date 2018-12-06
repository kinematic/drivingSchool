<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Services */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="services-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
		    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'duration')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-md-4">
		    <?= $form->field($model, 'decription')->textarea(['rows' => 6]) ?>
		</div>
	 </div>   

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
