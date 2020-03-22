<?php


namespace App\Classes\Parser;

use App\Classes\State\State;
use App\Models\Store;
use DOMElement;
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

    private function getFromDB($letter)
    {
        if(ctype_alpha($letter)) {
            $upperLetter = strtoupper($letter);
            $stores = Store::withoutTrashed()->where('storeName', '~', '^('.$letter.'|'.$upperLetter.')(\w|\s)*')
                ->get();
        }
        else {
            $stores = Store::withoutTrashed()->where('storeName', '~', '^\d(\w|\s|\d)*')->get();
        }

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
        $this->parseHTML();

        $result = [];
        $StoreDOMElements = $this->parseDOMEelements(config('parser.STORE_XPATH'));
        foreach ($StoreDOMElements as $storeDOM) {
            $parsedStoreInfo = $this->extractInfoFromDOM($storeDOM);
            if ($parsedStoreInfo) {
                $result[] = $parsedStoreInfo;
            }
        }

        return $result;
    }


    public function update(array $parsedArray, ?Collection $existedArray)
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

    public function parse($url)
    {
        $letter = State::getLastLetter($url);
        $parsedStores = $this->createParsedArray($url);
        $existedStores = $this->getFromDB($letter);
        $newStores = $this->update($parsedStores, $existedStores);

        return $this->saveParsedObjects($newStores);
    }
}
