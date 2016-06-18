<?php

namespace app\commands;

use app\models\Search;
use yii\console\Controller;
use yii\data\ActiveDataProvider;
use linslin\yii2\curl;
use app\parse;

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
            $result = $parseManager->proceed();
            $search->status = 'complete';
            $search->resources_count = count($result);
            $search->save();
            die();
        }
    }


    protected function _getInternalData($url = '')
    {
        if (empty($url)) {
            return false;
        }

        $curl = new curl\Curl();

        //get http://www.google.com:81/ -> timeout
        $response = $curl->get($url);

        // List of status codes here http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
        switch ($curl->responseCode) {

            case 'timeout':
                //timeout error logic here
                break;

            case 200:
                return $response;
                break;

            case 404:
                //404 Error logic here
                break;
        }
    }
}