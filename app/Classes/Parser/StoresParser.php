<?php


namespace App\Classes\Parser;

use App\Classes\State\State;
use App\Models\Store;
use DOMElement;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class StoresParser extends Parser
{
    private function extractInfoFromDOM(DOMElement $storeDOMElement)
    {
        if($storeDOMElement) {
            $result['storeName'] = $storeDOMElement->nodeValue;
            $result['storeLink'] = parse_url($storeDOMElement->getAttribute('href'))['path'];
        }
        else {
            $result = null;
        }

        return $result;
    }

    private function getFromDB()
    {
        $stores = Store::all(['storeName', 'storeLink']);
        return ($stores->isEmpty()) ? null : $stores;
    }

    private function createNew(string $name, string $link)
    {
        $store = new Store();
        $store->storeName = $name;
        $store->storeLink = $link;
        $store->save();

        return $store;
    }

    private function saveParsedObjects($stores)
    {
        $newStoreCounter = 0;
        if(is_array($stores)) {
            foreach($stores as $store) {
                $this->createNew($store['storeName'], $store['storeLink']);
                $newStoreCounter++;
            }
        }

        return $newStoreCounter;
    }

    public function createParsedArray($url)
    {
        $this->getHTML($url);
        try {
            $this->parseHTML();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $result = [];
        $StoreDOMElements = $this->parseDOMEelements(STORE_XPATH);
        foreach ($StoreDOMElements as $storeDOM) {
            $parsedStoreInfo = $this->extractInfoFromDOM($storeDOM);
            if ($parsedStoreInfo) {
                $result[] = $parsedStoreInfo;
            }
        }

        return $result;
    }

    public function parse($url)
    {
        $parsedStores = $this->createParsedArray($url);
        $existedStores = $this->getFromDB();

        $newStores = $this->update($parsedStores, $existedStores);

        return $this->saveParsedObjects($newStores);
    }



}
