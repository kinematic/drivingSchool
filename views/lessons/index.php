<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LessonsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Занятия';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lessons-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//             'id',
            'datetime',
            'duration',
            'instructor.name',
            'customer.name',
            'vehicle.name',
            //'payment',
            //'status',
            //'comment:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
