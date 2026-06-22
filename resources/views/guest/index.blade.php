@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Guest List</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div x-data="guestList">
                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                    <div class="px-5" x:data="guestList">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="mb-5 flex items-center gap-2">
                            </div>
                            <input type="text" class="border rounded px-2 py-1 w-80" placeholder="Search..."
                                style="outline: none;" x-model="searchText">
                        </div>
                    </div>
                    <br>
                    <div class="category-table">
                        <table id="myTable" class="whitespace-nowrap">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Nationality</th>
                                    <th>Balance Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in paginatedFilteredItems" :key="item.id">
                                    <tr>
                                        <td>
                                            <span x-text="item.first_name"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.last_name"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.email"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.phone"></span>
                                        </td>
                                        <td>
                                            <span x-text="item.nationality"></span>
                                        </td>
                                        <td>
                                        <span class="display_currency payment_due" data-currency_symbol="true" x-text="item.due"></span>
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
        document.addEventListener('alpine:init', () => {
            Alpine.data('guestList', () => ({
                selectedRows: [],
                items:  <?php echo $customers; ?>,
                searchText: '',
                openModal: false,
                editModal: false,
                viewModal: false,
                viewItem: {},
                itemToEdit: {},
                pageSize: 5, // Number of items per page
                currentPage: 1, // Current page number

                showViewModal(item) {
                    this.viewItem = item; // Set the item to view
                    this.viewModal = true; // Show the view modal
                },

                get filteredItems() {
                    return this.items.filter(item => {
                        return item.first_name.toLowerCase().includes(this.searchText.toLowerCase());
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

                handleIconChange(event) {
                    const file = event.target.files[0];

                    if (file) {
                        const allowedFormats = ['image/jpeg', 'image/png', 'image/gif'];

                        if (!allowedFormats.includes(file.type)) {
                            document.getElementById('iconError').classList.remove('hidden');
                            this.iconFile = null;
                        } else {
                            document.getElementById('iconError').classList.add('hidden');

                            const reader = new FileReader();
                            reader.readAsDataURL(file);
                            reader.onload = () => {
                                this.itemToEdit.icon = reader.result; // Update the icon with the new image data
                            };
                        }
                    }
                },

            }));
        });
    </script>
@endsection