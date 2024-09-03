<?php include "../config.php";?>
<?php include "../functions/functions.php";?>
<?php 
$id=$_GET["id"]; 
?>
<title>Credit Application #<?php echo $id;?></title>
<style>


table
{
    page-break-inside:auto;
    border-collapse: collapse;
    width: 100%;
    border: 1px solid black;
    padding: 2px;
    font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
}

tr, th, td {
    page-break-inside:avoid; page-break-after:auto ;
    height: 20px;
    border: 1px solid black;
    padding: 5px;
    font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
}

tr, th, td {
    page-break-inside:avoid; page-break-after:auto ;
    height: 20px;
    border: 1px solid black;
    padding: 5px;
    font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
}

p,li {
     word-spacing: 2px;
     line-height: 140%;
     font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
     font-size: 12px;
}
body, h1 {
     font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;
     font-size: 12px;
}
    .wrapper{position:relative; font-family: "Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif;}
    .right,.left{width:50%; position:absolute;}
    .right{right:0;}
    .left{left:0;}

</style>
<body>
<table style="width: 100%; border:0px">
<tr style="border:0px" >
<td style="width: 25%; border: 0px"></td>
<td align="center" style="width: 50%; border: 0px"><h2>CREDIT APPLICATION FORM</h2><h3>APPLICANTS DETAILS</h3></td>
<td style="width: 25%; border: 0px"></td>
</tr>
</table>
<br/>
<?php 
        
        
             $sql="SELECT * FROM credit_application where id='$id'";
             
             $query=mysqli_query($conn,$sql);
             while($fetch=mysqli_fetch_array($query))
             {
                  
                    $customer=$fetch['company'];
                         $sql2="SELECT * FROM customers where id='$customer'";
                         $query2=mysqli_query($conn,$sql2);
                         while($fetch2=mysqli_fetch_array($query2))
                               {
                                 $cust=$fetch2['name'];
                                 $address=$fetch2['address'];
                                 $phone=$fetch2['phone'];
                                 $fax=$fetch2['fax'];
                                 $email=$fetch2['email'];
                                 $tin=$fetch2['tin'];
                               }
                    $licence_no=$fetch['licence_no'];
                    $exp_date=$fetch['exp_date'];
                    $type_licence=$fetch['type_licence'];
                    $nature_bus=$fetch['nature_bus'];
                    $est_year=$fetch['est_year'];
                               
                    $d_name1=$fetch['d_name1'];
                    $d_name2=$fetch['d_name2'];
                    $d_name3=$fetch['d_name3'];
                    $d_natn1=$fetch['d_natn1'];
                    $d_natn2=$fetch['d_natn2'];
                    $d_natn3=$fetch['d_natn3'];
                    $d_pass1=$fetch['d_pass1'];
                    $d_pass2=$fetch['d_pass2'];
                    $d_pass3=$fetch['d_pass3'];
                    
                    $bank=$fetch['bank'];
                    $branch=$fetch['branch'];
                    $account=$fetch['account'];
                    $account_name=$fetch['account_name'];
                    
                    $name_authorized1=$fetch['name_authorized1'];
                    $name_authorized2=$fetch['name_authorized2'];
                    $name_specimen1=$fetch['name_specimen1'];
                    $name_specimen2=$fetch['name_specimen2'];
                    
                    $credit1 = $fetch['credit'];
                    $credit1 = ($credit1 != NULL) ? $credit1 : 0;
                    $credit2 = $fetch['credit1'];
                    $credit2 = ($credit2 != NULL) ? $credit2 : 0;
                    
                    $credit = $credit1 + $credit2;

                    $period=$fetch['period'];
                    $mode=$fetch['mode'];
                    
                    $com_name1=$fetch['com_name1'];
                    $com_name2=$fetch['com_name2'];
                    $rc_name11=$fetch['rc_name11'];
                    $rc_name12=$fetch['rc_name12'];
                    $rc_name21=$fetch['rc_name21'];
                    $rc_name22=$fetch['rc_name22'];
                    $rc_no1=$fetch['rc_no1'];
                    $rc_no2=$fetch['rc_no2'];
                    
                    $c_name1=$fetch['c_name1'];
                    $c_name2=$fetch['c_name2'];
                    $c_designation1=$fetch['c_designation1'];
                    $c_designation2=$fetch['c_designation2'];
                    $c_no1=$fetch['c_no1'];
                    $c_no2=$fetch['c_no2'];
                    
                    $name_authorized3=$fetch['name_authorized3'];
                    $name_authorized4=$fetch['name_authorized4'];
                    $des_authorized3=$fetch['des_authorized3'];
                    $des_authorized4=$fetch['des_authorized4'];
                    
               
             }
        ?>

<table style="width: 100%;" cellspacing="0" cellpadding="0">
   
<tr>
<td colspan="2">Name of the Company<br>Postal Address</td>
<td colspan="3"><?php echo $cust;?><br><?php echo $address;?></td>
</tr>

<tr>
<td colspan="2">Tel No:</td>
<td><?php echo $phone;?></td>
<td colspan="2">Fax No:<?php echo $fax;?></td>
</tr>     

<tr>
<td colspan="2">Email</td>
<td><b><?php echo $email;?></b></td>
<td colspan="2"></td>
</tr>


<tr>
<td colspan="2">Tradelicence/RegNo:<?php echo $licence_no;?></td>
<td></td>
<td colspan="2">Expiry Date:<?php echo $exp_date;?></td>
</tr>

<tr>
<td colspan="2">Type of Licence</td>
<td><b><?php echo $type_licence;?></b></td>
<td colspan="2"></td>
</tr>

<tr>
<td colspan="2">Nature of Business:<?php echo $nature_bus;?></td>
<td><b></b></td>
<td colspan="2">Established Year:<?php echo $est_year;?><b></b></td>
</tr>


<tr>
     <td colspan="3"><b>Directors/Partners/Proprietor [Attach Passport Copy]</b></td>
<td colspan="2"></td>
</tr>


<tr>
<td><b>Sl No</b></td>
<td><b>Name</b></td>
<td><b>Nationality</b></td>
<td><b>Passport No</b></td>
<td><b>Signature</b></td>
</tr>
<tr>
<td style="width: 8%;">1</td>
<td style="width: 25%;"><?php echo $d_name1;?></td>
<td style="width: 20%;"><?php echo $d_natn1;?></td>
<td><?php echo $d_pass1;?></td>
<td></td>
</tr>
<tr>
<td>2</td>
<td><?php echo $d_name2;?></td>
<td><?php echo $d_natn2;?></td>
<td><?php echo $d_pass2;?></td>
<td></td>
</tr>
<tr style="border-bottom:2px solid black;">
<td>3</td>
<td><?php echo $d_name3;?></td>
<td><?php echo $d_natn3;?></td>
<td><?php echo $d_pass3;?></td>
<td></td>
</tr>

<tr>
<td colspan="2">Name of Bank</td>
<td><?php echo $bank;?></td>
<td colspan="1">Branch</td>
<td colspan="1"><?php echo $branch;?></td>
</tr>
<tr>
<td colspan="2">Bank Account No.</td>
<td><?php echo $account;?></td>
<td colspan="1">A/c Name </td>
<td colspan="1"><?php echo $account_name;?></td>
</tr>

<tr>
<td colspan="2">Name of Authorized Signatory 1</td>
<td colspan="3"><?php echo $name_authorized1;?></td>
</tr>
<tr>
<td colspan="2">Name of Authorized Signatory 2</td>
<td colspan="3"><?php echo $name_authorized2;?></td>
</tr>
<tr>
<td colspan="2">Specimen Signatory 1</td>
<td colspan="3"><?php echo $name_specimen1;?></td>
</tr>
<tr>
<td colspan="2">Specimen Signatory 2</td>
<td colspan="3"><?php echo $name_specimen2;?></td>
</tr>

<tr>
<td colspan="2"><b>Value of Credit Limit</b></td>
<td><b>Credit</b></td>
<td colspan="2"><b>Mode of Payment</b></td>
</tr>
<tr>
<td colspan="2">AED <?php echo $credit;?></td>
<td><?php echo $period;?></td>
<td colspan="2"><?php echo $mode;?></td>
</tr>
</table>

<h5>Business References:</h5>
<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td colspan="2">Name of Company</td>
<td colspan="2">Contact Person</td>
<td>Contact Number</td>
</tr>
<tr>
<td style="width:8%;">1</td>
<td style="width:25%;"><?php echo $com_name1;?></td>
<td style="width:20%;"><?php echo $rc_name11;?></td>
<td style="width:22%;"><?php echo $rc_name12;?></td>
<td><?php echo $rc_no1;?></td>
</tr>
<tr>
<td>2</td>
<td><?php echo $com_name2;?></td>
<td><?php echo $rc_name21;?></td>
<td><?php echo $rc_name22;?></td>
<td><?php echo $rc_no2;?></td>
</tr>
</table>

<h5>Details of Persons to be contacted for payment:</h5>
<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td style="width:8%;">Sl No</td>
<td style="width:25%;">Name</td>
<td style="width:20%;">Designation</td>
<td style="width:22%;">Mobile No</td>
<td>Signature</td>
</tr>
<tr>
<td>1</td>
<td><?php echo $c_name1;?></td>
<td><?php echo $c_designation1;?></td>
<td><?php echo $c_no1;?></td>
<td></td>
</tr>
<tr>
<td>2</td>
<td><?php echo $c_name2;?></td>
<td><?php echo $c_designation2;?></td>
<td><?php echo $c_no2;?></td>
<td></td>
</tr>
</table>
<br/><br/><br/><br/><br/><br/>


<div>
     UNDERTAKING BY THE PROPRIETOR<br/>
     <p>1. I/We agree to settle our credit account promptly i.e. to settle your invoices within ....... days from the date of invoice.<br/>
     2. If I/We fail to settle the account more than the time allowed, Mancon Block Factory LLC. has complete right to take necessary action to recover the money.<br/>
     3. This Credit Period is valid for a period one (1) year from the date of approval and it is at discretion to renew the agreement thereafter and to revise the credit amount and / or credit period without assigning any reason.<br/>
     4. We hereby authorize you to take up any reference, which may be considered necessary.</p>
</div>

<h5>Specimen signature & details of the Authorized signatories who sign purchase orders.</h5>
<table style="width: 100%;" cellspacing="0" cellpadding="0">
<tr>
<td>NAME</td>
<td>DESIGNATION</td>
<td>SIGNATURE</td>
</tr>
<tr>
<td><?php echo $name_authorized3;?></td>
<td><?php echo $des_authorized3;?></td>
<td></td>
</tr>
<tr>
<td><?php echo $name_authorized4;?></td>
<td><?php echo $des_authorized4;?></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
</tr>
</table>
<br/><br/>

<div>
I/We <span style="border-bottom: 1px dotted #000;text-decoration: none;"><?php echo $name_authorized1;?></span>
The Partner/Director/Owner of <span style="border-bottom: 1px dotted #000;text-decoration: none;"><?php echo $cust;?></span>
hereby request for a credit facility and give the power of attorney to above authorized personnel to act on behalf my/our company.
</div>
<br/>

<h5>AUTHORIZED SIGNATURE</h5><br/><br/>
<h5>[Name and Company Seal]</h5>
<p><b>NOTE:</b> The form should be returned duty filled, signed and stamped with the following documents.</p>

<p>
1. Copy of Trade Licence.<br/>
2. Passport Copy of the Owner/Owners.<br/>
3. Copy of Chamber of Commerce Certificate.<br/>
4. 3 months Bank Statement.
</p>
<h4 align="center">FOR OFFICE USE ONLY</h4><br/>

<table style="width:60%;border:none;">
<tr style="border:none;">
<td style="border:none;">CREDIT LIMIT APPROVED</td>
<td height="50" style="border:none;">.....................................................</td>
</tr>
<tr style="border:none;">
<td style="border:none;">CREDIT PERIOD APPROVED </td>
<td height="50" style="border:none;">.....................................................</td>
</tr> 
<tr style="border:none;">
<td style="border:none;">APPROVED BY</td>
<td height="70" style="border:none;">.....................................................</td>
</tr> 
</table>

</body>
