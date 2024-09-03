<?php include "../config.php"; ?>
<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:$baseurl/login/");
}
?>
<?php
if (!isset($_POST['print'])) { ?>
    <!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">-->
    <link rel="stylesheet" type="text/css" href="<?php echo $baseurl; ?>/report/css/style.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">


    <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.4.js">
    </script>
    <!--	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js">
	</script>-->
    <script type="text/javascript" language="javascript" src="js/pagination.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js">
    </script>
    <!--	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/pdfmake.min.js">
	</script>-->
    <script type="text/javascript" language="javascript" src="js/pdf.js"></script>

    <script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/vfs_fonts.js">
    </script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js">
    </script>

    <style>
        table.dataTable tbody td {
            word-break: break-word;
            vertical-align: top;
            /*width:10%;*/
        }
    </style>

    <script type="text/javascript" class="init">
        $(document).ready(function() {
            $('#report').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf'
                ],
                "columnDefs": [{
                    "width": "20%",
                    "targets": 1
                }]
            });
        });
    </script>

<?php } ?>
<?php
if (isset($_POST)) {
    $fdate = $_POST["fdate"];
    $tdate = $_POST["tdate"];
    $driver = $_POST["driver"];

    $sqlType = "SELECT `vehicleType` FROM `driverType` WHERE `driver`='$driver'";
    $queryType = mysqli_query($conn, $sqlType);
    $resultType = mysqli_fetch_array($queryType);
    $driverType = $resultType['vehicleType'];

    switch ($driverType) {
        case "Tipper":
            $fairType = 'fair';
            break;
        case "6 Wheel":
            $fairType = 'fair1';
            break;
        case "2XL Trailor":
            $fairType = 'fair2';
            break;
        case "3XL Trailor":
            $fairType = 'fair3';
            break;
        default:
            $fairType = 'fair';
    }

    $sqldri = "SELECT name from customers where id='$driver'";
    $querydri = mysqli_query($conn, $sqldri);
    $fetchdri = mysqli_fetch_array($querydri);
    $driverName = $fetchdri['name'];
}
?>
<title>DRIVER TRIP ALLOWANCE REPORT&nbsp;&nbsp;[ <?php echo $driver1; ?> ]&nbsp;&nbsp;[ <?php echo date("d/m/Y"); ?> ]</title>
<?php
if (isset($_POST['print'])) { ?>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
            font-size: 15px;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        h1,
        h2 {
            font-family: Arial, Helvetica, sans-serif;
        }

        th,
        td {
            font-family: verdana;
        }

        .width {
            width: 15px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2
        }
    </style>
<?php } ?>
<center>
    <h1>MANCON BLOCK FACTORY.</h1>
    <h2>DRIVER TRIP ALLOWANCE REPORT <span style="font-size:20px;"> [<?php echo $fdate.'-'.$tdate;?>]</span></h2>
    <h2>[<?php echo $driverName;?>][<?php echo $driverType;?>]</h2>
</center>

<!--<table id="report" class="display nowrap" cellspacing="0" width="100%">-->
<table id="report" class="display nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>
                Sl No
            </th>
            <th>
                Date
            </th>
            <th>
                DNO
            </th>
            <th>
                Contractor
            </th>
            <th>
                Location
            </th>
            <th>
                Amount
            </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $sql = "SELECT * FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND driver='$driver' ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $sl = 1;
            $tfair = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];

                $name1 = $row["customer"];
                $sqlcust = "SELECT name from customers where id='$name1'";
                $querycust = mysqli_query($conn, $sqlcust);
                $fetchcust = mysqli_fetch_array($querycust);
                $cust = $fetchcust['name'];
                $site = $row["customersite"];
                $sqlsite = "SELECT p_name,location from customer_site where id='$site'";
                $querysite = mysqli_query($conn, $sqlsite);
                $fetchsite = mysqli_fetch_array($querysite);
                $site1 = $fetchsite['p_name'];
                $loc = $fetchsite['location'];

                $sqlfair = "SELECT `$fairType` AS fair,location from fair where id='$loc'";
                $queryfair = mysqli_query($conn, $sqlfair);
                $fetchfair = mysqli_fetch_array($queryfair);
                $fair = $fetchfair['fair'];
                $fair = ($fair != NULL) ? $fair : 0;
                $loc1 = $fetchfair['location'];

        ?>
                <tr>
                    <td><?php echo $sl; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo sprintf("%06d", $id); ?></td>
                    <td><?php echo $cust; ?></td>
                    <td><?php echo $loc1; ?></td>
                    <td style="text-align:right;"><?php echo custom_money_format("%!i", $fair); ?></td>
                </tr>

        <?php
                $sl = $sl + 1;
                $tfair = $tfair + $fair;
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align:right;">Total</td>
            <td colspan="1" style="text-align:right;"><b><?php echo custom_money_format("%!i", $tfair); ?></b></td>
        </tr>
    </tfoot>

</table>

<?php
if (isset($_POST['print'])) { ?>

    <body onload="window.print()">
    <?php } ?>