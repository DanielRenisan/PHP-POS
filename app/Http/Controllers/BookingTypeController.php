<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingType;

use Yajra\DataTables\Facades\DataTables;

class BookingTypeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('booking-type.view') && !auth()->user()->can('booking-type.create')) {
            abort(403, 'Unauthorized action.');
        }
        $types = BookingType::select(['name','id'])->get();
        $types = $types->transform(function($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'action' => 1,
            ];
        })->toArray();
        return view('booking_type.index')
        ->with('types',json_encode($types,JSON_NUMERIC_CHECK));
    }

    public function create()
    {
        if (!auth()->user()->can('booking-type.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('booking_type.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('booking-type.create')) {
            abort(403, 'Unauthorized action.');
        }
        $msg  = 'Created';
        $type = new BookingType();
        $type->name = $request->name;
        $type->save();
        return redirect('booking-types')->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('booking-type.update')) {
            abort(403, 'Unauthorized action.');
        }
        $type =  BookingType::findOrFail($id);
        return view('booking_type.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('booking-type.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $msg  = 'Created';
            $type =  BookingType::findOrFail($id);
            $type->name = $request->name;
            $type->save();
            $output = ['success' => true,
                            'msg' => $msg
                            ];
            return $output;
        }
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('booking-type.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $type =  BookingType::whereIn('id', $ids)->delete();

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
