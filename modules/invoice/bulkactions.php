<?php
if(!defined("APP_START")) die("No Direct Access");

if(isset($_GET["action"]) && $_GET["action"]!=""){
	$bulk_action=$_GET["action"];
	$id=explode(",",urldecode($_GET["Ids"]));	
	$err="";
	if($bulk_action=="null"){
		$err.="Select Action. <br>";
	}
	if(!isset($_GET["Ids"]) || $_GET["Ids"]==""){
		$err.="Select Records. <br>";	
	}
	if(empty($err)){
		if($bulk_action=="delete"){
			$i=0;
			while($i<count($id)){
				$invoice = doquery( "select * from invoice where id = '".$id[$i]."' ", $dblink );
				if( numrows( $invoice ) > 0 ) {
					$invoice = dofetch( $invoice );
					doquery("delete from invoice_item where invoice_id='".$id[$i]."'",$dblink);
					if( $invoice[ "project_payment_id" ] > 0 ) {
						doquery( "delete from project_payment where id = '".$invoice[ "project_payment_id" ]."'", $dblink );
					}
					doquery("delete from invoice where id='".$id[$i]."'",$dblink);
				}
				$i++;
			}
			header("Location: invoice_manage.php?tab=list&msg=".url_encode("Records Deleted."));
			die;
		}
		if($bulk_action=="statuson"){
			$i=0;
			while($i<count($id)){
				$rec = doquery( "select * from invoice where id='".$id[$i]."'", $dblink );
				if( numrows( $rec ) > 0 ) {
					$rec = dofetch( $rec );
					if( $rec[ "project_payment_id" ] > 0 ) {
						doquery( "update project_payment set status=1 where id = '".$rec[ "project_payment_id" ]."'", $dblink );
					}
				}
				doquery("update invoice set status=1 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: invoice_manage.php?tab=list&msg=".url_encode("Records Status On."));
			die;
		}
		if($bulk_action=="statusof"){
			$i=0;
			while($i<count($id)){
				$rec = doquery( "select * from invoice where id='".$id[$i]."'", $dblink );
				if( numrows( $rec ) > 0 ) {
					$rec = dofetch( $rec );
					if( $rec[ "project_payment_id" ] > 0 ) {
						doquery( "update project_payment set status=0 where id = '".$rec[ "project_payment_id" ]."'", $dblink );
					}
				}
				doquery("update invoice set status=0 where id='".$id[$i]."'",$dblink);
				$i++;
			}
			header("Location: invoice_manage.php?tab=list&msg=".url_encode("Records Status Off."));
			die;
		}
	}
	else{
		header("Location: invoice_manage.php?tab=list&err=".url_encode($err));
		die;					
	}
}
else{
	header("Location: index.php");
	die;	
}