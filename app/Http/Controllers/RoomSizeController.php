<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomSizeType;
use App\Models\Customer;

use Yajra\DataTables\Facades\DataTables;

class RoomSizeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('room-size.view') && !auth()->user()->can('room-size.create')) {
            abort(403, 'Unauthorized action.');
        }
        $sizes = RoomSizeType::select(['name','id'])->get();
        $sizes = $sizes->transform(function($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'action' => 1,
            ];
        })->toArray();

        return view('size.index')
        ->with('sizes',json_encode($sizes,JSON_NUMERIC_CHECK));
    }

    public function create()
    {
        if (!auth()->user()->can('room-size.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('size.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('room-size.create')) {
            abort(403, 'Unauthorized action.');
        }
        $msg  = 'Created';
        $size = new RoomSizeType();
        $size->name = $request->name;
        $size->save();
        return redirect('room-sizes')->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('room-size.update')) {
            abort(403, 'Unauthorized action.');
        }
        $size =  RoomSizeType::findOrFail($id);
        return view('size.edit', compact('size'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('room-size.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $msg  = 'Updated';
            $size =  RoomSizeType::findOrFail($id);
            $size->name = $request->name;
            $size->save();
            $output = ['success' => true,
                            'msg' => $msg
                            ];
            return $output;
        }
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('room-size.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $size =  RoomSizeType::whereIn('id', $ids)->delete();

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
