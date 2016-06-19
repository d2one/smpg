<?php
/**
 * Created by PhpStorm.
 * User: d2
 * Date: 18.06.16
 * Time: 18:41
 */

namespace app\parse\grabber;


class Text implements Grabber
{

    protected $_searchText;


    public function parseData($data = '')
    {
        $result = [];
        $searchText = $this->getSearchText();

        if (empty($searchText)) {
            return $result;
        }

        $data = preg_replace('/\s+/', ' ', preg_replace('/<[^>]*>/', '', $data));
        $matches = [];
        foreach (explode(' ', $searchText) as $item) {
            preg_match_all('(.{20}' . $item . '.{20})', strtolower($data), $matches);
            if (empty($matches)) {
                return $result;
            }
            $matches = reset($matches);
            foreach ($matches as $match) {
                if (strlen($match) <= 3) {
                    continue;
                }
                $result[] = mb_convert_encoding('...' . $match . '...', 'UTF-8');
            }
        }
        
        return $result;
    }

    /**
     * @return mixed
     */
    public function getSearchText()
    {
        return $this->_searchText;
    }

    /**
     * @param mixed $searchText
     */
    public function setSearchText($searchText)
    {
        $this->_searchText = trim(strtolower($searchText));
    }
}