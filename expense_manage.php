<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'expense_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "print", "voucher", "csv_report");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$extra='';
$is_search=false;
if(isset($_GET["project_id"])){
	$_SESSION["expense"]["list"]["project_id"]=$_GET["project_id"];
}
if(isset($_SESSION["expense"]["list"]["project_id"]))
	$project_id=$_SESSION["expense"]["list"]["project_id"];
else
	$project_id="";
if( $project_id!="" ){
	$extra.=" and a.project_id='".$project_id."'";
	$is_search=true;
}
if( isset($_GET["date_from"]) ){
	$_SESSION["expense"]["list"]["date_from"] = $_GET["date_from"];
}
if(isset($_SESSION["expense"]["list"]["date_from"]) && !empty($_SESSION["expense"]["list"]["date_from"])){
	$date_from = $_SESSION["expense"]["list"]["date_from"];
}
else{
	$date_from = "";
}
if( !empty($date_from) ){
	$extra.=" and datetime_added>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date_from)))."'";
	$is_search=true;
}
if( isset($_GET["date_to"]) ){
	$_SESSION["expense"]["list"]["date_to"] = $_GET["date_to"];
}
if(isset($_SESSION["expense"]["list"]["date_to"]) && !empty($_SESSION["expense"]["list"]["date_to"])){
	$date_to = $_SESSION["expense"]["list"]["date_to"];
}
else{
	$date_to = "";
}
if( !empty($date_to) ){
	$extra.=" and datetime_added<'".date("Y/m/d", strtotime(date_dbconvert($date_to))+3600*24)."'";
	$is_search=true;
}
if(isset($_GET["expense_category_id"])){
	$expense_category_id=slash($_GET["expense_category_id"]);
	$_SESSION["expense"]["list"]["expense_category_id"]=$expense_category_id;
}
if(isset($_SESSION["expense"]["list"]["expense_category_id"]))
	$expense_category_id=$_SESSION["expense"]["list"]["expense_category_id"];
else
	$expense_category_id="";
if($expense_category_id!=""){
	//$extra.=" and expense_category_id in (".explode(",", $expense_category_id).")";
	$extra.=" and expense_category_id='".$expense_category_id."'";
	$is_search=true;
}
if(isset($_GET["account_id"])){
	$account_id=slash($_GET["account_id"]);
	$_SESSION["expense"]["list"]["account_id"]=$account_id;
}
if(isset($_SESSION["expense"]["list"]["account_id"]))
	$account_id=$_SESSION["expense"]["list"]["account_id"];
else
	$account_id="";
if($account_id!=""){
	$extra.=" and account_id='".$account_id."'";
	$is_search=true;
}
$adminId = '';
if($_SESSION["logged_in_admin"]["admin_type_id"]!=1){
	$adminId = "and b.admin_id = '".$_SESSION["logged_in_admin"]["id"]."'";
}
$sql="select a.* from expense a left join admin_2_project b on a.project_id = b.project_id where 1 $extra $adminId order by datetime_added desc";

switch($tab){
	case 'add':
		include("modules/expense/add_do.php");
	break;
	case 'edit':
		include("modules/expense/edit_do.php");
	break;
	case 'delete':
		include("modules/expense/delete_do.php");
	break;
	case 'status':
		include("modules/expense/status_do.php");
	break;
	case 'bulk_action':
		include("modules/expense/bulkactions.php");
	break;
	case 'print':
		include("modules/expense/print_do.php");
	break;
	case 'voucher':
		include("modules/expense/voucher.php");
	break;
	case 'csv_report':
		include("modules/expense/report_csv.php");
	break;
}
?>
<?php include("include/header.php");?>
	<div class="container-widget row">
    	<div class="col-md-12">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/expense/list.php");
                break;
                case 'add':
                    include("modules/expense/add.php");
                break;
                case 'edit':
                    include("modules/expense/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>