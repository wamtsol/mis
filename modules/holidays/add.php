<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["holidays_manage"]["add"])){
	extract($_SESSION["holidays_manage"]["add"]);	
}
else{
	$date=date("d/m/Y");
	$is_working_day="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Holiday</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Holidays</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="holidays_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form action="holidays_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();" class="form-horizontal form-horizontal-left">
	<?php
    	$i=0;
  	?>
  	<div class="form-group">
        <div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="is_working_day">Is Working Day </label>
            </div>
            <div class="col-sm-10">
                <select name="is_working_day" title="Choose Option">
                    <option value="">Select Working Day</option>
                    <?php
                    foreach ($working_days as $key=>$value) {
                        ?>
                        <option value="<?php echo $key?>"<?php echo $key==$is_working_day?' selected="selected"':""?>><?php echo $value ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="date">Date</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Date" value="<?php echo $date; ?>" name="date" id="date" class="form-control date-picker" >
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="holidays_add" title="Submit Record" />
            </div>
        </div>
  	</div>
</form>