<?php

use yii;
use \app\parse\grabber\Img;

class ParserImgTest extends  \Codeception\Test\Unit {

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testGetSettingsValue() {
        $model = new Img();
    }
}