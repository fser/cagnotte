<?php

include('config.php');
include('utils.php');

set_json();
try 
{
    $uri = sprintf("mysql:host=%s;dbname=%s", $ro['host'], $ro['db']);
    $conn = new PDO($uri, $ro['user'], $ro['pass']);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sth = $conn->prepare('SELECT sum(montant) AS total FROM `dons`');
    $sth->execute();
    $total = $sth->fetch();

    reply(200, $total['total']);
}
catch(PDOException $e)
{
    reply(400, "SQL Error: $e->getMessage()");
}