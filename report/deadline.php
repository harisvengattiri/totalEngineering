<?php
  ini_set('max_execution_time', 300);
  include "../config.php";
  error_reporting(0);
  ?>
<?php
session_start();
if(!isset($_SESSION['userid']))
{
   header("Location:$baseurl/login/");
}
?>
<!--<title> <?php //echo $title;?></title>-->
<style type = "text/css">
   
      @media screen {
           @page { size: auto;  margin: 0mm; }
         /*p.bodyText {font-family:verdana, arial, sans-serif;}*/
      }

      @media print {
           @page { size: auto;  margin: 0mm; }
           
           #hlpo,#blpo{width:100px;word-break: break-all;}
           #hsite,#bsite{width:200px;word-break: break-all;}
           #hitem,#bitem{width:200px;word-break: break-all;}
           
         /*p.bodyText {font-family:georgia, times, serif;}*/
      }
      @media screen, print {
           
         /*p.bodyText {font-size:10pt}*/
      }
</style>

<style>
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 8px;
}
th {
    background-color: #4CAF50;
    color: white;
}
h1, h2 {
    font-family: Arial, Helvetica, sans-serif;
}
th,td {
    font-family: verdana;
}
tr:nth-child(even){background-color: #f2f2f2}
</style>

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<!--<center><h1>Mancon Block Factory.</h1>-->
<center> <h1 style="margin-bottom: 1%;"><?php // echo strtoupper($status);?> CUSTOMER LIMIT REPORT</h1></center>


<table width="100%">
        <thead>
          <tr>
              <th>
                  Sl No
              </th>
               <th>
                  Customer
              </th>
              <th>
                  Salesman
              </th>
              <th>
                  Period
              </th>
              <th>
                  Cash Credit
              </th>
              <th>
                  Used Credit
              </th>
              <th>
                  Credit Available
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
        
        $sql = "SELECT * FROM `credit_application` INNER JOIN `customers` ON `credit_application`.company=`customers`.id";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	    {
        $sl=1;
        while($row = mysqli_fetch_assoc($result)) {
            
        $cdt = $row['credit'];
        $cdt = ($cdt != NULL) ? $cdt : 0;
        $cdt1 = $row['credit1'];
        $cdt1 = ($cdt1 != NULL) ? $cdt1 : 0;
        $credit = $cdt+$cdt1;
        
            $opening = $row['op_bal'];
            $opening = ($opening != NULL) ? $opening : 0;
            
            $company = $row['company'];
        
            $sqlinvo="SELECT ROUND(SUM(total), 2) AS grand FROM delivery_note WHERE customer = $company";
            $queryinvo=mysqli_query($conn,$sqlinvo);
            $fetchinvo=mysqli_fetch_array($queryinvo);
            $invoamt=$fetchinvo['grand'];
            $invoamt = ($invoamt != NULL) ? $invoamt : 0;
                $sqltp="SELECT ROUND(SUM(transport), 2) AS trp FROM delivery_note WHERE customer = $company";
                $querytp=mysqli_query($conn,$sqltp);
                $fetchtp=mysqli_fetch_array($querytp);
                $trpamt=$fetchtp['trp'];
                $trpamt = ($trpamt != NULL) ? $trpamt : 0;
            $invoamt = $invoamt - $trpamt;
                  
            $sqlrpt="SELECT ROUND(SUM(grand), 2) AS amount from reciept where customer=$company AND status='Cleared'";
            $queryrpt=mysqli_query($conn,$sqlrpt);
            $fetchrpt=mysqli_fetch_array($queryrpt);
            $amountrpt1=$fetchrpt['amount'];
            $amountrpt1 = ($amountrpt1 != NULL) ? $amountrpt1 : 0;
                  
            $sqlcdt="SELECT ROUND(SUM(total), 2) AS credited from credit_note where customer=$company";
            $querycdt=mysqli_query($conn,$sqlcdt);
            $fetchcdt=mysqli_fetch_array($querycdt);
            $amountrpt2=$fetchcdt['credited'];
            $amountrpt2 = ($amountrpt2 != NULL) ? $amountrpt2 : 0;

            $amountrpt = $amountrpt1 + $amountrpt2;
                  
            $invoamt=$invoamt*1.05;
            $invoamt=$invoamt+$opening+$trpamt;

        $current_bal=$invoamt-$amountrpt;
        
        $due = $credit-$current_bal;
        
        
            
        $rep = $row['rep'];
            $sqlrep = "SELECT name FROM customers WHERE id='$rep'";
            $resultrep = mysqli_query($conn, $sqlrep);
            $rowrep = mysqli_fetch_assoc($resultrep);
            $staff = $rowrep['name'];
        
        
        if($due > 0 && $due < 2000) {    
        ?>
          <tr>
               <td><?php echo $sl;?></td> 
               <td><?php echo $row['name'];?></td>
               <td><?php echo $staff;?></td>
               <td><?php echo $row['period'];?></td>
               <td style="text-align:right;"><?php echo custom_money_format('%!i', $credit);?></td>
               <td style="text-align:right;"><?php echo custom_money_format('%!i', $current_bal);?></td>
               <td style="text-align:right;"><?php echo custom_money_format('%!i', $due);?></td>


          </tr>
		<?php
		    $sl=$sl+1;
        }
		}
		}
		?>
        </tbody>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>