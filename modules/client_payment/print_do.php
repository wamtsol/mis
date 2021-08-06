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
    	<?php echo get_config( 'fees_chalan_header' )?>
    	<h2>client Payment List</h2>
        <p>
        	<?php
			echo "List of";
			if( !empty( $client_id ) ){
				echo " client: ".get_field($client_id, "client","client_name");
			}
			?>
        </p>
    </th>
</tr>
<tr>
    <th width="5%" align="center">S.no</th>
    <th width="5%" align="center">ID</th>
    <th>client Name</th>
    <th>Datetime</th>
    <th align="right">Amount</th>
    <th>Paid By</th>
</tr>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	while( $r = dofetch( $rs ) ) {
		?>
		<tr>
        	<td align="center"><?php echo $sn++?></td>
           	<td align="center"><?php echo $r["id"]?></td>
            <td><?php echo unslash( $r[ "client_name" ] );?></td>
            <td><?php echo datetime_convert($r["datetime"]); ?></td>
            <td align="right"><?php echo curr_format(unslash($r["amount"])); ?></td>
            <td><?php echo get_field( unslash($r["account_id"]), "account", "title" ); ?></td>
        </tr>
		<?php
	}
}
?>
</table>
<?php
die;