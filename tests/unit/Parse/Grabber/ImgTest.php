<?php
namespace Parse\Grabber;

use app\parse\grabber\Img;

class ImgTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * Parse img test
     */
    public function testMe()
    {
        $imgParser = new Img();

        foreach ($this->getData() as $data) {
            $result = $imgParser->parseData($data['html']);
            foreach ($data['result'] as $assert => $data) {
                $this->$assert($data, $result);
            }
        }
    }

    /**
     * DataProvider
     *
     * @return array
     */
    public function getData()
    {
        return [
            [
                'html' => '<!DOCTYPE html><html><body><img src="w3schools.jpg" alt="W3Schools.com" width="104" height="142"></body></html>',
                'result' => [
                    'assertEquals' => ['w3schools.jpg']

                ]
            ],
            [
                'html' => '<!DOCTYPE html><html><body><img src=w3schools.jpg alt="W3Schools.com" width="104" height="142"></body></html>',
                'result' => [
                    'assertEquals' => ['w3schools.jpg']

                ]
            ],
            [
                'html' => '<!DOCTYPE html><html><body><img src="" alt="W3Schools.com" width="104" height="142"></body></html>',
                'result' => [
                    'assertEquals' => []
                ]
            ],
            [
                'html' => '<!DOCTYPE html><html><body></body></html>',
                'result' => [
                    'assertEquals' => []
                ]
            ]

        ];
    }
}