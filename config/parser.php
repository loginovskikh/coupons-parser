<?php

return [
    'CATEGORY_URL'     => 'https://www.coupons.com/coupon-codes/categories/',
    'STORE_URL'        => 'https://www.coupons.com/coupon-codes/stores/',

    'CATEGORIES_XPATH' => '//body/div[@id="app"]/div[@class="main-wrapper"]/div/
    div[@class="wrapper product-wrapper view-all-page"]/div[@class="main-container"]/div[@class="storelisting"]
    /div[@class="mod-storelisting"]/div[@class="category-stores-container"]/h2/a',

    'STORE_XPATH'      => '//body/div[@id="app"]/div[@class="main-wrapper"]/div/
    div[@class="wrapper product-wrapper view-all-page"]/div[@class="main-container"]/div[@class="view-all-container"]
    /ul[@class="horizontal-list"]/li[@class="column"]/ul/li[@class="item"]/*',

    'STORES_ALPHABET_CATEGORIES' => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
        'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0-9'],

    'PARSING_MODE' => ['all-stores', 'stores-by-letter', 'all-coupons', 'coupons-by-store'],
];






