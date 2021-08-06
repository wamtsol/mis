<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'placement_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "status", "delete", "bulk_action", "report","addedit");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["placement"]["list"]["date_from"]=$date_from;
}
if(isset($_SESSION["placement"]["list"]["date_from"]))
	$date_from=$_SESSION["placement"]["list"]["date_from"];
else
	$date_from="";
if($date_from != ""){
	$extra.=" and date>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["placement"]["list"]["date_to"]=$date_to;
}
if(isset($_SESSION["placement"]["list"]["date_to"]))
	$date_to=$_SESSION["placement"]["list"]["date_to"];
else
	$date_to="";
if($date_to != ""){
	$extra.=" and date<'".date("Y/m/d", strtotime(date_dbconvert($date_to))+3600*24)."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["placement"]["list"]["q"]=$q;
}
if(isset($_SESSION["placement"]["list"]["q"]))
	$q=$_SESSION["placement"]["list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and (note like '%".$q."%')";
	$is_search=true;
}
$order_by = "date";
$order = "desc";
if( isset($_GET["order_by"]) ){
	$_SESSION["placement"]["list"]["order_by"]=slash($_GET["order_by"]);
}
if( isset( $_SESSION["placement"]["list"]["order_by"] ) ){
	$order_by = $_SESSION["placement"]["list"]["order_by"];
}
if( isset($_GET["order"]) ){
	$_SESSION["placement"]["list"]["order"]=slash($_GET["order"]);
}
if( isset( $_SESSION["placement"]["list"]["order"] ) ){
	$order = $_SESSION["placement"]["list"]["order"];
}
$orderby = $order_by." ".$order;
$sql="select * from placement where 1 $extra order by $orderby";
switch($tab){
	case 'addedit':
		include("modules/placement/addedit_do.php");
	break;
	case 'delete':
		include("modules/placement/delete_do.php");
	break;
	case 'status':
		include("modules/placement/status_do.php");
	break;
	case 'bulk_action':
		include("modules/placement/bulkactions.php");
	break;
	case 'report':
		include("modules/placement/report.php");
		die;
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/placement/list.php");
			break;
			case 'addedit':
				include("modules/placement/addedit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>