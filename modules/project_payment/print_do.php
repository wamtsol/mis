<?php
if(!defined("APP_START")) die("No Direct Access");
$sql = str_replace("order by datetime_added desc", "order by datetime_added asc", $sql);
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
	<th colspan="6">
    	<?php echo get_config( 'fees_chalan_header' )?>
    	<h2>Project Payment List</h2>
        <p>
        	<?php
			echo "List of";
			if( !empty( $project_id ) ){
				echo " Project: ".get_field($project_id, "project","title");
			}
			?>
        </p>
    </th>
</tr>
<tr>
    <th width="5%" align="center">S.no</th>
    <th width="5%" align="center">ID</th>
    <th>Details</th>
    <th>Date</th>
    <th align="right">Amount</th>
    <th align="right">Sales Tax</th>
    <th align="right">Total</th>
</tr>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
    $total = $amount = $tax = 0;
	while( $r = dofetch( $rs ) ) {
        $amount += $r["amount"]-$r["sales_tax"];
        $tax += $r["sales_tax"];
        $total += $r["amount"];
		?>
		<tr>
        	<td align="center"><?php echo $sn++?></td>
           	<td align="center"><?php echo $r["id"]?></td>
            <td><?php echo unslash( $r[ "title" ] );?></td>
            <td><?php echo date_convert($r["datetime_added"]); ?></td>
            <td align="right"><?php echo curr_format(unslash($r["amount"]-$r["sales_tax"])); ?></td>
            <td align="right"><?php echo curr_format(unslash($r["sales_tax"])); ?></td>
            <td align="right"><?php echo curr_format(unslash($r["amount"])); ?></td>
        </tr>
		<?php
	}
	?>
    <tr>
        <td colspan="4">Total</td>
        <th align="right"><?php echo curr_format($amount)?></th>
        <th align="right"><?php echo curr_format($tax)?></th>
        <th align="right"><?php echo curr_format($total)?></th>
    </tr>
    <?php
}
?>
</table>
<?php
die;
