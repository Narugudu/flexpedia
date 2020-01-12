<?php
require_once(__DIR__."/dao/TransactionDAO.php");
$txn=new TransactionDAO();
$csvData=$txn->getInvoces($txn->getInvoicesCount(),0);
$filename="invoices.csv";
$f=fopen("php://memory","w");
$fields = array('ID', 'client', 'invoice_amount');
fputcsv($f, $fields, ",");

//output each row of the data, format line as csv and write to file pointer
foreach($csvData as $row){
    $lineData = array($row['id'], $row['client'], $row['invoice_amount']);
    fputcsv($f, $lineData, ",");
}

//move back to beginning of file
fseek($f, 0);

//set headers to download file rather than displayed
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '";');

//output all remaining data on a file pointer
fpassthru($f);

?>