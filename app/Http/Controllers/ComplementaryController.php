<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complementary;
use Illuminate\Support\Facades\Storage;
use App\Models\RoomType; 
use Yajra\DataTables\Facades\DataTables;

class ComplementaryController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('complementary.view') && !auth()->user()->can('complementary.create')) {
            abort(403, 'Unauthorized action.');
        }
        $complementaries = Complementary::select(['room_type', 
        'name',
        'rate', 
        'id'])->get();
        $complementaries = $complementaries->transform(function($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'room_type' => $item->room_type,
                'rate' => $item->rate,
                'action' => 1,
            ];
        })->toArray();
        $room_types = RoomType::pluck('name')->toArray();
        return view('complementary.index', compact('room_types'))
        ->with('complementaries',json_encode($complementaries,JSON_NUMERIC_CHECK));
    }

    public function create()
    {
        if (!auth()->user()->can('complementary.create')) {
            abort(403, 'Unauthorized action.');
        }
        $room_types = RoomType::pluck('name')->toArray();
        return view('complementary.create', compact('room_types'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('complementary.create')) {
            abort(403, 'Unauthorized action.');
        }
        $com = new Complementary();
        $msg  = 'Created';
        $com->room_type = $request->room_type;
        $com->name = $request->name;
        $com->rate = $request->rate;
        $com->save();

        return redirect("complementaries")->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('complementary.update')) {
            abort(403, 'Unauthorized action.');
        }
        $complementary = Complementary::findOrFail($id);
        $room_types = RoomType::pluck('name')->toArray();
        return view('complementary.edit', compact('complementary', 'room_types'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('complementary.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $msg  = 'Updated';
            $com = Complementary::findOrFail($id);
            $com->room_type = $request->room_type;
            $com->name = $request->name;
            $com->rate = $request->rate;
            $com->save();
            $output = ['success' => true,
                            'msg' => $msg
                            ];
            return $output;
        }
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('complementary.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $com = Complementary::whereIn('id', $ids)->delete();

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

    public function getDetail(Request $request)
    {
        if (!empty($request->input('complementry'))) {
            $id = $request->input('complementry');
            
            $complementary = Complementary::findOrFail($id);        
            if (!isset($complementary)) {
                return [
                    'rate' => 0,
                ];
            }
            return [
                'rate' => $complementary->rate,
            ];
        }
    }
}
