<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers;

$this->title = 'Search: ';
$this->title .= $search->url . ' ';
$this->title .= (!empty($search->text)) ? $search->type . ' (' . $search->text . ') ' : $search->type . ' ';
$this->title .= date("Y-m-d H:i:s", strtotime($search->created_at));

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        [
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value' => function ($data) use ($search) {
                switch ($search->type) {
                    case 'img':
                        return '<img src="' . $data->content . '">';
                    case 'link':
                        return '<a href="' . $data->content . '">' . $data->content . '</a>';
                    case 'text':
                        return $data->content;
                }
            },
            'label' => 'Content',
            'format' => 'html',
        ]
    ]
]);
?>
</div>
