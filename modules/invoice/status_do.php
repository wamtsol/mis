<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$status = slash($_GET["s"]);
	$id=slash($_GET["id"]);
	$rec = doquery( "select * from invoice where id='".$id."'", $dblink );
	if( numrows( $rec ) > 0 ) {
		$rec = dofetch( $rec );
		if( $rec[ "project_payment_id" ] > 0 ) {
			doquery( "update project_payment set status='".$status."' where id = '".$rec[ "project_payment_id" ]."'", $dblink );
		}
	}
	doquery("update invoice set status='".$status."' where id='".$id."'",$dblink);
	header("Location: invoice_manage.php");
	die;
}