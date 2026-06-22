<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TransactionSellLine;
use App\Models\FoodCalculation;
use App\Models\Product;
use App\Models\BusinessLocation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
class ProfitReportController extends Controller
{
    public function profitReport(Request $request)
    {
        if (!auth()->user()->can('sale-profit-report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date') && $request->get('end_date') != 'undefined' ? $request->get('end_date') : $request->get('start_date');
     
        $location = $request->get('location');
        $employee = $request->get('employee');
        $product = $request->get('product');
        $transactions = TransactionSellLine::orderBy('transaction_sell_lines.id', 'DESC')
        ->join('transactions', 'transaction_sell_lines.transaction_id', '=', 'transactions.id')
        ->join('products as pr', 'transaction_sell_lines.product_id', '=', 'pr.id')
        ->where('transactions.type', 'order')
        ->select([
            'transactions.id',
            'transactions.updated_at',
            'pr.id as product_id',
            'pr.sku_code',
            'pr.barcode',
            'pr.name as product_name',
            DB::raw("SUM(transaction_sell_lines.quantity) as quantity"),
            'transaction_sell_lines.unit_price',
            DB::raw("SUM(transaction_sell_lines.sub_total) as sub_total"),
            'pr.last_purchase_price',
            'transactions.created_by',
        ])->groupBy('transaction_sell_lines.product_id');
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $transactions->whereIn('transactions.created_by', $permitted_users);
        }

        if(!empty($start_date) && !empty($end_date))
        {
            $transactions->whereDate('transactions.updated_at', '>=', $start_date)->whereDate('transactions.updated_at', '<=', $end_date);
        }
        if(!empty($location))
        {
            $transactions->where('transactions.location_id', $location);
        }
        if(!empty($employee))
        {
            $transactions->where('transactions.staff_id',  $employee);
        }
        if(!empty($product))
        {
            $transactions->where('pr.id', $product);
        }
        
        $transactions = $transactions->get();
        $transactions->transform(function($item) {
            $net_sale = $item->sub_total;
            $food_cost = FoodCalculation::orderBy('id', 'DESC')->where('product_id', $item->product_id)->first();
            $product = Product::where('id', $item->product_id)->first();
         
            // $attry = isset($item->product_id) ? $product->attributes->pluck('product_attr_id')->toArray() : '';
            $purchase_price = $item->last_purchase_price ?? 0;
            $cost = $purchase_price * $item->quantity;
            $profit = $net_sale - $cost;
            return [
                'id' => $item->id,
                'sku' => $item->sku_code,
                'barcode' => $item->barcode,
                'product_name' => $item->product_name,
                'qty' => $item->quantity,
                'price' => $item->unit_price,
                'net_sale' => $item->sub_total,
                'purchase' => $purchase_price ?? 0,
                'cost' => $cost,
                'profit' => $profit,
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($transactions)
            ->editColumn(
                'price',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$price}}">{{$price}}</span>'
            )
            ->editColumn(
                'net_sale',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$net_sale}}">{{$net_sale}}</span>'
            )
            ->editColumn(
                'purchase',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$purchase}}">{{$purchase}}</span>'
            )
            ->editColumn(
                'cost',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$cost}}">{{$cost}}</span>'
            )
            ->editColumn(
                'profit',
                '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$profit}}">{{$profit}}</span>'
            )
            ->rawColumns(['price', 'net_sale', 'purchase','profit', 'cost'])
            ->make(true);
        }
        $departments = BusinessLocation::get();
        $products = Product::get();
        $employees =  User::join('employees as em', 'users.staff_id', '=', 'em.id')
        ->where('em.status', 'Active')->select(['users.id', 'users.first_name'])->get();
        return view('report.profit_report', compact('transactions', 'employees', 'departments', 'products'));
        
    }
}
