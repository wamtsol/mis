<?php
if(!defined("APP_START")) die("No Direct Access");
$sql = "select * from expense where id = '".slash($_GET["id"])."'";
$rs = doquery( $sql, $dblink );
$r=dofetch($rs);
?>
<style>
.clearfix:after{
    clear:both;
    content:"";
    display:block;
}
h1, h2, h3, p {
    margin: 0 0 10px;
}
.logo {
    float: left;
}
.voucher{
	max-width:960px;
	margin:0 auto;
}
body {
    margin:  0;
    font-family:  Arial;
    font-size:  14px;
}
.head th, .head td{ border:0;}
th, td {
    border: solid 1px #000;
    padding: 10px 10px;
    font-size: 14px;
	vertical-align:top;
}
table {
    border-collapse:  collapse;
	max-width:1200px;
	margin:0 auto;
}
.clear:after { 
  content: "";
  clear: both;
  display: table;
}
.voucher_head {
    text-align: right;
    margin: 15px 0;
}
.voucher_head h1{
	font-size: 20px;
	text-transform: uppercase;
	margin: 10px 0;
}
.voucher_head h2{
	font-size: 20px;
    text-transform: uppercase;
    margin-bottom: 0px;
    padding-top: 19px;
	color: #666;
}
.voucher_detail p{
	font-size:14px;
	border-bottom: 1px solid;
	padding-bottom: 5px;
}
.detail_left{
	float:left;
}
.detail_right{
	float:right;
}
.voucher_detail {
    margin: 10px 0;
}
.signature{
	margin-top:80px;
}
.signature p{
	font-size:20px;
}
.signature ul{
	margin:0;
	padding:0;
}
.signature li{
	width: 33.3%;
    float: left;
    list-style: none;
}
.signature td{ border: none;
padding: 0;
height: 100px;
vertical-align: bottom;}
.logo img{ width:200px;}
</style>
<div class="voucher">
<div class="voucher_head clearfix">
    <div class="logo"><?php $admin_logo=get_config("admin_logo"); if(empty($admin_logo)) echo $site_title; else { ?><img src="<?php echo $file_upload_root;?>config/<?php echo $admin_logo?>" /><?php }?></div>
    <h2>Expense Voucher</h2>
</div>
<div class="voucher_detail clear">
	<div class="detail_left">
    	<p>VOUCHER NO: <?php echo $r["id"] ?></p>
        <p>DEBIT ACCOUNT: <?php echo get_field( unslash($r["expense_category_id"]), "expense_category", "title" ); ?></p>
        <p>PROJECT: <?php echo get_field( unslash($r["project_id"]), "project", "title" ); ?></p>
    </div>
    <div class="detail_right">
    	<p>DATE: <?php echo datetime_convert($r["datetime_added"]); ?></p>
    </div>
</div>
<table width="100%" cellspacing="0" cellpadding="0">
<thead>
<tr>
    <th width="5%" align="center">S.no</th>
    <th width="20%" align="left">Paid By</th>
    <th width="15%" align="right">Amount</th>
    <th width="60%" align="left">Details</th>
</tr>
</thead>
<tbody>
<?php
if( numrows( $rs ) > 0 ) {
	$sn = 1;
	?>
	<tr>
		<td align="center"><?php echo $sn++?></td>
		<td><?php echo get_field( unslash($r["account_id"]), "account", "title" ); ?></td>
		<td align="right"><?php echo curr_format(unslash($r["amount"])); ?></td>
		<td><?php echo unslash($r["details"]); ?></td>
	</tr>
	<?php
}
?>
</tbody>
</table>
<div class="signature">
	<ul>
    	<li>Prepared By:</li>
        <li style="text-align:center;">Authorised By:</li>
        <li style="text-align:right">Received By:</li>
    </ul>
</div>
</div>
<?php
die;