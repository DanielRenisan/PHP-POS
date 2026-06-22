<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductATTAssign;

use Illuminate\Support\Facades\DB;
class StockReportController extends Controller
{
    public function report(Request $request)
    {
        if (!auth()->user()->can('stock-report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $soldSubQuery = DB::table('transaction_sell_lines as tsl')
                        ->join('transactions as t', 't.id', '=', 'tsl.transaction_id')
                        ->select('tsl.product_id', DB::raw('SUM(tsl.quantity) as sold_qty'))
                        ->where('t.status', 'final')  // only consider finalized sales
                        ->groupBy('tsl.product_id');
                        
        $query = Product::leftjoin('purchase_lines as PL', 'products.id', '=', 'PL.product_id')
                    ->leftjoin('product_stocks as PS', 'products.id', '=', 'PS.product_id')
                    ->join('product_a_t_t_assigns as PTA', 'products.id', '=', 'PTA.product_id')
                    ->leftJoinSub($soldSubQuery, 'sold_table', function($join) {$join->on('sold_table.product_id', '=', 'products.id');})
                    ->whereIn('PTA.product_attr_id', [1,2]);
            if (!empty($request->input('category_id'))) {
                $query->where('products.category_id', $request->input('category_id'));
            }
            if (!empty($request->input('sub_category_id'))) {
                $query->where('products.sub_category_id', $request->input('sub_category_id'));
            }
            if (!empty($request->input('brand_id'))) {
                $query->where('products.brand_id', $request->input('brand_id'));
            }
            $products = $query->select(


                DB::raw("(SELECT SUM(TPL.quantity) FROM transactions 
                    LEFT JOIN purchase_lines AS TPL ON transactions.id=TPL.transaction_id
                    WHERE transactions.status='received' AND transactions.type IN ('opening_stock')
                    AND TPL.product_id=products.id) as total_open_stock"),

                DB::raw("(SELECT SUM(TPL.quantity) FROM transactions
                    LEFT JOIN purchase_lines AS TPL ON transactions.id=TPL.transaction_id
                    WHERE transactions.status='received' AND transactions.type IN ('purchase')
                    AND TPL.product_id=products.id) as total_purchase_stock"),

                DB::raw("(SELECT SUM(TPL.quantity) FROM transactions
                    LEFT JOIN purchase_lines AS TPL ON transactions.id=TPL.transaction_id
                    WHERE transactions.status='received' AND transactions.type IN ('purchase', 'opening_stock')
                    AND TPL.product_id=products.id) as total_stock"), 

                DB::raw("(SELECT COALESCE(SUM(DISTINCT TSL.quantity),0)
                    FROM transaction_sell_lines AS TSL
                    JOIN transactions AS T ON T.id = TSL.transaction_id
                    WHERE T.status = 'final'
                    AND TSL.product_id = products.id) as sold_qty"),

                DB::raw('COALESCE(sold_table.sold_qty, 0) as sold_qty'),            
                'products.id as id',
                'sku_code',
                'products.name as product',
                'products.enable_stock as enable_stock',
                'PS.qty_available'
            )->groupBy('products.id')->get();
            $products = $products->transform(function($item) {
                $product_attries = ProductATTAssign::where('product_id', $item->id)
                ->pluck('product_attr_id')->toArray();
                $sold =  $item->sold_qty ?? 0;

                $ingredientUsedQty = DB::table('cost_calculation_product as ccp')
                    ->join('food_calculation as fc', 'fc.id', '=', 'ccp.food_calculation_id')
                    ->join('transaction_sell_lines as tsl', 'tsl.product_id', '=', 'fc.product_id')
                    ->join('transactions as t', function ($join) {
                        $join->on('t.id', '=', 'tsl.transaction_id')
                            ->where('t.status', 'final');
                    })
                    ->where('ccp.ingredient_product_id', $item->id)
                    ->select(DB::raw('SUM(tsl.quantity * ccp.ingredient_qty) as total'))
                    ->value('total');

                // Add ingredient usage to sold
                $sold += $ingredientUsedQty ?? 0;
                $total_stock = $item->total_stock ?? 0; 
                
                return [
                    'sku' => $item->sku_code,
                    'name' => $item->product,
                    'open_stock' => $item->total_open_stock ?? 0,
                    'purchase_stock' => $item->total_purchase_stock ?? 0,
                    'total_stock' => $total_stock,
                    'other_stock' => $item->enable_stock == 0 && in_array(1, $product_attries) ? '(∞)' : 0,
                    'total_sold' => $sold,
                    'balance_stock' => $total_stock - $sold,
                ];
            })->toArray();
  
            return view('report.stock_report')
            ->with('products',json_encode($products,JSON_NUMERIC_CHECK));
    }
}
