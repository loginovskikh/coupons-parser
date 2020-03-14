<?php


namespace App\Classes;


use App\Models\Store;
use DOMElement;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class StoresParser extends Parser
{
    private function extractStoreInfo(DOMElement $storeDOMElement)
    {
        if($storeDOMElement) {
            $result['name'] = $storeDOMElement->nodeValue;
            $result['link'] = parse_url($storeDOMElement->getAttribute('href'))['path'];
        }
        else {
            $result = null;
        }

        return $result;
    }

    private function getStoresFromDB()
    {
        $stores = Store::all();
        return ($stores->isEmpty()) ? null : $stores;
    }

    private function createStore(string $name, string $link)
    {
        $store = new Store();
        $store->storeName = $name;
        //var_dump($name);
        $store->storeLink = $link;
        //var_dump($link);
        $store->save();
    }

    private function checkStoreUpdates(Collection $currentStores, array $parsedStore)
    {
        $updates = false;
        $alreadyExists = false;
        foreach($currentStores as $currentStore) {
            if($currentStore->storeName === $parsedStore['name']) {
                if($currentStore->storeLink !== $parsedStore['link']) {
                    echo 'Same names' . PHP_EOL;
                    $currentStore->storeLink = $parsedStore['link'];
                    $currentStore->save();
                    $updates = true;
                }
                else {
                    $alreadyExists = true;
                }
            }
            else {
                if($currentStore->storeLink === $parsedStore['link']) {
                    echo 'Same links' . PHP_EOL;
                    $currentStore->storeName = $parsedStore['name'];
                    $currentStore->save();
                    $updates = true;
                }
            }
        }

        if(!$updates && !$alreadyExists) {
            $this->createStore($parsedStore['name'], $parsedStore['link']);
            echo 'Saved' . PHP_EOL;
        }
    }

    public function parseStores(?string $url)
    {
        if(!$this->html && $url) {
            $this->getHTML($url);
            try {
                $this->parseHTML();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        else {
            throw new Exception('Nothing to parse');
        }
        $result = null;
        $currentStores = $this->getStoresFromDB();
        $StoreDOMElements = $this->parseDOMEelements(STORE_XPATH);
        $start = microtime(true);
        foreach ($StoreDOMElements as $storeDOM) {
            $parsedStoreInfo = $this->extractStoreInfo($storeDOM);
            if($parsedStoreInfo) {
                if($currentStores) {
                    $this->checkStoreUpdates($currentStores, $parsedStoreInfo);
                    $result = true;
                }
                else {
                    $this->createStore($parsedStoreInfo['name'], $parsedStoreInfo['link']);
                    $result = true;
                }
            }
            else {
                $result = null;
            }
        }
        $end = microtime(true);
        $timing = $end - $start;
        echo 'Timing = ' . $timing . PHP_EOL;

        return $result;
    }

}
