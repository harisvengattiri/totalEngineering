<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        'dom': 'Bfrtip',
        'buttons': [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "ajax": "../api/near_period.php",
        columnDefs: [
    {
        targets: [-2,-3,-4],
        className: 'dt-body-right'
    },
    {
        targets: -1,
        width: '20%'
    } 
  ]
    } );
} );
</script>
    
    
    <table id="example" align="right" class="display" style="width:100%">

        <thead>
            <tr>
              <th>
                   Customer ID
              </th>
              <th>
                   Customer
              </th>
              <th>
                   Customer Type
              </th>
              <th>
                   Credit Period
              </th>
              <th>
                  Oldest Unpaid Invoice
              </th>
              <th>
                  Invoice Date
              </th>
              <th>
                   Over Credit Period
              </th>
          </tr>
        </thead>
        <tfoot>
            <tr>
              <th>
                   Customer ID
              </th>
              <th>
                   Customer
              </th>
              <th>
                   Customer Type
              </th>
              <th>
                   Credit Period
              </th>
              <th>
                  Oldest Unpaid Invoice
              </th>
              <th>
                  Invoice Date
              </th>
              <th>
                   Over Credit Period
              </th>
          </tr>
        </tfoot>
    </table>