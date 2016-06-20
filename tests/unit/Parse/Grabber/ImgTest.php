<?php
namespace Parse\Grabber;

use app\parse\grabber\Img;

class ImgTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testMe()
    {
        $imgParser = new Img();
    }
}