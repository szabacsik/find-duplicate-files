<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

$sql = "
SELECT
    hash.hash AS hash_value,
    hash.id AS hash_id,
    file.path AS file_path,
    greatest(ctime,mtime) AS file_latest_time,
    CHAR_LENGTH(path) AS file_path_length,
    (LENGTH(path)-LENGTH(REPLACE(path, '/', ''))) AS path_depth,
    file.atime AS file_atime,
    file.ctime AS file_ctime,
    file.mtime AS file_mtime,
    file.id AS file_id
FROM
     hash
JOIN
     file on hash.id = file.hash_id
ORDER BY
     hash.id,
     path_depth DESC,
     file_path_length DESC,
     file_latest_time DESC,
     file_id DESC
";

$delete = 0;
$filesByHash = [];
$statement = $pdo->prepare($sql);
$statement->execute();
$files = $statement->fetchAll();
foreach ($files as $index => $file) {
    if (isset($filesByHash[$file['hash_value']])) {
        $file['markedForDeath'] = true;
        $delete++;
    }
    $filesByHash[$file['hash_value']][] = $file;
}
file_put_contents('filesByHash.json', json_encode($filesByHash, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

foreach ($filesByHash as $test) {
    if (isset($test[0]['markedForDeath']))
        throw new \Exception('Error');
    if (count($test) > 1) {
        for ($i = 1; $i < count($test); $i++)
            if (!isset($test[$i]['markedForDeath']))
                throw new \Exception('Error');
    }
}

$markedForDeath = [];
foreach ($filesByHash as $hash => $files) {
    foreach ($files as $file)
        if (isset($file['markedForDeath']))
            $markedForDeath[] = $file['file_path'];
}
file_put_contents('markedForDeath.json', json_encode($markedForDeath, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

/*
atime – Last Access Time
Due to its definition, atime attribute must be updated – meaning written to a disk – every time a Unix file is accessed,
even if it was just a read operation.
https://www.unixtutorial.org/atime-ctime-mtime-in-unix-filesystems/
*/

/*
 * 143,694
 */
