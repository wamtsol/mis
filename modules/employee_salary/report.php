<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
?>
<style>
h1, h2, h3, p {
    margin: 0 0 10px;
}

body {
    margin:  0;
    font-family:  Arial;
    font-size:  11px;
}
.head th, .head td{ border:0;}
th, td {
    border: solid 1px #000;
    padding: 5px 5px;
    font-size: 11px;
	vertical-align:top;
}
table table th, table table td{
	padding:3px;
}
table {
    border-collapse:  collapse;
	max-width:1200px;
	margin:0 auto;
}
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<tr class="head">
	<th colspan="9">
    	<h2>Salary</h2>
        <p>
        	<?php
			if( !empty( $from ) ){
				echo " From: ".$from;
			}
            if( !empty( $to ) ){
                echo " To: ".$to;
            }
			?>
        </p>
    </th>
</tr>
<tr>
    <th width="5%" align="center">S.no</th>
    <th width="20%">Employee</th>
    <th width="15%">Project</th>
    <th width="10%">Month</th>
    <th width="10%">Amount</th>
</tr>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	$total = 0;
	while( $r = dofetch( $rs ) ) {
	    $total += $r["amount"];
		?>
		<tr>
        	<td align="center"><?php echo $sn++?></td>
           	<td><?php echo get_field($r["employee_id"], "employee","name"); ?></td>
            <td><?php echo get_field($r["project_id"], "project","title");?></td>
            <td><?php echo $month_array[$r["month"]]." ",unslash($r["year"]); ?></td>
            <td><?php echo curr_format(unslash($r["amount"])); ?></td>
        </tr>
		<?php
	}
	?>
    <tr>
        <td colspan="4">Total</td>
        <th><?php echo curr_format($total)?></th>
    </tr>
    <?php
}
?>
</table>
<?php
die;
