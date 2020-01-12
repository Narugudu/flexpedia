<?php
require_once(__DIR__."/dao/TransactionDAO.php");
$txn=new TransactionDAO();
$updateStatus=$txn->updateStatus((int)$_GET["companyId"],$_GET["status"]);
echo("updated");

?>