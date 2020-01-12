<?php 
require_once(__DIR__."/dao/TransactionDAO.php")
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>FlexPedia- Transaction management</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container">
        <a id="logo-container" href="#" class="brand-logo">
            <img  style="height:70px;"src="/public/images/logo.png">
        </a>
      <ul class="right hide-on-med-and-down">
        <li><a href="https://www.flexpedia.nl/">About us</a></li>
      </ul>

      <ul id="nav-mobile" class="sidenav">
      <li class="orange">
        <a href="/">
          FlexPedia</a>
        </li>
      
        <li><a href="/invoices.php">
          <i class="material-icons">cloud_download</i>Invoice Report</a>
        </li>
        <li><div class="divider"></div></li>
        <li><a href="/clients.php">
          <i class="material-icons">cloud_download  </i>Client Report</a>
        <li><div class="divider"></div></li>
        </li>        <li><a href="https://www.flexpedia.nl/">About Us</a></li>
      </ul>
      <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    </div>
  </nav>
  <main>
  <br>
  <div class="row hide-on-small-only"> 
 
    <span class="col offset-m6 ">
      <a class="orange btn waves-effect waves-light" href="/invoices.php">
      <i class="material-icons left">cloud_download</i> Invoice report</a>
    </span>
    <span class="col  ">
      <a class="orange btn waves-effect waves-light" href="/clients.php">
      <i class="material-icons left">cloud_download</i> Client report</a>
    </span>
    
  </div>
  <div class="row">
  <div class="col m8 offset-m2">
      <table>
      <tr><th>Invoice ID</th><th>Company Name</th><th>Invoice Ammount</th><th>Action</th></tr>
      
      <?php 


        if($_GET["limit"]==NULL){
          $pageLimit=5;  
        }else{
          $pageLimit=(int)$_GET["limit"];
        }
        $pageIndex=(int)$_GET["index"];
        $offset= ($pageIndex * $pageIndex) == 0 ? 0:($pageLimit * $pageIndex);
        
        $txn=new TransactionDAO();
        $transactionsList=$txn->getInvoces($pageLimit,$offset);
        foreach($transactionsList as $row){
          echo("<tr><td>".$row["id"]."</td>"."<td>".$row["client"]."</td>"."<td>".$row["invoice_amount"]."</td>");
          echo("<td>");
          if($row["invoice_status"]!="paid"){
            echo("<button class='orange btn btn-small' id='button".$row["id"]."' onclick=\"updateStatus(".$row["id"]
            .",'paid');\">Mark Paid</button></td>") ;
          }else{
            echo("<button class='orange  btn btn-small'  id='button".$row["id"]."' onclick=\"updateStatus(".$row["id"]
            .",'unpaid');\">Mark Unpaid</button></td>") ;
          }
          echo("<td><div style='display:none;' id='spinner".$row["id"]."' class='preloader-wrapper small active'>
          <div class='spinner-layer spinner-blue-only'>
            <div class='circle-clipper left'>
              <div class='circle'></div>
            </div><div class='gap-patch'>
              <div class='circle'></div>
            </div><div class='circle-clipper right'>
              <div class='circle'></div>
            </div>
          </div>
        </div></td></tr>");
        }
        

      ?>
      </table>


  <ul class="pagination">
    <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
    <?php 

      $pageCount=$txn->getInvoicesCount()/$pageLimit;

      for($i=0;$i<$pageCount;$i++){
        if($i==$pageIndex)
          echo("<li class='orange'><a href='/?limit=".$pageLimit."&index=".($i)."'>"
          .($i+1)."</a></li>");
        else
        echo("<li class='waves-effect'><a href='/?limit=".$pageLimit."&index=".($i)."'>".
        ($i+1)."</a></li>");
      }

      ?>

    <li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
  </ul>
            
      
      </div>
    </div>
  </main>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script >
        function updateStatus(id,invoiceStatus){
          
          $("#spinner"+id).show();
          $.get("/updateStatus.php?companyId="+id+"&status="+invoiceStatus,
          function(data,status){
            console.log(data);
            console.log(status);
            
            console.log("status updated"+invoiceStatus);
              if(invoiceStatus=="paid"){
                $("#button"+id).text("Mark Unpaid");
                
                $("#spinner"+id).hide();
                $("#button"+id).attr("onclick","updateStatus("+id+",'unpaid')");
              }
              else {
                $("#button"+id).text("Mark Paid");
                
                $("#spinner"+id).hide();
                $("#button"+id).attr("onclick","updateStatus("+id+",'paid')");
              }
          });
        }
     </script>
     <script>
     (function($){
        $(function(){
          $('.sidenav').sidenav();
        }); // end of document ready
      })(jQuery); // end of jQuery name space

     </script>

</body>
</html>