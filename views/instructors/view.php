<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Instructors */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Инструкторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="instructors-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
	<div class="row">
	    <div class="col-md-4">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
        //             'id',
                    'name',
                    'carid',
                    'description:ntext',
                ],
            ]) ?>
        </div>
		<div class="col-md-8">
<?php $form = ActiveForm::begin([
        'action' => ['view'],
        'method' => 'get',
//         'type' => ActiveForm::TYPE_INLINE,
    ]); ?>
			<?= $form->field($model, 'dateBegin')->widget(
		        DatePicker::className(), [
		            'clientOptions' => [
		                'autoclose' => true,
		                'format' => 'yyyy-mm-dd'
		            ]
		    ])->label(false); 
		    ?>
			<?= $form->field($model, 'dateEnd')->widget(
		        DatePicker::className(), [
		            'clientOptions' => [
		                'autoclose' => true,
		                'format' => 'yyyy-mm-dd'
		            ]
		    ])->label(false); 
		    ?>
			<?= $form->field($model, 'typeid')->dropDownList(array('1' => 'приход', '2' => 'расход'), ['prompt' => ''])->label(false) ?>
		    <div class="form-group">
		        <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
		        <?= Html::resetButton('Очистить', ['class' => 'btn btn-default']) ?>
				<?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
				
		    </div>
<?php ActiveForm::end(); ?>

<?php
	$dataProvider = new ArrayDataProvider([
        'allModels' => $model->lessons,
        'key' => 'id',
//         'sort' => [
//             'attributes' => ['date'],
//             'defaultOrder' => [
//                 'date' => SORT_DESC,
//             ],
//         ],
    ]);
		$totalLessonsDuration = 0;
		$tmp = $dataProvider->getModels();
		foreach ($tmp as $item) {
	        $totalLessonsDuration += $item['duration'];
	    };

    echo GridView::widget([
        'dataProvider' => $dataProvider,
		'caption' => '<h3 style="display:inline">Занятия</h3>' . ' ' . 
		Html::a(
		'<span class="glyphicon glyphicon-plus"></span>', 
		['lessons/create', 'Lessons[instructorid]' => $model->id], 
		['title' => Yii::t('yii', 'добавить'), 'name' => 'charges']),
        'showOnEmpty' => true,
		'showFooter' => true,
        'emptyText' => '',
        'layout' => "{items}",
// 		'rowOptions' => function ($model, $key, $index, $grid) use (&$totalLessonsQuantity, $paidFee)  {
// 	        if($totalLessonsQuantity < 0 or ($model->typeid == 2 and !$paidFee)) return ['class' => 'danger'];
// 	     },
        'columns' => [
            'datetime:date:дата',
            'customer.name',
			[
				'attribute' => 'duration',
				'footer' => $totalLessonsDuration,
			],
			'type',
	        [
				'class' => 'yii\grid\ActionColumn',
// 				'header' => 'Действия',
				'headerOptions' => ['style' => 'color:#337ab7'],
				'template' => '{view} {update} {delete}',
				'buttons' => [
				'view' => function ($url, $model) {
					return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
						'title' => Yii::t('app', 'посмотреть'),
					]);
				},

				'update' => function ($url, $model) {
					return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
						'title' => Yii::t('app', 'редактировать'),
					]);
				},
				'delete' => function ($url, $model) {
					return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
						'title' => Yii::t('app', 'удалить'),
					]);
				}

				],
				'urlCreator' => function ($action, $model, $key, $index) {
					if ($action === 'view') {
						$url ='index.php?r=lessons/view&id=' . $model['id'];
						return $url;
					}

					if ($action === 'update') {
						$url ='index.php?r=lessons/update&id=' . $model['id'];
						return $url;
					}
					if ($action === 'delete') {
						$url ='index.php?r=lessons/delete&id=' . $model['id'];
						return $url;
					}

				}
			],
		],
		

    ]) ?>
		</div>
    </div>

</div>
