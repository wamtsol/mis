<?php

if(!defined("APP_START")) die("No Direct Access");
if(isset($_SESSION["project_manage"]["add"])){
	extract($_SESSION["project_manage"]["add"]);	
}
else{
	$title="";
	$client_id="";
	$start_date=date("d/m/Y");
	$end_date=date("d/m/Y");
	$details="";
	$expected_revenue="";
}
?>
<div class="page-header">
	<h1 class="title">Add New Project</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage Project</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="project_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>
<form class="form-horizontal form-horizontal-left" role="form" action="project_manage.php?tab=add" method="post" enctype="multipart/form-data" name="frmAdd"  onSubmit="return checkFields();">
    <div class="form-group">
        <div class="row">
        	 <div class="col-sm-2 control-label">
            	<label class="form-label" for="title">Title <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Title" value="<?php echo $title; ?>" name="title" id="title" class="form-control" />
            </div>
        </div>
    </div>
    <div class="form-group">
    	<div class="row">
            <div class="col-sm-2 control-label">
                <label class="form-label" for="client_id">Client <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <select name="client_id" title="Choose Option">
                    <option value="0">Select Client</option>
                    <?php
                    $res=doquery("select * from client where status=1 order by client_name", $dblink);
                    if(numrows($res)>0){
                        while($rec=dofetch($res)){
                        ?>
                        <option value="<?php echo $rec["id"]?>"<?php echo($client_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["client_name"]); ?></option>
                     	<?php			
                        }			
                    }
                    ?>
                </select>
            </div>
        </div>
  	</div>
    <div class="form-group">
        <div class="row">
        	 <div class="col-sm-2 control-label">
            	<label class="form-label" for="start_date">Start Date </label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Start Date" value="<?php echo $start_date; ?>" name="start_date" id="start_date" class="form-control date-picker" />
            </div>
        </div>
    </div>
    <div class="form-group">

        <div class="row">

        	 <div class="col-sm-2 control-label">

            	<label class="form-label" for="end_date">End Date </label>

            </div>

            <div class="col-sm-10">

                <input type="text" title="Enter End Date" value="<?php echo $end_date; ?>" name="end_date" id="end_date" class="form-control date-picker" />

            </div>

        </div>

    </div>

    <div class="form-group">

        <div class="row">

        	<div class="col-sm-2 control-label">

            	<label class="form-label" for="details">Details </label>

            </div>

            <div class="col-sm-10">

                 <textarea title="Enter Details" value="" name="details" id="details" class="form-control" /><?php echo $details; ?></textarea>

            </div>

        </div>

    </div>

    <div class="form-group">

        <div class="row">

        	<div class="col-sm-2 control-label">

            	<label class="form-label" for="expected_revenue">Expected Revenue </label>

            </div>

            <div class="col-sm-10">

                <input type="text" title="Enter Expected Revenue" value="<?php echo $expected_revenue; ?>" name="expected_revenue" id="expected_revenue" class="form-control" />

            </div>

        </div>

    </div>

    

    

    <div class="form-group">

    	<div class="row">

            <div class="col-sm-2 control-label">

                <label for="company" class="form-label"></label>

            </div>

            <div class="col-sm-10">

                <input type="submit" value="SUBMIT" class="btn btn-default btn-l" name="project_add" title="Submit Record" />

            </div>

        </div>

  	</div>  

</form>