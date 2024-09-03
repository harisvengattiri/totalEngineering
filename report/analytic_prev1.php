<?php
  include "../config.php";
  error_reporting(0);
  ?>
<?php
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
}
?>
<!--<title> <?php //echo $title;?></title>-->
<style type = "text/css">
   
      @media screen {
         /*p.bodyText {font-family:verdana, arial, sans-serif;}*/
      }

      @media print {
           @page { size: auto;  margin: 0mm; }
           
         /*p.bodyText {font-family:georgia, times, serif;}*/
      }
      @media screen, print {
    
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
<center> <h1 style="margin-bottom: -3%;"><?php echo strtoupper($status);?> SALES SUMMARY REPORT</h1></center>
 <h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>

<table width="100%">
        <thead>
          <tr>    
              <th id="hitem">
                  Sold Items
              </th>
              
              <th id="hquan">
                  Quantity
              </th>
              <th id="hprice">
                  Average Price
              </th>
              <th>
                  Item Total
              </th>
              <th>
                  Sum Total
              </th>
              <th>
                  VAT
              </th>
              <th>
                  Grand Total
              </th>
                    
          </tr>
        </thead>
        <tbody>
		<?php
                  
        $sql = "SELECT sum(transport) AS transport,sum(total) AS total,customer,sum(thisquantity) AS quantity,item FROM delivery_note"
                . " INNER JOIN"
                . " delivery_item ON delivery_note.id=delivery_item.delivery_id"
                . " WHERE STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')"
                . " GROUP BY delivery_note.customer,delivery_item.item";
        
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $grandtotal=0;
        $tquantity=0;
        $prev_cust='';
        $p=-1;
        while($row = mysqli_fetch_assoc($result)) {
        $cust=$row["customer"];
              $sqlcust="SELECT name from customers where id='$cust'";
              $querycust=mysqli_query($conn,$sqlcust);
              $fetchcust=mysqli_fetch_array($querycust);
              $customer=$fetchcust['name'];
        if($prev_cust != $cust){$prev_cust = $cust;$tr=1;$p=$p+1;}else{$tr=0;}
        $transport=$row["transport"];
        $total=$row["total"];
        ?>
             <?php if($tr == 1) { ?>
             <tr>
                  <td colspan="3" id="bcust"><b><?php echo $sl;?>: <?php echo $customer;?></b></td>
                  <td colspan="4"><b></b></td>
             </tr> 
             <?php $sl++; } ?>
             
             <?php
             $item[] = $row["item"];  
             ?>
           
           <?php if($p != 0) { ?>
           <tr> 
               <td id="bitem">
                    <?php
                         foreach ($item AS $item){
                              echo $item.'<br>';
                         }
                         unset($item);
                    ?>
               </td>
           </tr>
		<?php
                    $grandtotal=$grandtotal+$grand;
                }
		}
		}
		?>
             <tr>
                  <td colspan="1"><b></b></td>
                  <td colspan="1"><b><?php echo $tquantity;?></b></td>
                  <td colspan="3"><b></b></td>
                  <td colspan="1"><b>GRAND TOTAL</b></td>
                  <td colspan="1"><b><?php echo $grandtotal;?></b></td>
             </tr>
        </tbody>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>