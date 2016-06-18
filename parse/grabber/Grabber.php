<?php
/**
 * Created by PhpStorm.
 * User: d2
 * Date: 18.06.16
 * Time: 19:02
 */
namespace app\parse\grabber;

interface Grabber
{
    public function parseData($data = '');
}