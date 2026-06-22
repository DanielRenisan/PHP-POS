<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
use Illuminate\Support\Facades\Storage;
class BOTController extends Controller
{
    public function index()
    {

        if (!auth()->user()->can('bot.view')) {
            abort(403, 'Unauthorized action.');
        }
        // $orders = Transactions::orderBy('id', 'DESC')->where('type', 'order')->whereNotIn('status', ['canceled', 'printed', 'final', 'draft'])->get();

        $orders = Transactions::orderBy('id', 'DESC')->where('type', 'order')->whereNotIn('status', ['canceled', 'final', 'draft']);
 
        $permitted_users = auth()->user()->permitted_users();
        if ($permitted_users != 'all') {
            $orders->whereIn('transactions.created_by', $permitted_users);
        }
        $orders = $orders->get();
        $transaction_data = [];
        foreach($orders as $order)
        {
            
            $transaction = [
                'id' => $order->id,
                'no' => $order->invoice_no,
                'time' => $order->created_at,
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
                'table' => $order->table->table_name ?? $order->room->room_id ?? '',
                'status' => "ORDER", // Set status as "Hold" for held invoices
                'items' => [

                ],
                'type' => $order->table_id ? 'Dine In('.$order->table->table_name ?? ''.')' : 'Room Order',
                'lineTotal' => $order->lines_of_sell->sum('sub_total'),
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
                $product_array = [
                    'id' => $product->id,
                    'line_id' => $line->id,
                    'description' => $product->name,
                    'unit_price' => $line->unit_price,
                    'skuCode' => $product->sku_code,
                    'barcode' => $product->barcode,
                    'qty' => $line->quantity,
                    'dis' => $product->discount ?? 0,
                    'price' => $product->sale_price ?? $product->sale_price_includ_tax ?? 0,
                    'dateTime' => date('Y.m.d', strtotime($product->created_at)),
                    'name' => $product->name,
                    'availability' => $product->status == 'Active' ? 'in stock' : 'out-of-stock',
                    'category' => $product->mainCategory->name ?? '',
                    'imageUrl' => $image_url,
                    'status' => $line->status,
                    'orderNo' => $line->order_no ?? ''
                ];

                if($product->is_bot == 1 && (in_array(3, $arriy_array) || in_array(1, $arriy_array) || in_array(2, $arriy_array)))
                {
                   
                    array_push($transaction['items'], $product_array);
                }

            }
            if($transaction['items'] != [])
            {
                array_push($transaction_data, $transaction);
            }  
        }
        return view('bot.index')->with('orders',json_encode($transaction_data,JSON_NUMERIC_CHECK));
    }
}
