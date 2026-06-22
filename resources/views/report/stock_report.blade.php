@extends('layouts.app_rest')
@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li>
                <a href="{{action('Rest\ProductController@create')}}" class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">Product</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Stock Report</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div x-data="menu">
                <div class="panel">
                <div class="category-table">
                        <table id="myTable" class="whitespace-nowrap">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Product</th>
                                    <th>Total Open Stock</th>
                                    <th>Total Purchase Stock</th>
                                    <th>Total Stock</th>
                                    <th>Total Sold Stock</th>
                                    <th>Balance Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in items" :key="index">
                                    <tr>
                                        <td>
                                            <span x-text="item.sku"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.name"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.open_stock"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.purchase_stock"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.total_stock"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.total_sold"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.balance_stock"></span>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script type="text/javascript">
    document.addEventListener("alpine:init", () => {
        Alpine.data("menu", () => ({
            items: <?php echo $products ?>,
            pageSize: 5, // Number of items per page
            currentPage: 1, // Current page number

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
        }));
    });
</script>
@endsection