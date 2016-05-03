<?php

include('utils.php');
include('config.php');

session_start();

set_json();

function add_stuff($nom, $mail, $montant)
{
	global $wo;

	try 
	{
	    $uri = sprintf("mysql:host=%s;dbname=%s", $wo['host'], $wo['db']);
	    $conn = new PDO($uri, $wo['user'], $wo['pass']);
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	    $sth = $conn->prepare('INSERT INTO `dons` (nom, mail,montant) VALUES(?,?,?)');
	    $sth->execute(array($nom, $mail, $montant));
	}
	catch(PDOException $e)
	{
echo $e->getMessage();
	    return FALSE;
	}

	return TRUE;
}

if (!isset($_SESSION['pass_phrase']))
{
	reply(400, 'No session found');
}

$fields = array('nom', 'email', 'montant', 'captcha');

foreach ($fields AS $field)
{
	if (!isset($_POST[$field]) || empty($_POST[$field]))
		reply(400, "Field '$field' is required");
}

if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === FALSE)
{
	reply(400, 'Bad email address');
}

if (filter_var($_POST['montant'], FILTER_VALIDATE_INT) === FALSE)
{
	reply(400, 'Error with donation value');
}

if ($_POST['montant'] < 0)
{
	reply(400, 'Donation should be positive!');
}

if (
	(filter_var($_POST['captcha'], FILTER_SANITIZE_STRING) === FALSE) 
	|| 
	(sha1($_POST['captcha']) !== $_SESSION['pass_phrase'])
   )
{
	reply(400, 'Captcha was invalid (got ' . $_POST['captcha'] . ')');
}

if (!add_stuff($_POST['nom'], $_POST['email'], $_POST['montant']))
{
	reply(400, 'Error inserting values');
}
else
{
    reply(200, 'Thank you for contributing!');
    header('Location: ./merci.html');
    echo 'Si vous n\'êtes pas redirigé, <a href="./merci.html">suivez ce lien</a>';
}



