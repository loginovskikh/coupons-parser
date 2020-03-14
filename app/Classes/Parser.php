<?php


namespace App\Classes;

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
    protected $parsedData;

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
            $this->parsedData = new Crawler($this->html);
        }
        else {
            throw new Exception('Nothing to parse', 404);
        }
    }


}
