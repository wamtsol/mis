<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_date':
			$response = date_convert( date( "Y-m-d" ) );
		break;
		case 'get_project_id':
			$response = isset($_SESSION["invoice"]["list"]["project_id"])?$_SESSION["invoice"]["list"]["project_id"]:0;
		break;
		case "get_projects":
			$rs = doquery( "select * from project where status=1 order by title", $dblink );
			$projects = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$projects[] = array(
						"id" => $r[ "id" ],
						"title" => unslash($r[ "title" ]),
					);
				}
			}
			$response = $projects;
		break;
		case "get_accounts":
			$rs = doquery( "select * from account where status=1 order by title", $dblink );
			$accounts = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$accounts[] = array(
						"id" => $r[ "id" ],
						"title" => unslash($r[ "title" ]),
					);
				}
			}
			$response = $accounts;
		break;
		case "get_invoice":
			$id = slash( $_POST[ "id" ] );
			$rs = doquery( "select a.*, b.amount, b.account_id, b.details from invoice a left join project_payment b on a.project_payment_id = b.id where a.id='".$id."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				$r = dofetch( $rs );
				$invoice = array(
					"id" => $r[ "id" ],
					"due_date" => date_convert( $r[ "due_date" ] ),
					"invoice_date" => date_convert( $r[ "invoice_date" ] ),
					"project_id" => $r[ "project_id" ],
					"work_order_number" => $r[ "work_order_number" ],
					"type" => $r[ "type" ],
					"total_amount" => $r[ "total_amount" ],
					"discount" => $r[ "discount" ],
					"net_amount" => $r[ "net_amount" ],
					"sales_tax" => $r[ "sales_tax" ],
					"wht" => $r[ "wht" ],
					"project_payment_id" => $r[ "project_payment_id" ],
					"payment_amount" => $r[ "amount" ],
					"payment_account_id" => $r[ "account_id" ],
				);
				$items = array();
				$rs = doquery( "select * from invoice_item where invoice_id='".$id."' order by id", $dblink );
				if( numrows( $rs ) > 0 ) {
					while( $r = dofetch( $rs ) ) {
						$items[] = $r;
					}
				}
				$invoice[ "items" ] = $items;
			}
			$response = $invoice;
		break;
		case "save_invoice":
			$err = array();
			$invoice = json_decode( $_POST[ "invoice" ] );
			if( empty( $invoice->project_id ) || empty( $invoice->invoice_date ) || empty( $invoice->due_date ) ) {
				$err[] = "Fields with * are mandatory";
			}
			if( count( $invoice->items ) == 0 ) {
				$err[] = "Add some items first.";
			}
			else {
				$i=1;
				foreach( $invoice->items as $item ) {
					if( empty( $item->details ) || empty( $item->rate ) || empty( $item->quantity ) || empty( $item->amount ) ){
						$err[] = "Fill all the required fields on Row#".$i;
					}
					$i++;
				}
			}
			if( count( $err ) == 0 ) {
				if( !empty( $invoice->id ) ) {
					doquery( "update invoice set `project_id`='".slash($invoice->project_id)."', `work_order_number`='".slash($invoice->work_order_number)."', `due_date`='".slash(date_dbconvert(unslash($invoice->due_date)))."', `invoice_date`='".slash(date_dbconvert(unslash($invoice->invoice_date)))."', `type`='".slash($invoice->type)."', `total_amount`='".slash($invoice->total_amount)."', `discount`='".slash($invoice->discount)."', `net_amount`='".slash($invoice->net_amount)."', `sales_tax`='".slash($invoice->sales_tax)."', `wht`='".slash($invoice->wht)."' where id='".$invoice->id."'", $dblink );
					$invoice_id = $invoice->id;
				}
				else {
					doquery( "insert into invoice (project_id, work_order_number, due_date, invoice_date, type, total_amount, discount, net_amount, sales_tax, wht) VALUES ('".slash($invoice->project_id)."', '".slash($invoice->work_order_number)."', '".slash(date_dbconvert($invoice->due_date))."', '".slash(date_dbconvert($invoice->invoice_date))."', '".slash($invoice->type)."', '".slash($invoice->total_amount)."', '".slash($invoice->discount)."', '".slash($invoice->net_amount)."', '".slash($invoice->sales_tax)."', '".slash($invoice->wht)."' )", $dblink );
					$invoice_id = inserted_id();
				}
				$item_ids = array();
				foreach( $invoice->items as $item ) {
					if( empty( $item->id ) ) {
						doquery( "insert into invoice_item( invoice_id, details, rate, quantity, quantity_unit, amount) values( '".$invoice_id."', '".$item->details."', '".$item->rate."', '".$item->quantity."', '".$item->quantity_unit."', '".$item->amount."')", $dblink );
						$item_ids[] = inserted_id();
					}
					else {
						doquery( "update invoice_item set `invoice_id`='".$invoice_id."', `details`='".$item->details."', `rate`='".$item->rate."', `quantity`='".$item->quantity."', `quantity_unit`='".$item->quantity_unit."', `amount`='".$item->amount."' where id='".$item->id."'", $dblink );
						$item_ids[] = $item->id;
					}
				}
				if( !empty( $invoice->id ) && count( $item_ids ) > 0 ) {
					doquery( "delete from invoice_item where invoice_id='".$invoice_id."' and id not in( ".implode( ",", $item_ids )." )", $dblink );
				}
				if( !empty( $invoice->payment_account_id ) ) {
					if( empty( $invoice->project_payment_id ) ) {
						doquery( "insert into project_payment ( project_id, datetime_added, amount, account_id, details) VALUES ('".slash($invoice->project_id)."', NOW(), '".slash($invoice->payment_amount)."', '".slash($invoice->payment_account_id)."', 'Payment against Invoice #.".$invoice_id."') ", $dblink );
						$project_payment_id = inserted_id();
						doquery( "update invoice set project_payment_id = '".$project_payment_id."' where id ='".$invoice->id."'", $dblink);
					}
					else {
						doquery( "update project_payment set project_id = '".slash( $invoice->project_id )."', amount = '".slash( $invoice->payment_amount )."', account_id = '".slash( $invoice->payment_account_id )."' where id = '".$invoice->project_payment_id."'", $dblink );
					}
				}
				else if( !empty( $invoice->project_payment_id ) ) {
					doquery( "delete from project_payment where id = '".$invoice->payment_account_id."'", $dblink );
					doquery( "update invoice set project_payment_id = '0' where id ='".$invoice->id."'", $dblink);
				}
				$response = array(
					"status" => 1,
					"id" => $invoice_id
				);
			}
			else {
				$response = array(
					"status" => 0,
					"error" => $err
				);
			}
		break;
	}
	echo json_encode( $response );
	die;
}