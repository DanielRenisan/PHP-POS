<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bed;

use Yajra\DataTables\Facades\DataTables;

class BedController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('bed.view') && !auth()->user()->can('bed.create')) {
            abort(403, 'Unauthorized action.');
        }
        $beds = Bed::select(['name','id'])->get();
        $beds = $beds->transform(function($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'action' => 1,
            ];
        })->toArray();
        return view('bed.index')
        ->with('beds',json_encode($beds,JSON_NUMERIC_CHECK));
    }

    public function create()
    {
        if (!auth()->user()->can('bed.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('bed.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('bed.create')) {
            abort(403, 'Unauthorized action.');
        }
        $msg  = 'Created';
        $bed = new Bed();
        $bed->name = $request->name;
        $bed->save();
        return redirect('beds')->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('bed.update')) {
            abort(403, 'Unauthorized action.');
        }
        $bed =  Bed::findOrFail($id);
        return view('bed.edit', compact('bed'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('bed.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            $msg  = 'Updated';
            $bed =  Bed::findOrFail($id);
            $bed->name = $request->name;
            $bed->save();
            $output = ['success' => true,
                            'msg' => $msg
                            ];
            return $output;
        }
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('bed.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $bed =  Bed::whereIn('id', $ids)->delete();

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
