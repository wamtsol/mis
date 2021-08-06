<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["save_salary"])){
	$start_date = strtotime(date_dbconvert($_POST[ "start_date" ]));
	$year = date( "Y", $start_date );
	$month = date( "n", $start_date )-1;
	$sql="select * from admin where status=1 order by name";
    $rs=doquery($sql,$dblink);
	if( numrows( $rs ) > 0 ) {
		while( $r = dofetch( $rs ) ) {
			if( isset( $_POST[ "monthly_salary" ][ $r[ "id" ] ] ) ) {
				doquery( "update admin set monthly_salary='".slash($_POST[ "monthly_salary" ][ $r[ "id" ] ])."' where id='".$r[ "id" ]."'", $dblink );
			}
			if( isset( $_POST[ "salary" ][ $r[ "id" ] ] ) ) {
				$salary = doquery( "select id, amount from salary where employee_id='".$r[ "id" ]."' and month='".$month."' and year='".$year."'", $dblink );
				if( numrows( $salary ) > 0 ) {
					$salary = dofetch( $salary );
					doquery( "update salary set amount='".slash($_POST[ "salary" ][ $r[ "id" ] ])."' where id='".$salary[ "id" ]."'", $dblink );
					$salary_id = $salary[ "id" ];
				}
				else {
					doquery( "insert into salary(employee_id, month, year, amount) values( '".$r[ "id" ]."', '".$month."', '".$year."', '".slash($_POST[ "salary" ][ $r[ "id" ] ])."' )", $dblink );
					$salary_id = inserted_id();
				}
				if( isset( $_POST[ "payment" ][ $r[ "id" ] ] ) && !empty( $_POST[ "payment" ][ $r[ "id" ] ] ) ) {
					$salary_payment = doquery( "select id from salary_payment where salary_id='".$salary_id."'", $dblink );
					if( numrows( $salary_payment ) > 0 ) {
						$salary_payment = dofetch( $salary_payment );
						doquery( "update salary_payment set amount='".slash($_POST[ "payment" ][ $r[ "id" ] ])."', account_id='".slash($_POST[ "account_id" ][ $r[ "id" ] ])."', details='".slash($_POST[ "details" ][ $r[ "id" ] ])."' where id='".$salary_payment[ "id" ]."'", $dblink );
					}
					else {
						doquery( "insert into salary_payment(employee_id, salary_id, datetime_added, amount, account_id, details) values( '".$r[ "id" ]."', '".$salary_id."', NOW(), '".slash($_POST[ "payment" ][ $r[ "id" ] ])."', '".slash($_POST[ "account_id" ][ $r[ "id" ] ])."', '".slash($_POST[ "details" ][ $r[ "id" ] ])."' )", $dblink );
					}
				}
			}
		}
	}
}
$extra='';
$is_search=false;
if( isset($_GET["start_date"]) ){
	$_SESSION["employee"]["reports"]["salary"]["start_date"] = $_GET["start_date"];
}
if(isset($_SESSION["employee"]["reports"]["salary"]["start_date"]) && !empty($_SESSION["employee"]["reports"]["salary"]["start_date"])){
	$start_date = $_SESSION["employee"]["reports"]["salary"]["start_date"];
}
else{
	$start_date = date("d/m/Y", strtotime('first day of this month'));
	$is_search=true;
}

