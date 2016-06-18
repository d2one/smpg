<?php
/**
 * Created by PhpStorm.
 * User: d2
 * Date: 18.06.16
 * Time: 18:41
 */

namespace app\parse\grabber;


class Img implements Grabber
{

    protected $_domDocument;

    /**
     * @return mixed
     */
    public function getDomDocument()
    {
        if (!$this->_domDocument) {
            $this->_domDocument = new \DomDocument();
        }
        return $this->_domDocument;
    }

    public function parseData($data = '')
    {
        $result = [];
        $internalErrors = libxml_use_internal_errors(true);
        $this->getDomDocument()->loadHTML($data);
        libxml_use_internal_errors($internalErrors);
        /*** discard white space ***/
        $this->getDomDocument()->preserveWhiteSpace = false;

        $images = $this->getDomDocument()->getElementsByTagName('img');

        foreach($images as $img)
        {
            if (empty($img->getAttribute('src'))) {
                continue;
            }
            $result[] = $img->getAttribute('src');
        }
        return $result;
    }
}