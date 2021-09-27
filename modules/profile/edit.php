<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Edit User</h1>
  	<ol class="breadcrumb">
    	<li class="active">Manage User</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="index.php" class="btn btn-light editproject">Back to Home</a> </div>
  	</div>
</div>
<form action="profile.php?tab=edit" method="post" enctype="multipart/form-data" name="frmAdd"  class="form-horizontal form-horizontal-left">
	<input type="hidden" name="id" value="<?php echo $_SESSION["logged_in_admin"]["id"];?>">
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="name">Name <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Name" value="<?php echo $name; ?>" name="name" id="name" class="form-control" >
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="username">Username <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="text" value="<?php echo $username; ?>" name="username" id="username" class="form-control" title="Enter User Name">
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="email">Email <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="email" title="Enter Email" value="<?php echo $email; ?>" name="email" id="email" class="form-control">
            </div>
        </div>
  	</div>
    <div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="monthly_salary">Monthly Salary</label>
            </div>
            <div class="col-sm-10">
                <input type="text" title="Enter Salary" value="<?php echo $monthly_salary; ?>" name="monthly_salary" id="monthly_salary" class="form-control">
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label class="form-label" for="password">Password <span class="manadatory">*</span></label>
            </div>
            <div class="col-sm-10">
                <input type="password" title="Enter Password" value="" placeholder="Password" name="password" id="password" class="form-control">
                <span id="helpBlock" class="help-block">Leave empty for no change.</span> 
            </div>
        </div>
  	</div>
  	<div class="form-group">
    	<div class="row">
        	<div class="col-sm-2 control-label">
            	<label for="company" class="form-label"></label>
            </div>
            <div class="col-sm-10">
                <input type="submit" value="UPDATE" class="btn btn-default btn-l" name="profile_edit" title="Update Record" />
            </div>
        </div>
  	</div>
</form>