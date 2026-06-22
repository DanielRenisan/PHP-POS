@extends('layouts.app_rest')
@section('title', __('Role'))

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.0/css/buttons.dataTables.css">
<style>
    .dt-search label {
        display: none;
    }

    /* Assuming the input has a fixed width */
    div.dt-container .dt-search input {
        background-image: url('https://cdn3.iconfinder.com/data/icons/feather-5/24/search-512.png');
        background-size: 18px;
        background-repeat: no-repeat;
        background-position: left 10px center;
        /* box-shadow: 0 0 5px rgba(0, 0, 0, 0.3); */
        padding: 10px 10px 3px 30px !important;
        border-radius: 20px !important;
        height:80%;
    }

    div.dt-container .dt-paging .dt-paging-button.current {
        color: white !important;
    }

    .dt-buttons .buttons-html5,
    .buttons-collection,
    .buttons-print {
        border-radius: 5px !important;
        background-color: transparent !important;
        border: 1px solid skyblue !important;
        background: none !important;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

    .dt-buttons button:hover {
        background-color: skyblue !important;
        color: white !important;
    }

    button.dt-paging-button.current {
        border-radius: 30px !important;
        background-color: skyblue !important;
        border: none !important;
        color: white !important;
    }

    span.dt-paging-button {
        border-radius: 30px !important;
        background-color: skyblue !important;
        border: none !important;
        color: white !important;
    }

    a.dt-paging-button {
        border-radius: 30px !important;
        background-color: transparent !important;
        border: none !important;
        color: black !important;
    }

    .custom-table thead tr {
        /* background-color: rgb(235, 235, 235); */
        /* border: 1px solid lightgray; */
        font-family: Arial, Helvetica, sans-serif;

    }
    #example_wrapper table.dataTable tbody td {
        border: none !important;
        /* background-color:white  !important; */
    }
    .custom-table tbody tr {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }

    .dt-info {
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
    }
    .total-details .bottom{
        display: flex;
        align-items: center !important;
        margin: auto;
        gap: 10px;
        height: 40px;
        font-weight: bold;
        }

        .total-details .bottom h2{
        margin: 0;
        font-size: 40px;
        }

        .total-details p{
        font-size: 12px;
        }
        div.dt-container .dt-paging .dt-paging-button.last {
            font-size: 24px;
            font-weight:bold;
        }
        div.dt-container .dt-paging .dt-paging-button.next {
            font-size: 24px;
            font-weight:bold;
        }

        div.dt-container .dt-paging .dt-paging-button.previous {
            font-size: 24px;
            font-weight:bold;
        }

        div.dt-container .dt-paging .dt-paging-button.first {
            font-size: 24px;
            font-weight:bold;
        }
        table.dataTable tbody tr:hover {
            background-color: #87ceeb !important;
            color:white;
}
</style>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Roles</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div>
                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                    <div class="px-5" x:data="roleList">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="mb-5 flex items-center gap-2">
                                @can('user.update')
                                <button class="btn btn-primary gap-2 edit-btn"  style="display: none;">
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </button>
                                @endcan
                                @can('role.delete')
                                <button type="button" class="btn btn-danger delete-button"  style="display: none;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                        <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                        <path
                                            d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                        <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round"></path>
                                        <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round"></path>
                                        <path opacity="0.5"
                                            d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                    </svg>
                                    Delete
                                </button>
                                @endcan
                                
                            </div>
                        </div>
                    </div>
                    <div class="category-table" style="position: relative;padding: 15px;">
                        <lable class="dt-buttons" style="position: absolute;margin-left: 300px;margin-top: 5px;z-index:99;">
                            Show
                            <select class="dt-button buttons-collection buttons-page-length" id="pagination" style="height: 2.4em;">
                                <option value="25" {{request()->get('items') == 25 ? 'selected' : ''}}>25</option>
                                <option value="50" {{request()->get('items') == 50 ? 'selected' : ''}}>50</option>
                                <option value="100" {{request()->get('items') == 100 ? 'selected' : ''}}>100</option>
                            </select>
                            rows
                        </lable>
                        <table  id="example" class="custom-table display nowrap" style="width:100%">
                            <thead>
                                <tr style="border:none;background-color:skyblue !important;color:white;">
                                    <th  width="10%">
                                    </th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles_data as $role)
                                    <tr id="check-tr" data-id="{{ $role['id'] }}">
                                        <td>
                                            <input type="checkbox" class="form-checkbox mt-1 check-box" id="check-box"
                                                value="{{ $role['id'] }}" />
                                        </td>
                                        <td style="text-align:left">{{ $role['name'] }}</td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="11">{{$roles_data->links('vendor.pagination.tailwind')}}</td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('javascript')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.print.min.js"></script>
<script type="text/javascript">
    var table = new DataTable('#example', {
        fixedColumns: true,
        lengthMenu: false,
        bPaginate: false,
        bInfo:false,
        layout: {
            topStart: {
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            }
        }
    });
    document.getElementById('pagination').onchange = function() { 
        window.location = "{!! $roles_data->url(1) !!}&items=" + this.value; 
    };

    $(document).ready(function () {
        $(document).on('click', "table#example tbody tr td", function() {
            var currentRow = $(this).closest("tr");
            $('table#example tbody tr').css('background-color', '');
            $('table#example tbody tr').css('color', '');
            currentRow.css('background-color', '#87ceeb');
            currentRow.css('color', 'white');
            $('button.edit-btn').show();
            $('button.delete-button').hide();
            var id = currentRow.data('id');
            
            $(document).on('click', 'button.edit-btn', function() {
                $('button.edit-btn').hide();
                currentRow.css('background-color', '');
                currentRow.css('color', '');
                const edit_url = "{{action('RoleController@edit', ['ID'])}}".replace('ID', id);
                window.location.href = edit_url;
            });
	    });

        var searchIDs = [];
        $(document).on('change', '#check-box', function()
        {
            $('button.view-btn').hide();
            $('button.edit-btn').hide();
            if($(this).is(":checked")) 
            {
                searchIDs.push($(this).val());
            }
            else
            {
                searchIDs.splice(searchIDs.indexOf($(this).val()), 1)
            }
            if(searchIDs.length > 0)
            {   
                $('button.delete-button').show();
                $(document).on('click', 'button.delete-button', function() {
                const delete_url = "{{action('RoleController@deleteItem')}}";
                $.ajax({
                    method: "GET",
                    url: delete_url,
                    dataType: "json",
                    data: {
                        ids : searchIDs
                    },
                    success: function (result) {
                        if (result.success == true) {
                            window.location.reload();

                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            });
            }
        });
    });
</script>
@endsection