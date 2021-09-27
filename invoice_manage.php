<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'purchase_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "status", "delete", "bulk_action", "report","addedit", "print");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$is_search=false;
if(isset($_SESSION["invoice"]["list"]["project_id"])) {
	$project_id = $_SESSION["invoice"]["list"]["project_id"];
} else {
	$project_id = "";
}
if( !empty( $project_id ) ){
	$extra .= " and a.project_id = '".$project_id."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$_SESSION["invoice"]["list"]["q"] = slash( $_GET["q"] );
}
if(isset($_SESSION["invoice"]["list"]["q"])) {
	$q=$_SESSION["invoice"]["list"]["q"];
} else {
	$q="";
}
if(!empty($q)){
	$extra.=" and (b.title like '%".$q."%')";
	$is_search=true;
}
$sql="select a.*, b.title, ifnull(c.amount, 0) as payment_amount from invoice a left join project b on a.project_id = b.id left join project_payment c on a.project_payment_id = c.id where 1 $extra order by a.due_date desc, a.invoice_date asc, a.ts desc";

switch($tab){
	case 'addedit':
		include("modules/invoice/addedit_do.php");
	break;
	case 'delete':
		include("modules/invoice/delete_do.php");
	break;
	case 'status':
		include("modules/invoice/status_do.php");
	break;
	case 'bulk_action':
		include("modules/invoice/bulkactions.php");
	break;
	case 'report':
		include("modules/invoice/report.php");
		die;
	break;
	case 'print':
		include("modules/invoice/print.php");
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
				include("modules/invoice/list.php");
			break;
			case 'addedit':
				include("modules/invoice/addedit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>