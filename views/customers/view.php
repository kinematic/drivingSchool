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
	$totalPrices = 0;
	$tmp = $dataProvider->getModels();
	foreach ($tmp as $item) {
        $totalLessonsQuantity += $item['lessonsquantity'];
		$totalPayments += $item->cost;
		$totalPrices += $item->service->price * $item->quantity;
    };
	$paidFee = $model->paidfee;

	if($totalPrices > $totalPayments) $footerOptions = ['class' => 'danger'];
	else $footerOptions = [];
	
    $balanceMoney = 
        Payments::find()
        ->innerJoin('services s', 's.id = payments.serviceid')
        ->where(['customerid' => $model->id])->sum('price * quantity');
    
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
			if($model->error) return ['class' => 'warning'];
	     },
        'columns' => [
            'date',
            'service.name',
			[
				'attribute' => 'service.price',
				'footer' => $totalPrices,
			],
			'quantity',
			[			
				'attribute' => 'cost',
				'footer' => $totalPayments,
				'format' => 'raw',
				'value' => function($data) {
					if($data->error)  
						return Html::tag('span', $data->cost, [
						    'title'=> $data->error,
						    'data-toggle'=>'tooltip',
						    'style' => 'text-decoration: underline; cursor:pointer;'
						]);
					else return $data->cost;
				},
				'footerOptions' => $footerOptions,
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
						'data-method' => 'post',
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
$lessonsBalance = 0;
$paymentid = 0;
	    
	    
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
// 			Yii::warning($totalLessonsQuantity);
// 			if() return ['class' => 'danger'];
// 			else return ['class' => 'blue-color'];
	     },
        'beforeRow' => function ($model, $key, $index, $grid) use (&$paymentid)
        {
            if($model->paymentid != $paymentid) {
//                 $currentCompanyID = $model->man->companyid;
                return '<tr><td colspan=6><b>' . $model->payment->date . ' - ' . $model->payment->service->name . '</b></td></tr>';
            }
        },
        'columns' => [
			'datetime:date:дата',
            'payment.service.name',
            [
                'attribute' => 'баланс',
                'value' => function($data) use (&$lessonsBalance, &$paymentid) {
                    if($paymentid != $data->paymentid) {
                        $paymentid = $data->paymentid;
                        $lessonsBalance = $data->payment->lessonsquantity - $data->duration;
                        return $data->payment->lessonsquantity;
                        
                    } else {
                        $tmp = $lessonsBalance;
                        $lessonsBalance -= $data->duration;
                        return $tmp;
                    
                    }

                },
            ],
            [
            'attribute' => 'duration',
            'footer' => $totalLessonsDuration,
            ],
//             [
//                 'attribute' => 'остаток, ч',
//                 'value' => function($data) use (&$totalLessonsQuantity) {
// 					$totalLessonsQuantity = $totalLessonsQuantity - $data->duration;
//                     return $totalLessonsQuantity;
//                 },
// 				'visible' => true,
//             ],
			[
				'attribute' => 'type',
				'format' => 'raw',
				
				'value' => function($data) use (&$totalLessonsQuantity, $paidFee) {
					if($totalLessonsQuantity < 0) 
						return Html::tag('span', $data->type, [
						    'title'=>'не оплачено занятие',
						    'data-toggle'=>'tooltip',
						    'style' => 'text-decoration: underline; cursor:pointer;'
						]);
					else if($data->typeid == 2 and !$paidFee)
						return Html::tag('span', $data->type, [
						    'title'=>'не оплачен сбор',
						    'data-toggle'=>'tooltip',
						    'style' => 'text-decoration: underline; cursor:pointer;'
						]);
					else return $data->type;
				}
            ],
// 			'instructor.name',
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
						'data-method' => 'post',
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
		

    ]) ;

?>
		</div>
    </div>
</div>
