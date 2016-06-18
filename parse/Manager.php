<?php
/**
 * Created by PhpStorm.
 * User: d2
 * Date: 18.06.16
 * Time: 18:41
 */

namespace app\parse;
use app\parse\grabber\ParserGrabberException;

class Manager
{

    protected $_currentParsers = [];

    protected $_availableTypes = [
        'img' => 'app\parse\grabber\Img',
        'text',
        'link'
    ];

    protected $_type;

    /**
     * @return mixed
     */
    public function getParseData()
    {
        return $this->_parseData;
    }

    /**
     * @param mixed $parseData
     */
    public function setParseData($parseData)
    {
        $this->_parseData = $parseData;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        if (empty($this->_availableTypes[$type])) {
            throw new ParserException('Types incompatabilty');
        }

        $this->_type = $type;
    }

    protected $_parseData;


    public function proceed()
    {
        if (empty($this->_type) || empty($this->_parseData)) {
            throw new ParserException('Types incompatabilty');
        }

        $parser = new $this->_availableTypes[$this->_type];
        try {
            return $parser->parseData($this->_parseData);
        } catch (ParserGrabberException $e) {
            throw new ParserException($e);
        }
    }

}