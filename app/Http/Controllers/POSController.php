<?php

namespace App\Http\Controllers;
use App\Exceptions\PurchaseSellMismatch;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategeory;
use App\Models\Menu;
use App\Models\User;
use App\Models\Table;
use App\Models\Contact;
use App\Models\BusinessLocation;
use App\Models\DepartmentPoss;
use App\Models\RoomAssign;
use App\Models\ProductVariation;
use App\Models\ProductVariationValue;
use App\Models\Transactions;
use App\Models\TransactionPayment;
use App\Models\SellLineVariation;
use App\Models\OrderType;
use App\Models\ProductStock;
use App\Models\Order;
use App\Models\Brand;
use App\Models\Cousine;
use App\Models\Business;
use App\Models\TransactionSellLine;
use App\Models\ProductATTAssign;
use App\Models\FoodCalculation;
use App\Models\OrderAddress;
use App\Models\Unit;
use App\Models\DrintType;
use App\Models\Printer;
use App\Models\CashRegister;
use App\Models\CashRegisterTransaction;
use App\Services\StationRoutingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class POSController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('pos.dashboard')) {
            abort(403, 'Unauthorized action.');
        }
        $business = Business::first();
        $business_locations = BusinessLocation::forDropdown(false, true);
        $bl_attributes = $business_locations['attributes'];
        $business_locations = $business_locations['locations'];

        $default_location = null;
        if (count($business_locations) == 1) {
            foreach ($business_locations as $id => $name) {
                $default_location = $id;
            }
        }
        // $departments = [];
        $departments = DepartmentPoss::where('status', "Active");
        if($default_location != null)
        {
            $departments->where('location_id', $default_location);
        }
        $departments = $departments->get();
        $employees =  User::join('employees as em', 'users.staff_id', '=', 'em.id')
        ->where('em.status', 'Active')->select('users.first_name', 'users.id')->get();
        $order_types = OrderType::where('status', 'Active')->pluck('name')->toArray();
        $customers = Contact::forCustomerDropdown();
        $tables = Table::where('status', "Active")->get();
        $tables = $tables->transform(function($item) {
            $background = 'background-color: rgb(192, 219, 226)';
            $title = '';
            if($item->available_status == 0)
            {
                $background = 'background-color: rgb(192, 219, 226)';
                $title = 'Available';
            }
            if($item->available_status == 1)
            {
                $background = 'background-color: rgb(242, 149, 245)';
                $title = 'Booked';
            }

            if($item->available_status == 2)
            {
                $background = 'background-color: rgb(193, 192, 226)';
                $title = 'Order';
            }
            return [
                'id' => $item->id,
                'label' => $item->table_name,
                'style' => $background,
                'title' => $title,
            ];
        })->toArray();
        $rooms = RoomAssign::whereIn('checkin_status', [1])->get();
        $rooms = $rooms->transform(function($item) {
            $background = 'background-color: rgb(192, 219, 226)';
            $title = '';
            if($item->status == 0)
            {
                $background = 'background-color: rgb(192, 219, 226)';
                $title = 'Available';
            }
            if($item->status == 2)
            {
                $background = 'background-color: rgb(242, 149, 245)';
                $title = 'Checkin';
            }
            return [
                'id' => $item->id,
                'label' => $item->room_id,
                'style' => $background,
                'title' => $title .'('.$item->room_type.')',
            ];
        })->toArray();
        $products = Product::
            join(
                'product_a_t_t_assigns as ata',
                'products.id',
                '=',
                'ata.product_id'
            )
            ->leftjoin(
                'product_stocks as st',
                'products.id',
                '=',
                'st.product_id'
            )
            ->leftjoin(
                'product_categeories as pc',
                'products.category_id',
                '=',
                'pc.id'
            )
            ->leftjoin(
                'product_categeories as psc',
                'products.sub_category_id',
                '=',
                'psc.id'
            )
            ->leftjoin(
                'drint_types as br',
                'products.drink_type_id',
                '=',
                'br.id'
            )
            ->leftjoin(
                'cousines as cu',
                'products.cousine_id',
                '=',
                'cu.id'
            )
            ->leftjoin('menus as me', 'products.menu_id', '=', 'me.id')
            ->whereIn('ata.product_attr_id', [1,3])
                ->select(
                    'products.id',
                    'products.name',
                    'products.sku_code',
                    'products.barcode',
                    'products.sale_price',
                    'products.sale_price_includ_tax',
                    'products.alert_quantity',
                    'products.discount',
                    'st.qty_available',
                    'products.created_at',
                    'pc.name as category',
                    'psc.name as sub_category',
                    'br.name as brand',
                    'cu.name as cousin',
                    'me.name as menu',
                    'products.image',
                    'products.status',
                    'products.open_stock',
                    'products.enable_stock',
                    'products.stock',
                    'st.qty_available',
                    'products.category_id',
                    'products.sub_category_id',
                    'products.menu_id',
                    'products.drink_type_id'
                )
                ->groupBy('products.id')->get();
        
        $products = $products->transform(function($product) use($business){
            $image_url = ' ';
            if (!empty($product->image)) {
                $image_url = asset(Storage::url(config('constants.product_img_path') . '/' . $product->image));
            } else {
                $image_url = asset('asset/images/no-image.png');
            }
            $variation = [];
            $values = ProductVariationValue::where('product_id', $product->id)->get()->groupBy('product_variation_id');
            $attry = $product->attributes->pluck('product_attr_id')->toArray();
            foreach($values as $var_id => $value)
            {
                $variation_arry = [];
                $variation_type = ProductVariation::find($var_id);
                foreach ($value as $var_value)
                {
                    $check = $variation_type->decimal_value == 1;
                    $value_arr  = [
                        'id' => $var_value->id,
                        'name' => $var_value->name,
                        'price' => $check ? $var_value->selling_price : 0,
                    ];

                    array_push($variation_arry, $value_arr);
                }
                $items_amount = $value->pluck('name_value')->toArray();
                $product_variant = [
                    'product_id' => $product->id,
                    'type' => $variation_type->type, // or "checkbox", "dropdown"
                    'name' => $variation_type->name,
                    'amounts' => $items_amount,
                    'values' => $variation_arry,
                    'varImg' => "https://adminsc.pizzahut.lk//images/mainmenu/52d3aba8-57ac-4dd1-8bbd-9a424e7bd9dd.jpg",
                ];
                array_push($variation, $product_variant);
            }
            $calculation = FoodCalculation::where('product_id', $product->id)->first();

            if(in_array(3, $attry) && isset($calculation) && $business->is_need_food_calculation == 1){
                return [
                    'id' => $product->id,
                    'description' => $product->name,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => 1,
                    'dis' => $product->discount ?? 0,
                    'price' => $product->sale_price ?? $product->sale_price_includ_tax ?? 0,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'availability' => (in_array(3, $attry) && $product->status == 1) || (in_array(1, $attry) && $product->status == 1 && $product->enable_stock == '0')  || (in_array(1, $attry) && $product->status == 1 && $product->enable_stock == 1 && $product->stock > 0) ? 'in stock' : 'out-of-stock',
                    'category' => $product->category,
                    'subCategory' => $product->sub_category,
                    'brand' => $product->brand,
                    'cuisine' => $product->cousin,
                    'menu' => $product->menu,
                    'imageUrl' => $image_url,
                    'variations' => $variation,
                    'category_id' => $product->category_id,
                    'sub_category_id' => $product->sub_category_id,
                    'menu_id' => $product->menu_id,
                    'drink_type_id' => $product->drink_type_id
                ];
            }
            if(in_array(3, $attry) && (!isset($calculation) || isset($calculation))  && $business->is_need_food_calculation == 0)
            {
                return [
                    'id' => $product->id,
                    'description' => $product->name,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => 1,
                    'dis' => $product->discount ?? 0,
                    'price' => $product->sale_price ?? $product->sale_price_includ_tax ?? 0,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'availability' => (in_array(3, $attry) && $product->status == 1) || (in_array(1, $attry) && $product->status == 1 && $product->enable_stock == '0')  || (in_array(1, $attry) && $product->status == 1 && $product->enable_stock == 1 && $product->stock > 0) ? 'in stock' : 'out-of-stock',
                    'category' => $product->category,
                    'subCategory' => $product->sub_category,
                    'brand' => $product->brand,
                    'cuisine' => $product->cousin,
                    'menu' => $product->menu,
                    'imageUrl' => $image_url,
                    'variations' => $variation,
                    'category_id' => $product->category_id,
                    'sub_category_id' => $product->sub_category_id,
                    'menu_id' => $product->menu_id,
                    'drink_type_id' => $product->drink_type_id
                ];
            }
            if(in_array(1, $attry))
            {
                return [
                    'id' => $product->id,
                    'description' => $product->name,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => 1,
                    'dis' => $product->discount ?? 0,
                    'price' => $product->sale_price ?? $product->sale_price_includ_tax ?? 0,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'availability' => (in_array(3, $attry) && $product->status == 1) || (in_array(1, $attry) && $product->status == 1 && $product->enable_stock == '0')  || (in_array(1, $attry) && $product->status == 1 && $product->enable_stock == 1 && $product->stock > 0) ? 'in stock' : 'out-of-stock',
                    'category' => $product->category,
                    'subCategory' => $product->sub_category,
                    'brand' => $product->brand,
                    'cuisine' => $product->cousin,
                    'menu' => $product->menu,
                    'imageUrl' => $image_url,
                    'variations' => $variation,
                    'category_id' => $product->category_id,
                    'sub_category_id' => $product->sub_category_id,
                    'menu_id' => $product->menu_id,
                    'drink_type_id' => $product->drink_type_id
                ];
            }
        })->toArray();
        $products = array_filter($products);
        $category_ids = array_column($products, 'category_id');
        $sub_ids = array_column($products, 'sub_category_id');
        $menu_ids = array_column($products, 'menu_id');
        $type_ids = array_column($products, 'drink_type_id');
        $categories = ProductCategeory::orderBy('name', 'DESC')->whereIn('id', $category_ids)->where('status', 'Active')->get();
        $categories = $categories->transform( function($item) use($sub_ids){
            $sub_category = $item->childs()->whereIn('id', $sub_ids)->get();
            $child_data = [];
            foreach($sub_category as $category)
            {
                $data = [
                    'name' => $category->name,
                    'icon' => "fas fa-gift"
                ];
                array_push($child_data, $data);
            }
            
            return [
                'name' => $item->name,
                'icon' => "fas fa-gift",
                'subcategories' => $child_data
            ];
        })->toArray();
    
        $menus = Menu::whereIn('id', $menu_ids)->where('status', 'Active')->get()->unique();
        $menus = $menus->transform( function($item) {
            return [
                'name' => $item->name,
                'icon' => "fa fa-th-list"
            ];
        })->toArray();
        $drint_type = DrintType::whereIn('id', $type_ids)->where('status', 'Active')->get()->unique();
        $drint_type = $drint_type->transform( function($item) {
            return [
                'name' => $item->name,
                'icon' => "fa fa-th-list"
            ];
        })->toArray();
        $cusiones = Cousine::where('status', 'Active')->get();
        $cusiones = $cusiones->transform( function($item) {
            return [
                'name' => $item->name,
                'icon' => "fa fa-th-list"
            ];
        })->toArray();
        $stock_data = Product::
            join(
                'product_a_t_t_assigns as ata',
                'products.id',
                '=',
                'ata.product_id'
            )
            ->leftjoin(
                'product_stocks as st',
                'products.id',
                '=',
                'st.product_id'
            )
            ->leftjoin(
                'product_categeories as pc',
                'products.category_id',
                '=',
                'pc.id'
            )
            ->leftjoin('menus as me', 'products.menu_id', '=', 'me.id')
            ->whereIn('ata.product_attr_id', [1,2])
                
                ->select(
                    'products.id',
                    'products.name',
                    'products.sku_code',
                    'products.stock',
                    'products.barcode',
                    'products.sale_price',
                    'products.sale_price_includ_tax',
                    'products.alert_quantity',
                    'products.discount',
                    'st.qty_available',
                    'products.created_at',
                    'pc.name as category',
                    'me.name as menu',
                    'products.image'
        )->groupBy('products.id')->get();
   
        $stock_data->transform(function($product) {
            $image_url = ' ';
            if (!empty($product->image)) {
                $image_url = asset(Storage::url(config('constants.product_img_path') . '/' . $product->image));
            } else {
                $image_url = asset('asset/images/no-image.png');
            }
            return [
                'id' => $product->id,
                'description' => $product->name,
                'skuCode' => $product->sku_code,
                'barcode' => $product->barcode ?? 'none',
                'qty' => 1,
                'dis' => $product->discount ?? 0,
                'price' => $product->sale_price ?? $product->sale_price_includ_tax ?? 0,
                'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                'name' => $product->name,
                'availability' => $product->qty_available > 0 ? 'in stock' : 'out-of-stock',
                'category' => $product->category,
                'stocks'  => $product->stock ?? 0,
                'imageUrl' => $image_url,
            ];
        });

        $drafts = Transactions::where('type', 'order')->where('status', 'draft');
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $drafts->whereIn('transactions.created_by', $permitted_users);
        }
        $drafts = $drafts->get();
        $transaction_draft = [];
        foreach($drafts as $draft)
        {
            $order_type = '';
            if($draft->room_id && $draft->order_type == 'Room Order')
            {
                $room_get =  RoomAssign::find($draft->room_id);
                $order_type = $draft->order_type .'(' .$room_get->room_id. ')';
            }
            if($draft->table_id && $draft->order_type == 'Dine in')
            {
                $table =  Table::find($draft->table_id);
                $order_type = $draft->order_type .'(' .$table->table_name. ')';
            }
            if($draft->order_type == 'Take away')
            {
                $order_type = $draft->order_type;
            }
            $transaction = [
                'id' => $draft->id,
                'no' => $draft->invoice_no,
                'location' => $draft->location->name ?? '',
                'location_id' => $draft->location_id,
                'department' => $draft->department->name ?? '',
                'department_id' => $draft->department_id ?? '',
                'employee' => $draft->staff->first_name ?? '',
                'employee_id' => $draft->staff_id ?? '',
                'invoiceNo' => $draft->invoice_no,
                'cusName' => $draft->customer->first_name ?? '',
                'discount' => $draft->discount_amount,
                'tax' => $draft->tax_amount,
                'cus_id' => $draft->contact_id ?? '',
                'room_id' => $draft->room_id ?? '',
                'table_id' => $draft->table_id ?? '',
                'table' => $draft->table->table_name ??  '',
                'mobileNo' => $draft->customer->mobile_no  ?? '',
                'status' => $draft->status == 'draft' ? 'DRAFT' : 'ORDER', // Set status as "Hold" for held invoices
                'products' => [

                ],
                'type' => $order_type,
                'order_type' => $draft->order_type,
                'lineTotal' => $draft->final_total,  
            ];
            foreach($draft->lines_of_sell as $line)
            {
                $product = $line->product;
                $image_url = ' ';
                if (!empty($product->image)) {
                    $image_url = asset(Storage::url(config('constants.product_img_path') . '/' . $product->image));
                } else {
                    $image_url = "https://buffer.com/cdn-cgi/image/w=1000,fit=contain,q=90,f=auto/library/content/images/size/w1200/2023/10/free-images.jpg";
                }
                $product_array = [
                    'line_id' => $line->id,
                    'id' => $product->id,
                    'description' => $product->name,
                    'unit_price' => $line->unit_price,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => $line->quantity,
                    'dis' => $line->discount_amount ?? 0,
                    'price' => $line->unit_price ?? 0,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'availability' => $product->status == 'Active' ? 'in stock' : 'out-of-stock',
                    'category' => $product->mainCategory->name ?? '',
                    'imageUrl' => $image_url,
                    'status' => $line->status,
                    'orderNo' => $line->order_no,
                ];
                array_push($transaction['products'], $product_array);

            }
            array_push($transaction_draft, $transaction);
        }

        $orders = Transactions::where('type', 'order')->whereNotIn('status', ['canceled', 'final', 'draft'])->where('payment_status', 'due');
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $orders->whereIn('transactions.created_by', $permitted_users);
        }
        $orders = $orders->get();
        $transaction_data = [];
        $kot_data = [];
        foreach($orders as $order)
        {
            $order_type = '';
            if($order->room_id && $order->order_type == 'Room Order')
            {
                $room_get =  RoomAssign::find($order->room_id);
                $order_type = $order->order_type .'(' .$room_get->room_id. ')';
            }
            if($order->table_id && $order->order_type == 'Dine in')
            {
                $table =  Table::find($order->table_id);
                $order_type = $order->order_type .'(' .$table->table_name. ')';
            }
            if($order->order_type == 'Take away')
            {
                $order_type = $order->order_type;
            }
            if($order->order_type == 'Online')
            {
                $order_type = $order->order_type;
            }
            $transaction = [
                'id' => $order->id,
                'no' => $order->invoice_no,
                'location' => $order->location->name ?? '',
                'location_id' => $order->location_id,
                'department' => $order->department->name ?? '',
                'department_id' => $order->department_id ?? '',
                'employee' => $order->staff->first_name ?? '',
                'employee_id' => $order->staff_id ?? '',
                'invoiceNo' => $order->invoice_no,
                'cusName' => $order->customer->first_name ?? '',
                'cus_id' => $order->contact_id ?? '',
                'discount' => $order->discount_amount,
                'tax' => $order->tax_amount,
                'room_id' => $order->room_id ?? '',
                'orderNo' => $order->lines_of_sell->first() ? $order->lines_of_sell->first()->order_no : '',
                'table_id' => $order->table_id ?? '',
                'mobileNo' => $order->customer->mobile_no  ?? '',
                'status' => $order->status == 'draft' ? 'DRAFT' : 'ORDER', // Set status as "Hold" for held invoices
                'table' => $order->table->table_name ?? '',
                'status' => "ORDER", // Set status as "Hold" for held invoices
                'products' => [

                ],
                'type' => $order_type,
                'order_type' => $order->order_type,
                'lineTotal' => $order->final_total,
                'time' => $order->updated_at,
                'date' => date('Y-m-d h:i:s', strtotime($order->updated_at))
            ];

            $transaction_kot = [
                'id' => $order->id,
                'no' => $order->invoice_no,
                'location' => $order->location->name ?? '',
                'location_id' => $order->location_id,
                'department' => $order->department->name ?? '',
                'department_id' => $order->department_id ?? '',
                'employee' => $order->staff->first_name ?? '',
                'employee_id' => $order->staff_id ?? '',
                'invoiceNo' => $order->invoice_no,
                'cusName' => $order->customer->first_name ?? '',
                'cus_id' => $order->contact_id ?? '',
                'room_id' => $order->room_id ?? '',
                'orderNo' => $order->lines_of_sell->first() ? $order->lines_of_sell->first()->order_no : '',
                'table_id' => $order->table_id ?? '',
                'mobileNo' => $order->customer->mobile_no  ?? '',
                'status' => $order->status == 'draft' ? 'DRAFT' : 'ORDER', // Set status as "Hold" for held invoices
                'table' => $order->table->table_name ?? '',
                'status' => "ORDER", // Set status as "Hold" for held invoices
                'products' => [

                ],
                'type' => $order_type,
                'order_type' => $order->order_type,
                'lineTotal' => $order->final_total, 
                'time' => $order->updated_at,
                'date' => date('Y-m-d h:i:s', strtotime($order->updated_at))
            ];
            foreach($order->lines_of_sell as $line)
            {
                $product = $line->product;
                $arriy_array = $product->attributes->pluck('product_attr_id')->toArray();
                $image_url = ' ';
                if (!empty($product->image)) {
                    $image_url = asset(Storage::url(config('constants.product_img_path') . '/' . $product->image));
                } else {
                    $image_url = "https://buffer.com/cdn-cgi/image/w=1000,fit=contain,q=90,f=auto/library/content/images/size/w1200/2023/10/free-images.jpg";
                }
                if($line->status != 'canceled')
                {
                    $product_array = [
                        'line_id' => $line->id,
                        'id' => $product->id,
                        'description' => $product->name,
                        'unit_price' => $line->unit_price,
                        'skuCode' => $product->sku_code,
                        'barcode' => $product->barcode,
                        'qty' => $line->quantity,
                        'dis' => $line->discount_amount ?? 0,
                        'price' => $line->unit_price ?? 0,
                        'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                        'name' => $product->name,
                        'availability' => $product->status == 'Active' ? 'in stock' : 'out-of-stock',
                        'category' => $product->mainCategory->name ?? '',
                        'imageUrl' => $image_url,
                        'status' => $line->status
                    ];
                    array_push($transaction['products'], $product_array);
                }
                
                $product_kot_array = [
                    'line_id' => $line->id,
                    'id' => $product->id,
                    'description' => $product->name,
                    'unit_price' => $line->unit_price,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => $line->quantity,
                    'dis' => $line->discount_amount ?? 0,
                    'price' => $line->unit_price ?? 0,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'availability' => $product->status == 'Active' ? 'in stock' : 'out-of-stock',
                    'category' => $product->mainCategory->name ?? '',
                    'imageUrl' => $image_url,
                    'status' => $line->status
                ];
                array_push($transaction_kot['products'], $product_kot_array);
            }
            if($transaction_kot['products'] != [])
            {
                array_push($kot_data, $transaction_kot);
            }
            if($transaction['products'] != [])
            {
                array_push($transaction_data, $transaction); 
            }
           
        }
        $walk_customer = $this->getWalkInCustomer();
        $cancels = Transactions::where('type', 'order')->where('status', 'canceled');
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $cancels->whereIn('transactions.created_by', $permitted_users);
        }
        $cancels = $cancels->get();
        $canceled_data = [];
        foreach($cancels as $cancel)
        {
            $order_type = '';
            if($cancel->room_id && $cancel->order_type == 'Room Order')
            {
                $room_get =  RoomAssign::find($cancel->room_id);
                $order_type = isset($room_get->room_id) ? $cancel->order_type .'(' .$room_get->room_id ?? ''. ')' : $cancel->order_type;
            }
            if($cancel->table_id && $cancel->order_type == 'Dine in')
            {
                $table =  Table::find($cancel->table_id);
                $order_type = $cancel->order_type .'(' .$table->table_name. ')';
            }
            if($cancel->order_type == 'Take away')
            {
                $order_type = $cancel->order_type;
            }
            $transaction = [
                'id' => $cancel->id,
                'no' => $cancel->invoice_no,
                'location' => $cancel->location->name ?? '',
                'location_id' => $cancel->location_id,
                'department' => $cancel->department->name ?? '',
                'department_id' => $cancel->department_id ?? '',
                'employee' => $cancel->staff->first_name ?? '',
                'employee_id' => $cancel->staff_id ?? '',
                'invoiceNo' => $cancel->invoice_no,
                'cusName' => $cancel->customer->first_name ?? '',
                'cus_id' => $cancel->contact_id ?? '',
                'room_id' => $cancel->room_id ?? '',
                'orderNo' => $cancel->lines_of_sell->first() ? $cancel->lines_of_sell->first()->order_no : '',
                'table_id' => $cancel->table_id ?? '',
                'mobileNo' => $cancel->customer->mobile_no  ?? '',
                'status' => $cancel->status == 'draft' ? 'DRAFT' : 'ORDER', // Set status as "Hold" for held invoices
                'table' => $cancel->table->table_name ?? '',
                'status' => "ORDER", // Set status as "Hold" for held invoices
                'products' => [

                ],
                'type' => $order_type,
                'order_type' => $cancel->order_type,
                'lineTotal' => $cancel->final_total, 
            ];
            foreach($cancel->lines_of_sell as $line)
            {
                $product = $line->product;
                $arriy_array = $product->attributes->pluck('product_attr_id')->toArray();
                $image_url = ' ';
                if (!empty($product->image)) {
                    $image_url = asset(Storage::url(config('constants.product_img_path') . '/' . $product->image));
                } else {
                    $image_url = "https://buffer.com/cdn-cgi/image/w=1000,fit=contain,q=90,f=auto/library/content/images/size/w1200/2023/10/free-images.jpg";
                }
                $product_arr = [
                    'line_id' => $line->id,
                    'id' => $product->id,
                    'description' => $product->name,
                    'unit_price' => $line->unit_price,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => $line->quantity,
                    'dis' => $line->discount_amount ?? 0,
                    'price' => $line->unit_price ?? 0,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'availability' => $product->status == 'Active' ? 'in stock' : 'out-of-stock',
                    'category' => $product->mainCategory->name ?? '',
                    'imageUrl' => $image_url,
                    'status' => $line->status
                ];
                array_push($transaction['products'], $product_arr);
            }
            array_push($canceled_data, $transaction); 
        } 
        $existing_customers = Contact::orderBy('id')->where('contact_type_id', 1)->where('status', "Active")->get();

        $existing_customers =  $existing_customers->transform(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->first_name,
                'mobile' => $item->mobile_no ?? '7777777',
                'place' => $item->city,
                'country' => $item->country,
                'age' => 'N/A',
            ];
        })->toArray();
        // $draft = Transactions::join('')
        return view('pos.index', compact('business_locations', 'default_location', 
        'employees', 'customers', 'departments', 'order_types', 'walk_customer','business','stock_data'))
        ->with('products', json_encode($products))
        ->with('menus',json_encode($menus,JSON_NUMERIC_CHECK))
        ->with('cusiones',json_encode($cusiones,JSON_NUMERIC_CHECK))
        ->with('brands',json_encode($drint_type,JSON_NUMERIC_CHECK))
        ->with('categories',json_encode($categories,JSON_NUMERIC_CHECK))
        ->with('tables',json_encode($tables,JSON_NUMERIC_CHECK))
        ->with('rooms',json_encode($rooms,JSON_NUMERIC_CHECK))
        ->with('transaction_data',json_encode($transaction_data,JSON_NUMERIC_CHECK))
        ->with('transaction_draft',json_encode($transaction_draft,JSON_NUMERIC_CHECK))
        ->with('orders',json_encode($kot_data,JSON_NUMERIC_CHECK))
        ->with('canceled_data',json_encode($canceled_data,JSON_NUMERIC_CHECK))
        ->with('existing_customers',json_encode($existing_customers,JSON_NUMERIC_CHECK));
    }

    public function draft(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $exchange_rate = 1;
            $transaction_id = $request->transaction_id;
            $exit_transaction = Transactions::where('contact_id', $request->contact_id)
            ->where(function($qu)  use($request){
                $qu->where('order_type', 'Dine in')->where('table_id', $request->table_id);
            })
            ->orWhere(function($qur)  use($request){
                $qur->where('order_type', 'Take away');
            })
            ->orWhere(function($q) use($request){
                $q->where('order_type', 'Room Order')->where('room_id', $request->room_id);
            })->where('status', 'draft')->first();
            if(isset($transaction_id))
            {
                $transaction  = Transactions::findOrFail($transaction_id);
                $transaction->lines_of_sell()->delete();
            }
            else
            {
                $transaction = Transactions::where('contact_id', $request->contact_id)
                ->where(function($qu)  use($request){
                    $qu->where('order_type', 'Dine in')->where('table_id', $request->table_id);
                })
                ->orWhere(function($qur)  use($request){
                    $qur->where('order_type', 'Take away');
                })
                ->orWhere(function($q) use($request){
                    $q->where('order_type', 'Room Order')->where('room_id', $request->room_id);
                })->where('status', 'draft')->first();
                if(!isset($transaction))
                {
                    $transaction  = new Transactions();
                    $transaction->created_by = $user_id;
                    $transaction->type = 'order';
                }  
            }
            $transaction->status =  'draft';
            $transaction->location_id = $request->location_id;
            $transaction->department_id =  $request->department_id;
            $transaction->contact_id = $request->contact_id;
            $transaction->staff_id  = $request->staff_id;
            $transaction->room_id  = $request->room_id;
            $transaction->table_id  = $request->table_id;
            $transaction->is_include  = $request->is_include == 1 ? $request->is_include :0;
            $transaction->transaction_date  = $request->transaction_date;
            $transaction->discount_type  = $request->discount_type ?? 'percentage'; 
            $transaction->discount_amount  += $request->discount_amount ?? 0; 
            $transaction->tax_amount  += $request->tax_amount;
            $transaction->final_total  += $this->num_uf($request->final_total, null)*$exchange_rate;
            $transaction->order_type = $request->order_type;
            // $transaction->total_before_tax  = $this->num_uf($request->final_total, null)*$exchange_rate;
            // $transaction->discount_amount = 0;
            $transaction->payment_status = 'due';
            
           
            DB::beginTransaction();
            $transaction->save();
            if(!isset($exit_transaction)) {
                $invoice_no = $this->generateInvoiceNo($transaction->id);
                $transaction->invoice_no = $invoice_no;
                $transaction->save();
            }
            else
            {
                $transaction->invoice_no = $exit_transaction->invoice_no;
                $transaction->save();
            }
            $sell_lines = [];
            $products = $request->input('products');
            foreach ($products as $product) {
                $new_sell_line = [
                'product_id' => $product['product_id'],
                'quantity'=> $this->num_uf($product['quantity'], []),
                'unit_price' => $this->num_uf($product['unit_price'], [])*$exchange_rate,
                'discount_amount' => $this->num_uf($product['discount_amount'], []),
                'discount_type' => $product['discount_type'],
                'sub_total' => $this->num_uf($product['sub_total'], [])*$exchange_rate,
                
                ];
                $sell_lines[] = $new_sell_line;

                if(isset($product['variants']))
                {
                    foreach($product['variants'] as $variant)
                    {
                        $new_variant_line = new SellLineVariation();
                        $new_variant_line->transaction_id = $transaction->id;
                        $new_variant_line->product_id = $product['product_id'];
                        $new_variant_line->value = $variant['value'];
                        $new_variant_line->amount = $variant['amount'];
                        $new_variant_line->save();
                    }
                }
            }
            if (!empty($sell_lines)) {
                $transaction->lines_of_sell()->createMany($sell_lines);
            }

            $order_type = '';
            if($transaction->room_id && $transaction->order_type == 'Room Order')
            {
                $room_get =  RoomAssign::find($transaction->room_id);
                $order_type = $transaction->order_type .'(' .$room_get->room_id. ')';
            }
            if($transaction->table_id && $transaction->order_type == 'Dine in')
            {
                $table =  Table::find($transaction->table_id);
                $order_type = $transaction->order_type .'(' .$table->table_name. ')';
            }
            if($transaction->order_type == 'Take away')
            {
                $order_type = $transaction->order_type;
            }

            if($transaction->order_type == 'Online')
            {
                $order_type = $transaction->order_type;
            }

            $transaction_data = [
                'id' => $transaction->id,
                'no' => $transaction->invoice_no,
                'location' => $transaction->location->name ?? '',
                'location_id' => $transaction->location_id,
                'department' => $transaction->department->name ?? '',
                'department_id' => $transaction->department_id ?? '',
                'employee' => $transaction->staff->first_name ?? '',
                'employee_id' => $transaction->staff_id ?? '',
                'invoiceNo' => $transaction->invoice_no,
                'cusName' => $transaction->customer->first_name ?? '',
                'discount' => $transaction->discount_amount,
                'tax' => $transaction->tax_amount,
                'cus_id' => $transaction->contact_id ?? '',
                'room_id' => $transaction->room_id ?? '',
                'table_id' => $transaction->table_id ?? '',
                'table' => $transaction->table->table_name ?? '',
                'mobileNo' => $transaction->customer->mobile_no  ?? '',
                'status' => "Draft", // Set status as "Hold" for held invoices
                'products' => [

                ],
                'type' => $order_type,
                'order_type' => $transaction->order_type,
                'lineTotal' => $transaction->final_total,
                'time' => $transaction->updated_at  
            ];
            foreach($transaction->lines_of_sell as $line)
            {
                $product = $line->product;
                $image_url = ' ';
                if (!empty($product->image)) {
                    $image_url = asset(Storage::url(config('constants.product_img_path') . '/' . $product->image));
                } else {
                    $image_url = asset('asset/images/no-image.png');
                }
                $product_array = [
                    'id' => $product->id,
                    'description' => $product->name,
                    'unit_price' => $line->unit_price,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => $line->quantity,
                    'dis' => $line->discount_amount ?? 0,
                    'price' => $line->unit_price ?? 0,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'availability' => $product->status == 'Active' ? 'in stock' : 'out-of-stock',
                    'category' => $product->mainCategory->name ?? '',
                    'imageUrl' => $image_url,
                    'status' => $line->status,
                    'orderNo' => $line->order_no
                ];
                array_push($transaction_data['products'], $product_array);

            }
            DB::commit();
            $output = ['success' => 1,
                            'msg' => __('Add Success'),
                            'data' => $transaction_data,
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' =>  $e->getMessage()
                        ];
        }

        return $output;
    }

    public function order(Request $request)
    {
        // dd($request->all());
        try {
            $user_id = auth()->user()->id;
            $exchange_rate = 1;
            $business = Business::first();
            $transaction_id = $request->transaction_id;
            
            // Only look for existing transaction if NOT Take away
            $exit_transaction = null;
            if($request->order_type != 'Take away') {
                $exit_transaction = Transactions::whereNotIn('status', ['canceled', 'completed', 'final', 'draft'])
                ->where('contact_id', $request->contact_id)
                ->where(function($qu) use($request){
                    if($request->order_type == 'Dine in') {
                        $qu->where('order_type', 'Dine in')->where('table_id', $request->table_id);
                    }
                    elseif($request->order_type == 'Room Order') {
                        $qu->where('order_type', 'Room Order')->where('room_id', $request->room_id);
                    }
                })->first();
            }
          
            $type = '';
            if(isset($transaction_id))
            {
                $transaction  = Transactions::findOrFail($transaction_id);
                $type = 'edit';
            }
            else
            {
                // Only look for existing transaction if NOT Take away
                $transaction = null;
                if($request->order_type != 'Take away') {
                    $transaction = Transactions::where('contact_id', $request->contact_id)->whereNotIn('status', ['canceled', 'completed', 'final', 'draft'])
                    ->where(function($qu) use($request){
                        if($request->order_type == 'Dine in') {
                            $qu->where('order_type', 'Dine in')->where('table_id', $request->table_id);
                        }
                        elseif($request->order_type == 'Room Order') {
                            $qu->where('order_type', 'Room Order')->where('room_id', $request->room_id);
                        }
                    })->first();
                }
                
                if(!isset($transaction))
                {
                    $transaction  = new Transactions();
                    $transaction->created_by = $user_id;
                    $transaction->type = 'order';
                }  
            }
            $transaction->status =  'ordered';
            $transaction->location_id = $request->location_id;
            $transaction->department_id =  $request->department_id;
            $transaction->contact_id = $request->contact_id;
            $transaction->staff_id  = $request->staff_id;
            $transaction->room_id  = $request->room_id;
            $transaction->is_include  = $request->is_include == 1 ? $request->is_include :0;
            $transaction->table_id  = $request->table_id;
            $transaction->transaction_date  = $request->transaction_date;
            $transaction->discount_type  = $request->discount_type ?? 'percentage'; 
            $transaction->discount_amount  += $request->discount_amount ?? 0; 
            $transaction->tax_amount  += $request->tax_amount;
            $transaction->order_type = $request->order_type;
            $transaction->final_total  += $this->num_uf($request->final_total, null)*$exchange_rate;
            // $transaction->total_before_tax  = $this->num_uf($request->final_total, null)*$exchange_rate;
            // $transaction->discount_amount = 0;
            $transaction->payment_status = 'due';
            
            $order_no = $this->generateOrderNumber();
            DB::beginTransaction();
            $transaction->save();
            if(!isset($exit_transaction)) {
                $invoice_no = $this->generateInvoiceNo($transaction->id);
                $transaction->invoice_no = $invoice_no;
                $transaction->save();
            }
            else
            {
                $transaction->invoice_no = $exit_transaction->invoice_no;
                $transaction->save();
                $order = new Order();
                $order->transaction_id = $transaction->id;
                $order->order_no = $order_no;
                $order->save();
            }

            if($request->table_id)
            {
                $table = Table::find($request->table_id);
                $table->available_status = 2;
                $table->save();
            }
            

            $sell_lines = [];
            $edit_ids = [];
            $check = [];
            $orderNo = $order_no ? $order_no : $exit_transaction->lines_of_sell->first()->order_no ?? '';
            $products = $request->input('products');
            foreach ($products as $product) {
                $sell_line = TransactionSellLine::where('transaction_id', $transaction->id)
                ->where('product_id', $product['product_id'])->whereNotIn('status', ['canceled'])->first();
                if (isset($sell_line)) {
                    // $edit_ids[] = $product['product_id'];
                    $this->editSellLine($product, $transaction, $sell_line);
                }
                else {
                    $new_sell_line = [
                    'product_id' => $product['product_id'],
                    'quantity'=> $this->num_uf($product['quantity'], []),
                    'unit_price' => $this->num_uf($product['unit_price'], [])*$exchange_rate,
                    'discount_amount' => $this->num_uf($product['discount_amount'], []),
                    'discount_type' => $product['discount_type'],
                    'sub_total' => $this->num_uf($product['sub_total'], [])*$exchange_rate,
                    'order_no' => $order_no ? $order_no : $exit_transaction->lines_of_sell->first()->order_no ?? ''
                    ];
                    $sell_lines[] = $new_sell_line;
                    $product_line = Product::find($product['product_id']);
                    if($product_line->is_kot == 1)
                    {
                        array_push($check, $product_line->id);
                    } 
                    if ($transaction->status == 'ordered') {
                        //if status received update existing quantity
                        $arrays = ProductATTAssign::where('product_id', $product['product_id'])
                        ->pluck('product_attr_id')->toArray();
                        if(in_array(1, $arrays) && $product_line->enable_stock == 1)
                        {
                            $this->decreaseProductQuantity(
                                $product['product_id'],
                                $transaction->location_id,
                                $this->num_uf($product['quantity'])
                            );

                            $this->decreaseProductStock($product['product_id'],$this->num_uf($product['quantity']));
                        }
                        if(in_array(3, $arrays) && $business->is_need_food_calculation == 1)
                        {
                            $this->decreaseFoodQuantity(
                                $product['product_id'],
                                $transaction->location_id,
                                $this->num_uf($product['quantity'])
                            );
                        }
                       
                    }
                    if(isset($product['variants']))
                    {
                        foreach($product['variants'] as $variant)
                        {
                            $new_variant_line = new SellLineVariation();
                            $new_variant_line->transaction_id = $transaction->id;
                            $new_variant_line->product_id = $product['product_id'];
                            $new_variant_line->value = $variant['value'];
                            $new_variant_line->amount = $variant['amount'];
                            $new_variant_line->save();
                        }
                    }

                }
            }

            $deleted_lines = [];
            if (!empty($edit_ids)) {
                $deleted_lines = TransactionSellLine::where('transaction_id', $transaction->id)->whereNotIn('id', $edit_ids)->select('id')->get()->toArray();
                $this->deleteSellLines($deleted_lines, $transaction->location_id);
            }
            if (!empty($sell_lines)) {
                $transaction->lines_of_sell()->createMany($sell_lines);
            }

            if($transaction->order_type == 'Take away')
            {
                $shipment = $request->shipment;
                $contact = $transaction->customer;
                $address = new OrderAddress();
                $address->transaction_id = $transaction->id;
                $address->contact_id = $contact->id;

                if($request->address_type == 'default')
                {
                    $address->address_one = $contact->address_one;
                    $address->address_two = $contact->address_two;
                    $address->city = $contact->city;
                    $address->state = $contact->state;
                    $address->zipcode = $contact->zip_code;
                    $address->country = $contact->country;
                }
                else
                {
                    $address->address_one = $shipment['address_one'] ?? '';
                    $address->address_two = $shipment['address_two'];
                    $address->city = $shipment['city'];
                    $address->state = $shipment['state'];
                    $address->zipcode = $shipment['zip_code'];
                    $address->country = $shipment['country'];
                }
                $address->save();
            }
            $order_type = '';
            if($transaction->room_id && $transaction->order_type == 'Room Order')
            {
                $room_get =  RoomAssign::find($transaction->room_id);
                $order_type = $transaction->order_type .'(' .$room_get->room_id. ')';
            }
            if($transaction->table_id && $transaction->order_type == 'Dine in')
            {
                $table =  Table::find($transaction->table_id);
                $order_type = $transaction->order_type .'(' .$table->table_name. ')';
            }
            if($transaction->order_type == 'Take away')
            {
                $order_type = $transaction->order_type;
            }
            
            $receipt_details = '';

            if($business->printer_display !== "KotDisplay")
            {
                $transaction->status = 'printed';
                $transaction->save();
                $receipt_details = $this->receiptContent($transaction->location_id, $transaction->id,$check, null,$orderNo, $products);
            }
            $transaction_data = [
                'id' => $transaction->id,
                'no' => $transaction->invoice_no,
                'location' => $transaction->location->name ?? '',
                'mobileNo' => $transaction->customer->mobile_no  ?? '',
                'location_id' => $transaction->location_id,
                'department' => $transaction->department->name ?? '',
                'department_id' => $transaction->department_id ?? '',
                'employee' => $transaction->staff->first_name ?? '',
                'employee_id' => $transaction->staff_id ?? '',
                'invoiceNo' => $transaction->invoice_no,
                'discount' => $transaction->discount_amount,
                'tax' => $transaction->tax_amount,
                'cusName' => $transaction->customer->first_name ?? '',
                'cus_id' => $transaction->contact_id ?? '',
                'room_id' => $transaction->room_id ?? '',
                'table_id' => $transaction->table_id ?? '',
                'table' => $transaction->table->table_name ?? '',
                'status' => "ORDER", // Set status as "Hold" for held invoices
                'products' => [

                ],
                'type' => $order_type,
                'order_type' => $transaction->order_type,
                'lineTotal' => $transaction->final_total,
                'orderNo' => $transaction->lines_of_sell->first() ? $transaction->lines_of_sell->first()->order_no : '',
                'test_type' => $type,
                'time' => $transaction->updated_at,
                'date' => date('Y-m-d h:i:s', strtotime($transaction->updated_at))
            ];
            foreach($transaction->lines_of_sell as $line)
            {
                $product = $line->product;
                $arriy_array = $product->attributes->pluck('product_attr_id')->toArray();
                $image_url = ' ';
                if (!empty($product->image)) {
                    $image_url = asset(Storage::url(config('constants.product_img_path') . '/' . $product->image));
                } else {
                    $image_url = asset('asset/images/no-image.png');
                }
                $product_array = [
                    'line_id' => $line->id,
                    'id' => $product->id,
                    'description' => $product->name,
                    'unit_price' => $line->unit_price,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => $line->quantity,
                    'dis' => $line->discount_amount ?? 0,
                    'price' => $line->unit_price ?? 0,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'availability' => $product->status == 'Active' ? 'in stock' : 'out-of-stock',
                    'category' => $product->mainCategory->name ?? '',
                    'imageUrl' => $image_url,
                    'status' => $line->status,
                ];
                array_push($transaction_data['products'], $product_array);

            }
            DB::commit();

            // Dynamic station routing: create per-station tickets (KOT/BOT/DOT/...)
            // Failure here must NOT fail the order.
            try {
                app(StationRoutingService::class)
                    ->createTicketsForTransaction($transaction->fresh(), $user_id);
            } catch (\Throwable $stationEx) {
                \Log::warning('Station routing failed for transaction ' . $transaction->id . ': ' . $stationEx->getMessage());
            }

            $output = ['success' => 1,
                            'msg' => __('Add Success'),
                            'data' => $transaction_data,
                            'receipt' => $receipt_details
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => 0,
                            'msg' =>  $e->getMessage()
                        ];
        }
        return $output;
    }

    public function generateInvoiceNo($id)
    {
        $prefix = date('y');
        $count = str_pad($id, 6, '0', STR_PAD_LEFT);

        //Prefix + count
        $invoice_no = $prefix  .'-'. $count;

        return $invoice_no;
    }

    private function num_uf($input_number, $currency_details = [])
    {
        $thousand_separator  = '';
        $decimal_separator  = '';

        if (!empty($currency_details)) {
            $thousand_separator = $currency_details->thousand_separator;
            $decimal_separator = $currency_details->decimal_separator;
        } else {
            $thousand_separator = ',';
            $decimal_separator = '.';
        }

        $num = str_replace($thousand_separator, '', $input_number);
        $num = str_replace($decimal_separator, '.', $num);

        return (float)$num;
    }

    public function addPaidorder(Request $request)
    {
        try {

            $now = Carbon::now();

            $contactId = DB::table('bookings')
                        ->join('booking_rooms', 'bookings.id', '=', 'booking_rooms.booking_id')
                        ->join('room_assigns', 'booking_rooms.room_no', '=', 'room_assigns.room_id')
                        ->where('room_assigns.id', $request->room_id)
                        ->where('booking_rooms.check_in_at', '<=', $now)
                        ->where('booking_rooms.check_out_at', '>=', $now)
                        ->value('bookings.contact_id');
            if($contactId) {
                $request->merge(['contact_id' => $contactId]);
            } 
            $user_id = auth()->user()->id;
            $exchange_rate = 1;
            $business = Business::first();
            $transaction_id = $request->transaction_id;
            $exit_transaction = Transactions::where('contact_id', $request->contact_id)->whereNotIn('status', ['canceled', 'completed', 'final', 'draft'])
            ->where(function($qu)  use($request){
                $qu->where('order_type', $request->order_type)->where('table_id', $request->table_id);
                $qu->orwhere('order_type', $request->order_type);
                $qu->orwhere('order_type', $request->order_type)->where('room_id', $request->room_id);
            })->first();
            $type = '';
            $before_status = '';
            if(isset($transaction_id))
            {
                $transaction  = Transactions::findOrFail($transaction_id);
                $type = 'edit';
                $before_status = 'edited';
            }
            else
            {
                $transaction = Transactions::where('contact_id', $request->contact_id)->whereNotIn('status', ['canceled', 'completed', 'final', 'draft'])
                ->where(function($qu)  use($request){
                    $qu->where('order_type', $request->order_type)->where('table_id', $request->table_id);
                    $qu->orwhere('order_type', $request->order_type);
                    $qu->orwhere('order_type', $request->order_type)->where('room_id', $request->room_id);
                })->first();
                if(!isset($transaction))
                {
                    $transaction  = new Transactions();
                    $transaction->created_by = $user_id;
                    $transaction->type = 'order';
                }  
            }
            $transaction->status =  'final';
            $transaction->location_id = $request->location_id;
            $transaction->department_id =  $request->department_id;
            $transaction->contact_id = $request->contact_id;
            $transaction->staff_id  = $request->staff_id;
            $transaction->room_id  = $request->room_id;
            $transaction->is_include  = $request->is_include == 1 ? $request->is_include :0;
            $transaction->table_id  = $request->table_id;
            $transaction->transaction_date  = $request->transaction_date;
            $transaction->discount_type  = $request->discount_type ?? 'percentage'; 
            $transaction->discount_amount  = $request->discount_amount ?? 0; 
            $transaction->tax_amount  = $request->tax_amount;
            $transaction->order_type = $request->order_type;
            $transaction->final_total  = $this->num_uf($request->final_total, null)*$exchange_rate;
            $transaction->service_charge = $request->service_charge;
            // $transaction->total_before_tax  = $this->num_uf($request->final_total, null)*$exchange_rate;
            // $transaction->discount_amount = 0;
            $transaction->details = $request->sellNote;
            $transaction->payment_status = 'due';
            if($request->table_id)
            {
                $table = Table::find($request->table_id);
                $table->available_status = 0;
                $table->save();
            }
            $order_no = $this->generateOrderNumber();
            DB::beginTransaction();
            $transaction->save();
            if(!isset($exit_transaction)) {
                $invoice_no = $this->generateInvoiceNo($transaction->id);
                $transaction->invoice_no = $invoice_no;
                $transaction->save();
            }

            $sell_lines = [];
            $products = $request->input('products');
            $transaction->lines_of_sell()->delete();
            foreach ($products as $product) {
                $new_sell_line = [
                'product_id' => $product['product_id'],
                'quantity'=> $this->num_uf($product['quantity'], []),
                'unit_price' => $this->num_uf($product['unit_price'], [])*$exchange_rate,
                'discount_amount' => $this->num_uf($product['discount_amount'], []),
                'discount_type' => $product['discount_type'],
                'sub_total' => $this->num_uf($product['sub_total'], [])*$exchange_rate,
                'order_no' => $order_no ? $order_no : $exit_transaction->lines_of_sell->first()->order_no ?? ''
                ];
                $sell_lines[] = $new_sell_line;
                $product_line = Product::find($product['product_id']);   
                if ($transaction->status == 'final' && $before_status == '') {
                    //if status received update existing quantity
                    $arrays = ProductATTAssign::where('product_id', $product['product_id'])
                    ->pluck('product_attr_id')->toArray();
                    if(in_array(1, $arrays) && $product_line->enable_stock == 1)
                    {
                        $this->decreaseProductQuantity(
                            $product['product_id'],
                            $transaction->location_id,
                            $this->num_uf($product['quantity'])
                        );

                        $this->decreaseProductStock($product['product_id'],$this->num_uf($product['quantity']));
                    }
                    if(in_array(3, $arrays))
                    {
                        $this->decreaseFoodQuantity(
                            $product['product_id'],
                            $transaction->location_id,
                            $this->num_uf($product['quantity'])
                        );
                    }

                    if($transaction->order_type == 'Take away')
                    {
                        $shipment = $request->shipment;
                        $contact = $transaction->customer;
                        $address = new OrderAddress();
                        $address->transaction_id = $transaction->id;
                        $address->contact_id = $contact->id;

                        if($request->address_type == 'default')
                        {
                            $address->address_one = $contact->address_one;
                            $address->address_two = $contact->address_two;
                            $address->city = $contact->city;
                            $address->state = $contact->state;
                            $address->zipcode = $contact->zip_code;
                            $address->country = $contact->country;
                        }
                        else
                        {
                            $address->address_one = $shipment['address_one'] ?? '';
                            $address->address_two = $shipment['address_two'];
                            $address->city = $shipment['city'];
                            $address->state = $shipment['state'];
                            $address->zipcode = $shipment['zip_code'];
                            $address->country = $shipment['country'];
                        }
                        $address->save();
                    }
                   
                }
                if(isset($product['variants']))
                {
                    foreach($product['variants'] as $variant)
                    {
                        $new_variant_line = new SellLineVariation();
                        $new_variant_line->transaction_id = $transaction->id;
                        $new_variant_line->product_id = $product['product_id'];
                        $new_variant_line->value = $variant['value'];
                        $new_variant_line->amount = $variant['amount'];
                        $new_variant_line->save();
                    }
                }
            }
            $payments = $request->input('payment');
            $paymentCount = count($payments);
            if ($transaction->status == 'final') {
                foreach ($payments as $payment) {

                $paidamount = ($paymentCount > 1) ? $this->num_uf($payment['amount']) : $this->num_uf($request->final_total);

                    TransactionPayment::create([
                        'transaction_id' => $transaction->id,
                        // 'amount' => $payment['method'] !==  'credit' ? $this->num_uf($request->final_total) : 0,
                        'amount' => $payment['method'] !==  'credit' ? $paidamount : 0,
                        'customer_paid' => $payment['method'] !==  'credit' ? $this->num_uf($payment['amount']) : 0,
                        'credit_amount' => $payment['method'] ==  'credit' ? $this->num_uf($payment['amount']) : null,
                        'method' => $payment['method'],
                        'card_transaction_number' => $payment['card_transaction_number'] ?? null,
                        'card_number' => $payment['card_number'] ?? null,
                        'card_type' => $payment['card_type'] ?? 'visa',
                        'card_holder_name' => $payment['card_holder_name'] ?? null,
                        'card_month' => $payment['card_month'] ?? null,
                        'card_security' => $payment['card_security'] ?? null,
                        'cheque_number' => $payment['cheque_number'] ?? null,
                        'cheque_issued_date' => $payment['cheque_issued_date'] ?? null,
                        'cheque_due_date' => $payment['cheque_due_date'] ?? null,
                        'bank_account_number' => $payment['bank_account_number'] ?? null,
                        'note' => $payment['note'] ?? null,
                        'payment_ref_no' => $this->generateReferenceNumber(),
                        'payment_status' =>  null,
                    ]);
                }
                //update payment status
                $this->updatePaymentStatus($transaction->id, $transaction->final_total);
            }
            $this->cashRegisterUpdate($transaction, $payments);
            if (!empty($sell_lines)) {
                $transaction->lines_of_sell()->createMany($sell_lines);
            }
            $receipt_details = $this->receiptContent($transaction->location_id, $transaction->id,null,'receipt');
            DB::commit();

            try {
                app(StationRoutingService::class)
                    ->createTicketsForTransaction($transaction->fresh(), auth()->id());
            } catch (\Throwable $stationEx) {
                \Log::warning('Station routing failed for transaction ' . $transaction->id . ': ' . $stationEx->getMessage());
            }

            $output = ['success' => 1,
                            'msg' => __('Add Success'),
                            'receipt' => $receipt_details,
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            $output = ['success' => 0,
                            'msg' =>  $e->getMessage()
                        ];
        }

        return $output;
    }

    private function generateReferenceNumber()
    {
        $prefix = 'TP';
        $ref_count = rand(1000,9999);
        $ref_digits =  str_pad($ref_count, 4, 0, STR_PAD_LEFT);

        $ref_year = \Carbon::now()->year;
        $ref_number = $prefix . $ref_year . '/' . $ref_digits;

        return $ref_number;
    }

    private function cashRegisterUpdate($transaction, $payments)
    {
        $user_id = auth()->user()->id;
        $register =  CashRegister::where('user_id', $user_id)
                                ->where('status', 'open')
                                ->first();
        if(!isset($register))
        {
            return false;
        } 
        $payments_formatted = [];
        foreach ($payments as $payment) {
            $payments_formatted[] = new CashRegisterTransaction([
                    'amount' => $this->num_uf($payment['amount']),
                    'pay_method' => $payment['method'],
                    'type' => 'credit',
                    'transaction_type' => 'sell',
                    'transaction_id' => $transaction->id
                ]);
        }

        if (!empty($payments_formatted)) {
            $register->cash_register_transactions()->saveMany($payments_formatted);
        }

        return true;
    }
    private function updatePaymentStatus($transaction_id, $final_amount = null)
    {
        $status = $this->calculatePaymentStatus($transaction_id, $final_amount);
        Transactions::where('id', $transaction_id)
            ->update(['payment_status' => $status]);

        return $status;
    }

    private function calculatePaymentStatus($transaction_id, $final_amount = null)
    {
        $total_paid = $this->getTotalPaid($transaction_id);

        if (is_null($final_amount)) {
            $final_amount = Transactions::find($transaction_id)->final_total;
        }
        $transaction = Transactions::find($transaction_id);

        $status = 'due';
        if ($final_amount != 0 && $total_paid == 0 &&  $transaction->credit_note == 0) {
            $status = 'due';
        } elseif ($final_amount > $total_paid) {
            $status = 'partial';
        } else {
            $status = 'paid';
        }

        return $status;
    }

    private function getTotalPaid($transaction_id)
    {
        $total_paid = TransactionPayment::where('transaction_id', $transaction_id)
            ->select(DB::raw('SUM(amount) as total_paid'))
            ->first()
            ->total_paid;

        return $total_paid;
    }

    private function generateOrderNumber()
    {
        $prefix = 'TK';
        $ref_count = rand(1000,9999);
        $ref_digits =  str_pad($ref_count, 4, 0, STR_PAD_LEFT);

        $ref_year = \Carbon::now()->year;
        $ref_number = $prefix . $ref_year . '-' . $ref_digits;

        return $ref_number;
    }

    private function getWalkInCustomer()
    {
        $contact = Contact::where('contact_type_id', 1)
                    ->where('is_default', 1)
                    ->where('status', 'Active')
                    ->select(['id', 'first_name'])
                    ->first();

        if (!empty($contact)) {
            return $contact->toArray();
        }

        // The pos view dereferences $walk_customer['id'] in 6 places, so a
        // false return crashes "Trying to access array offset on false".
        // Fall back to the first active customer; if none, a synthetic stub.
        $fallback = Contact::where('contact_type_id', 1)
                    ->where('status', 'Active')
                    ->select(['id', 'first_name'])
                    ->orderBy('id')
                    ->first();

        if (!empty($fallback)) {
            return $fallback->toArray();
        }

        return ['id' => 0, 'first_name' => 'Walk-in customer'];
    }

    public function decreaseProductQuantity($product_id, $location_id, $new_quantity, $old_quantity = 0)
    {
        $qty_difference = $new_quantity - $old_quantity;
        // $this->mapPurchaseSell($product_id, $location_id, 'purchase');
        //Decrement Quantity in variations location table
        ProductStock::where('product_id', $product_id)
            ->where('location_id', $location_id)
            ->decrement('qty_available', $qty_difference);
        
        return true;
    }

    private function decreaseProductStock($product_id, $quantity, $old_quantity = 0)
    {
        $stock_difference = $quantity - $old_quantity;
        
        $product = Product::where('id', $product_id)
            ->decrement('stock', $stock_difference);

        return true;
    }

    private function receiptContent(
        $location_id,
        $transaction_id,
        $check,
        $type = null,
        $orderNo = null,
        $products = [],
        $printer_type = null
    ) {
    
        $output = ['is_enabled' => false,
                    'print_type' => 'browser',
                    'html_content' => null,
                    'printer_config' => [],
                    'data' => []
                ];
        $transaction = Transactions::where('id', $transaction_id)
        ->with(['sell_lines', 'sell_lines.product'])->first();
        $location_details = BusinessLocation::find($location_id);

        $output['is_enabled'] = true;
        $business_details = Business::first();
        $layout = 'receipt';
        //If print type browser - return the content, printer - return printer config data, and invoice format config
        // if ($receipt_printer_type == 'printer') {
        //     $output['print_type'] = 'printer';
        //     $output['printer_config'] = $this->businessUtil->printerConfig($business_id, $location_details->printer_id);
        //     $output['data'] = $receipt_details;
        // } else {
            $output['check'] = $check;
            $output['html_content'] = view('pos.receipt.receipt', compact('transaction', 'location_details', 'business_details', 'type', 'orderNo', 'products'))->render();    
        // }
        // $output['print_type'] = 'printer';
        // $output['printer_config'] = $this->printerConfig($layout);
        // $output['data'] = $this->receipt_details($transaction, $location_details, $business_details);
        return $output;
    }

    // public function editSellLine($product, $transaction, $sell_line)
    // {
    //     //Adjust quanity
    //     $difference = $sell_line->quantity - $this->num_uf($product['quantity']);

    //     if($difference !== 0)
    //     {
    //         //Update sell lines.
    //         $sell_line->fill([
    //             'product_id' => $product['product_id'],
    //             'quantity'=> $this->num_uf($product['quantity'], []),
    //             'unit_price' => $this->num_uf($product['unit_price'], [])*1,
    //             'discount_amount' => $this->num_uf($product['discount_amount'], []),
    //             'discount_type' => $product['discount_type'],
    //             'sub_total' => $this->num_uf($product['sub_total'], []),
    //         ]);
    //         $sell_line->save();
    //     }
        
    // }

    public function editSellLine($product, $transaction, $sell_line)
    {
        $new_qty    = $this->num_uf($product['quantity']);
        $unit_price = $this->num_uf($product['unit_price']);
        $discount   = $this->num_uf($product['discount_amount'] ?? 0);

        // Calculate delta
        $line_total = ($new_qty * $unit_price) - $discount;

        // Update sell line
        $sell_line->quantity += $new_qty;
        $sell_line->sub_total += $line_total;
        $sell_line->unit_price = $unit_price;
        $sell_line->discount_amount += $discount;
        $sell_line->save();
    }


    public function deleteSellLines($transaction_line_ids, $location_id)
    {
        if (!empty($transaction_line_ids)) {
            

            TransactionSellLine::whereIn('id', $transaction_line_ids)
                ->delete();
        }
    }

    private function adjustQuantity($location_id, $product_id, $increment_qty)
    {
        if ($increment_qty != 0) {

            ProductStock::where('product_id', $product_id)
                    ->where('location_id', $location_id)
                    ->increment('qty_available', $increment_qty);
        }
    }
    public function decreaseFoodQuantity($product_id, $location_id, $new_quantity, $old_quantity = 0)
    {
        $calculation = FoodCalculation::where('product_id', $product_id)->first();
        if(isset($calculation))
        {
            $rows = $calculation->costCalculationProducts;
            foreach($rows as $row)
            {
                $new_product_id = $row->ingredient_product_id;
                $stock = ProductStock::where('product_id', $new_product_id)
                    ->where('location_id', $location_id)->first();
                if(isset($stock) && $stock->qty_available > 0)
                {
                    $intrety_unit = Unit::find($row->ingredient_unit_id);
                    $wast_unit = Unit::find($row->wast_unit_id);
                    $total_int = $row->ingredient_qty;
                    $total_wast = $row->wast_qty;
                    
                    if(isset($intrety_unit) && $total_int > 0)
                    {
                        $unit_val = $intrety_unit->add_shortcode_for_otherunit;
                        $total_int = $unit_val > 0? $total_int/$unit_val : $total_int;
                    }
                    if(isset($wast_unit) && $total_wast > 0)
                    {
                        $waste_unit_val = $wast_unit->add_shortcode_for_otherunit;
                        $total_wast = $waste_unit_val > 0? $total_wast/$waste_unit_val : $total_wast;
                    }
  
                    $total_in_qty = ($total_int  +  $total_wast) * $new_quantity;

                    ProductStock::where('product_id', $new_product_id)
                    ->where('location_id', $location_id)
                    ->decrement('qty_available', $total_in_qty);

                    Product::where('id', $new_product_id)->decrement('stock', $total_in_qty);
                }   
            
                
            }
        }
        return true;
    }

    public function mapPurchaseSell($product_id, $mapping_type = 'purchase', $check_expiry = true, $purchase_line_id = null)
    {
        if (empty($transaction_lines)) {
            return false;
        }
        $qty_selling = null;
        foreach ($transaction_lines as $line) {
            //Check if stock is not enabled then no need to assign purchase & sell
            $product = Product::find($line->product_id);
            if ($product->enable_stock != 1) {
                continue;
            }

            //Get purchase lines, only for products with enable stock.
            $query = Transaction::join('purchase_lines AS PL', 'transactions.id', '=', 'PL.transaction_id')
                ->whereIn('transactions.type', [
                    'purchase',
                    'opening_stock'
                ])
                ->where('transactions.status', 'received')
                ->whereRaw('PL.quantity_sold < PL.quantity')
                ->where('PL.product_id', $line->product_id);

           


            //If purchase_line_id is given consider only that purchase line
            if (!empty($purchase_line_id)) {
                $query->where('PL.id', $purchase_line_id);
            }

            //Sort according to LIFO or FIFO
            // if ($business['accounting_method'] == 'lifo') {
            //     $query = $query->orderBy('transaction_date', 'desc');
            // } else {
            //     $query = $query->orderBy('transaction_date', 'asc');
            // }

            $rows = $query->select(
                'PL.id as purchase_lines_id',
                DB::raw('(PL.quantity - PL.quantity_sold) AS quantity_available'),
                'PL.quantity_sold as quantity_sold',
                'transactions.invoice_no'
            )
                ->get();

            $purchase_sell_map = [];

            //Iterate over the rows, assign the purchase line to sell lines.
            $qty_selling = $line->quantity;
            foreach ($rows as $k => $row) {
                $qty_allocated = 0;

                //Check if qty_available is more or equal
                if ($qty_selling <= $row->quantity_available) {
                    $qty_allocated = $qty_selling;
                    $qty_selling = 0;
                } else {
                    $qty_selling = $qty_selling - $row->quantity_available;
                    $qty_allocated = $row->quantity_available;
                }
                if ($mapping_type == 'purchase') {
                    //Update purchase line
                    PurchaseLine::where('id', $row->purchase_lines_id)
                        ->update(['quantity_sold' => $row->quantity_sold + $qty_allocated]);
                }

                if ($qty_selling == 0) {
                    break;
                }
            }

            if (!($qty_selling == 0 || is_null($qty_selling))) {
                $mismatch_name = $product->name;
                if (!empty($product->sub_sku)) {
                    $mismatch_name .= ' ' . 'SKU: ' . $product->sku_code;
                }
                if (!empty($qty_selling)) {
                    $mismatch_name .= ' ' . 'Quantity: ' . abs($qty_selling);
                }

                if ($mapping_type == 'purchase') {
                    $mismatch_error = trans(
                        "messages.purchase_sell_mismatch_exception",
                        ['product' => $mismatch_name]
                    );

                    if ($stop_selling_expired) {
                        $mismatch_error .= ' OR available stock has expired.';
                    }
                }

                throw new PurchaseSellMismatch($mismatch_error);
            }

        }
    }

    
    public function printerConfig($layout)
    {
        $printer = Printer::where('invoice_layout', $layout)
                    ->first();

        $output = [];

        if (!empty($printer)) {
            $output['connection_type'] = $printer->connection_type;
            $output['capability_profile'] = $printer->capability_profile;
            $output['char_per_line'] = $printer->char_per_line;
            $output['ip_address'] = $printer->ip_address;
            $output['port'] = $printer->port;
            $output['path'] = $printer->path;
        }

        return $output;
    }

    private function receipt_details($transaction, $location_details, $business_details)
    {
        $output['content'] = view('pos.receipt.receipt', compact('transaction', 'location_details', 'business_details'))->render();
        return (object)$output;
    }

    public function getDraft()
    {
        $drafts = Transactions::where('type', 'order')->where('status', 'draft');
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $drafts->whereIn('transactions.created_by', $permitted_users);
        }
        $drafts = $drafts->get();
        $transaction_draft = [];
        foreach($drafts as $draft)
        {
            $order_type = '';
            if($draft->room_id && $draft->order_type == 'Room Order')
            {
                $room_get =  RoomAssign::find($draft->room_id);
                $order_type = $draft->order_type .'(' .$room_get->room_id. ')';
            }
            if($draft->table_id && $draft->order_type == 'Dine in')
            {
                $table =  Table::find($draft->table_id);
                $order_type = $draft->order_type .'(' .$table->table_name. ')';
            }
            if($draft->order_type == 'Take away')
            {
                $order_type = $draft->order_type;
            }
            $transaction = [
                'id' => $draft->id,
                'no' => $draft->invoice_no,
                'location' => $draft->location->name ?? '',
                'location_id' => $draft->location_id,
                'department' => $draft->department->name ?? '',
                'department_id' => $draft->department_id ?? '',
                'employee' => $draft->staff->first_name ?? '',
                'employee_id' => $draft->staff_id ?? '',
                'invoiceNo' => $draft->invoice_no,
                'cusName' => $draft->customer->first_name ?? '',
                'discount' => $draft->discount_amount,
                'tax' => $draft->tax_amount,
                'cus_id' => $draft->contact_id ?? '',
                'room_id' => $draft->room_id ?? '',
                'table_id' => $draft->table_id ?? '',
                'table' => $draft->table->table_name ??  '',
                'mobileNo' => $draft->customer->mobile_no  ?? '',
                'status' => $draft->status == 'draft' ? 'DRAFT' : 'ORDER', // Set status as "Hold" for held invoices
                'products' => [

                ],
                'type' => $order_type,
                'order_type' => $draft->order_type,
                'lineTotal' => $draft->final_total,  
            ];
            foreach($draft->lines_of_sell as $line)
            {
                $product = $line->product;
                $image_url = ' ';
                if (!empty($product->image)) {
                    $image_url = asset(Storage::url(config('constants.product_img_path') . '/' . $product->image));
                } else {
                    $image_url = "https://buffer.com/cdn-cgi/image/w=1000,fit=contain,q=90,f=auto/library/content/images/size/w1200/2023/10/free-images.jpg";
                }
                $product_array = [
                    'line_id' => $line->id,
                    'id' => $product->id,
                    'description' => $product->name,
                    'unit_price' => $line->unit_price,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => $line->quantity,
                    'dis' => $line->discount_amount ?? 0,
                    'price' => $line->unit_price ?? 0,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'availability' => $product->status == 'Active' ? 'in stock' : 'out-of-stock',
                    'category' => $product->mainCategory->name ?? '',
                    'imageUrl' => $image_url,
                    'status' => $line->status,
                    'orderNo' => $line->order_no,
                ];
                array_push($transaction['products'], $product_array);

            }
            array_push($transaction_draft, $transaction);
        }

        return json_encode($transaction_draft,JSON_NUMERIC_CHECK);
    }

}
