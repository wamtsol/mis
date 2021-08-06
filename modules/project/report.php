<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Project List</title>
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
	text-align:left
}
</style>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr class="head" align="center">
        <th colspan="6">
            <?php echo get_config( 'fees_chalan_header' )?>
            <h2>Project List</h2>
            <p>
                <?php
                if( !empty( $client_id ) ){
                    echo " Client: ".get_field($client_id, "client","client_name");
                }
                ?>
            </p>
        </th>
    </tr>
    <tr>
        <th width="10%" style="text-align:center">S#</th>
        <th width="20%">Title</th>
        <th width="20%">Client</th>
        <th width="15%">Start Date</th>
        <th width="15%">End Date</th>
        <th width="20%" align="right">Expected Revenue</th>
    </tr>
    <?php
	$expected_revenue=0;
    if(numrows($rs)>0){
        $sn=1;
        while($r=dofetch($rs)){
			$expected_revenue += $r["expected_revenue"];
            ?>
            <tr>
                <td style="text-align:center"><?php echo $sn++?></td>
                <td><?php echo unslash($r["title"]); ?></td>
                <td><?php echo get_field( unslash($r["client_id"]), "client", "client_name" ); ?></td>
                <td><?php echo date_convert($r["start_date"]); ?></td>
                <td><?php echo date_convert($r["end_date"]); ?></td>
                <td align="right"><?php echo curr_format(unslash($r["expected_revenue"])); ?></td>
            </tr>
            <?php
        }
    }
    ?>
    <tr>
        <th colspan="5" style="text-align:right;">Total</th>
        <th style="text-align:right;"><?php echo $expected_revenue;?></th>
    </tr>
</table>
</body>
</html>
<?php
die;
//}