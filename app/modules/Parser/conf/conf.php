<?php
namespace App\modules\Parser;

define('CATEGORY_URL', 'https://www.coupons.com/coupon-codes/categories/');
define('STORE_URL', 'https://www.coupons.com/coupon-codes/stores/');

$STORES_ALPHABET_CATEGORIES = array('a/', 'b/', 'c/', 'd/', 'e/', 'f/', 'g/', 'h/', 'i/', 'j/', 'k/', 'l/', 'm/',
    'n/', 'o/', 'p/', 'q/', 'r/', 's/', 't/', 'u/', 'v/', 'w/', 'x/', 'y/', 'z/', '0-9/');

define('CATEGORIES_XPATH', '//body/div[@id="app"]/div[@class="main-wrapper"]/div/
div[@class="wrapper product-wrapper view-all-page"]/div[@class="main-container"]/
div[@class="storelisting"]/div[@class="mod-storelisting"]/div[@class="category-stores-container"]/h2/a');

define('STORE_XPATH', '//body/div[@id="app"]/div[@class="main-wrapper"]/div/
div[@class="wrapper product-wrapper view-all-page"]/div[@class="main-container"]/
div[@class="view-all-container"]/ul[@class="horizontal-list"]/li[@class="column"]/ul/li[@class="item"]/*');





