<?php

namespace App\Http\Controllers\Rest;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{
    public function index()
    {

        $payment_method = PaymentMethod::get();
        $payment_method = $payment_method->transform(function ($item) {
            $item->action = 1;
            return [
                'id' => $item->id,
                'name' => $item->name,
                'status' => $item->status,
                'action' => 1
            ];
        })->toArray();
        return view('rest.payment_method.index')
            ->with('payment_method', json_encode($payment_method, JSON_NUMERIC_CHECK));
    }

    public function store(Request $request)
    {
        $id = $request->id; // id

        if (!isset($id)) {

            $payment_method = new PaymentMethod();
            $payment_method->name = $request->Name;
            $payment_method->status = $request->has('Status') ? 'Active' : 'Inactive';
            $payment_method->save();

            return redirect()->route('payment_method.index')
                ->with('success', 'payment_method successfully Created!!');
        } else {

            $payment_method = PaymentMethod::find($id);
            $payment_method->name = $request->Name;
            $payment_method->status = $request->has('Status') ? 'Active' : 'Inactive';
            $payment_method->save();
            return redirect()->route('payment_method.index')
                ->with('success', 'payment_method successfully Updated!!');
        }
    }


    public function delete(Request $request)
    {
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $call =  PaymentMethod::whereIn('id', $ids)->delete();;

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


}