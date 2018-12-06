<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Instructors */

$this->title = 'Добавление';
$this->params['breadcrumbs'][] = ['label' => 'Инструкторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instructors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
