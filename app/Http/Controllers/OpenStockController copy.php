<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessLocation;
use App\Models\Product;
use App\Models\Transactions;
use App\Models\ProductStock;
use App\Models\Currency;
use DB;
class OpenStockController extends Controller
{
    public function create($id)
    {
        if (!auth()->user()->can('open-stock.create')) {
            abort(403, 'Unauthorized action.');
        }
        $locations = BusinessLocation::forDropdown()->toArray();
        $product = Product::find($id);
        return view('open_stock.index', compact('locations', 'product'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('open-stock.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $opening_stocks = $request->input('stocks');
            $product_id = $request->input('product_id');
            $product = Product::find($product_id);
            $currency_details = $this->purchaseCurrencyDetails();
            $exchange_rate = 1;
            foreach($opening_stocks as $key => $stock)
            {
                $open_stock = new Transactions();
                $open_stock->type = 'opening_stock';
                $open_stock->status = 'received';
                $open_stock->payment_status = 'paid';
                $open_stock->location_id = $key;
                $open_stock->created_by = auth()->user()->id;
                $open_stock->save();
    
                $purchase_lines = [];
                $new_purchase_line = [
                'product_id' => $product->id,
                'quantity'=> $this->num_uf($stock['quantity'], $currency_details),
                'purchase_price' => $this->num_uf($stock['purchase_price'], $currency_details)*$exchange_rate,
                'discount' => $this->num_uf($product->discount, $currency_details),
                'discount_type' => 'percentage',
                'line_total' => $this->num_uf(0, $currency_details)*$exchange_rate,
                
                ];
                $purchase_lines[] = $new_purchase_line;
                if ($open_stock->status == 'received') {
                    //if status received update existing quantity
                    $this->updateProductQuantity($open_stock->location_id, $product->id, $stock['quantity']);
                }
    
                if (!empty($purchase_lines)) {
                    $open_stock->lines_of_purchase()->createMany($purchase_lines);
                }
            }
            
            $output = ['success' => 1,
                             'msg' => __('Opening stock added successfully')
                        ];
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => $e->getMessage()
                        ];
            return back()->with('status', $output);
        }
        return redirect('/rest/product')->with('status', $output);
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

    public function purchaseCurrencyDetails()
    {
        $output = [
            'purchase_in_diff_currency' => false,
            'p_exchange_rate' => 1,
        ];

        
        $currency = Currency::find(111);
        $output['thousand_separator'] = $currency->thousand_separator;
        $output['decimal_separator'] = $currency->decimal_separator;
        $output['symbol'] = $currency->symbol;
        $output['code'] = $currency->code;
        $output['name'] = $currency->currency;

        return (object)$output;
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



}
