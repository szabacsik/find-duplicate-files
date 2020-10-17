<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

$pdo->query('TRUNCATE file');
$pdo->query('TRUNCATE hash');

$count=0;
$directoryIterator = new RecursiveDirectoryIterator($path);
foreach (new RecursiveIteratorIterator($directoryIterator) as $file) {
    if ($file->isFile()) {
        $count++;
    }
}
$total = number_format($count);

$i = 0;
$directoryIterator = new RecursiveDirectoryIterator($path);
foreach (new RecursiveIteratorIterator($directoryIterator) as $file) {
    if ($file->isFile()) {
        $i++;
        echo "\033[2K\r"; echo $total.'/'.number_format($i)."\r";
        $hashValue = hash_file('sha256', $file->getPathname());
        try {
            $pdo->beginTransaction();
            $hashId = storeHash($pdo, $hashValue);
            storeFile($pdo, $file, $hashId);
            $pdo->commit();
        } catch (\Exception $exception)
        {
            $pdo->rollBack();
            echo $exception.PHP_EOL;
            echo PHP_EOL.$file->getPathname().PHP_EOL;
        }
    }
}
echo PHP_EOL.$total.'/'.number_format($i).PHP_EOL;

function storeHash($pdo, $hashValue)
{
    $sql = 'SELECT id FROM hash WHERE hash=:hash';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':hash', $hashValue);
    $statement->execute();
    $id = $statement->fetchColumn();
    if ($id)
        return $id;
    $sql = 'INSERT INTO hash (hash, created_at) VALUES (:hash, :created_at)';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':hash', $hashValue);
    $now = new \DateTime('now', new \DateTimeZone('Europe/Budapest'));
    $statement->bindValue('created_at', $now->format('Y-m-d H:i:s'));
    $statement->execute();
    return $pdo->lastInsertId();
}

function storeFile($pdo, $file, $hashId)
{
    $aTime = new \DateTime("@" . $file->getATime());
    $cTime = new \DateTime("@" . $file->getCTime());
    $mTime = new \DateTime("@" . $file->getMTime());
    $timezone = new DateTimeZone('Europe/Budapest');
    $aTime->setTimezone($timezone);
    $cTime->setTimezone($timezone);
    $mTime->setTimezone($timezone);
    $sql = 'INSERT INTO file (path, hash_id, atime, ctime, mtime, created_at) ' .
        'VALUES (:path, :hash_id, :atime, :ctime, :mtime, :created_at)';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':path', $file->getPathname());
    $statement->bindValue(':hash_id', $hashId, \PDO::PARAM_INT);
    $statement->bindValue(':atime', $aTime->format('Y-m-d H:i:s'));
    $statement->bindValue(':ctime', $cTime->format('Y-m-d H:i:s'));
    $statement->bindValue(':mtime', $mTime->format('Y-m-d H:i:s'));
    $now = new \DateTime('now', new \DateTimeZone('Europe/Budapest'));
    $statement->bindValue(':created_at', $now->format('Y-m-d H:i:s'));
    $statement->execute();
}

