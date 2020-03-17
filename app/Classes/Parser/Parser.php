<?php


namespace App\Classes\Parser;

use Exception;
use Symfony\Component\DomCrawler\Crawler;

require_once './app/modules/Parser/conf/conf.php';
require_once './app/modules/Curl/curl.php';

class Parser
{
    protected $link;

    protected $html;

    /**
     * @var Crawler|null
     */
    protected $crawlerData;

    protected function getHTML($url)
    {
        try {
            $this->html = getRequest($url);
            $result = true;
        } catch (Exception $e) {
            echo $e->getMessage();
            $result = false;
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    protected function parseHTML()
    {
        if(isset($this->html)) {
            $this->crawlerData = new Crawler($this->html);
        }
        else {
            throw new Exception('Nothing to parse', 404);
        }
    }

    protected function parseDOMEelements($xpath)
    {
        return  $this->crawlerData->filterXPath($xpath);

    }

    protected function saveState($parsingMode, $storeId)
    {

    }


}
