@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6 no-print" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Checkout</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div x-data="sizeList">
                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                    <div class="px-5" x:data="sizeList">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="mb-5 flex items-center gap-2">
                                
                            </div>
                            <input type="text" class="border rounded px-2 py-1 w-80" placeholder="Search..."
                                style="outline: none;" x-model="searchText">
                        </div>
                    </div>

                    @can('checkin.view')    
                    <div class="category-table" style="min-height: .01%;
                        overflow-x: auto;">
                        <table id="myTable">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-checkbox" x-model="checkAllCheckbox"
                                            @click="checkAll($event.target.checked)" :checked="checkAllCheckbox" />
                                    </th>
                                    
                                    <th>Booking No</th>
                                    <th>Rooms</th>
                                    <th>Name</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th  class="whitespace-nowrap">Total Amount</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in paginatedFilteredItems" :key="item.id">
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-checkbox mt-1" :id="'chk' + item.id"
                                                :value="item.id" x-model.number="selectedRows" />
                                        </td>
                                        <td>
                                            <span x-text="item.ref_no"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.room"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.name"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.check_in"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.check_out"></span>
                                        </td> 
                                        <td>
                                            <span class="display_currency final_total" data-currency_symbol="true" x-text="item.total"></span>
                                        </td> 
                                        <td>
                                            <span x-text="item.status"></span>
                                        </td>
                                        <td>
                                        <span x-bind:class="item.color" x-text="item.payment_status"></span>
                                        </td>
                                         
                                        <td>
                                            <div class="flex gap-4 items-center">
                                                <a x-bind:href="item.show_url">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="print-invoice" x-bind:data-href="item.print_url"><i class="fa fa-print" aria-hidden="true"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <div class="pagination">
                            <button style="margin-right: 20px;" @click="previousPage()"
                                :disabled="currentPage === 1">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <template x-for="page in totalPages" :key="page">
                                <button style="margin-right: 5px;" @click="changePage(page)"
                                    :class="{ 'active': currentPage === page }"><span x-text="page"></span></button>
                            </template>
                            <button style="margin-left: 20px;" @click="nextPage()"
                                :disabled="currentPage === totalPages">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>

                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
<section class="invoice print_section" id="receipt_section">
</section>
@endsection
@section('javascript')
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        Alpine.data('sizeList', () => ({
            selectedRows: [],
            items:  <?php echo $transactions; ?>,
            searchText: '',
            openModal: false,
            editModal: false,
            expenseModal: false,
            viewModal: false,
            viewItem: {},
            itemToEdit: {},
            itemToExpense: {},
            pageSize: 10, // Number of items per page
            currentPage: 1, // Current page number

            showViewModal(item) {
                this.viewItem = item; // Set the item to view
                this.viewModal = true; // Show the view modal
            },

            get filteredItems() {
                return this.items.filter(item => {
                    return item.name.toLowerCase().includes(this.searchText.toLowerCase());
                });
            },

            get paginatedFilteredItems() {
                const filtered = this.filteredItems;
                return filtered.slice(this.startIndex, this.endIndex);
            },

            get totalPages() {
                return Math.ceil(this.items.length / this.pageSize);
            },

            get startIndex() {
                return (this.currentPage - 1) * this.pageSize;
            },

            get endIndex() {
                return this.currentPage * this.pageSize;
            },

            get paginatedItems() {
                return this.items.slice(this.startIndex, this.endIndex);
            },

            changePage(pageNumber) {
                this.currentPage = pageNumber;
            },

            previousPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                }
            },

            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                }
            },
            checkAllCheckbox() {
                if (this.items.length && this.selectedRows.length === this.items.length) {
                    return true;
                } else {
                    return false;
                }
            },

            checkAll(isChecked) {
                if (isChecked) {
                    this.selectedRows = this.items.map((d) => {
                        return d.id;
                    });
                } else {
                    this.selectedRows = [];
                }
            },
            editItem(itemId) {
                const itemToEdit = this.items.find(item => item.id === itemId);
                this.itemToEdit = { ...itemToEdit };
                this.editModal = true;
            },
            addExpense(itemId) {
                const itemToExpense = this.items.find(item => item.id === itemId);
                this.itemToExpense = { ...itemToExpense };
                this.expenseModal = true;
            },
            updateExpense() {
                var data = $('form#expense_add_form').serialize();
                var id = $('form#expense_add_form').find('#edit-id').val();
                var url = $('form#expense_add_form').attr("action").replace('ID', id)
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            window.location.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            },
            deleteRow(item) {
                if (confirm('Are you sure want to delete selected row ?')) {
                        var href = $('.delete-button').attr('data-href');
                        $.ajax({
                            method: "GET",
                            url: href,
                            dataType: "json",
                            data: {
                                ids :this.selectedRows
                            },
                            success: function (result) {
                                if (result.success == true) {
                                    window.location.reload();

                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                }
            },
        }));
    });
</script>
@endsection