<?php

$json = file_get_contents('http://twitter.mizofumi.net/deathnote/deathnote/0_06/api/?t=s');
$obj = json_decode($json,true);
foreach ($obj as $laco => $a) {
	print $laco;
}