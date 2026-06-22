<?php

namespace App\Http\Controllers;

use App\Models\CostCalculationProduct;
use App\Models\FoodCalculation;
use App\Models\Product;
use App\Models\ProductATTAssign;
use App\Models\ProductATTR;
use App\Models\Tax;
use App\Models\Unit;
use Illuminate\Http\Request;

use DB;
class FoodCalculationController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('room-facility.view') && !auth()->user()->can('room-facility.create')) {
            abort(403, 'Unauthorized action.');
        }

        $facilities = FoodCalculation::with('costCalculationProducts')
            ->select(
                'food_calculation.id',
                'food_calculation.quantity',
                'food_calculation.selling_price',
                'food_calculation.wastage_cost',
                'food_calculation.ingredients_cost',
                'food_calculation.service_cost',
                'food_calculation.extra_cost',
                'food_calculation.gross_profit',
                'food_calculation.labour_cost',
                'food_calculation.total_cost',
                'food_calculation.tax',
                'food_calculation.food_cost',
                'food_calculation.labour_hour',
                'food_calculation.prepare_time',
                'food_calculation.service_time',
                'food_calculation.total_time',
                'food_calculation.cooking_instruction',
                'food_calculation.service_instruction',
                'products.name as product_name',
            )
            ->join('products', 'food_calculation.product_id', '=', 'products.id')
            ->get();

        $facilities = $facilities->transform(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->product_name,
                'unit_name' => $item->unit_name,
                'qty' => $item->quantity,
                'selling_price' => $item->selling_price,
                'wastage_cost' => $item->wastage_cost,
                'ingredients_cost' => $item->ingredients_cost,
                'service_cost' => $item->service_cost,
                'extra_cost' => $item->extra_cost,
                'gross_profit' => $item->gross_profit,
                'labour_cost' => $item->labour_cost,
                'total_cost' => $item->total_cost,
                'tax' => $item->tax,
                'food_cost' => $item->food_cost,
                'labour_hour' => $item->labour_hour,
                'prepare_time' => $item->prepare_time,
                'service_time' => $item->service_time,
                'total_time' => $item->total_time,
                'cooking_instruction' => $item->cooking_instruction,
                'service_instruction' => $item->service_instruction,
                'costCalculationProducts' => $item->costCalculationProducts,
                'action' => 1,
                'edit_url' => action('FoodCalculationController@edit', $item->id)
            ];
        });
        return view('food_calculation.index', compact('facilities'));
    }

    public function create()
    {
        if (!auth()->user()->can('room-facility.view') && !auth()->user()->can('room-facility.create')) {
            abort(403, 'Unauthorized action.');
        }

        $facilities = FoodCalculation::with('costCalculationProducts')
            ->select(
                'food_calculation.id',
                'food_calculation.quantity',
                'food_calculation.selling_price',
                'food_calculation.wastage_cost',
                'food_calculation.ingredients_cost',
                'food_calculation.service_cost',
                'food_calculation.extra_cost',
                'food_calculation.gross_profit',
                'food_calculation.labour_cost',
                'food_calculation.total_cost',
                'food_calculation.tax',
                'food_calculation.food_cost',
                'food_calculation.labour_hour',
                'food_calculation.prepare_time',
                'food_calculation.service_time',
                'food_calculation.total_time',
                'food_calculation.cooking_instruction',
                'food_calculation.service_instruction',
                'products.name as product_name',
                'units.name as unit_name'
            )
            ->join('products', 'food_calculation.product_id', '=', 'products.id')
            ->join('units', 'food_calculation.unit_id', '=', 'units.id')
            ->get();

        $facilities = $facilities->transform(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->product_name,
                'unit_name' => $item->unit_name,
                'qty' => $item->quantity,
                'selling_price' => $item->selling_price,
                'wastage_cost' => $item->wastage_cost,
                'ingredients_cost' => $item->ingredients_cost,
                'service_cost' => $item->service_cost,
                'extra_cost' => $item->extra_cost,
                'gross_profit' => $item->gross_profit,
                'labour_cost' => $item->labour_cost,
                'total_cost' => $item->total_cost,
                'tax' => $item->tax,
                'food_cost' => $item->food_cost,
                'labour_hour' => $item->labour_hour,
                'prepare_time' => $item->prepare_time,
                'service_time' => $item->service_time,
                'total_time' => $item->total_time,
                'cooking_instruction' => $item->cooking_instruction,
                'service_instruction' => $item->service_instruction,
                'costCalculationProducts' => $item->costCalculationProducts,
                'action' => 1,
            ];
        })->toArray();

        //products belong to the Is for Sales and Is Purchased
        $purchaseAndSaleProduct = ProductATTR::whereIn('name', ['Is For Sales', 'Is Purchased'])->pluck('id');
        $catelogProduct = ProductATTR::where('name', 'Digital Menu')->pluck('id');

        $productsAttributeAssign = ProductATTAssign::whereIn('product_attr_id', $purchaseAndSaleProduct)->get();
        $productsAttributeAssignForCatelog = ProductATTAssign::whereIn('product_attr_id', $catelogProduct)->get();

        $productsIds = $productsAttributeAssign->pluck('product_id');
        $catelogProductIds = $productsAttributeAssignForCatelog->pluck('product_id');

        $products = Product::select('id', 'name', 'sale_price', 'sale_unit_id', 'last_purchase_price', 'purchase_unit_id')->whereIn('id', $productsIds)->get();
        $catelogProductDetails = Product::leftjoin('menus', 'products.menu_id', '=', 'menus.id')
        ->select('products.id', 'products.name', 
        'menus.name as menu', 
        'products.sale_price', 'products.sale_unit_id', 
        'products.purchase_unit_id')->whereIn('products.id', $catelogProductIds)->get();

        //unit products
        $purchaseProductsIds = ProductATTR::where('name', 'Is Purchased')->pluck('id');
        $unitProductAttributeAssign = ProductATTAssign::whereIn('product_attr_id', $purchaseProductsIds)
            ->get();

        $allUnutProduct = $productsAttributeAssign->merge($unitProductAttributeAssign);

        $unitProductIds = $allUnutProduct->pluck('product_id');
        $unitProducts = Product::select('id', 'name')->whereIn('id', $unitProductIds)->get();

        //units details
        $units = Unit::select('id', 'name', 'short_code', 'add_shortcode_for_otherunit', 'value', 'unit_parent_id')
            ->where('status', 'Active')->get();

        $taxes = Tax::select('id', 'name', 'amount', 'percentage')
            ->get();

        return view('food_calculation.create')
            ->with('facilities', json_encode($facilities, JSON_NUMERIC_CHECK))
            ->with('products', $products)
            ->with('catelogProductDetails', $catelogProductDetails)
            ->with('unitProducts', $unitProducts)
            ->with('units', $units)
            ->with('taxes', $taxes);
    }

    public function store(Request $request)
    {
        if ($request->all()) {
            $allAvailableProduct = Product::all();
            $allAvailableUnits = Unit::all();

            $menuName = $allAvailableProduct->filter(function ($product) use ($request) {
                return $product->id == $request->menuItem;
            })->first()->name;

            $foodCalculation = FoodCalculation::create([
                'product_id' => $request->menuItem,
                'menu_name' => $menuName,

                'selling_price' => $request->selling_price,
                'wastage_cost' => $request->waste_cost,
                'ingredients_cost' => $request->ingredient_cost,
                'service_cost' => $request->service_cost,
                'extra_cost' => $request->extra_cost,
                'gross_profit' => $request->profit_margin,
                'labour_cost' => $request->labour_cost,
                'total_cost' => $request->grand_total,
                'tax' => $request->tax,
                'labour_hour' => $request->labour_hour,
                'service_instruction' => $request->service_instruction,
                'cooking_instruction' => $request->cook_instruction,
                'total_time' => $request->total_time,
                'service_time' => $request->service_time,
                'prepare_time' => $request->prepare_time,
                'status' => $request->status, //
            ]);

            foreach ($request->rows as $row) {
                CostCalculationProduct::create([
                    'food_calculation_id' => $foodCalculation->id,
                    'ingredient_product_id' => $row['name'],
                    'ingredient_product_name' => $allAvailableProduct->filter(function ($product) use ($row) {
                        return $product->id == $row['name'];
                    })->first()->name,
                    'ingredient_cost' => $row['intAmount'],
                    'wast_qty' => $row['wastQty'],
                    'wast_cost' => $row['wastAmount'],
                    'ingredient_qty' => $row['qty'],
                    'ingredient_unit_id' => $row['selectedUnit'],
                    'ingredient_unit_name' => $allAvailableUnits->filter(function ($unit) use ($row) {
                        return $unit->id == $row['selectedUnit'];
                    })->first()->name,
                    'wast_unit_id' => $row['wastUnit'],
                    'wast_unit_name' => $allAvailableUnits->filter(function ($unit) use ($row) {
                        return $unit->id == $row['wastUnit'];
                    })->first()->name,
                    'total_cost' => $row['total'],
                ]);
            }

            return redirect()->route('food-calculation.index')->with('success', 'Data saved successfully in both tables!');
        }
    }

    public function update(Request $request)
    {
        if ($request->all()) {
            $foodCalculation = FoodCalculation::findOrFail($request->id);
            $allAvailableProduct = Product::all();
            $allAvailableUnits = Unit::all();

            $menuName = $allAvailableProduct->filter(function ($product) use ($request) {
                return $product->id == $request->menuItem;
            })->first()->name;

            $foodCalculation->update([
                'product_id' => $request->menuItem,
                'menu_name' => $menuName,

                'selling_price' => $request->selling_price,
                'wastage_cost' => $request->waste_cost,
                'ingredients_cost' => $request->ingredient_cost,
                'service_cost' => $request->service_cost,
                'extra_cost' => $request->extra_cost,
                'gross_profit' => $request->profit_margin,
                'labour_cost' => $request->labour_cost,
                'total_cost' => $request->grand_total,
                'tax' => $request->tax,
                'labour_hour' => $request->labour_hour,
                'service_instruction' => $request->service_instruction,
                'cooking_instruction' => $request->cook_instruction,
                'total_time' => $request->total_time,
                'service_time' => $request->service_time,
                'prepare_time' => $request->prepare_time,
                'status' => $request->status,
            ]);

            $foodCalculation->costCalculationProducts()->delete();

            foreach ($request->rows as $row) {
                CostCalculationProduct::create([
                    'food_calculation_id' => $foodCalculation->id,
                    'ingredient_product_id' => $row['name'],
                    'ingredient_product_name' => $allAvailableProduct->filter(function ($product) use ($row) {
                        return $product->id == $row['name'];
                    })->first()->name ?? null,
                    'ingredient_cost' => isset($row['intAmount']) ? $row['intAmount'] : null,
                    'wast_qty' => isset($row['wastQty']) ? $row['wastQty'] : null,
                    'wast_cost' => isset($row['wastAmount']) ? $row['wastAmount'] : null,
                    'ingredient_qty' => isset($row['qty']) ? $row['qty'] : null,
                    'ingredient_unit_id' => isset($row['selectedUnit']) ? $row['selectedUnit'] : null,
                    'ingredient_unit_name' => $allAvailableUnits->filter(function ($unit) use ($row) {
                        return $unit->id == $row['selectedUnit'];
                    })->first()->name ?? null,
                    'wast_unit_id' => isset($row['wastUnit']) ? $row['wastUnit'] : null,
                    'wast_unit_name' => $allAvailableUnits->filter(function ($unit) use ($row) {
                        if(isset($row['wastUnit']))
                        {
                            return $unit->id == $row['wastUnit'];
                        }
                    })->first()->name ?? null,
                    'total_cost' => isset($row['total']) ? $row['total'] : null,
                ]);
            }

            return redirect()->route('food-calculation.index')->with('success', 'Data updated successfully in both tables!');
        }
    }

    public function delete(Request $request)
    {
        if (!auth()->user()->can('room-facility.view') && !auth()->user()->can('room-facility.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $foodCalculation = FoodCalculation::whereIn('id', $ids)->get();

                $foodCalculation->each(function ($foodCost) {
                    FoodCalculation::where('id', $foodCost->id)->delete();
                    CostCalculationProduct::where('food_calculation_id', $foodCost->id)->delete();
                });


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

    public function edit($id)
    {
        if (!auth()->user()->can('room-facility.update')) {
            abort(403, 'Unauthorized action.');
        }

        $facilities = FoodCalculation::with('costCalculationProducts')
            ->select(
                'food_calculation.id',
                'food_calculation.quantity',
                'food_calculation.selling_price',
                'food_calculation.wastage_cost',
                'food_calculation.ingredients_cost',
                'food_calculation.service_cost',
                'food_calculation.extra_cost',
                'food_calculation.gross_profit',
                'food_calculation.labour_cost',
                'food_calculation.total_cost',
                'food_calculation.tax',
                'food_calculation.food_cost',
                'food_calculation.labour_hour',
                'food_calculation.prepare_time',
                'food_calculation.service_time',
                'food_calculation.total_time',
                'food_calculation.cooking_instruction',
                'food_calculation.service_instruction',
                'products.name as product_name',
                'food_calculation.product_id',
            )
            ->join('products', 'food_calculation.product_id', '=', 'products.id')
            ->where('food_calculation.id', $id)
            ->first();
            $facilitie =  [
                'id' => $facilities->id,
                'name' => $facilities->product_name,
                'product_id' => $facilities->product_id,
                'unit_name' => $facilities->unit_name,
                'qty' => $facilities->quantity,
                'selling_price' => $facilities->selling_price,
                'wastage_cost' => $facilities->wastage_cost,
                'ingredients_cost' => $facilities->ingredients_cost,
                'service_cost' => $facilities->service_cost,
                'extra_cost' => $facilities->extra_cost,
                'gross_profit' => $facilities->gross_profit,
                'labour_cost' => $facilities->labour_cost,
                'total_cost' => $facilities->total_cost,
                'tax' => $facilities->tax,
                'food_cost' => $facilities->food_cost,
                'labour_hour' => $facilities->labour_hour,
                'prepare_time' => $facilities->prepare_time,
                'service_time' => $facilities->service_time,
                'total_time' => $facilities->total_time,
                'cooking_instruction' => $facilities->cooking_instruction,
                'service_instruction' => $facilities->service_instruction,
                'costCalculationProducts' => $facilities->costCalculationProducts,
                'action' => 1,
            ];

            //products belong to the catelogs
        $product_attr = ProductATTR::where('name', 'Is Catalog')->pluck('id');
        $productsAssign = ProductATTAssign::whereIn('product_attr_id', $product_attr)->pluck('product_id')->unique()->toArray();

        $catProducts = Product::whereIn('id', $productsAssign)->get();

        //products belong to the catelogs
        $purchaseAndSaleProduct = ProductATTR::where('name', 'Is Purchased')->pluck('id');
        $productsAttributeAssign = ProductATTAssign::whereIn('product_attr_id', $purchaseAndSaleProduct)->get();

        $productsIds = $productsAttributeAssign->pluck('product_id');

        $products = Product::whereIn('id', $productsIds)->get();

        //unit products
        $purchaseProductsIds = ProductATTR::where('name', 'Is Purchased')->pluck('id');
        $unitProductAttributeAssign = ProductATTAssign::whereIn('product_attr_id', $purchaseProductsIds)
            ->get();

        $allUnutProduct = $productsAttributeAssign->merge($unitProductAttributeAssign);

        $unitProductIds = $allUnutProduct->pluck('product_id');
        $unitProducts = Product::select('id', 'name')->whereIn('id', $unitProductIds)->get();
        $costCalculationProducts = $facilities->costCalculationProducts;
        $costCalculationProducts = $costCalculationProducts->transform(function($item){
            $product = Product::find($item->ingredient_product_id);
            $unit_id = $product->sale_unit_id ?? $product->purchase_unit_id;
            $unit = Unit::find($unit_id);


            return [
                'intAmount' => $item->ingredient_cost,
                'productUnits' => [],
                'qty' => $item->ingredient_qty,
                'relatedProductUnit' => $unit->short_code ?? '',
                'selectedUnit' => $item->ingredient_unit_id,
                'selectedUnitProduct' => $item->ingredient_product_id,
                'total' => $item->total_cost,
                'unitAmount' => $product->sale_price ?? $product->last_purchase_price ?? '',
                'units1' => [

                ],
                'wastAmount' => $item->wast_cost,
                'wastQty' => $item->wast_qty,
                'wastUnit' => $item->wast_unit_id,
                'wastageUnit' => []
            ];
        });

        //units details
        $units = Unit::select('id', 'name', 'short_code', 'add_shortcode_for_otherunit', 'value', 'unit_parent_id')
            ->where('status', 'Active')->get();


            
        return view('food_calculation.edit', compact('facilities'))
            ->with('facilitie', json_encode($facilitie, JSON_NUMERIC_CHECK))
            ->with('products', $products)
            ->with('unitProducts', $unitProducts)
            ->with('costCalculationProducts', $costCalculationProducts)
            ->with('units', $units)
            ->with('catProducts', $catProducts);
    }

    public function show($id)
    {
        $facilities = FoodCalculation::with('costCalculationProducts')
            ->select(
                'food_calculation.id',
                'food_calculation.quantity',
                'food_calculation.selling_price',
                'food_calculation.wastage_cost',
                'food_calculation.ingredients_cost',
                'food_calculation.service_cost',
                'food_calculation.extra_cost',
                'food_calculation.gross_profit',
                'food_calculation.labour_cost',
                'food_calculation.total_cost',
                'food_calculation.tax',
                'food_calculation.food_cost',
                'food_calculation.labour_hour',
                'food_calculation.prepare_time',
                'food_calculation.service_time',
                'food_calculation.total_time',
                'food_calculation.cooking_instruction',
                'food_calculation.service_instruction',
                'products.name as product_name',
                'food_calculation.product_id',
            )
            ->join('products', 'food_calculation.product_id', '=', 'products.id')
            ->where('food_calculation.id', $id)
            ->first();
            $facility =  [
                'id' => $facilities->id,
                'name' => $facilities->product_name,
                'product_id' => $facilities->product_id,
                'unit_name' => $facilities->unit_name,
                'qty' => $facilities->quantity,
                'selling_price' => $facilities->selling_price,
                'wastage_cost' => $facilities->wastage_cost,
                'ingredients_cost' => $facilities->ingredients_cost,
                'service_cost' => $facilities->service_cost,
                'extra_cost' => $facilities->extra_cost,
                'gross_profit' => $facilities->gross_profit,
                'labour_cost' => $facilities->labour_cost,
                'total_cost' => $facilities->total_cost,
                'tax' => $facilities->tax,
                'food_cost' => $facilities->food_cost,
                'labour_hour' => $facilities->labour_hour,
                'prepare_time' => $facilities->prepare_time,
                'service_time' => $facilities->service_time,
                'total_time' => $facilities->total_time,
                'cooking_instruction' => $facilities->cooking_instruction,
                'service_instruction' => $facilities->service_instruction,
                'action' => 1,
            ];

            $costCalculationProducts = $facilities->costCalculationProducts;
            $costCalculationProducts = $costCalculationProducts->transform(function($item){
                $product = Product::find($item->ingredient_product_id);
                $unit_id = $product->sale_unit_id ?? $product->purchase_unit_id;
                $wast_unit_id = $item->wast_unit_id;
                $unit = Unit::find($unit_id);

                $wast_unit = $wast_unit_id != null ? Unit::find($wast_unit_id)->short_code : null;
                return [
                    'product_name' => $product->name,
                    'intAmount' => $item->ingredient_cost,
                    'productUnits' => [],
                    'qty' => $item->ingredient_qty,
                    'relatedProductUnit' => $unit->short_code ?? '',
                    'selectedUnit' => $item->ingredient_unit_id,
                    'selectedUnitProduct' => $item->ingredient_product_id,
                    'total' => $item->total_cost,
                    'unitAmount' => $product->sale_price ?? $product->last_purchase_price ?? '',
                    'units1' => [

                    ],
                    'wastAmount' => $item->wast_cost,
                    'wastQty' => $item->wast_qty,
                    'wastUnit' => $wast_unit,
                    'wastageUnit' => []
                ];
            });
            return view('food_calculation.show', compact('facility', 'costCalculationProducts'));
    }
}
