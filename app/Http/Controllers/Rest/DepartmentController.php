<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\DepartmentPoss;
use App\Models\BusinessLocation;
use App\Models\Product;
use App\Models\Business;
use App\Models\ProductVariationValue;
use App\Models\FoodCalculation;
use App\Models\ProductVariation;
use App\Models\ProductCategeory;
use App\Models\DrintType;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DepartmentController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('department.view') && !auth()->user()->can('department.create')) {
            abort(403, 'Unauthorized action.');
        }
        $items = $request->items ?? 25;
        $departments = DepartmentPoss::leftjoin('business_locations as bl', 'department_posses.location_id', '=', 'bl.id')
        ->select([
            'department_posses.id',
            'department_posses.name',
            'department_posses.icon',
            'department_posses.status',
            'department_posses.location_id',
            'bl.name as location'
        ])->paginate($items);
        $departments->transform(function ($item) {
            $item->action = 1;
            if (!empty($item->icon)) {
                $image_url = asset(Storage::url(config('constants.department_img_path') . '/' . $item->icon));
            } else {
                $image_url = asset('/img/default.png');
            }
            return [
                'id' => $item->id,
                'name' => $item->name,
                'icon' => $image_url,
                'status' => $item->status,
                'location' => $item->location,
                'location_id' => $item->location_id,
                'action' => 1
            ];
        });
        $business_locations = BusinessLocation::forDropdown();
        return view('rest.department.index', compact('business_locations', 'departments'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('department.create')) {
            abort(403, 'Unauthorized action.');
        }
        $department = new DepartmentPoss();
        $department->name = $request->name;
        $department->location_id = $request->location_id;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $new_file_name = time() . '_' . $request->image->getClientOriginalName();
            $image_path = config('constants.department_img_path');
            $path = $request->image->storeAs($image_path, $new_file_name);
            if ($path) {
                $department->icon = $new_file_name;
            }
        }

        $department->status = $request->has('status') ? 'Active' : 'Inactive';
        $department->save();

        return redirect()->route('department.index')
                ->with('success', 'Department successfully Created!!');
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('department.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id;
        $department = DepartmentPoss::find($id);
        $department->name = $request->name;
        $department->location_id = $request->location_id;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $new_file_name = time() . '_' . $request->image->getClientOriginalName();
            $image_path = config('constants.department_img_path');
            $path = $request->image->storeAs($image_path, $new_file_name);
            if ($path) {
                $department->icon = $new_file_name;
            }
        }

        $department->status = $request->has('status') ? 'Active' : 'Inactive';
        $department->save();
        return redirect()->route('department.index')
            ->with('success', 'Department successfully Updated!!');
    }

    public function show($id)
    {
        $department = DepartmentPoss::find($id);
        return $department;
    }

    public function delete(Request $request)
    {
        if (!auth()->user()->can('department.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $department =  DepartmentPoss::whereIn('id', $ids)->delete();;

                $output = ['success' => true,
                            'msg' => __("Deleted Success")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

    public function getProducts(Request $request)
    {
        $department_id = $request->get('department_id');
        $business = Business::first();
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
        'product_departments as pd',
        'products.id',
        '=',
        'pd.product_id'
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
        ->whereIn('ata.product_attr_id', [1,3]);
            if(isset($department_id) && $department_id != 'all')
            {   
                $products->whereIn('pd.department_id', [$department_id])->orwhereIn('products.product_type', [0]);
            }
            else 
            {
                $products->whereIn('products.product_type', [0]);
            }
            $products = $products->select(
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
            $menu_ids = array_column($products, 'menu_id');
            $sub_ids = array_column($products, 'sub_category_id');
            $type_ids = array_column($products, 'drink_type_id');
            $categories = ProductCategeory::whereIn('id', $category_ids)->where('status', 'Active')->get();
            $$categories = $categories->transform( function($item) use($sub_ids){
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
        return  [
            'products' => $products,
            'categories' => $categories,
            'menus' => $menus,
            'drint_type' => $drint_type,
        ];                
    }

    public function getEmployees(Request $request)
    {
        $department_id = $request->get('department_id');
        $employees = User::join('employees as em', 'users.staff_id', '=', 'em.id')
        ->where('em.status', 'Active');
        if(isset($department_id) && $department_id != 'all')
        {   
            $employees->where('em.department_id', $department_id);
        }
        $employees = $employees->get();
        $employees = $employees->transform( function($item) {
            return [
                'id' => $item->id,
                'name' => $item->first_name
            ];
        })->toArray();
        return $employees;
    }

}
