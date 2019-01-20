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
	<div class="row">
	    <div class="col-md-4">
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
	        [
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {update} {delete} {comment}',
				'headerOptions' => ['style' => 'color:#337ab7'],
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
                    },
                    'comment' => function ($url, $model) {
//                         Yii::warning('коммент = ' . $model->comment);
                        return Html::a('<span class="glyphicon glyphicon-comment"></span>', $url, [
                            'title' => Yii::t('app', $model->comment),
                            'hidden' => !isset($model->comment),
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
                    if ($action === 'comment') {
						$url ='index.php?r=payments/view&id=' . $model['id'];
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
    ]);
	$totalLessonsDuration = 0;
	$tmp = $dataProvider->getModels();
	foreach ($tmp as $item) {
        $totalLessonsDuration += $item['duration'];
    };
	$lessonsBalance = 0;
	$totalLessonsBalance = $totalLessonsQuantity - $totalLessonsDuration;
	$paymentid = 0;
	    
	if($totalLessonsBalance <> 0) $footerOptions = ['class' => 'danger'];
	else $footerOptions = [];   

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
	     },
        'beforeRow' => function ($model, $key, $index, $grid) use (&$lessonsBalance, &$paymentid)
        {
            if($model->paymentid != $paymentid) {
				$paymentid = $model->paymentid;
                $lessonsBalance = $model->payment->lessonsquantity;
                return '<tr><td colspan=6><b>' . $model->payment->date . ' - ' . $model->payment->service->name . '</b></td></tr>';
            } else {
                $lessonsBalance -= $model->duration;
            }
        },
        'columns' => [
			'datetime:date:дата',
            'payment.service.name',
            [
                'attribute' => 'баланс',
                'value' => function($data) use (&$lessonsBalance) {
					return $lessonsBalance;
					
                },
				'footer' => $totalLessonsBalance,
				'footerOptions' => $footerOptions,
            ],
            [
            'attribute' => 'duration',
            'footer' => $totalLessonsDuration,
            ],
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
				'template' => '{view} {update} {delete} {comment}',
// 				},
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
				},
				'comment' => function ($url, $model) {
					return Html::a('<span class="glyphicon glyphicon-comment"></span>', $url, [
						'title' => Yii::t('app', $model->comment),
						'hidden' => !isset($model->comment),
					]);
				},

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
                    if ($action === 'comment') {
						$url ='index.php?r=lessons/view&id=' . $model['id'];
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
