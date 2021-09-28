<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "report","csv_report");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["project_id"])){
	$project_id=slash($_GET["project_id"]);
	$_SESSION["project_payment"]["list"]["project_id"]=$project_id;
}
if(isset($_SESSION["project_payment"]["list"]["project_id"]))
	$project_id=$_SESSION["project_payment"]["list"]["project_id"];
else
	$project_id="";
if($project_id!=""){
	$extra.=" and a.project_id='".$project_id."'";
	$is_search=true;
}
if( isset($_GET["date_from"]) ){
	$_SESSION["project_payment"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["project_payment"]["list"]["date_from"]) && !empty($_SESSION["project_payment"]["list"]["date_from"])){
	$date_from = $_SESSION["project_payment"]["list"]["date_from"];
}
else{
	$date_from = date("01/m/Y");
}
if( !empty($date_from) ){
	$extra.=" and datetime_added>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["project_payment"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["project_payment"]["list"]["date_to"]) && !empty($_SESSION["project_payment"]["list"]["date_to"])){
	$date_to = $_SESSION["project_payment"]["list"]["date_to"];
}
else{
	$date_to = date("d/m/Y");
}
if( !empty($date_to) ){
	$extra.=" and datetime_added<'".date("Y/m/d", strtotime(date_dbconvert($date_to))+3600*24)."'";
	$is_search=true;
}
if(isset($_GET["exempt_tax"])){
	$exempt_tax=slash($_GET["exempt_tax"]);
	$_SESSION["project_payment"]["list"]["exempt_tax"]=$exempt_tax;
}
if(isset($_SESSION["project_payment"]["list"]["exempt_tax"])){
	$exempt_tax=$_SESSION["project_payment"]["list"]["exempt_tax"];
}
else{
	$exempt_tax="";
}	
if(($exempt_tax!= "")){
	$extra.=" and a.exempt_tax='".$exempt_tax."'";
	$is_search=true;
}
$adminId = '';
if($_SESSION["logged_in_admin"]["admin_type_id"]!=1){
	$adminId = "and b.admin_id = '".$_SESSION["logged_in_admin"]["id"]."'";
}
$sql="select a.*, c.title from project_payment a left join admin_2_project b on a.project_id = b.project_id left join project c on a.project_id = c.id where 1 ".$extra." $adminId order by datetime_added desc";

switch($tab){
	case 'add':
		include("modules/project_payment/add_do.php");
	break;
	case 'edit':
		include("modules/project_payment/edit_do.php");
	break;
	case 'delete':
		include("modules/project_payment/delete_do.php");
	break;
	case 'status':
		include("modules/project_payment/status_do.php");
	break;
	case 'bulk_action':
		include("modules/project_payment/bulkactions.php");
	break;
	case 'report':
		include("modules/project_payment/print_do.php");
	break;
	case 'csv_report':
		include("modules/project_payment/report_csv.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/project_payment/list.php");
			break;
			case 'add':
				include("modules/project_payment/add.php");
			break;
			case 'edit':
				include("modules/project_payment/edit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>