<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Floor;

use Yajra\DataTables\Facades\DataTables;

class FloorController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('floor.view') && !auth()->user()->can('floor.create')) {
            abort(403, 'Unauthorized action.');
        }
        $floors = Floor::select(['name','id'])->get();
        $floors = $floors->transform(function($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'action' => 1,
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($floors)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('floor.index');
    }

    public function create()
    {
        if (!auth()->user()->can('floor.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('floor.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('floor.create')) {
            abort(403, 'Unauthorized action.');
        }
        $msg  = 'Created';
        $floor = new Floor();
        $floor->name = $request->name;
        $floor->save();
        return redirect('floors')->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('floor.update')) {
            abort(403, 'Unauthorized action.');
        }
        $floor =  Floor::findOrFail($id);
        return view('floor.edit', compact('floor'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('floor.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id;
        $msg  = 'Updated';
        $floor =  Floor::findOrFail($id);
        $floor->name = $request->name;
        $floor->save();
        return redirect('floors')->with("msg",$msg);
    }

    public function show($id)
    {
        $floor =  Floor::findOrFail($id);

        return $floor;
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('floor.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $floor =  Floor::whereIn('id', $ids)->delete();

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
