<?php

function createDb($host, $dbName)
{
    $check = function (PDO $obj) {
        if ($obj->errorCode() != '0000') {
            var_dump($obj->errorInfo());
            exit(-1);
        }
    };
    $tablesSql = file_get_contents(__DIR__ . '/../../schema/schema.sql');
    $pdo = new PDO('mysql:host='.$host.';dbname=mysql', 'root', '');
    $result = $pdo->query("SELECT 1 FROM INFORMATION_SCHEMA.SCHEMATA d WHERE d.SCHEMA_NAME = '$dbName'");
    $result->execute();
    if ($result->fetchColumn()) {
        echo $dbName.' ya esta creada'.PHP_EOL;
        $pdo->exec("DROP DATABASE $dbName");
        echo $dbName.' ha sido eliminada'.PHP_EOL;
    }
    $pdo->exec("CREATE DATABASE $dbName DEFAULT CHARACTER SET utf8;");
    $check($pdo);
    $pdo = new PDO('mysql:host='.$host.';dbname='.$dbName, 'root', '');
    $pdo->exec($tablesSql);
    $check($pdo);

    echo $dbName.' completado!!!.'.PHP_EOL;
}

$host = getenv('SUFEL_DB_HOST') ?: '127.0.0.1';
createDb($host, 'sufel_dev');