<?php
ini_set('memory_limit', '-1');
set_time_limit(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Budapest');

$my = json_decode(file_get_contents('markedForDeath.json'), true);
$rmlint = json_decode(file_get_contents('rmlint-PDM.json'), true);
$notFound = [];
$differentResult = [];

$total = number_format(count($my));
$i = 0;
foreach ($my as $path) {
    $i++;
    echo "\033[2K\r";
    echo $total . '/' . number_format($i) . "\r";
    $p = str_replace("\/", "/", $path);
    //$key = array_search($p, array_column($rmlint, 'path'));  <----- fails
    $found = search($rmlint, 'path', $p);
    if (!$found) {
        $notFound[] = $p;
    } else {
        if (count($found) > 1)
            echo PHP_EOL . 'found multiple: ' . $p . PHP_EOL;
        //$rmlintItem = $rmlint[$key];
        if ($found[0]['is_original'] === true)
            $differentResult[] = $p;
    }
    $found = null;
}
file_put_contents('notFound.json', json_encode($notFound, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents('differentResult.json', json_encode($differentResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

//https://www.geeksforgeeks.org/how-to-search-by-keyvalue-in-a-multidimensional-array-in-php/
function search($array, $key, $value)
{
    $results = array();

    // if it is array
    if (is_array($array)) {

        // if array has required key and value
        // matched store result
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        // Iterate for each element in array
        foreach ($array as $subarray) {

            // recur through each element and append result
            $results = array_merge($results,
                search($subarray, $key, $value));
        }
    }

    return $results;
}