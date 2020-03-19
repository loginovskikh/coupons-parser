<?php


namespace App\Classes\Parser;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\DomCrawler\Crawler;

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
        if (isset($this->html)) {
            $this->crawlerData = new Crawler($this->html);
        } else {
            throw new Exception('Nothing to parse', 404);
        }
    }

    protected function parseDOMEelements($xpath)
    {
        return $this->crawlerData->filterXPath($xpath);

    }

    /**
     * @param array|null $parsedArray
     * @param Collection|null $existedArray
     * @return array
     */
    public function update($parsedArray, $existedArray)
    {
        if ($parsedArray) {
            echo 'Parsed array = ' . count($parsedArray) .PHP_EOL;
            if ($existedArray) {
                echo 'Existed array = ' . count($existedArray) .PHP_EOL;
                foreach ($existedArray as $existedObject) {
                    foreach ($parsedArray as $key => $parsedObject) {
                        $compareKey = null;
                        if (!array_diff_assoc($existedObject->attributeToArray(), $parsedObject)) {
                            $compareKey = $key;
                            break;
                        }
                    }
                    if($compareKey !== null) {
                        unset($parsedArray[$compareKey]);
                    }
                    else {
                        $existedObject->delete();
                    }
                }
            }
        }

        return $parsedArray;
    }
}

