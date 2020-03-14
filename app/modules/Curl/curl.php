<?php

require_once ('./app/modules/Curl/conf.php');

function getRequest($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL , 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36 OPR/65.0.3467.69)');

//Set the proxy IP.
    curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);

//Set the port.
    curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);

    $output = curl_exec($ch);

//Check for errors.
    if(curl_errno($ch)){
        throw new Exception(curl_error($ch));
    }

    return $output;
}

