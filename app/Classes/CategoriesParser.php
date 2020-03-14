<?php


namespace App\Classes;


use App\Models\Category;
use DOMElement;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class CategoriesParser extends Parser
{
    /**
     * @param string|null $url
     * @return null|int
     * @throws Exception
     */
    public function parseCategories(?string $url)
    {
        if(!$this->html && $url) {
            $this->getHTML($url);
            $this->parseHTML();
        }
        else {
            throw new Exception('Nothing to parse');
        }
        $result = null;
        $currentCategories = $this->getCategoriesFromDB();
        $categories = $this->parsedData->filterXPath(CATEGORIES_XPATH);
        $categoryDOMElements = $categories->filterXPath(CATEGORY_INFO);
        $start = microtime(true);
        foreach ($categoryDOMElements as $categoryDOM) {
            $parsedCategoryInfo = $this->extractCategoryInfo($categoryDOM);
            if($parsedCategoryInfo) {
                if($currentCategories) {
                    $this->checkCategoryUpdates($currentCategories, $parsedCategoryInfo);
                    $result = true;
                }
                else {
                    $this->createCategory($parsedCategoryInfo['name'], $parsedCategoryInfo['link']);
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

    private function extractCategoryInfo(DOMElement $categoryDOMElement)
    {
        if($categoryDOMElement) {
            $result['name'] = $categoryDOMElement->nodeValue;
            $result['link'] = parse_url($categoryDOMElement->getAttribute('href'))['path'];
        }
        else {
            $result = null;
        }

        return $result;
    }

    private function getCategoriesFromDB()
    {
        $categories = Category::all();
        return ($categories->isEmpty()) ? null : $categories;
    }

    private function checkCategoryUpdates(Collection $currentCategories, $parsedCategory)
    {
        $updates = false;
        $alreadyExists = false;
        foreach($currentCategories as $currentCategory) {
            if($currentCategory->categoryName === $parsedCategory['name']) {
                if($currentCategory->categoryLink !== $parsedCategory['link']) {
                    echo 'Same names' . PHP_EOL;
                    $currentCategory->categoryLink = $parsedCategory['link'];
                    $currentCategory->save();
                    $updates = true;
                }
                else {
                    $alreadyExists = true;
                }
            }
            else {
                if($currentCategory->categoryLink === $parsedCategory['link']) {
                    echo 'Same links' . PHP_EOL;
                    $currentCategory->categoryName = $parsedCategory['name'];
                    $currentCategory->save();
                    $updates = true;
                }
            }
        }

        if(!$updates && !$alreadyExists) {
            $this->createCategory($parsedCategory['name'], $parsedCategory['link']);
            echo 'Saved' . PHP_EOL;
        }

    }

    private function createCategory(string $name, string $link)
    {
        $category = new Category();
        $category->categoryName = $name;
        var_dump($name);
        $category->categoryLink = $link;
        var_dump($link);
        $category->save();
    }

}
