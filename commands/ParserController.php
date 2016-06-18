<?php

namespace app\commands;

use app\models\Resources;
use app\models\Search;
use yii\console\Controller;
use yii\data\ActiveDataProvider;
use linslin\yii2\curl;
use app\parse;
use Yii;

class ParserController extends Controller
{
    public function actionIndex()
    {
        $searchQuery = Search::find()->where("status='added'");
        $dataProvider = new ActiveDataProvider([
            'query' => $searchQuery,
        ]);

        $parseManager = new parse\Manager();
        foreach($dataProvider->getModels() as $search) {
            $downloadedData = $this->_getInternalData($search->url);
            $parseManager->setType($search->type);
            $parseManager->setParseData($downloadedData);
            try {
                $result = $parseManager->proceed($search->text);
                $search->status = 'complete';
                $search->resources_count = count($result);
            } catch (ParserException $e) {
                $search->status = 'error';
            }
            $search->save();
            if (!empty($result)) {
                $this->saveResources($search->id, $result);
            }
        }
    }


    protected function saveResources($searchId, $resources = [])
    {
        if (empty($resources) || empty($searchId)) {
            return false;
        }
        $connection = Yii::$app->db;

        foreach (array_chunk($resources, 50) as $chunk) {
            $insertData = [];
            foreach ($chunk as $item) {
                $insertData[] = [$searchId, $item];
            }

            $connection
                ->createCommand()
                ->batchInsert(Resources::tableName(), ['search_id', 'content'], $insertData)
                ->execute();
        }
    }


    protected function _getInternalData($url = '')
    {
        if (empty($url)) {
            return false;
        }

        $curl = new curl\Curl();

        $response = $curl->get($url);

        switch ($curl->responseCode) {
            case 'timeout':
            case 404:
                break;
            case 200:
                return $response;
                break;
        }
    }
}