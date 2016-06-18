<?php

namespace app\modules\api\controllers;

use app\models\Resources;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;

class ResourcesController extends ActiveController
{
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete'], $actions['update'], $actions['options']);

        $actions = array_merge($actions, [
                'index' => [
                    'class' => 'yii\rest\IndexAction',
                    'modelClass' => $this->modelClass,
                    'prepareDataProvider' => function ($action) {
                        /* @var $model Resources */
                        $model = new $this->modelClass;
                        $query = $model::find();
                        $dataProvider = new ActiveDataProvider(['query' => $query]);
                        $query->andWhere(['=', 'search_id', (int)@$_GET['search_id']]);
                        return $dataProvider;
                    }
                ]
            ]
        );

        return $actions;
    }

    public $modelClass = 'app\models\Resources';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\ContentNegotiator::className(),
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }


}