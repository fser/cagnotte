<?php

function reply($code, $msg)
{
	echo json_encode(array('code' => $code, 'msg' => $msg));
	exit (0);
}
function set_json()
{
	header('Content-Type: application/json');
}