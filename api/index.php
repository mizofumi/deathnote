<?php

switch ($_GET["t"]) {
	case 's':
		$obj = array('1'=>'hogehoge','2'=>'lacolaco');
		break;
	
	default:
		$obj = "Error";
		break;
}

if($_GET["file"] === "1"){
  header("Content-disposition: attachment; filename=idList.json ");
  header("Content-type: application/octet-stream; name=idList.json");
  echo json_encode($obj);
 }else{ 
  echo json_encode($obj);
 }