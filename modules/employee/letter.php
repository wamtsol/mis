<?php
if(!defined("APP_START")) die("No Direct Access");
$sql = "select * from employee where id = '".slash($_GET["id"])."'";
$rs = doquery( $sql, $dblink );
$r=dofetch($rs);
?>
<style>
.wrp{
    width:960px; 
    margin:2in auto;
}
.clearfix:after{
    clear:both;
    content:"";
    display:block;
}
h1, h2, h3, p {
    margin: 0 0 10px;
}
body {
    margin:  0;
    font-family:  Arial;
    font-size:  24px;
}
p{
    font-size: 24px;
    font-family: Cambria, 'Arial', 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif;
    line-height: 32px;
    margin: 30px 0 30px;
}
p strong{
    font-size:24px; 
    font-family:Cambria, 'Arial', 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif;
}
</style>
<div class="wrp">
    <?php
    $employee=str_replace(array(
        "%employee_name%",
        "%father_name%",
        "%cnic%",
        "%salary%"
    ), array(
        unslash($r["name"]),
        unslash($r["father_name"]),
        unslash($r["cnic_number"]),
        curr_format($r["salary"])
    ), get_config('salary_letter'));
    echo $employee;
    ?>
    <!-- <p>To,<br>The Manager<br>Bank Islami<br>Latifabad Unit 7 Hyderabad.</p>
    <p >Subject: <strong>Account opening Application.</strong></p>
    <p>Dear Sir,</p>
    <p>We would like to inform you that <strong>Mr <?php echo unslash($r["name"]);?> S/O <?php echo unslash($r["father_name"]);?> Holding CNIC #<br> <?php echo unslash($r["cnic_number"]);?></strong> is working with us. You are kindly requested to open his<br> Account also in your bank and conform to us.</p>
    <p>His Mailing address also verified by us</p>
    <p>Thanking You,</p>
    <p>Yours Faithfully</p>
    <p><strong>Waqarul Aziz Shaikh</strong><br>Director<br>Wamtsol (Pvt) Limited</p> -->
</div>
<?php
die;