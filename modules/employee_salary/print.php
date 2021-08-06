<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["action"]) && $_GET["action"]=='curr_format'){
    echo '<strong>'.curr_format($_GET["amount"]).'</strong> (Rupees '.convert_number_to_words($_GET["amount"]).' )';
    die;
}
require_once('employee_salary_do.php');
if( count( $employees > 0 ) > 0 ) {
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title>Employee </title>
</head>
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
    font-size: 14px;
    font-family: Cambria, 'Arial', 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif;
    line-height: 20px;
    margin: 15px 0;
}
table{
    width: 100%;
}
table th, table td{
    padding:5px;
    font-family: Cambria, 'Arial', 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif;
    font-size:14px;
    text-align: center;
}
    @media print {
        .close, .calculate-total{
            display: none;
        }
    }
</style>
<?php
foreach( $employees as $employee ){
    if(!empty($employee["employee"]["bank_account_number"]) && $employee[ "final_salary" ]>0 && $employee[ "payment_amount" ] > 0 ){
        $employee["payment_amount_formatted"] = curr_format($employee["payment_amount"]);
        $employee["employee"]["name"] = unslash($employee["employee"]["name"]);
        $records[] = $employee;
    }
}
?>
<script src="js/jquery.js"></script>
<script>
    var records = JSON.parse('<?php echo json_encode($records)?>');
    let total = 0;
    $(document).ready(function(){
        drawTable();
        $(".calculate-total").click(function(e){
            e.preventDefault();
            $.get('employee_salary_manage.php?tab=print&action=curr_format&amount='+total, function(response){
                $(".total").html(response);
            });
        });
    });
    function drawTable(){
        $sn = 0;
        total = 0;
        $("tbody").html('');
        for(record of records){
            $sn++;
            total += Number(record.payment_amount);
            $("tbody").append('<tr>\n' +
                '<td>'+$sn+'<a href="#" class="close" data-index="'+($sn-1)+'">&times;</a></td>\n' +
                '<td>'+record.employee.bank_account_number+'</td>\n' +
                '<td style="text-align:left">'+record.employee.name+'</td>\n' +
                '<td style="text-align:right">'+record.payment_amount_formatted+'</td>\n' +
                '</tr>'
            );
        }
        $(".close").click(function(e){
            e.preventDefault();
            records.splice($(this).data('index'), 1);
            drawTable();
        });
    }
</script>
<body>
<div class="wrp">
    
        <div style="page-break-after: always;">
            <p> <strong>To,</strong> <br />The Operation Manager<br />Bank Islam, Latifabad Unit 7 Hyderabad.</p>
            <p><strong>Subject:</strong> Request for Credit Salaries of WAMTSOL (PVT) LIMITED Staff and Debit WAMTSOL (PVT) LIMITED <strong>Account # 0107339262810001</strong> through cheque # <input type="text" value="" style="
    border: 0;
    font-size: inherit;
"/></p>
            <p><strong>Dear Sir,</strong> It is request you to kindly credit the below mentioned employees salary of WAMTSOL (PVT) LIMITED in their respective account and debit from WAMTSOL (PVT) LIMITED <strong>Account # 0107339262810001.</strong></p>
            <table border="1" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>S.#</th>
                        <th>Account.#</th>
                        <th>Title of Account</th>
                        <th style="text-align:right">Amount</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <p style="text-align:center">Total:- <span class="total"></span><a href="#" class="calculate-total">Calculate</a></p>
            <p><strong>Regards </strong><br><br><br><br></p>
            <p><strong><input type="text" value="WAQAR UL AZIZ" style="
    border: 0;
    font-size: inherit;
"/></strong><br />Director<br />Wamtsol (Pvt) Limited</p>
        </div>
        
</div>
</body>
</html>
<?php
die();
}
