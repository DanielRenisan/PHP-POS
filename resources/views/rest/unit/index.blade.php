@extends('layouts.app_rest')

@section('content')

<style>
    .title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    /* CSS to style the modal */
    .modal {
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    #iconError.hidden {
        display: none;
    }

    .heading {
        background-color: #4361ee;
        color: white;
        overflow: hidden;
        padding: 15px 20px;
    }

    .heading h2 {
        font-size: 18px;
        font-weight: bold;
    }

    .pagination {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin: 20px 100px 0 0;

    }

    .pagination button {
        border: 1px solid lightblue;
        padding: 3px 10px;
        border-radius: 50%;
    }

    .pagination button:hover {
        background-color: #4361ee;
        border: 1px solid transparent;
        color: white;
    }

    .pagination button.active {
        background-color: #4361ee;
        border: 1px solid transparent;
        color: white;
    }

    .view-deatils h2 {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 60%;
        margin: 0 auto;
        font-size: 18px;
        font-weight: 500;
    }

    ::-webkit-scrollbar {
        display: none;
    }

    .cardViewheight {
        max-height: 40rem;
        width: 30rem;
    }
</style>


{{-- ---Body Content for Unit start ------------------------------- --}}

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div x-data="unitList">
        <script src="assets/js/simple-datatables.js"></script>

        <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">
            <div class="px-5" x:data="categoryList">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="mb-5 flex items-center gap-2">
                        @can('unit.create')
                        <button class="btn btn-primary gap-2" @click="openModal = true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="h-5 w-5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Add New
                        </button>
                        @endcan
                        @can('unit.delete')
                        <button type="button" href="#" class="btn btn-danger delete-button"
                            data-href="{{ route('unit.delete') }}" @click="deleteRow()">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round"></path>
                                <path
                                    d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round"></path>
                                <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round"></path>
                                <path opacity="0.5"
                                    d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                                    stroke="currentColor" stroke-width="1.5"></path>
                            </svg>
                            Delete
                        </button>
                        @endcan
                    </div>
                    <input type="text" class="border rounded px-2 py-1 w-80" placeholder="Search..."
                        style="outline: none;" x-model="searchText">
                </div>

                <div x-show="openModal" class="mb-5">
                    <!-- modal -->
                    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
                        <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                            <div x-transition x-transition.duration.300
                                class="panel border-0 p-0 rounded-lg overflow-hidden my-8  cardViewheight">
                                <div class="heading">
                                    <h2 class="m-0">Add Unit</h2>
                                </div>
                                <div class="p-5" style="max-height: 70vh; height: auto; overflow: auto;">
                                    <form enctype="multipart/form-data" id="unit_add_form" class="needs-validation"
                                        novalidate="" method="POST" action="{{ route('unit.store') }}">
                                        @csrf
                                        <div style="display: flex; flex-direction: column; gap: 10px;">
                                            <div>
                                                <label for="code">Short Code</label>
                                                <input id="code" type="text" class="form-input" name="short_code"
                                                    required />
                                            </div>
                                            <div>
                                                <label for="name">Name</label>
                                                <input id="name" type="text" class="form-input" name="name" required />
                                            </div>

                                            <div>
                                                <label>
                                                    <input type="checkbox" name="allow_decimal"
                                                        x-on:click="showAdditionalFields = !showAdditionalFields">
                                                    Allow Decimal
                                                </label>
                                            </div>

                                            <!-- Additional fields -->
                                            <div x-show="showAdditionalFields">
                                                <div class="mt-2">
                                                    <label for="value">Value</label>
                                                    <input id="value" type="text" class="form-input" name="value" />
                                                </div>

                                            </div>

                                            <div style="margin-top: 10px">
                                                <label>
                                                    <input type="checkbox"
                                                           x-on:click="showAdditionalFieldsUnit = !showAdditionalFieldsUnit">
                                                    Show Parent Short Unit
                                                </label>
                                            </div>
                                            <div x-show="showAdditionalFieldsUnit">
                                                <label for="parentUnit">Parent Unit </label>
                                                <select class="form-select text-white-dark" name="unit_parent_id"
                                                        id="parentUnit" required>
                                                    <option value="" selected>Select Parent Unit</option>
                                                    @foreach ($Parent as $ct)
                                                        <option name="SelectParent" class="pro-type"
                                                                value="{{ $ct->id }}">
                                                            {{ $ct->name }}</option>
                                                    @endforeach
                                                    <span class="text-danger">
                                                            @error('SelectParent')
                                                        {{ $message }}
                                                        @enderror
                                                        </span>
                                                </select>
                                            </div>

                                            <div x-show="showAdditionalFieldsUnit">
                                                <label for="otherUnitShortCode">Add as 1 Short Code of other
                                                    Unit</label>
                                                <input id="otherUnitShortCode" type="text" class="form-input"
                                                       name="add_shortcode_for_otherunit" />
                                            </div>

                                            <div style="margin-bottom: 20px">
                                                <label for="status">Status</label>
                                                <input id="statusCheckbox" type="checkbox" class="form-checkbox"
                                                    name="Status" x-model="statusChecked">
                                                <span>Active</span>
                                            </div>

                                        </div>
                                        <!-- End of additional fields -->
                                    </form>
                                </div>
                                <!-- Fixed Footer -->
                                <div class="sticky bottom-0 bg-white py-4" style="margin-right: 20px;">
                                    <div class="flex justify-end items-center mt-3">
                                        <button type="button" class="btn btn-outline-danger"
                                            @click="openModal = false">Discard</button>
                                        <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                            @click="addUnit">Create</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="editModal" class="mb-5">
                    <!-- modal -->
                    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
                        <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                            <div x-transition x-transition.duration.300
                                class="panel border-0 p-0 rounded-lg overflow-hidden my-8  cardViewheight">
                                <div class="heading">
                                    <h2 class="m-0">Edit Unit</h2>
                                </div>
                                <div class="p-5" style="max-height: 70vh; height: auto; overflow: auto;">
                                    <form enctype="multipart/form-data" id="unit_edit_form" class="needs-validation"
                                        novalidate="" method="POST" action="{{ route('unit.update') }}">
                                        @csrf
                                        <input type="hidden" name="id" id="id" x-model="itemToEdit.id">
                                        {{-- <div style="display: flex; flex-direction: column; gap: 10px;">
                                            <div>
                                                <label for="code">Short Code</label>
                                                <input id="code" type="text" class="form-input"
                                                    x-model="itemToEdit.short_code" name="short_code" required />
                                            </div>
                                            <div>
                                                <label for="name">Name</label>
                                                <input id="name" type="text" class="form-input"
                                                    x-model="itemToEdit.name" name="name" required />
                                            </div>

                                            <div>
                                                <label for="parentUnit">Parent ID </label>
                                                <select class="form-select text-white-dark" name="unit_parent_id"
                                                    x-model="itemToEdit.unit_parent_id" id="parentUnit" required>
                                                    <option value=" " selected>Select Parent ID mljk</option>
                                                    @foreach ($Parent as $ct)
                                                    <option name="SelectParent" class="pro-type" value="{{ $ct->id }}"
                                                        >
                                                        {{ $ct->name }}</option>
                                                    @endforeach
                                                    <span class="text-danger">
                                                        @error('SelectParent')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="status">Status</label>
                                                <input id="statusCheckbox" type="checkbox" class="form-checkbox"
                                                    name="Status" x-model="statusChecked">
                                                <span>Active</span>
                                            </div>
                                        </div> --}}
                                        <div style="display: flex; flex-direction: column; gap: 10px;">
                                            <div>
                                                <label for="code">Short Code</label>
                                                <input id="code" type="text" class="form-input" name="short_code"
                                                    x-model="itemToEdit.short_code" required />
                                            </div>
                                            <div>
                                                <label for="name">Name</label>
                                                <input id="name" type="text" class="form-input" name="name"
                                                    x-model="itemToEdit.name" required />
                                            </div>

                                            <div>
                                                <label>
                                                    <input type="checkbox" name="allow_decimal"
                                                        x-on:click="showAdditionalFields = !showAdditionalFields">
                                                    Allow Decimal
                                                </label>
                                            </div>
{{--                                                <div class="mt-2">--}}
{{--                                                    <label for="value">Value</label>--}}
{{--                                                    <input id="value" type="text" class="form-input" name="value"--}}
{{--                                                           x-model="itemToEdit.value" />--}}
{{--                                                </div>--}}



                                                <div style="margin-top: 10px">
                                                    <label>
                                                        <input type="checkbox"
                                                               x-on:click="showAdditionalFields = !showAdditionalFields">
                                                        Show Parent Short Unit
                                                    </label>
                                                </div>
                                                <div x-show="showAdditionalFields">
                                                    <label for="parentUnit">Parent Unit </label>
                                                    <select class="form-select text-white-dark" name="unit_parent_id"
                                                            x-model="itemToEdit.unit_parent_id"
                                                            id="parentUnit" required>
                                                        <option value="" selected>Select Parent Unit</option>
                                                        @foreach ($Parent as $ct)
                                                            <option name="SelectParent" class="pro-type"
                                                                    value="{{ $ct->id }}" >
                                                                {{ $ct->name }}</option>
                                                        @endforeach
                                                        <span class="text-danger">
                                                            @error('SelectParent')
                                                            {{ $message }}
                                                            @enderror
                                                        </span>
                                                    </select>

                                                    <div x-show="showAdditionalFields">
                                                        <label for="otherUnitShortCode">Add as 1 Short Code of other
                                                            Unit</label>
                                                        <input id="otherUnitShortCode" type="text" class="form-input"
                                                               name="add_shortcode_for_otherunit"
                                                               x-model="itemToEdit.add_shortcode_for_otherunit" />
                                                    </div>
                                                </div>


                                            <!-- Additional fields -->
                                            <div x-show="showAdditionalFields">
                                            </div>
                                            <div style="margin-bottom: 20px">
                                                <label for="status">Status</label>
                                                <input id="statusCheckbox" type="checkbox" class="form-checkbox"
                                                    name="Status" x-bind:checked="itemToEdit.status === 'Active'">
                                                <span>Active</span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- Fixed Footer -->
                                <div class="sticky bottom-0 bg-white py-4" style="margin-right: 20px;">
                                    <div class="flex justify-end items-center mt-3">
                                        <button type="button" class="btn btn-outline-danger"
                                            @click="editModal = false">Discard</button>
                                        <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                            @click="editUnit">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="viewModal" class="mb-5">
                    <!-- modal -->
                    <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
                        <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                            <div x-transition x-transition.duration.300
                                class="panel border-0 p-0 rounded-lg overflow-hidden my-8 w-full max-w-lg">
                                <div class="heading">
                                    <h2 class="m-0">View Unit</h2>
                                </div>
                                <div class="p-5">
                                    <form class="space-y-5 pt-3 pb-3 view-deatils">
                                        <h2>Code : <span x-text="viewItem.short_code"></span></h2>
                                        <h2>Name : <span x-text="viewItem.name"></span></h2>
                                        <h2>Parent Unit : <span x-text="viewItem.unit_parent_id"></span></h2>
                                        <h2>Status : <span
                                                :class="viewItem.status === 'Active' ? 'badge badge-outline-success' : 'badge badge-outline-danger'"
                                                x-text="viewItem.status"></span></h2>
                                        <div class=" flex justify-end items-center mt-3">
                                            <button type="button" class="btn btn-outline-danger"
                                                @click="viewModal = false">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @can('unit.view')
            <div class="unit-table">
                <table id="myTable" class="whitespace-nowrap">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-checkbox" x-model="checkAllCheckbox"
                                    @click="checkAll($event.target.checked)" :checked="checkAllCheckbox" />
                            </th>
                            <th>Short Code</th>
                            <th>Name</th>
                            <th>Parent Unit</th>
                            <th>Status</th>
                            <th>Actions</th>
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
                                    <span x-text="item.short_code"></span>
                                </td>
                                <td>
                                    <div class="flex items-center font-semibold">
                                        <span x-text="item.name"></span>
                                    </div>
                                </td>
                                <td>
                                    <span x-text="item.parent_name"></span>
                                </td>
                                <td>
                                    <span
                                        :class="item.status === 'Active' ? 'badge badge-outline-success' : 'badge badge-outline-danger'"
                                        x-text="item.status"></span>
                                </td>
                                <td>
                                    <div class="flex gap-4 items-center">
                                        @can('unit.update')
                                        <button class="hover:text-info" @click="editItem(item.id)">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                                                <path opacity="0.5"
                                                    d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                                                </path>
                                                <path
                                                    d="M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z"
                                                    stroke="currentColor" stroke-width="1.5"></path>
                                                <path opacity="0.5"
                                                    d="M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797M10.1002 15.5876L8.4126 13.9"
                                                    stroke="currentColor" stroke-width="1.5"></path>
                                            </svg>
                                        </button>
                                        @endcan
                                        <button class="hover:text-info" @click="showViewModal(item)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="pagination">
                    <button style="margin-right: 20px;" @click="previousPage()" :disabled="currentPage === 1">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <template x-for="page in totalPages" :key="page">
                        <button style="margin-right: 5px;" @click="changePage(page)"
                            :class="{ 'active': currentPage === page }"><span x-text="page"></span></button>
                    </template>
                    <button style="margin-left: 20px;" @click="nextPage()" :disabled="currentPage === totalPages">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

            </div>
            @endcan
        </div>
    </div>
