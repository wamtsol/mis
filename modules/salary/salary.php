<?php
if(!defined("APP_START")) die("No Direct Access");
$is_search = true;
?>
<div class="page-header">
	<h1 class="title">Generate Salary</h1>
  	<ol class="breadcrumb">
    	<li class="active">Employee Salary</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
    	</div> 
    </div> 
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
    <li class="col-xs-12 col-lg-12 col-sm-12">
    	<div>
        	<form class="form-horizontal" action="" method="get">
                <input type="hidden" name="tab" value="salary"  />
                <div class="col-sm-3 margin-btm-5">                    <input type="text" title="Enter Date" value="<?php echo $start_date;?>" name="start_date" id="start_date" class="date-picker form-control" />
                </div>
                <div class="col-sm-3 margin-btm-5">
                    <input type="text" title="Enter Date" value="<?php echo $end_date;?>" name="end_date" id="end_date" class="date-picker form-control" />
                </div>
                <div class="col-sm-3 text-left margin-btm-5">
                    <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                    <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                </div>
            </form>
        </div>
  	</li>
</ul>
<div class="panel-body table-responsive">                   
    <form method="post">
        <input type="hidden" value="<?php echo $start_date;?>" name="start_date" />
        <input type="hidden" value="<?php echo $end_date;?>" name="end_date" />
        <table id="dynamic-table" class="table table-hover list">
            <thead>
                <tr>
                    <th width="2%">S.No</th>
                    <th width="20%">Name</th>
                    <th width="15%">Details</th>
                    <th width="10%">Salary</th>
                    <th width="10%">Calculated</th>
                    <th width="10%">Approved</th>
                    <th width="10%">Balance</th>
                    <th width="10%">Paid</th>
                    <th class="center">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if( count( $employees > 0 ) ) {
                    $sn=1;
                    
                    foreach( $employees as $employee ){
                        
                        ?>
                        <tr class="<?php if($employee[ "final_salary" ]>0 && $employee[ "balance" ]+$employee[ "payment_amount" ] == 0 ) echo " danger";if($employee[ "final_salary" ] > 0 && $employee[ "payment_amount" ]>0) echo " success";?>">
                            <td><?php echo $sn;?></td>
                            <td><?php echo unslash( $employee[ "employee" ][ "name" ] )?></td>
                            <td>
                                Working days: <?php echo $employee[ "working_days" ]; ?><br  />
                                Present: <?php echo $employee[ "present" ]; ?><br  />
                                Absent: <?php echo $employee[ "absent" ]; ?><br  />
                               
                            </td>
                            <td><input type="text" class="monthly_salary" name="monthly_salary[<?php echo $employee[ "employee" ][ "id" ]?>]" value="<?php echo $employee[ "salary" ]?>" id="monthly_salary_<?php echo $employee[ "employee" ][ "id" ]?>" style="width:80px;"  /></td>
                            <td><span data-workingdays="<?php echo $employee[ "working_days" ]?>" data-totaldays="<?php echo $employee[ "total_days" ]?>" class="expected_salary" id="expected_salary_<?php echo $employee[ "employee" ][ "id" ]?>"><?php echo curr_format( round( ($employee[ "salary" ]/$employee[ "working_days" ]) * $employee[ "total_days" ] ) ); ?></span></td>
                            <td><input type="text" name="salary[<?php echo $employee[ "employee" ][ "id" ]?>]" value="<?php echo $employee[ "final_salary" ]?>" style="width:80px;"  /></td>
                            <td><?php echo curr_format( $employee[ "balance" ] )?></td>
                            <td><input type="text" name="payment[<?php echo $employee[ "employee" ][ "id" ]?>]" value="<?php echo $employee[ "payment_amount" ]?>" style="width:80px;"  /></td>
                            
                            <td>
                                <div class="col-md-12">Account:</div>
                                <div class="col-md-12"><select name="account_id[<?php echo $employee[ "employee" ][ "id" ]?>]">
                                    <option value="">Select Account</option>
                                    <?php
                                    foreach( $accounts as $account ) {
                                        ?>
                                        <option value="<?php echo $account[ "id" ]?>"<?php echo $account[ "id" ]==$employee[ "account_id" ]?' selected':''?>><?php echo $account[ "title" ]?></option>
                                        <?php
                                    }
                                    ?>
                                </select></div>
                                <div class="col-md-12">Details:</div>
                                <div class="col-md-12"><textarea style="width:100%;" name="details[<?php echo $employee[ "employee" ][ "id" ]?>]" ><?php echo $employee[ "details" ]?></textarea></div>
                            </td>
                        </tr> 
                     <?php 
                        $sn++;
                     }
                }
                else{	
                    ?>
                    <tr>
                        <td colspan="8"  class="no-record">No Result Found</td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
        <div class="salary-btn">
        	<input type="submit" name="save_salary" value="Save Values" class="btn btn-default btn-l" /> <a href="salary_manage.php?tab=salary&action=print" target="_blank">Print Salary Sheets</a>
        </div>
    </form>
</div>
<script>
$(document).ready(function(){
	$( ".monthly_salary" ).change(function(){
		$salary = parseFloat( $(this).val() );
		$expected_salary = $(this).siblings('.expected_salary');
		$workingdays = parseFloat($expected_salary.data( "workingdays" ));
		$totaldays = parseFloat($expected_salary.data( "totaldays" ));
		$expected_salary.html( Math.round( ($salary/$workingdays)*$totaldays ) );
	});
});
</script>