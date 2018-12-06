<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use app\models\Payments;

/* @var $this yii\web\View */
/* @var $model app\models\Customers */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customers-view">

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
		            'driverslicense',
		            'dob',
		            'phonenumber',
		            'description:ntext',
		        ],
		    ]) ?>
		</div>
        <div class="col-md-8">

	<?php
	$dataProvider = new ArrayDataProvider([
        'allModels' => $model->payments,
        'key' => 'id',
//         'sort' => [
//             'attributes' => ['date'],
//             'defaultOrder' => [
//                 'date' => SORT_DESC,
//             ],
//         ],
    ]);
	$totalLessonsQuantity = 0;
	$totalPayments = 0;
	$tmp = $dataProvider->getModels();
	foreach ($tmp as $item) {
        $totalLessonsQuantity += $item['lessonsquantity'];
		$totalPayments += $item['cost'] * $item['quantity'];
    };
	$paidFee = $model->paidfee;


	
    $balanceMoney = 
        Payments::find()
        ->innerJoin('services s', 's.id = payments.serviceid')
        ->where(['customerid' => $model->id])->sum('price * quantity');
	$totalLessons = 0;
    
    echo GridView::widget([
        'dataProvider' => $dataProvider,
		'caption' => '<h3 style="display:inline">Платежи</h3>' . ' ' . 
		Html::a(
		'<span class="glyphicon glyphicon-plus"></span>', 
		['payments/create', 'Payments[customerid]' => $model->id], 
		['title' => Yii::t('yii', 'добавить'), 'name' => 'payments']),
        'showOnEmpty' => true,
		'showFooter' => true,
        'emptyText' => '',
        'layout' => "{items}",
		'rowOptions' => function ($model, $key, $index, $grid) use (&$balanceMoney)  {
	        if($balanceMoney < 0) return ['class' => 'warning'];
// 			else return ['class' => 'info'];
	     },
        'columns' => [
            'date',
            'service.name',
			'service.price',
			'cost',
			'quantity',
            [
                'attribute' => 'долг, $',
				'footer' => $totalPayments,
                'value' => function($data) use (&$balanceMoney) {
                    $balanceMoney = $balanceMoney - $data->cost * $data->quantity;
// 					$balanceMoney =  $data->cost - $balanceMoney;
                    return $balanceMoney;
                }
            ],
			[
				'attribute' => 'lessonsquantity',
				'footer' => $totalLessonsQuantity,
			],
// 			'lessonsquantity',
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
						$url ='index.php?r=payments/view&id=' . $model['id'];
						return $url;
					}

					if ($action === 'update') {
						$url ='index.php?r=payments/update&id=' . $model['id'];
// 						print_r($model);
						return $url;
					}
					if ($action === 'delete') {
						$url ='index.php?r=payments/delete&id=' . $model['id'];
						return $url;
					}

				}
			],
		],
		

    ])?>

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
		['lessons/create', 'Lessons[customerid]' => $model->id], 
		['title' => Yii::t('yii', 'добавить'), 'name' => 'charges']),
        'showOnEmpty' => true,
		'showFooter' => true,
        'emptyText' => '',
        'layout' => "{items}",
		'rowOptions' => function ($model, $key, $index, $grid) use (&$totalLessonsQuantity, $paidFee)  {
	        if($totalLessonsQuantity < 0 or ($model->typeid == 2 and !$paidFee)) return ['class' => 'danger'];
			Yii::warning($totalLessonsQuantity);
// 			if() return ['class' => 'danger'];
// 			else return ['class' => 'blue-color'];
	     },
        'columns' => [
            'datetime:date:дата',
            'instructor.name',
			[
				'attribute' => 'duration',
				'footer' => $totalLessonsDuration,
			],
			'type',
// 			'balance',
            [
                'attribute' => 'остаток, ч',
                'value' => function($data) use (&$totalLessonsQuantity) {
// Yii::warning($totalLessonsQuantity);
                    $totalLessonsQuantity = $totalLessonsQuantity - $data->duration;
                    return $totalLessonsQuantity;
                },
				'visible' => true,
            ],
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
