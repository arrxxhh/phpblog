<?php
function getPDO() {
    $root=__DIR__.'/../';
    $database=$root.'data/data.sqlite';
    $dsn="sqlite:".$database;

    try{
        $pdo=new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }catch(PDOException $e){
        die("Connection Failed".$e->getMessage());
    }
}

?>
