<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class PositionController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('position.view') && !auth()->user()->can('position.create')) {
            abort(403, 'Unauthorized action.');
        }
        $items = $request->items ?? 25;
        $positions = Position::orderBy('id', 'DESC')->paginate($items);
        $positions->transform(function ($item) {
            $item->action = 1;
            return [
                'id' => $item->id,
                'name' => $item->name,
                'status' => $item->status,
                'action' => 1
            ];
        });
        return view('rest.position.index', compact('positions'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('position.create')) {
            abort(403, 'Unauthorized action.');
        }
        $Position = new Position();
        $Position->name = $request->name;
        $Position->status = $request->has('status') ? 'Active' : 'Inactive';
        $Position->save();

        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = 'web';
        $role->save();
        return redirect()->route('position.index')
            ->with('success', 'customerGroup successfully Created!!');
        
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('position.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id;
        $Position = Position::find($id);

        $edit_role = Role::where('name', $Position->name)->first();
        if(isset($edit_role))
        {
            $edit_role->name = $request->name;
            $edit_role->save();
        }
        $Position->name = $request->name;
        $Position->status = $request->has('status') ? 'Active' : 'Inactive';
        $Position->save();
        return redirect()->route('position.index')
            ->with('success', 'customerGroup successfully Updated!!');
        
    }

    public function show($id)
    {
        $position = Position::find($id);
        return $position;
    }

    //delete
    public function delete(Request $request)
    {
        if (!auth()->user()->can('position.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $Position =  Position::whereIn('id', $ids)->delete();;

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