</div>

@endsection


{{-- ---Body Content for Unit End ------------------------------- --}}


{{-- ---script code for unit---------------- --}}
<link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        //Unit list
        Alpine.data('unitList', () => ({
            selectedRows: [],
            items:  <?php echo $unit; ?>,
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

            statusChecked: false,
            showAdditionalFields: false,
            showAdditionalFieldsUnit: false,
            addUnit() {
                if (document.getElementById("code").value === "") {
                    this.generateCode();
                }

                const code = document.getElementById('code').value;
                const name = document.getElementById('name').value;
                const otherUnitShortCode = document.getElementById('otherUnitShortCode').value;
                const value = document.getElementById('value').value;
                const parentUnit = document.getElementById('parentUnit');
                // const parentUnitElement = parentUnitElement.options[parentUnitElement.selectedIndex].text;
                const status = this.statusChecked ? 'Active' : 'Not-Active';

                if (!code || !name) {
                    console.error('Please fill in all required fields.');
                    return;
                }

                const newCategory = {
                    id: this.items.length + 1,
                    code: code,
                    name: name,
                    parentUnit: parentUnit,
                    status: status,
                    action: 1,
                };

                if (otherUnitShortCode) {
                    newCategory.otherUnitShortCode = otherUnitShortCode;
                }

                if (value) {
                    newCategory.value = value;
                }
                // this.items.push(newCategory);
                $('form#unit_add_form').submit();
                location.reload();

                // Close the modal after adding the category
                this.openModal = false;

                // Reset the form inputs
                document.getElementById('code').value = '';
                document.getElementById('name').value = '';
                document.getElementById('otherUnitShortCode').value = '';
                document.getElementById('value').value = '';
                // parentUnitElement.selectedIndex = 0; // Reset the parent unit selection
                this.statusChecked = false; // Reset the checkbox value
            },

            generateCode() {
                const length = 4;
                const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWYXZ';
                let code = '';

                for (let i = 0; i < length; i++) {
                    const randomIndex = Math.floor(Math.random() * characters.length);
                    code += characters.charAt(randomIndex);
                }

                // return code;

                document.getElementById('code').value = code;
            },

            editItem(itemId) {
                const itemToEdit = this.items.find(item => item.id === itemId);
                this.itemToEdit = { ...itemToEdit };
                this.editModal = true;
            },

            editUnit() {
                const index = this.items.findIndex(item => item.id === this.itemToEdit.id);

                // Update status based on the checkbox state
                this.itemToEdit.status = this.statusChecked ? 'Active' : 'Not-Active';

                // Get the value of parentUnit from the Alpine.js model
                this.itemToEdit.parentUnit = this.itemToEdit.parentUnit;

                this.items.splice(index, 1, { ...this.itemToEdit });
                console.log("editUnit", this.itemToEdit)
                $('form#unit_edit_form').submit();


                this.editModal = false;

                // Reflect changes in the table
                if (this.items[index]) {
                    this.items[index].code = this.itemToEdit.code;
                    this.items[index].name = this.itemToEdit.name;
                    this.items[index].status = this.itemToEdit.status;
                    // Update other properties as needed based on your data structure
                }

                // Check or uncheck status checkbox based on the edited item's status
                this.statusChecked = this.itemToEdit.status === 'Active';
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
