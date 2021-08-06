<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'project_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "view", "report");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["client_id"])){
	$client_id=slash($_GET["client_id"]);
	$_SESSION["project_manage"]["client_id"]=$client_id;
}
if(isset($_SESSION["project_manage"]["client_id"]))
	$client_id=$_SESSION["project_manage"]["client_id"];
else
	$client_id="";
if($client_id!=""){
	$extra.=" and client_id='".$client_id."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["project_manage"]["q"]=$q;
}
if(isset($_SESSION["project_manage"]["q"]))
	$q=$_SESSION["project_manage"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and title like '%".$q."%'";
	$is_search=true;
}
if( isset($_GET["start_date"]) ){
	$_SESSION["project_manage"]["start_date"] = $_GET["start_date"];
}
if(isset($_SESSION["project_manage"]["start_date"]) && !empty($_SESSION["project_manage"]["start_date"])){
	$start_date = $_SESSION["project_manage"]["start_date"];
}
else{
	$start_date = "";
}
if( !empty($start_date) ){
	$extra=" and start_date>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($start_date)))."' and start_date<'".date("Y/m/d H:i:s", strtotime(date_dbconvert($start_date))+3600*24)."'";
	$is_search=true;
}
if( isset($_GET["end_date"]) ){
	$_SESSION["project_manage"]["end_date"] = $_GET["end_date"];
}
if(isset($_SESSION["project_manage"]["end_date"]) && !empty($_SESSION["project_manage"]["end_date"])){
	$end_date = $_SESSION["project_manage"]["end_date"];
}
else{
	$end_date = "";
}
if( !empty($end_date) ){
	$extra=" and end_date>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($end_date)))."' and end_date<'".date("Y/m/d H:i:s", strtotime(date_dbconvert($end_date))+3600*24)."'";
	$is_search=true;
}
$sql="select * from project where 1 $extra";
switch($tab){
	case 'add':
		include("modules/project/add_do.php");
	break;
	case 'edit':
		include("modules/project/edit_do.php");
	break;
	case 'delete':
		include("modules/project/delete_do.php");
	break;
	case 'status':
		include("modules/project/status_do.php");
	break;
	case 'bulk_action':
		include("modules/project/bulkactions.php");
	break;
	case 'view':
		include("modules/project/view_do.php");
	break;
	case 'report':
		include("modules/project/report.php");
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
                    include("modules/project/list.php");
                break;
                case 'add':
                    include("modules/project/add.php");
                break;
                case 'edit':
                    include("modules/project/edit.php");
                break;
				case 'view':
                    include("modules/project/view.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>