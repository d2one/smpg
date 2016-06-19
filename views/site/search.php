<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers;

$this->title = 'All searches';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions'   => function ($model, $key, $index, $grid) {
        return ['data-id' => $model->id, 'style' => 'cursor:pointer;'];
    },
    'columns' => [
        'url',
        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:Y-m-d H:i:s']
        ],
        [
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value' => function ($data) {
                return (!empty($data->text)) ? $data->type . ' (' . $data->text . ')' : $data->type;
            },
            'label' => 'Search Type'
        ],
        'status', 'resources_count'
    ]

]);

$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').data('id');
        if(e.target == this)
            location.href = '" . \yii\helpers\Url::to(['site/search']) . "/' + id;
    });

");
?>
</div>
