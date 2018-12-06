<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Vehicles */

$this->title = 'Добавление';
$this->params['breadcrumbs'][] = ['label' => 'Машины', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vehicles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
