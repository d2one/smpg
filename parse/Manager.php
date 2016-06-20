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

    /**
     * Prepared parsers
     *
     * @var array
     */
    protected $_currentParsers = [];

    /**
     * Resorces types for parcing
     *
     * @var array
     */
    protected $_availableTypes = [
        'img' => 'app\parse\grabber\Img',
        'text' => 'app\parse\grabber\Text',
        'link' => 'app\parse\grabber\Link',
    ];

    /**
     * Current type resource to parce
     *
     * @var string
     */
    protected $_type;

    /**
     * Row parsed data
     *
     * @var string
     */
    protected $_parseData;


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
     * @throws ParserException
     */
    public function setType($type)
    {
        if (empty($this->_availableTypes[$type])) {
            throw new ParserException('Types incompatabilty');
        }

        $this->_type = $type;
    }

    /**
     * Start data to parse
     * @param string $text
     * @return array
     * @throws ParserException
     */
    public function proceed($text = '')
    {
        if (empty($this->_type) || empty($this->_parseData)) {
            throw new ParserException('Types incompatabilty');
        }

        try {
            $parser = $this->getParser();
            if (!empty($text)) {
                $parser->setSearchText($text);
            }
            return $parser->parseData($this->_parseData);
        } catch (ParserGrabberException $e) {
            throw new ParserException($e);
        }
    }

    /**
     * Get available parser instance
     *
     * @return {object}
     */
    public function getParser()
    {
        if (empty($this->_currentParsers[$this->_type])) {
            $this->_currentParsers[$this->_type] = new $this->_availableTypes[$this->_type];
        }

        return $this->_currentParsers[$this->_type];
    }

}