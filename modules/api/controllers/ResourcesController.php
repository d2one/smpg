<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;

class ResourcesController extends ActiveController
{
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete'], $actions['update'], $actions['options']);
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