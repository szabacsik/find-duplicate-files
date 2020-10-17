<?php
ini_set('memory_limit', '-1');
set_time_limit(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Budapest');

//var_dump(differ('d:\temp\1.JPG','d:\temp\2.JPG')); die();

if (file_exists('DELETE.log'))
    unlink('DELETE.log');

$total=0;
$filesByHash = json_decode(file_get_contents('filesByHash.json'), true);
foreach ($filesByHash as $hash => $files) {
    foreach ($files as $index => $file)
        if (isset($file['markedForDeath'])) $total++;
}

foreach ($filesByHash as $hash => $files) {
    $removed = [];
    foreach ($files as $index => $file)
        if (isset($file['markedForDeath'])) {
            $original = $filesByHash[$hash][0];
            $duplicate = $file;
            if (differ($original['file_path'], $duplicate['file_path']))
                die('ERROR they are not equal: `' . $original['file_path'] . '` and `' . $duplicate['file_path'] . '`!!!');
            else {
                if (file_exists($duplicate['file_path']))
                    unlink($duplicate['file_path']);
                $removed[] = $duplicate['file_path'];
                $total--;
                echo "\033[2K\r"; echo number_format($total)."\r";
            }
        }
    $log = 'megtarott: '.$original['file_path'] . PHP_EOL;
    foreach ($removed as $r)
        $log .= "\ttörölt: " . $r . PHP_EOL;
    $log .= PHP_EOL;
    file_put_contents('DELETE.log', $log, FILE_APPEND);
}


function differ($file1, $file2): bool
{
    $command = 'diff "' . $file1 . '" "' . $file2.'"';
    $output = [];
    $status = 0;
    exec($command, $output, $status);
    return (bool)substr_count(json_encode($output), 'differ');
}