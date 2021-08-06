<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
if(numrows($rs)>0){
    //$filename= "employee_salary.csv";
    //header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    //header('Content-Description: File Transfer');
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=employee_salary.csv");
    //header("Expires: 0");
    //header("Pragma: public");
    $fh = fopen( 'php://output', 'w' );
    if( !empty( $from ) ){
        fputcsv($fh,array('From:', $from, '', 'To:', $to));
    }
    fputcsv($fh,array('S.no','Employee','Project','Month','Amount'));
    $sn=1;
    $total = 0;
    while($r=dofetch($rs)){
        $total += $r["amount"];
        fputcsv($fh,array(
            $sn++,
            get_field($r["employee_id"], "employee","name"),
            get_field($r["project_id"], "project","title"),
            $month_array[$r["month"]]." ".unslash($r["year"]),
            curr_format(unslash($r["amount"]))
        ));
    }
    fputcsv($fh,array('','','','Total:',curr_format($total)));
    fclose($fh);
    //header("Location: ".$filename);
    //die;
}
die;
?>
