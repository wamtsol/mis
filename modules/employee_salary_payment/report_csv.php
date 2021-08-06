<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
if(numrows($rs)>0){
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=employee_salary_payment.csv");
    $fh = fopen( 'php://output', 'w' );
    if( !empty( $employee_id ) ){
        $employee = get_field($employee_id, "emmployee", "name");
        fputcsv($fh,array('Employee:', $employee));
    }
    fputcsv($fh,array('S.no','Employee','Project','Datetime','Amount','Cheque Number','Paid By'));
    $sn=1;
    $total = 0;
    while($r=dofetch($rs)){
        $total += $r["amount"];
        fputcsv($fh,array(
            $sn++,
            get_field($r["employee_id"], "employee","name"),
            get_field($r["project_id"], "project","title"),
            datetime_convert($r["datetime_added"]),
            curr_format($r["amount"]),
            unslash($r["cheque_number"]),
            get_field($r["account_id"], "account","title")
        ));
    }
    fputcsv($fh,array('','','Total:',curr_format($total)));
    fclose($fh);
}
die;
?>
