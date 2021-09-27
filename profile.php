<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'profile.php';
//include("include/admin_type_access.php");
$tab_array=array("edit");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="edit";
}

switch($tab){
	case 'edit':
		include("modules/profile/edit_do.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'edit':
				include("modules/profile/edit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>