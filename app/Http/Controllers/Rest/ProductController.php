<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Cousine;
use App\Models\DepartmentPoss;
use App\Models\DrintType;
use App\Models\Menu;
use App\Models\Product;
use App\Models\ProductCategeory;
use App\Models\Station;
use App\Models\ProductVariation;
use App\Models\Tax;
use App\Models\Type;
use App\Models\ProductATTR;
use App\Models\ProductATTAssign;
use App\Models\ProductVariationValue;
use App\Models\ProductDepartment;
use App\Models\Unit;
use App\Models\Transactions;
use App\Models\Currency;
use App\Models\PurchaseLine;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('product.view') && !auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }
        $items = $request->items ?? 25;
        $products = Product::leftjoin('product_categeories as pc', 'products.category_id', '=', 'pc.id')
        ->leftjoin('product_categeories as psc', 'products.sub_category_id', '=', 'psc.id')
        ->leftjoin('brands as bra', 'products.brand_id', '=', 'bra.id')
        ->leftjoin('cousines as cu', 'products.cousine_id', '=', 'cu.id')
        ->leftjoin('types as ty', 'products.type_id', '=', 'ty.id')
        ->leftjoin('menus as mn', 'products.menu_id', '=', 'mn.id')
        ->leftjoin('drint_types as dt', 'products.drink_type_id', '=', 'dt.id')
        ->leftjoin('taxes as pt', 'products.purchase_tax_id', '=', 'pt.id')
        ->leftjoin('taxes as st', 'products.sale_tax_id', '=', 'st.id')
        ->leftjoin('units as pu', 'products.purchase_unit_id', '=', 'pu.id')
        ->leftjoin('units as su', 'products.sale_unit_id', '=', 'su.id')
        ->select([
            'products.id',
            'products.product_type',
            'products.barcode',
            'products.sku_code',
            'products.name',
            'products.description',
            'products.alert_quantity',
            'products.discount',
            'products.mrp',
            'pc.name as category',
            'psc.name as sub_category',
            'bra.name as brand',
            'cu.name as cousin',
            'ty.name as type',
            'mn.name as menu',
            'dt.name as drink',
            'pt.name as purchase_tax',
            'st.name as sale_tax',
            'pu.name as purchase_unit',
            'su.name as sale_unit',
            'products.last_purchase_price',
            'products.sale_price',
            'products.sale_price_includ_tax',
            'products.enable_stock',
            'products.open_stock',
            'products.stock',
            'products.is_kot',
            'products.is_bot',
            'products.image',
            'products.status'
        ])->get();
        $products->transform(function($item){
            $status = "Inactive";
            if($item->status == 1)
            {
                $status = 'Active';
            }
            if($item->product_type == 0)
            {
                $type = 'Default';
            }
            else
            {
                $type = 'Specific Department';
            }
            $kot = 'No';
            if($item->is_kot == 1)
            {
                $kot = 'Yes';
            }
            $bot = 'No';
            if($item->is_bot == 1)
            {
                $bot = 'Yes';
            }
            $enable_stock  = 'No';
            if($item->enable_stock == 1)
            {
                $enable_stock = 'Yes';
            }
            $image_url = ' ';
            if (!empty($item->image)) {
                $image_url = asset(Storage::url(config('constants.product_img_path') . '/' . $item->image));
            } else {
                $image_url = asset('asset/images/no-image.png');
            }
            $product_attries = ProductATTAssign::where('product_id', $item->id)
                                ->pluck('product_attr_id')->toArray();
            $arries = ProductATTR::whereIn('id', $product_attries)->pluck('name')->toArray();
            $result = implode(', ',$arries);                    
            return [
                'id' => $item->id,
                'product_type' => $type,
                'barcode' => $item->barcode,
                'sku_code' => $item->sku_code,
                'name' => $item->name,
                'alert_quantity' => $item->alert_quantity,
                'category' => $item->category ?? 'None',
                'sub_category' => $item->sub_category ?? 'None',
                'brand' => $item->brand ?? 'None',
                'cousin' => $item->cousin ?? 'None',
                'menu' => $item->menu ?? 'None',
                'type' => $item->type ?? 'None',
                'drink' => $item->drink ?? 'None',
                'mrp' => $item->mrp ?? 'None',
                'discount' => $item->discount ?? 'None',
                'purchase_tax' => $item->purchase_tax,
                'sale_tax' => $item->sale_tax,
                'purchase_unit' => $item->purchase_unit,
                'sale_unit' => $item->sale_unit,
                'kot' => $kot,
                'bot' => $bot,
                'sale' => $item->sale_price ?? 0,
                'purchase' => $item->last_purchase_price ?? 0,
                'sale_price_includ_tax' => $item->sale_price_includ_tax	 ?? 0,
                'image' => $image_url,
                'attributes' => $result,
                'status' => $status,
                'edit_url' => action('Rest\ProductController@edit', $item->id),
                'enable_stock' => $enable_stock,
                'open_stock' => $item->open_stock
            ];
        });

        if (request()->ajax()) {
            return Datatables::of($products)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('rest.product.index', compact('products'));
    }
    public function create()
    {
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }
        $product_attres = ProductATTR::all();
        $productCategory = ProductCategeory::whereNull('parent_id')
        ->where('status', 'Active')->get();
        $brand = Brand::where('status', 'Active')->get();
        $type = Type::where('status', 'Active')->get();
        $cousine = Cousine::where('status', 'Active')->get();
        $menu = Menu::where('status', 'Active')->get();
        $drintType = DrintType::where('status', 'Active')->get();
        $departement = DepartmentPoss::where('status', 'Active')->get();
        $productVariation = ProductVariation::where('status', 'Active')->get();
        $units = Unit::where('status', 'Active')->get();
        $taxes = Tax::all();

        
        $stations = Station::active()->orderBy('display_order')->orderBy('name')->get();

        return view('rest.product.create',compact(
            'productCategory', 'units', 'productVariation',
            'brand',
            'type',
            'cousine',
            'menu',
            'drintType',
            'departement',
            'product_attres',
            'stations'
        ));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $product_details = $request->only(['name',  'product_type', 'sku_code' , 'barcode',"description",'alert_quantity','category_id',
            'sub_category_id', 'brand_id','cousine_id','type_id', 'menu_id', 'drink_type_id','purchase_tax_id',
            'sale_tax_id', "sale_tax_status", 'purchase_unit_id', 'sale_unit_id', "is_purchase_equals", 'unit_value',
            'last_purchase_price', 'discount', 'sale_price', 'sale_price_includ_tax', 'mrp','open_stock', 'stock', 'enable_stock',
            'warranty_status' ,'serial_status','weight', 'promotion_status', 'expired_period','status']);

            // Empty form fields arrive as null (ConvertEmptyStringsToNull middleware)
            // but the DB columns are NOT NULL. Coerce numerics so the DB default kicks in.
            foreach (['last_purchase_price','sale_price','sale_price_includ_tax','mrp','discount','alert_quantity','stock','unit_value','weight'] as $numField) {
                if (!isset($product_details[$numField]) || $product_details[$numField] === null || $product_details[$numField] === '') {
                    $product_details[$numField] = 0;
                }
            }

            //upload document
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                if ($request->image->getSize() <= config('constants.image_size_limit')) {
                    $new_file_name = time() . '_' . $request->image->getClientOriginalName();
                    $image_path = config('constants.product_img_path');
                    $path = $request->image->storeAs($image_path, $new_file_name);
                    if ($path) {
                        $product_details['image'] = $new_file_name;
                    }
                }
            }
            $product = Product::create($product_details);
            $exchange_rate = 1;
            $currency_details = $this->purchaseCurrencyDetails();

            // Sync stations (dynamic ticket places)
            $stationIds = array_filter((array) $request->input('station_ids', []));
            $product->stations()->sync($stationIds);

            // If the product is routed to any station, ensure it's also visible
            // in POS. Without this, the legacy POS query (filters on product
            // attribute id 1/3) silently hides station-tagged products and the
            // user keeps wondering why their item won't appear in POS.
            if (!empty($stationIds)) {
                $existingAttrs = ProductATTAssign::where('product_id', $product->id)->pluck('product_attr_id')->toArray();
                foreach ([1, 3] as $autoAttr) {
                    if (!in_array($autoAttr, $existingAttrs)) {
                        ProductATTAssign::create(['product_id' => $product->id, 'product_attr_id' => $autoAttr]);
                    }
                }
            }

            // Mirror station selection into the legacy is_kot/is_bot flags so the
            // existing kitchen/BOT display screens and receipt KOT-tagging keep
            // working — even when the user only ticks the new Stations checkbox.
            // Legacy checkboxes still act as a manual override (OR logic).
            $selectedStationCodes = $stationIds
                ? Station::whereIn('id', $stationIds)->pluck('code')->toArray()
                : [];
            $product->is_kot = (in_array('KOT', $selectedStationCodes) || $request->is_kot) ? 1 : 0;
            $product->is_bot = (in_array('BOT', $selectedStationCodes) || $request->is_bot) ? 1 : 0;
            if($product->open_stock == 1 && $product->stock > 0)
            {
                $open_stock = new Transactions();
                $open_stock->type = 'opening_stock';
                $open_stock->status = 'received';
                $open_stock->payment_status = 'paid';
                $open_stock->location_id = 1;
                $open_stock->created_by = auth()->user()->id;
                $open_stock->save();

                $purchase_lines = [];
                $new_purchase_line = [
                'product_id' => $product->id,
                'quantity'=> $this->num_uf($product->stock, $currency_details),
                'purchase_price' => $this->num_uf(0, $currency_details)*$exchange_rate,
                'discount' => $this->num_uf($product->discount, $currency_details),
                'discount_type' => 'percentage',
                'line_total' => $this->num_uf(0, $currency_details)*$exchange_rate,
                
                ];
                $purchase_lines[] = $new_purchase_line;
                if ($open_stock->status == 'received') {
                    //if status received update existing quantity
                    $this->updateProductQuantity($open_stock->location_id, $product->id, $product->stock);
                }

                if (!empty($purchase_lines)) {
                    $open_stock->lines_of_purchase()->createMany($purchase_lines);
                }
            }
            $product->save();
            if (empty(trim($request->input('sku_code')))) {
                $sku = $this->generateProductSku($product->id);
                $product->sku_code = $sku;
                $product->save();
            }
            
            $departements = $request->department;
            if($product_details['product_type'] == 1)
            {
                if(isset($departements) && $departements[0] != [])
                {
                    foreach($departements as $ky => $departement)
                    {
                        $newDepartement = new ProductDepartment();
                        $newDepartement->product_id = $product->id;
                        $newDepartement->department_id = $departement;
                        $newDepartement->save();
                    }
                }
            }
            
            $attributes = $request->product_attry;
            if(isset($attributes) && $attributes[0] != [])
            {
                foreach($attributes as $att => $attribute)
                {
                    $newAttry = new ProductATTAssign();
                    $newAttry->product_id = $product->id;
                    $newAttry->product_attr_id = $attribute;
                    $newAttry->save();
                }
            }
            $product_variations = $request->product_variant;
            if(isset($product_variations) && $product_variations[0] != [])
            {
                foreach($product_variations as $key => $type)
                {
                    foreach ($type['variations'] as $k => $v) 
                    {
                        $variation = new ProductVariationValue();
                        $variation->product_id = $product->id;
                        $variation->product_variation_id = $type['type_id'];
                        $variation->sku = $v['sku'];
                        $variation->name = $v['name'];
                        $variation->selling_price = $v['amount'];
                        $variation->save(); 
                    }
                }
            }


            $output = ['success' => 1,
                            'msg' => __('Product Added Success')
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __("Something went wrong")
                        ];
        }
  
        return redirect()->route('product.index')
        ->with('status', $output);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('product.update')) {
            abort(403, 'Unauthorized action.');
        }
        $product = Product::findOrFail($id);
        $product_departments = ProductDepartment::where('product_id', $product->id)
                                ->pluck('department_id')->toArray();
        $product_attries = ProductATTAssign::where('product_id', $product->id)
                                ->pluck('product_attr_id')->toArray();
        $variations = ProductVariationValue::where('product_id', $product->id)->get()->groupBy('product_variation_id');
                                              
        $product_attres = ProductATTR::all();
        $productCategory = ProductCategeory::whereNull('parent_id')
        ->where('status', 'Active')->get();
        $subCategory = ProductCategeory::whereNotNull('parent_id')
        ->where('status', 'Active')->get();
        $brand = Brand::where('status', 'Active')->get();
        $type = Type::where('status', 'Active')->get();
        $cousine = Cousine::where('status', 'Active')->get();
        $menu = Menu::where('status', 'Active')->get();
        $drintType = DrintType::where('status', 'Active')->get();
        $departement = DepartmentPoss::where('status', 'Active')->get();
        $productVariation = ProductVariation::where('status', 'Active')->get();
        $units = Unit::where('status', 'Active')->get();
        $taxes = Tax::all();
        $image_url = ' ';
        if (!empty($product->image)) {
            $image_url = asset(Storage::url(config('constants.product_img_path') . '/' . $product->image));
        } else {
            $image_url = asset('asset/images/no-image.png');
        }
        
        $stations = Station::active()->orderBy('display_order')->orderBy('name')->get();
        $product_station_ids = $product->stations()->pluck('stations.id')->toArray();

        return view('rest.product.edit',compact(
            'subCategory',
            'image_url',
            'product',
            'product_departments',
            'product_attries',
            'variations',
            'productCategory',
            'brand',
            'type',
            'cousine',
            'menu',
            'drintType',
            'departement',
            'product_attres', 'productVariation', 'units', 'taxes',
            'stations', 'product_station_ids'
        ));

    }

    public function generateProductSku($string)
    {
        $sku_prefix = 'PR';

        return $sku_prefix . str_pad($string, 4, '0', STR_PAD_LEFT);
    }
    //delete
    public function delete(Request $request)
    {
        if (!auth()->user()->can('product.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $types =  Product::whereIn('id', $ids)->delete();;

                $output = [
                    'success' => true,
                    'msg' => __("Deleted Success")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }

    public function subCategories(Request $request)
    {
        if (!empty($request->input('cat_id'))) {
            $category_id = $request->input('cat_id');
            $sub_categories = ProductCategeory::where('parent_id', $category_id)
                        ->select(['name', 'id'])
                        ->get();
            $html = '<option value="">None</option>';
            if (!empty($sub_categories)) {
                foreach ($sub_categories as $sub_category) {
                    $html .= '<option value="' . $sub_category->id .'">' .$sub_category->name . '</option>';
                }
            }
            echo $html;
            exit;
        }
    }

    public function purchaseCurrencyDetails()
    {
        $output = [
            'purchase_in_diff_currency' => false,
            'p_exchange_rate' => 1,
        ];

        
        // Was hardcoded to id=111 which doesn't exist in fresh DBs.
        // Fall back to the first available currency, then to safe defaults.
        $currency = Currency::find(111) ?? Currency::orderBy('id')->first();
        $output['thousand_separator'] = $currency->thousand_separator ?? ',';
        $output['decimal_separator']  = $currency->decimal_separator ?? '.';
        $output['symbol']             = $currency->symbol ?? 'Rs';
        $output['code']               = $currency->code ?? 'LKR';
        $output['name']               = $currency->currency ?? 'Sri Lankan Rupee';

        return (object)$output;
    }

    public function updateProductQuantity($location_id, $product_id, $new_quantity, $old_quantity = 0, $number_format = null)
    {
        $qty_difference = $this->num_uf($new_quantity, $number_format) - $this->num_uf($old_quantity, $number_format);
        $product = Product::find($product_id);

        //Check if stock is enabled or not.
        if ($qty_difference != 0) {

            //Add quantity in Stock
            $variation_location_d = ProductStock::where('product_id', $product_id)
                ->where('location_id', $location_id)
                ->first();


            if (empty($variation_location_d)) {
                $variation_location_d = new ProductStock();

                $variation_location_d->product_id = $product_id;
                $variation_location_d->location_id = $location_id;
                $variation_location_d->qty_available = 0;
            }

            $variation_location_d->qty_available += $qty_difference;
            $variation_location_d->save();

        }

        return true;
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

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('product.update')) {
            abort(403, 'Unauthorized action.');
        }
     
        try {
            $product_details = $request->only(['name',  'barcode',"description",'alert_quantity','category_id','product_type',
            'sub_category_id', 'brand_id','cousine_id','type_id', 'menu_id', 'drink_type_id','purchase_tax_id',
            'sale_tax_id', "sale_tax_status", 'purchase_unit_id', 'sale_unit_id', "is_purchase_equals", 'unit_value',
            'last_purchase_price', 'discount', 'sale_price', 'sale_price_includ_tax', 'mrp','open_stock', 'stock', 'enable_stock',
            'warranty_status' ,'serial_status','weight', 'promotion_status', 'expired_period','status']);

            $product = Product::findOrFail($id);
            $before_stock = $product->stock;
            $product->name = $product_details['name'];
            $product->product_type = $product_details['product_type'];
            $product->barcode = $product_details['barcode'];
            $product->description = $product_details['description'];
            $product->alert_quantity = in_array(1,$request->product_attry) ? $product_details['alert_quantity'] : null;
            $product->category_id = (in_array(1,$request->product_attry) || in_array(2,$request->product_attry)) ? $product_details['category_id'] : null;
            $product->sub_category_id = (in_array(1,$request->product_attry) || in_array(2,$request->product_attry)) && isset($product_details['sub_category_id']) ? $product_details['sub_category_id'] : null;
            $product->brand_id = (in_array(1,$request->product_attry) || in_array(2,$request->product_attry)) && isset($product_details['brand_id']) ? $product_details['brand_id'] : null;
            $product->cousine_id = in_array(3,$request->product_attry)  && isset($product_details['cousine_id']) ? $product_details['cousine_id'] : null;
            $product->type_id = in_array(3,$request->product_attry)  && isset($product_details['type_id']) ? $product_details['type_id'] : null;
            $product->menu_id = in_array(3,$request->product_attry)  && isset($product_details['menu_id']) ? $product_details['menu_id'] : null;
            $product->drink_type_id = in_array(3,$request->product_attry)  && isset($product_details['drink_type_id']) ? $product_details['drink_type_id'] : null;
            $product->purchase_tax_id = in_array(2,$request->product_attry) && isset($product_details['purchase_tax_id']) ? $product_details['purchase_tax_id'] : null;
            $product->sale_tax_id = in_array(1,$request->product_attry)  && isset($product_details['sale_tax_id']) ? $product_details['purchase_tax_id'] : null;
            $product->sale_tax_status = in_array(1,$request->product_attry)  && isset($product_details['sale_tax_status']) ? $product_details['sale_tax_status'] : 0;
            $product->purchase_unit_id = in_array(2,$request->product_attry)   && isset($product_details['purchase_unit_id']) ? $product_details['purchase_unit_id'] : null;
            $product->sale_unit_id = in_array(1,$request->product_attry) && isset($product_details['sale_unit_id']) ? $product_details['sale_unit_id'] : null;
            $product->is_purchase_equals = in_array(2,$request->product_attry) && isset($product_details['is_purchase_equals']) ? $product_details['is_purchase_equals'] : 0;
            $product->unit_value = in_array(2,$request->product_attry) ? $product_details['unit_value'] : null;
            $product->last_purchase_price = in_array(2,$request->product_attry) ? $product_details['last_purchase_price'] : null;
            $product->discount = $product_details['discount'];
            $product->sale_price = $product_details['sale_price'];
            $product->mrp = $product_details['mrp'];
            $product->status =  isset($product_details['status']) ? 1 : 0;
            $product->open_stock = isset($product_details['enable_stock']) && $product_details['enable_stock'] == 1 && isset($product_details['open_stock']) ? 1 : 0;
            $product->enable_stock = isset($product_details['enable_stock']) ? 1 : 0;
            $product->stock = isset($product_details['enable_stock']) && $product_details['enable_stock'] == 1 && isset($product_details['open_stock']) ? $product_details['stock'] : 0;
            $product->sale_price_includ_tax = in_array(1,$request->product_attry) && isset($product_details['sale_tax_status']) && $product_details['sale_tax_status'] == 1 ? $product_details['sale_price_includ_tax'] : null;

            //upload document
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // if ($request->image->getSize() <= config('constants.image_size_limit')) {
                    $new_file_name = time() . '_' . $request->image->getClientOriginalName();
                    $image_path = config('constants.product_img_path');
                    $path = $request->image->storeAs($image_path, $new_file_name);
                    if ($path) {
                        $product->image  = $new_file_name;
                    }
                // }
            }
            $product->save();
            $exchange_rate = 1;
            $currency_details = $this->purchaseCurrencyDetails();

            // Sync stations (dynamic ticket places)
            $stationIds = array_filter((array) $request->input('station_ids', []));
            $product->stations()->sync($stationIds);

            // Auto-attach POS visibility attrs when stations are selected
            // (see store() for rationale).
            if (!empty($stationIds)) {
                $existingAttrs = ProductATTAssign::where('product_id', $product->id)->pluck('product_attr_id')->toArray();
                foreach ([1, 3] as $autoAttr) {
                    if (!in_array($autoAttr, $existingAttrs)) {
                        ProductATTAssign::create(['product_id' => $product->id, 'product_attr_id' => $autoAttr]);
                    }
                }
            }

            // Mirror station selection into the legacy is_kot/is_bot flags
            // (see store() for rationale). Legacy checkboxes still apply as
            // an OR override, but we no longer require the attribute gate
            // (in_array(3,...)) because the station IS the source of truth.
            $selectedStationCodes = $stationIds
                ? Station::whereIn('id', $stationIds)->pluck('code')->toArray()
                : [];
            $product->is_kot = (in_array('KOT', $selectedStationCodes) || $request->is_kot) ? 1 : 0;
            $product->is_bot = (in_array('BOT', $selectedStationCodes) || $request->is_bot) ? 1 : 0;
            if($product->open_stock == 1 && $before_stock !=  $request->stock && $product->stock > 0)
            {
                $purchase_line = PurchaseLine::where('purchase_lines.product_id', $product->id)
                ->join('transactions', 'purchase_lines.transaction_id', '=', 'transactions.id')
                ->where('transactions.type', 'opening_stock')->first();
                if(!isset($purchase_line))
                {
                    $open_stock = new Transactions();
                    $open_stock->type = 'opening_stock';
                    $open_stock->status = 'received';
                    $open_stock->payment_status = 'paid';
                    $open_stock->location_id = 1;
                    $open_stock->created_by = auth()->user()->id;
                    $open_stock->save();
    
                    $purchase_lines = [];
                    $new_purchase_line = [
                    'product_id' => $product->id,
                    'quantity'=> $this->num_uf($product->stock, $currency_details),
                    'purchase_price' => $this->num_uf(0, $currency_details)*$exchange_rate,
                    'discount' => $this->num_uf($product->discount, $currency_details),
                    'discount_type' => 'percentage',
                    'line_total' => $this->num_uf(0, $currency_details)*$exchange_rate,
                    
                    ];
                    $purchase_lines[] = $new_purchase_line;
                    if ($open_stock->status == 'received') {
                        //if status received update existing quantity
                        $this->updateProductQuantity($open_stock->location_id, $product->id, $product->stock);
                    }
                    if (!empty($purchase_lines)) {
                        $open_stock->lines_of_purchase()->createMany($purchase_lines);
                    }
                }
                else
                {
                    $purchase_line->quantity = $product->stock;
                    $purchase_line->save();
                    $transaction = Transactions::find($purchase_line->transaction_id);
                    $variation_location_d = ProductStock::where('product_id', $product->id)
                    ->where('location_id', $transaction->location_id)
                    ->update([
                        'qty_available'  => $product->stock
                    ]);
                }
                

                
            }
            $product->save();
            
            
            $attributes = $request->product_attry;
            if(isset($attributes) && $attributes[0] != [])
            {
                $product->attributes()->delete();
                foreach($attributes as $att => $attribute)
                {
                    $newAttry = new ProductATTAssign();
                    $newAttry->product_id = $product->id;
                    $newAttry->product_attr_id = $attribute;
                    $newAttry->save();
                }
            }

            $departements = $request->department;
            if($product->product_type == 1)
            {
                if(isset($departements) && $departements[0] != [])
                {
                    $product->departments()->delete();
                    foreach($departements as $ky => $departement)
                    {
                        $newDepartement = new ProductDepartment();
                        $newDepartement->product_id = $product->id;
                        $newDepartement->department_id = $departement;
                        $newDepartement->save();
                    }
                }
            }
            if($product->product_type == 0)
            {
                $product->departments()->delete();
            }

            $output = ['success' => 1,
                            'msg' => __('Product Added Success')
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __("Something went wrong")
                        ];
        }
  
        return redirect()->route('product.index')
        ->with('status', $output);

    }

    public function show($id)
    {
        if (!auth()->user()->can('product.view')) {
            abort(403, 'Unauthorized action.');
        }
        $product = Product::leftjoin('product_categeories as pc', 'products.category_id', '=', 'pc.id')
        ->leftjoin('product_categeories as psc', 'products.sub_category_id', '=', 'psc.id')
        ->leftjoin('brands as bra', 'products.brand_id', '=', 'bra.id')
        ->leftjoin('cousines as cu', 'products.cousine_id', '=', 'cu.id')
        ->leftjoin('types as ty', 'products.type_id', '=', 'ty.id')
        ->leftjoin('menus as mn', 'products.menu_id', '=', 'mn.id')
        ->leftjoin('drint_types as dt', 'products.drink_type_id', '=', 'dt.id')
        ->leftjoin('taxes as pt', 'products.purchase_tax_id', '=', 'pt.id')
        ->leftjoin('taxes as st', 'products.sale_tax_id', '=', 'st.id')
        ->leftjoin('units as pu', 'products.purchase_unit_id', '=', 'pu.id')
        ->leftjoin('units as su', 'products.sale_unit_id', '=', 'su.id')
        ->where('products.id', $id)
        ->select([
            'products.id',
            'products.product_type',
            'products.barcode',
            'products.sku_code',
            'products.name',
            'products.description',
            'products.alert_quantity',
            'products.discount',
            'products.mrp',
            'pc.name as category',
            'psc.name as sub_category',
            'bra.name as brand',
            'cu.name as cousin',
            'ty.name as type',
            'mn.name as menu',
            'dt.name as drink',
            'pt.name as purchase_tax',
            'st.name as sale_tax',
            'pu.name as purchase_unit',
            'su.name as sale_unit',
            'products.last_purchase_price',
            'products.sale_price',
            'products.sale_price_includ_tax',
            'products.enable_stock',
            'products.open_stock',
            'products.stock',
            'products.is_kot',
            'products.is_bot',
            'products.image',
            'products.status'
        ])->first();
        $status = "Inactive";
        if($product->status == 1)
        {
            $status = 'Active';
        }
        if($product->product_type == 0)
        {
            $type = 'Default';
        }
        else
        {
            $type = 'Specific Department';
        }
        $kot = 'No';
        if($product->is_kot == 1)
        {
            $kot = 'Yes';
        }
        $bot = 'No';
        if($product->is_bot == 1)
        {
            $bot = 'Yes';
        }
        $enable_stock  = 'No';
        if($product->enable_stock == 1)
        {
            $enable_stock = 'Yes';
        }
        $image_url = ' ';
        if (!empty($product->image)) {
            $image_url = asset(Storage::url(config('constants.product_img_path') . '/' . $product->image));
        } else {
            $image_url = asset('asset/images/no-image.png');
        }
        $product_attries = ProductATTAssign::where('product_id', $product->id)
                            ->pluck('product_attr_id')->toArray();
        $arries = ProductATTR::whereIn('id', $product_attries)->pluck('name')->toArray();
        $result = implode(', ',$arries); 
        return view('rest.product.view',compact('product','result', 'image_url','type', 'enable_stock', 'status', 'bot', 'kot'));
    }
}
