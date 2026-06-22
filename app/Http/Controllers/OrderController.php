<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionSellLine;
use App\Models\Product;
use App\Models\ProductATTAssign;
use App\Models\FoodCalculation;
use App\Models\Unit;
use App\Models\ProductStock;
use App\Models\Transactions;
class OrderController extends Controller
{
    public function delete(Request $request)
    {
        $id = $request->line_id;
        $product_id = $request->id;
        $sell_line = TransactionSellLine::find($id);

        $product = Product::find($product_id);
        $arrays = ProductATTAssign::where('product_id', $product_id)
                    ->pluck('product_attr_id')->toArray();
        if(in_array(1, $arrays) && $product->enable_stock == 1)
        {
            $this->adjustQuantity($product_id, $this->num_uf($sell_line->quantity));
        }
        if(in_array(3, $arrays))
        {
            $this->increaseFoodQuantity(
                            1,
                            $product_id,
                            $this->num_uf($sell_line->quantity)
                        );
        }
        $sell_line->delete();
        $output = ['success' => 1,
        'msg' =>  'deleted successfull'
        ];
        return $output;
    }

    public function cancel(Request $request)
    {
        $id = $request->line_id;
        $product_id = $request->id;
        $sell_line = TransactionSellLine::find($id);

        $product = Product::find($product_id);
        $arrays = ProductATTAssign::where('product_id', $product_id)
                    ->pluck('product_attr_id')->toArray();
        if(in_array(1, $arrays) && $product->enable_stock == 1)
        {
            $this->adjustQuantity($product_id, $this->num_uf($sell_line->quantity));
        }
        if(in_array(3, $arrays))
        {
            $this->increaseFoodQuantity(
                            1,
                            $product_id,
                            $this->num_uf($sell_line->quantity)
                        );
        }
        $sell_line->status = 'canceled';
        $sell_line->save();

        Transactions::where('id', $sell_line->transaction_id)
                    ->decrement('final_total', $sell_line->sub_total);

        $output = ['success' => 1,
        'msg' =>  'deleted successfull'
        ];
        return $output;
    }

    public function orderCancel(Request $request)
    {
        $id = $request->id;
        $transaction = Transactions::find($id);
        $sell_lines = $transaction->lines_of_sell;
        foreach($sell_lines as $sell_line)
        {
            $product = Product::find($sell_line->product_id);
            $arrays = ProductATTAssign::where('product_id', $product->id)
                    ->pluck('product_attr_id')->toArray();
            if(in_array(1, $arrays) && $product->enable_stock == 1)
            {
                $this->adjustQuantity($product->id, $this->num_uf($sell_line->quantity));
            }
            if(in_array(3, $arrays))
            {
                $this->increaseFoodQuantity(
                                1,
                                $product->id,
                                $this->num_uf($sell_line->quantity)
                            );
            }
            $sell_line->status = 'canceled';
            $sell_line->save();
        }
        $transaction->status = 'canceled';
        $transaction->save();
        $output = ['success' => 1,
        'msg' =>  'deleted successfull'
        ];
        return $output;
    }

    public function cancelInvoice(Request $request, $id)
    {
        $transaction = Transactions::find($id);
        $transaction->status = 'canceled';
        $transaction->save();
        $output = ['success' => 1,
        'msg' =>  'deleted successfull'
        ];
        return redirect()->back();
    }

    private function adjustQuantity($product_id, $increment_qty)
    {
        if ($increment_qty != 0) {

            ProductStock::where('product_id', $product_id)
            //         ->where('location_id', $location_id)
                    ->increment('qty_available', $increment_qty);
        }
    }

    public function increaseFoodQuantity($location_id, $product_id, $new_quantity, $old_quantity = 0)
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
                    ->increment('qty_available', $total_in_qty);

                }   
            
                
            }
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
}
