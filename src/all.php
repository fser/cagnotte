<?php
   header('Content-Type: text/html; encoding=utf-8');
?>
<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Cadeau Mickey</title>
  <meta name="description" content="Collecte de sous pour faire un cadeau à Jeanne">
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <div id="content">
    <h1>Les donnateurs</h1>

    <ul>
    <?php
include('config.php');
try 
{
    $uri = sprintf("mysql:host=%s;dbname=%s", $ro['host'], $ro['db']);
    $conn = new PDO($uri, $ro['user'], $ro['pass'],array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET CHARACTER SET utf8");
    $sth = $conn->prepare('SELECT * FROM `dons`');
    $sth->execute();
    
    foreach ($sth->fetchAll() AS $row)
    {
       printf("\t<li>%s</li>\n", htmlspecialchars(utf8_decode($row['nom'])));
    }
}
catch(PDOException $e)
{
    echo 'Error!';
}

    ?>
    </ul>
  </div>
</body>
</html>
