@extends('layouts.app_rest')

@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
            <!-- start main content section -->
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="{{action('Auth\LoginController@dashboard')}}" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Room Size List</span>
            </li>
        </ul>
        <div class="grid grid-cols-1 gap-4 pt-5">
            <div x-data="sizeList">
                <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
                    <div class="px-5" x:data="sizeList">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="mb-5 flex items-center gap-2">
                                @can('room-size.delete')
                                    <button type="button" class="btn btn-danger gap-2 delete-button" @click="deleteRow()" data-href = "{{action('RoomSizeController@destroy')}}">
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
                                @endif
                                @can('room-size.create')    
                                    <button class="btn btn-primary gap-2" @click="openModal = true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Add New
                                    </button>
                                @endcan
                            </div>
                            <input type="text" class="border rounded px-2 py-1 w-80" placeholder="Search..."
                                style="outline: none;" x-model="searchText">
                        </div>

                        <div x-data="{ iconFile: null }" x-show="openModal" class="mb-5">
                            <!-- modal -->
                            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
                                :class="open && '!block'">
                                <div class="flex items-start justify-center min-h-screen px-4"
                                    @click.self="open = false">
                                    
                                    <div x-transition x-transition.duration.300
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg">
                                        <div class="heading">
                                            <h2 class="m-0">Add Room Size</h2>
                                        </div>
                                        <div class="p-5">
                                        {!! Form::open(['url' => action('RoomSizeController@store'), 'method' => 'post', 'class' => 'space-y-5', 'id' => 'facilityi6_add_form' ]) !!}
                                                <div>
                                                    {!! Form::label('name', __('Room Size') . ':*') !!}
                                                    {!! Form::text('name', null, ['class' => 'form-input','required']); !!}
                                                </div>
                                                <div class=" flex justify-end items-center mt-3">
                                                            <button type="button" class="btn btn-outline-danger"
                                                                @click="openModal = false">Discard</button>
                                                            <button
                                                                class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                                                type="submit">Create</button>
                                                </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div x-show="editModal" class="mb-5">
                            <!-- modal -->
                            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
                                :class="open && '!block'">
                                <div class="flex items-start justify-center min-h-screen px-4"
                                    @click.self="open = false">
                                    <div x-transition x-transition.duration.300
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg">
                                        <div class="heading">
                                            <h2 class="m-0">Edit Room Size</h2>
                                        </div>
                                        <div class="p-5">
                                            {!! Form::open(['url' => action('RoomSizeController@update', ['ID']),'class'=>'space-y-5', 'method' => 'PUT', 'id' => 'call_edit_form' ]) !!}
                                                <div>
                                                    {!! Form::label('name', __('Room Size') . ':*') !!}
                                                    {!! Form::text('name', null, ['class' => 'form-input','required', 'x-model'=>'itemToEdit.name']); !!}
                                                </div>
                                                <input id="edit-id" type="hidden" x-model="itemToEdit.id">
                                                <div class=" flex justify-end items-center mt-3">
                                                    <button type="button" class="btn btn-outline-danger"
                                                        @click="editModal = false">Discard</button>
                                                        <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                                        @click="editCategory">Update</button>
                                                </div>
                                            {!! Form::close() !!}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div x-show="viewModal" class="mb-5">
                            <!-- modal -->
                            <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto"
                                :class="open && '!block'">
                                <div class="flex items-start justify-center min-h-screen px-4"
                                    @click.self="open = false">
                                    <div x-transition x-transition.duration.300
                                        class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg">
                                        <div class="heading">
                                            <h2 class="m-0">View Category</h2>
                                        </div>
                                        <div class="p-5">
                                            <form class="space-y-5 pt-3 pb-3 view-deatils">
                                                <h2>Code : <span x-text="viewItem.code"></span></h2>
                                                <h2>Name : <span x-text="viewItem.code"></span></h2>
                                                <h2>Category : <span x-text="viewItem.mainCategory"></span></h2>
                                                <h2>Icon : <span
                                                        class="p-0.5 bg-white-dark/30 rounded-full w-max ltr:mr-2 rtl:ml-2">
                                                        <img :src="viewItem.icon"
                                                            class="h-8 w-8 rounded-full object-cover" />
                                                    </span>
                                                </h2>
                                                <h2>Status : <span
                                                        :class="viewItem.status === 'Active' ? 'badge badge-outline-success' : 'badge badge-outline-danger'"
                                                        x-text="viewItem.status"></span></h2>
                                                <div class=" flex justify-end items-center mt-3">
                                                    <button type="button" class="btn btn-outline-danger"
                                                        @click="viewModal = false">Discard</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    @can('room-size.view')    
                    <div class="category-table">
                        <table id="myTable" class="whitespace-nowrap">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-checkbox" x-model="checkAllCheckbox"
                                            @click="checkAll($event.target.checked)" :checked="checkAllCheckbox" />
                                    </th>
                                    <th>Facility Name</th>
                                    @can('room-size.update') 
                                    <th>Actions</th>
                                    @endcan
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
                                            <span x-text="item.name"></span>
                                        </td>
                                        @can('room-size.update') 
                                        <td>
                                            <div class="flex gap-4 items-center">
                                                <button class="hover:text-info" @click="editItem(item.id)">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                                        <path opacity="0.5"
                                                            d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5"
                                                            stroke="currentColor" stroke-width="1.5"
                                                            stroke-linecap="round"></path>
                                                        <path
                                                            d="M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z"
                                                            stroke="currentColor" stroke-width="1.5"></path>
                                                        <path opacity="0.5"
                                                            d="M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797M10.1002 15.5876L8.4126 13.9"
                                                            stroke="currentColor" stroke-width="1.5"></path>
                                                    </svg>
                                                </button>
                                                <!-- <button class="hover:text-info" @click="showViewModal(item)">
                                                    <i class="fas fa-eye"></i>
                                                </button> -->
                                            </div>
                                        </td>
                                        @endcan
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
@endsection
@section('javascript')
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        Alpine.data('sizeList', () => ({
            selectedRows: [],
            items:  <?php echo $sizes; ?>,
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
            editItem(itemId) {
                const itemToEdit = this.items.find(item => item.id === itemId);
                this.itemToEdit = { ...itemToEdit };
                this.editModal = true;
            },

            editCategory() {
                var data = $('form#call_edit_form').serialize();
                var id = $('form#call_edit_form').find('#edit-id').val();
                var url = $('form#call_edit_form').attr("action").replace('ID', id)
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