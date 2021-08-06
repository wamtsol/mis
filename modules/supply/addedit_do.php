<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["action"])){
	$response = array();
	switch($_POST["action"]){
		case 'get_date':
			$response = date_convert( date( "Y-m-d" ) );
		break;
		case "get_location":
			$rs = doquery( "select * from location where status = 1 order by title", $dblink );
			$locations = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$locations[] = array(
						"id" => $r[ "id" ],
						"title" => unslash($r[ "title" ])
					);
				}
			}
			$response = $locations;
		break;
		case "get_item":
			$rs = doquery( "select * from item where status = 1 order by title", $dblink );
			$items = array();
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$items[] = array(
						"id" => $r[ "id" ],
						"title" => unslash($r[ "title" ]),
					);
				}
			}
			$response = $items;
		break;
		case "get_supply":
			$id = slash( $_POST[ "id" ] );
			$rs = doquery( "select * from supply where id='".$id."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				$r = dofetch( $rs );
				$supply = array(
					"id" => $r[ "id" ],
					"date" => date_convert( $r[ "date" ] ),
					"location_id" => $r[ "location_id" ],
					"note" => unslash($r[ "note" ])
				);
				$items = array();
				$rs = doquery( "select * from supply_item where supply_id='".$id."' order by id", $dblink );
				if( numrows( $rs ) > 0 ) {
					while( $r = dofetch( $rs ) ) {
						$items[] = $r;
					}
				}
				$supply[ "items" ] = $items;
			}
			$response = $supply;
		break;
		case "save_supply":
			$err = array();
			$supply = json_decode( $_POST[ "supply" ] );
			if( empty( $supply->date )  ) {
				$err[] = "Fields with * are mandatory";
				
			}
			if( count( $supply->items ) == 0 ) {
				$err[] = "Add some items first.";
			}
			else {
				$i=1;
				foreach( $supply->items as $item ) {
					if( empty( $item->item_id ) || empty( $item->quantity ) ){
						$err[] = "Fill all the required fields on Row#".$i;
					}
					$i++;
				}
			}
			if( count( $err ) == 0 ) {
				if( !empty( $supply->id ) ) {
					doquery( "update supply set `date`='".slash(date_dbconvert(unslash($supply->date)))."', `location_id`='".slash($supply->location_id)."', `note`='".slash($supply->note)."' where id='".$supply->id."'", $dblink );
					$supply_id = $supply->id;
				}
				else {
					doquery( "insert into supply (date, location_id, note) VALUES ('".slash(date_dbconvert($supply->date))."', '".slash($supply->location_id)."', '".slash($supply->note)."')", $dblink );
					$supply_id = inserted_id();
				}
				$item_ids = array();
				foreach( $supply->items as $item ) {
					if( empty( $item->id ) ) {
						doquery( "insert into supply_item( supply_id, item_id, quantity ) values( '".$supply_id."', '".$item->item_id."', '".$item->quantity."' )", $dblink );
						$item_ids[] = inserted_id();
					}
					else {
						doquery( "update supply_item set `supply_id`='".$supply_id."', `item_id`='".$item->item_id."', `quantity`='".$item->quantity."' where id='".$item->id."'", $dblink );
						$item_ids[] = $item->id;
					}
				}
				if( !empty( $supply->id ) && count( $item_ids ) > 0 ) {
					doquery( "delete from supply_item where supply_id='".$supply_id."' and id not in( ".implode( ",", $item_ids )." )", $dblink );
				}
				$response = array(
					"status" => 1,
					"id" => $supply_id
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