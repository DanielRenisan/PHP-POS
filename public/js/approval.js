$(document).ready(function () {
 
    //Purchase table
    purchase_table = $('#approval_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [[0, 'desc']],
        ajax: '/get-approvals',
        columnDefs: [{
            "targets": [0, 6],
            "orderable": false,
            "searchable": false
        }],
        columns: [
            { data: 'transaction_date', name: 'transaction_date' },
            { data: 'ref_no', name: 'ref_no' },
            { data: 'location_name', name: 'BS.name' },
            { data: 'total_quantity', name: 'total_quantity' },
            { data: 'new_total_quantity', name: 'new_total_quantity' },
            { data: 'first_name', name: 'u.first_name' },
            { data: 'approval_status', name: 'approval_status' },
            { data: 'action', name: 'action' }
        ],
        "fnDrawCallback": function (oSettings) {
            __currency_convert_recursively($('#approval_table'));
        }
    });

});   