if( isset($_GET["end_date"]) ){
	$_SESSION["employee"]["reports"]["salary"]["end_date"] = $_GET["end_date"];
}
if(isset($_SESSION["employee"]["reports"]["salary"]["end_date"]) && !empty($_SESSION["employee"]["reports"]["salary"]["end_date"])){
	$end_date = $_SESSION["employee"]["reports"]["salary"]["end_date"];
}
else{
	$end_date = date("d/m/Y");
	$is_search=true;
}
$s = strtotime(date_dbconvert($start_date));
$year = date( "Y", $s );
$month = date( "n", $s )-1;
$accounts = array();
$rs = doquery( "select * from account order by title", $dblink );
if( numrows( $rs ) > 0 ) {
	while( $r = dofetch( $rs ) ) {
		$accounts[] = array(
			"id" => $r[ "id" ],
			"title" => unslash( $r[ "title" ] )
		);
	}
}
$employees = array();
$sql="select * from admin where status=1 and monthly_salary > 0 order by name";
$rs=doquery($sql,$dblink);
if(numrows($rs)>0){
	$sn=1;
	 while($r=dofetch($rs)){
		
		$working_days = 0;
		$present = 0;
		$absent = 0;
		
		for( $i = 0; $i < date( "j", strtotime(date_dbconvert($end_date)) ); $i++ ) {
			$day = strtotime(date_dbconvert($start_date))+24*3600*$i;
			if( !is_holiday( date("Y-m-d", $day) ) ) {
				$sql2="select * from employee_attendance where employee_id='".$r["id"]."' and (checked_in>='".date("Y/m/d H:i:s", $day)."' and checked_in<'".date("Y/m/d H:i:s", $day+24*3600)."' or checked_out>='".date("Y/m/d H:i:s", $day)."' and checked_out<'".date("Y/m/d H:i:s", $day+24*3600)."')";	
				$rs2 = doquery( $sql2, $dblink );
				if( numrows( $rs2 ) > 0 ) {
					$present++;
				}
				else {
					$absent++;
				}
				$working_days++;
			}
		}
		$salary = unslash( $r["monthly_salary"] );
		$total_days = $working_days-$absent;
		$payment_amount = 0;
		$account_id = get_account_of_type( 2 );
		$details = '';
		$final_salary = 0;
		$balance = 0;
		$fs = doquery( "select id, amount from salary where employee_id='".$r[ "id" ]."' and month='".$month."' and year='".$year."'", $dblink );
		if( numrows( $fs ) > 0 ) {
			$fs = dofetch( $fs );
			$final_salary = round($fs[ "amount" ]);
			//$payment_amount = $final_salary;
			$payment = doquery( "select * from salary_payment where employee_id='".$r[ "id" ]."' and salary_id='".$fs[ "id" ]."'", $dblink );
			if( numrows( $payment ) > 0 ) {
				$payment = dofetch( $payment );
				$payment_amount = $payment[ "amount" ];
				$account_id = $payment[ "account_id" ];
				$details = unslash( $payment[ "details" ] );
			}														
		}
		$r1 = dofetch( doquery( "SELECT sum(amount) as salary FROM salary where employee_id = '".$r[ "id" ]."' and ( year < '".$year."' or month < '".$month."')  and status = 1", $dblink ) );
		$r2 = dofetch( doquery( "SELECT sum(amount) as salary_payment FROM `salary_payment` where employee_id = '".$r[ "id" ]."' and datetime_added < '".date('Y-m-d 23:59:59', strtotime(date_dbconvert($end_date)))."'", $dblink ) );
		$balance = $r1[ "salary" ]-$r2[ "salary_payment" ];
		$employees[] = array(
			"employee" => $r,
			"working_days" => $working_days,
			"present" => $present,
			"absent" => $absent,
			"salary" => $salary,
			"total_days" => $total_days,
			"final_salary" => $final_salary,
			"balance" => $balance,
			"payment_amount" => $payment_amount,
			"account_id" => $account_id,
			"details" => $details,			
		);
	}
}
if( isset( $_GET[ "action" ] ) && $_GET[ "action" ] == 'print' ) {
	if( count( $employees ) > 0 ) {
		?>
		<!doctype html>
        <html>
        <head>
        <meta charset="utf-8">
        <title>Untitled Document</title>
        <style>@charset "utf-8";
/* CSS Document */
.wrapper{ width:1024px; margin:0 auto 30px; padding-bottom:30px; border-bottom:dashed 1px;}
.wrapper:nth-child(3n){ page-break-after:always;}
.clear:after { 
  content: "";
  clear: both;
  display: table;
}
.salary {
  font-size: 46px;
  font-family: arial;
}
.date span {
  font-size: 16px;
  font-family: arial;
  font-weight: bold;
}
.logo_area {
    float: left;
    width: 700px;
}

.address span {
  font-size: 24px;
  margin: 0px 0px 6px;
  padding: 0px;
  line-height: 20px;
  font-family: arial;
  font-weight: bold;
  display: block;
}
.address p {
  margin: 0px 0px 0px;
  font-size:16px;
  font-family:arial;
}
.address {
  margin-top: 20px;
}
.logo {
    width: 120px;
    float: left;
    position: relative;
    margin-left: -28px;
	margin-right:30px;
}
.logo_area img {
	width:100%;
  margin:0 auto;
  display:block;
}
.right_details{ float:right; font-family:arial;}
.employee_details{ margin:0 0 20px;}
.header_inn {
  padding: 24px 0px 30px;
}
.name span {
  display: block;
  margin-bottom: 4px;
  font-size: 16px;
  font-family: arial;
  font-weight: bold;
}
.name p {
  margin: 0px;
}
.address h1 {
    margin: 0;
    font-family: sans-serif;
    font-size: 22px;
}

h1 {}

.address h2 {
    margin-top: 0;
    font-weight: normal;
    font-family: sans-serif;
    margin: 5px 0;
    font-size: 16px;
}

.logo {height: 80px;width: auto;margin: 0 20px 0px 0px;}

.logo_area img {
    height: 100%;
    width: auto;
}

.address {
    margin: 0;
}

.address p {
    font-size: 12px;
}

.salary {
    text-transform: uppercase;
    font-size: 22px;
    font-weight: bold;
    text-align: right;
}

.name span {
    font-size: 12px;
    display: inline;
    margin-right: 10px;
}

.name p {
    display: inline;
}

.name {
    border-bottom: solid 1px;
    margin-bottom: 5px;
}
table {
    width: 100%;
    border-collapse: collapse;
}

td, th {
    border: solid 1px;
    padding: 5px;
    text-align: left;
}
td.no-border {
    border: 0;
}
td strong{ font-size:12px; font-family: arial; font-weight:bold;}
td{ width: 12.5%}
</style>
        </head>
        <body>
			<?php
			$fees_chalan_header = get_config( 'fees_chalan_header' );
            foreach( $employees as $employee ) {
				?>
				<div class="wrapper">
                    <div class="header">
                        <div class="header_inn clear">
                            <div class="logo_area">
                                <div class="logo"><img src="<?php echo $file_upload_url;?>config/<?php echo $admin_logo?>" alt="image" style="width:80px; height:auto;" /></div>
                                <div class="address">
                                    <?php echo $fees_chalan_header;?>
                                </div>
                            </div>
                            <div class="right_details">
                                    
                                <div class="date_area">
                                    <div class="salary">Pay Slip</div>
                                    <div class="date"><span>Date: </span><?PHP echo date( "d/m/Y" )?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <div class="employee_details">
                            <div class="name"><span>Employee Name:</span><p><?php echo unslash( $employee[ "employee" ][ "name" ] )?></p></div>
                        </div>
                        <table>
                        	<tr>
                            	<th colspan="10">Details</th>
                            </tr>
                            <tr>
                            	<td><strong>Month</strong></td>
                                <td><strong>Working Days</strong></td>
                                <td><strong>Present</strong></td>
                                <td><strong>Absent</strong></td>
                                <td><strong>Late In/Out</strong></td>
                                <td><strong>Total Days</strong></td>
                                <td><strong>Salary</strong></td>
                                <td><strong>Calculated</strong></td>
                            </tr>
                            <tr>
                            	<td><?php echo date( "M Y", strtotime( $start_date ) )?></td>
                                <td style="text-align: right"><?php echo $employee[ "working_days" ]?></td>
                                <td style="text-align: right"><?php echo $employee[ "present" ]?></td>
                                <td style="text-align: right"><?php echo $employee[ "absent" ]?></td>
                                <td style="text-align: right"><?php echo $employee[ "present" ]-$employee[ "total_days" ]?></td>
                                <td style="text-align: right"><?php echo $employee[ "total_days" ]?></td>
                                <td style="text-align: right"><?php echo curr_format( $employee[ "salary" ] )?></td>
                                <td style="text-align: right"><?php echo curr_format( round( ($employee[ "salary" ]/$employee[ "working_days" ]) * $employee[ "total_days" ] ) ); ?></td>
                            </tr>
                            <tr>
                            	<td colspan="6" class="no-border"></td>
                                <td><strong>Approved</strong></td>
                                <td style="text-align: right"><?php echo curr_format( $employee[ "final_salary" ] )?></td>
                            </tr>
                            <tr>
                            	<td colspan="6" class="no-border"></td>
                                <td><strong>Previous Balance</strong></td>
                                <td style="text-align: right"><?php echo curr_format( $employee[ "balance" ] )?></td>
                            </tr>
                            <tr>
                            	<td colspan="6" class="no-border"></td>
                                <td><strong>Payment</strong></td>
                                <td style="text-align: right"><?php echo curr_format( $employee[ "payment_amount" ] )?></td>
                            </tr>
                            <tr>
                            	<td colspan="6" class="no-border"></td>
                                <td><strong>Remaning Balance</strong></td>
                                <td style="text-align: right"><?php echo curr_format( $employee[ "final_salary" ]+$employee[ "balance" ]-$employee[ "payment_amount" ] )?></td>
                            </tr>
                        </table>
                	</div>
                </div>
				<?php
			}
			?>
		</body>
		</html>
        <?php
	}
	die;
}