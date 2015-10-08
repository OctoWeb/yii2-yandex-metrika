<?

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;

use kartik\form\ActiveForm;
use kartik\widgets\DatePicker;
//use kartik\grid\GridView;
use yii\grid\GridView;

use miloschuman\highcharts\Highcharts;

$this->title = 'Yandex метрика';
$this->params['breadcrumbs'][] = $this->title;

if(Yii::$app->user->identity->hasRouter('/default/settings')){
    echo Html::a('<i class="glyphicon glyphicon-cog"></i> Настройки', ['settings'], ['class' => 'btn btn-default']);
}?>
<div style="width: 300px;float: right;">
    <?$form = ActiveForm::begin(['method' => 'get']);?>
        <div style="width: 252px;display: inline-block;">
            <?=DatePicker::widget([
                'name' => 'date1',
                'value' => date('Y-m-d',strtotime($returt->date1)),
                'type' => DatePicker::TYPE_RANGE,
                'name2' => 'date2',
                'value2' => date('Y-m-d',strtotime($returt->date2)),
                'separator'=>'<i class="glyphicon glyphicon-resize-horizontal"></i>',
                'pluginOptions' => [
                    'autoclose'=>true,
                    
                    'format' => 'yyyy-mm-dd'
                ]
            ]);?>
        </div>
        <?echo Html::submitButton('<i class="glyphicon glyphicon-refresh"></i>', ['class' => 'btn btn-default','style'=>'vertical-align: top;']);?>
    <?ActiveForm::end();?>
</div>
<div class="clearfix"></div>
<?
echo Highcharts::widget([
   'options' => [
      'title' => false,
      'xAxis' => [
         'categories' => ArrayHelper::getColumn($returt->row, function ($element) {
                            return date('d.m',strtotime($element['date']));
                        })
      ],
      'yAxis' => [
         'title' => ['text' => 'Количество']
      ],
      'series' => [
         ['name' => 'Визиты', 'data' => ArrayHelper::getColumn($returt->row, function ($element) {return (int)$element['visits'];})],
         ['name' => 'Просмотры', 'data' => ArrayHelper::getColumn($returt->row, function ($element) {return (int)$element['page_views'];})],
         ['name' => 'Посетители', 'data' => ArrayHelper::getColumn($returt->row, function ($element) {return (int)$element['visitors'];})],
         ['name' => 'Новые посетители', 'data' => ArrayHelper::getColumn($returt->row, function ($element) {return (int)$element['new_visitors'];})],
      ]
   ]
]);
function sec_to_time($seconds) { 
    $hours = floor($seconds / 3600); 
    $minutes = floor($seconds % 3600 / 60); 
    $seconds = $seconds % 60; 
    return sprintf("%d:%02d:%02d", $hours, $minutes, $seconds); 
} 
echo GridView::widget([
    'dataProvider'=> new ArrayDataProvider(['allModels'=>$returt->row]),
    //'export'=>false,
    'columns' => [
        ['attribute' => 'date',
            'header'=>'Дата',
            'value'=>function($model){
                    return strftime('%e %b %Y',strtotime($model['date']));
                }
        ],
        ['attribute' => 'visits',
            'header'=>'Визиты<br/><div style="font-size: 12px;font-weight: normal;">Всего: <strong style="float: right;">'.$returt->totals['visits'].'</strong></div>',
        ],
        ['attribute' => 'page_views',
            'header'=>'Просмотры<br/><div style="font-size: 12px;font-weight: normal;">Всего:<strong style="float: right;">'.$returt->totals['page_views'].'</strong></div>',
        ],
        ['attribute' => 'visitors',
            'header'=>'Посетители<br/><div style="font-size: 12px;font-weight: normal;">Всего:<strong style="float: right;">'.$returt->totals['visitors'].'</strong></div>',
        ],
        ['attribute' => 'new_visitors',
            'header'=>'Новые посетители<br/><div style="font-size: 12px;font-weight: normal;">Всего:<strong style="float: right;">'.$returt->totals['new_visitors'].'</strong></div>',
        ],
        ['attribute' => 'denial',
            'header'=>'Отказы<br/><div style="font-size: 12px;font-weight: normal;">Среднее:<strong style="float: right;">'.(((float)str_replace(",",".",$returt->totals['denial']))*100).'%</strong></div>',
            'value'=>function($model){
                return (((float)str_replace(",",".",$model['denial']))*100).'%';
            }
        ],
        ['attribute' => 'depth',
            'header'=>'Глубина просмотра<br/><div style="font-size: 12px;font-weight: normal;">Среднее:<strong style="float: right;">'.$returt->totals['depth'].'</strong></div>',
        ],
        ['attribute' => 'visit_time',
            'header'=>'Время на сайте<br/><div style="font-size: 12px;font-weight: normal;">Всего:<strong style="float: right;">'.$returt->totals['visit_time'].'</strong></div>',
            'value'=>function($model){
                return sec_to_time($model['visit_time']);
                
            }
        ],
    ],
    /*'responsive'=>true,
    'hover'=>true,
    'floatHeader'=>true,
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
    ]*/
]);?>