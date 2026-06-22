@extends('layouts.app')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div x-data="form">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Purchases</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <!-- Basic -->
            <!-- type=text -->
            <div class="panel">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-semibold dark:text-white-light">Purchases</h3>
                    @can('purchase.create')
                    <a class="font-semibold hover:text-gray-400 btn btn-primary" href="{{action('TransactionController@create')}}">
                        Add New                
                    </a>
                    @endcan
                </div>
                <div class="my-4 h-px w-full border-b border-[#e0e6ed] dark:border-[#1b2e4b]"></div>
                <div class="mb-5">
                @can('purchase.view')
                    <div class="table-responsive">
                        <table class="table-hover whitespace-nowrap dataTable-table" id="customer_table">
                            <thead>
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Supplier</th>
                                    <th>Date</th>
                                    <th>Total Amount</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready( function(){
            customer_table = $('#customer_table').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [[0, 'desc']],
                "ajax": {
                    "url": "/transactions",
                    "data": function ( d ) {
                    }
                },
                columnDefs: [ {
                    "targets": 5,
                    "orderable": false,
                    "searchable": false
                } ],
                columns: [
                    { data: 'invoice_no', name: 'invoice_no'},
                    { data: 'name', name: 'suppliers.name'},
                    { data: 'transaction_date', name: 'transaction_date'},
                    { data: 'final_total', name: 'final_total'},
                    { data: 'payment_status', name: 'payment_status'},
                    { data: 'action', name: 'action'}
                ],
                "fnDrawCallback": function (oSettings) {
                    __currency_convert_recursively($('#customer_table'));
                },
                createdRow: function( row, data, dataIndex ) {
                    $( row ).find('td:eq(4)').attr('class', 'clickable_td');
                }
            });
            $(document).on('click', '.delete-customer', function (e) {
		e.preventDefault();
		swal({
			title: LANG.sure,
			icon: "warning",
			buttons: true,
			dangerMode: true,
		}).then((willDelete) => {
			if (willDelete) {
				var href = $(this).attr('href');
				$.ajax({
					method: "DELETE",
					url: href,
					dataType: "json",
					success: function (result) {
						if (result.success == true) {
							toastr.success(result.msg);
							if (typeof customer_table !== 'undefined') {
								customer_table.ajax.reload();
							}

						} else {
							toastr.error(result.msg);
						}
					}
				});
			}
		});
	});
        });
    </script>
@endsection