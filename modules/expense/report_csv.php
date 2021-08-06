<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
if(numrows($rs)>0){
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=expense.csv");
    $fh = fopen( 'php://output', 'w' );
    if( !empty( $expense_category_id ) ){
        $category = get_field($expense_category_id, "expense_category", "title");
        fputcsv($fh,array('Category:', $category));
    }
    fputcsv($fh,array('S.no','Date/Time','Expense Category','Paid By','Amount','Added By'));
    $sn=1;
    $total = 0;
    while($r=dofetch($rs)){
        $total += $r["amount"];
        fputcsv($fh,array(
            $sn++,
            datetime_convert($r["datetime_added"]),
            get_field( unslash($r["expense_category_id"]), "expense_category", "title" ),
            get_field( unslash($r["account_id"]), "account", "title" ),
            curr_format($r["amount"]),
            get_field( unslash($r["added_by"]), "admin", "username" )
        ));
    }
    fputcsv($fh,array('','','Total:',curr_format($total)));
    fclose($fh);
}
die;
?>
