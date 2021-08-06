<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	
	doquery("delete from supply_item where supply_id='".$id."'",$dblink);
	doquery("delete from supply where id='".$id."'",$dblink);
	header("Location: supply_manage.php");
	die;
}