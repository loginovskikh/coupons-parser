<?php


namespace App\Classes\Parser;


use App\Classes\State\State;
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

    private function existsOrCreate(Collection $currentStores, array $parsedStore)
    {
        $alreadyExists = false;
        foreach($currentStores as $currentStore) {
            if($currentStore->storeName === $parsedStore['name'] && $currentStore->storeLink === $parsedStore['link']) {
                $alreadyExists = true;
                break;
            }
        }
        if(!$alreadyExists) {
            $this->createStore($parsedStore['name'], $parsedStore['link']);
        }
    }

    public function parseStores(string $url)
    {
        $state = State::saveState('stores-by-letter', $url);
        echo $state . PHP_EOL;

        $this->getHTML($url);
        try {
            $this->parseHTML();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $result = null;
        $currentStores = $this->getStoresFromDB();
        $StoreDOMElements = $this->parseDOMEelements(STORE_XPATH);
        $start = microtime(true);
        foreach ($StoreDOMElements as $storeDOM) {
            $parsedStoreInfo = $this->extractStoreInfo($storeDOM);
            if($parsedStoreInfo) {
                if($currentStores) {
                    $this->existsOrCreate($currentStores, $parsedStoreInfo);
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
        State::deleteState($state);
        return $result;
    }

}
