<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WakeUpCall;
use App\Models\Customer;

use Yajra\DataTables\Facades\DataTables;
class WakeUpController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('wakeup.view') && !auth()->user()->can('wakeup.create')) {
            abort(403, 'Unauthorized action.');
        }

            $brands = WakeUpCall::join('customers', 'wake_up_calls.customer_id', '=', 'customers.id')
            ->select(['customers.first_name', 'wake_up_calls.wake_up_at','wake_up_calls.remarks', 'wake_up_calls.id', 'wake_up_calls.customer_id'])->get();

            $brands = $brands->transform(function($item){
                return [
                    'id' => $item->id,
                    'name' => $item->first_name,
                    'date' => $item->wake_up_at,
                    'remark' => $item->remarks,
                    'action' => 1,
                    'customer' => $item->customer_id
                ];
            })->toArray();
            $customers = Customer::forDropdown();
        return view('wakeup.index', compact('customers'))->with('brands',json_encode($brands,JSON_NUMERIC_CHECK));
    }

    public function create()
    {
        if (!auth()->user()->can('wakeup.create')) {
            abort(403, 'Unauthorized action.');
        }
        $customers = Customer::forDropdown();
        return view('wakeup.create', compact('customers'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('wakeup.create')) {
            abort(403, 'Unauthorized action.');
        }
        $call = new WakeUpCall();
        $msg  = 'Created';
        
        $call->customer_id = $request->customer_id;
        $call->wake_up_at = $request->wake_up_at;
        $call->remarks = $request->remarks;
        $call->save();

        return redirect("wakeup-call")->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!!auth()->user()->can('wakeup.update')) {
            abort(403, 'Unauthorized action.');
        }
        $call =  WakeUpCall::findOrFail($id);
        $customers = Customer::forDropdown();
        return view('wakeup.edit', compact('call', 'customers'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('wakeup.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $msg  = 'Updated';
            $call =  WakeUpCall::findOrFail($id);
            $call->customer_id = $request->customer_id;
            $call->wake_up_at = $request->wake_up_at;
            $call->remarks = $request->remarks;
            $call->save();
            $output = ['success' => true,
                            'msg' => $msg
                            ];
            return $output;
        }
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('wakeup.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $call =  WakeUpCall::whereIn('id', $ids)->delete();;

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
