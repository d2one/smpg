<?php

namespace app\controllers;

use app\models\Resources;
use app\models\Search;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView($id)
    {
        $id = (int)$id;
        $search = Search::findOne($id);

        if (empty($search)) {
            throw new \yii\web\NotFoundHttpException("Search id {$id} not found");
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Resources::find()->where('search_id=' . (int)$id),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('result', [
            'dataProvider' => $dataProvider,
            'search' => $search
        ]);
    }

    public function actionSearch()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Search::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('search', [
            'dataProvider' => $dataProvider,
        ]);
    }

}
