<?php
// Check this namespace:
namespace app\modules\api;


class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // ...  other initialization code ...
    }

}