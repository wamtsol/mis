<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from employee_daily_attendance where date='".date_dbconvert( $_GET[ "date" ] )."'",$dblink);
	doquery("delete from employee_attendance where date='".date_dbconvert( $_GET[ "date" ] )."'",$dblink);
	header("Location: employee_attendance_manage.php");
	die;
}