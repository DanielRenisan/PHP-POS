<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\FoodCalculation;
use App\Models\Business;
use App\Models\ProductVariationValue;
use App\Models\BusinessLocation;
use App\Models\Unit;
use App\Models\DrintType;
use App\Models\ProductCategeory;
use App\Models\Menu;
use App\Models\Brand;
use App\Models\Cousine;
use App\Models\SellLineVariation;
use App\Models\Transactions;
use App\Models\ProductVariation;
class QRController extends Controller
{
    public function index()
    {
        $customers = Contact::orderBy('id')->where('contact_type_id', 1)->where('status', "Active")->get();

        return view('qr_customer', compact('customers'));
    }

    public function menu()
    {
        $business = Business::first();
        $business_locations = BusinessLocation::pluck('name', 'id');
        

        $default_location = null;
        if (count($business_locations) == 1) {
            foreach ($business_locations as $id => $name) {
                $default_location = $id;
            }
        }
        
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
                    'products.drink_type_id',
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
        
        
        // $draft = Transactions::join('')
        return view('qr_products', compact('business_locations', 'default_location', 
        'business'))
        ->with('products',json_encode($products,JSON_NUMERIC_CHECK))
        ->with('menus',json_encode($menus,JSON_NUMERIC_CHECK))
        ->with('cusiones',json_encode($cusiones,JSON_NUMERIC_CHECK))
        ->with('brands',json_encode($drint_type,JSON_NUMERIC_CHECK))
        ->with('categories',json_encode($categories,JSON_NUMERIC_CHECK));
        
        return view('qr_products', compact('products'));
    }


    public function store(Request $request)
    {
        try {
            $exchange_rate = 1;
            $transaction_id = $request->transaction_id;
            $exit_transaction = Transactions::where('contact_id', $request->contact_id)
            ->where(function($qu)  use($request){
                $qu->where('order_type', $request->order_type)->where('table_id', $request->table_id);
                $qu->orwhere('order_type', $request->order_type);
                $qu->orwhere('order_type', $request->order_type)->where('room_id', $request->room_id);
            })->where('status', 'draft')->first();
            $before_status = null;
            if(isset($transaction_id))
            {
                $transaction  = Transactions::findOrFail($transaction_id);
                $transaction->lines_of_sell()->delete();
                $before_status = $transaction->status;
            }
            else
            {
                $transaction = Transactions::where('contact_id', $request->contact_id)
                ->where(function($qu)  use($request){
                    $qu->where('order_type', $request->order_type)->where('table_id', $request->table_id);
                    $qu->orwhere('order_type', $request->order_type);
                })->where('status', 'draft')->first();
                if(!isset($transaction))
                {
                    $transaction  = new Transactions();
                    $transaction->created_by = 1;
                    $transaction->type = 'order';
                }  
            }
            $transaction->status =  'draft';
            $transaction->location_id = $request->location_id;
            $transaction->department_id =  0;
            $transaction->contact_id = $request->contact_id;
            $transaction->staff_id  = 1;
            $transaction->room_id  = null;
            $transaction->table_id  = $request->table_id ?? null;
            $transaction->is_include  = $request->is_include == 1 ? $request->is_include :0;
            $transaction->transaction_date  = $request->transaction_date;
            $transaction->discount_type  = $request->discount_type ?? 'percentage'; 
            if(!isset($before_status) && $before_status == 'draft')
            {
                $transaction->final_total  += $this->num_uf($request->final_total, null)*$exchange_rate;
            }
            else
            {
                $transaction->final_total  = $this->num_uf($request->final_total, null)*$exchange_rate;
            }
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

            
            DB::commit();
            $output = ['success' => 1,
                            'msg' => __('Add Success')
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' =>  $e->getMessage()
                        ];
        }
        return redirect()->back()->with('msg', 'success');
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

    public function getCustomer()
    {
        if (request()->ajax()) {
            $term = request()->term;

            
            if (empty($term)) {
                return json_encode([]);
            }
            $q = Contact::
            where('contact_type_id', 1)
            ->where(function ($query) use ($term) {
                $query->where('first_name', 'like', '%' . $term .'%');
                $query->orWhere('mobile_no', 'like', '%' . $term .'%');
            })
            ->select(
                    'id',
                    'first_name',
                    'mobile_no',
                );

            // if ($check_enable_stock) {
            //     $q->where('enable_stock', 0);
            // }
            $products = $q->get();
            $products_array = [];
            foreach ($products as $product) {
                $products_array[$product->id]['name'] = $product->first_name;
                $products_array[$product->id]['sku'] = $product->mobile_no;
            }

            $result = [];
            $i = 1;
            $no_of_records = $products->count();
            if (!empty($products_array)) {
                foreach ($products_array as $key => $value) {
                    $result[] = [ 'id' => $i,
                                    'text' => $value['name'] . ' - ' . $value['sku'],
                                    'product_id' => $key
                                ];
                    $name = $value['name'];
                    $i++;
                }
            }
            return $result;
        }
    }
}
