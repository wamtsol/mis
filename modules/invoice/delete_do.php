<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	$invoice = doquery( "select * from invoice where id = '".$id."' ", $dblink );
	if( numrows( $invoice ) > 0 ) {
		$invoice = dofetch( $invoice );
		doquery("delete from invoice_item where invoice_id='".$id."'",$dblink);
		if( $invoice[ "project_payment_id" ] > 0 ) {
			doquery( "delete from project_payment where id = '".$invoice[ "project_payment_id" ]."'", $dblink );
		}
		doquery("delete from invoice where id='".$id."'",$dblink);
	}
	header("Location: invoice_manage.php?msg=".url_encode( "Record deleted successfully." ));
	die;
}