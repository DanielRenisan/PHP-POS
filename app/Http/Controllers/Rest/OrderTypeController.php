<?php

namespace App\Http\Controllers\Rest;
use App\Http\Controllers\Controller;
use App\Models\OrderType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrderTypeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('order-type.view')) {
            abort(403, 'Unauthorized action.');
        }


        $orderType = OrderType::get();
        $orderType = $orderType->transform(function ($item) {
            $item->action = 1;
            return [
                'id' => $item->id,
                'name' => $item->name,
                'status' => $item->status,
                'action' => 1
            ];
        })->toArray();
        return view('rest.order_type.index')
            ->with('orderType', json_encode($orderType, JSON_NUMERIC_CHECK));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('order-type.store')) {
            abort(403, 'Unauthorized action.');
        }

        $id = $request->id; // id

        if (!isset($id)) {

            $orderType = new OrderType();
            $orderType->name = $request->name;
            $orderType->status = $request->has('status') ? 'Active' : 'Inactive';
            $orderType->save();

            return redirect()->route('order_type.index')
                ->with('success', 'orderType successfully Created!!');
        } else {
            if (!auth()->user()->can('order-type.update')) {
                abort(403, 'Unauthorized action.');
            }
    

            $orderType = OrderType::find($id);
            $orderType->name = $request->name;
            $orderType->status = $request->has('status') ? 'Active' : 'Inactive';
            $orderType->save();
            return redirect()->route('order_type.index')
                ->with('success', 'orderType successfully Updated!!');
        }
    }


    public function delete(Request $request)
    {
        if (!auth()->user()->can('order-type.delete')) {
            abort(403, 'Unauthorized action.');
        }

        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $orderType =  OrderType::whereIn('id', $ids)->delete();;

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
