<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "print");
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
	$_SESSION["client_payment"]["list"]["client_id"]=$client_id;
}
if(isset($_SESSION["client_payment"]["list"]["client_id"]))
	$client_id=$_SESSION["client_payment"]["list"]["client_id"];
else
	$client_id="";
if($client_id!=""){
	$extra.=" and client_id='".$client_id."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["client_payment"]["list"]["q"]=$q;
}
if(isset($_SESSION["client_payment"]["list"]["q"]))
	$q=$_SESSION["client_payment"]["list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and b.client_name like '%".$q."%'";
	$is_search=true;
}
$sql="select a.*, b.client_name from client_payment a inner join client b on a.client_id = b.id where 1 ".$extra." order by datetime desc";
switch($tab){
	case 'add':
		include("modules/client_payment/add_do.php");
	break;
	case 'edit':
		include("modules/client_payment/edit_do.php");
	break;
	case 'delete':
		include("modules/client_payment/delete_do.php");
	break;
	case 'status':
		include("modules/client_payment/status_do.php");
	break;
	case 'bulk_action':
		include("modules/client_payment/bulkactions.php");
	break;
	case 'print':
		include("modules/client_payment/print_do.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/client_payment/list.php");
			break;
			case 'add':
				include("modules/client_payment/add.php");
			break;
			case 'edit':
				include("modules/client_payment/edit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>