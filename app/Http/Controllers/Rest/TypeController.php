<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
class TypeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('type.view') && !auth()->user()->can('type.create')) {
            abort(403, 'Unauthorized action.');
        }

        $types = Type::all();
        $types->transform(function ($item) {
            $item->action = 1;

            if (!empty($item->image)) {
                $image_url = asset(Storage::url(config('constants.type_img_path') . '/' . $item->image));
            } else {
                $image_url = asset('/img/default.png');
            }

            return [
                'id' => $item->id,
                'name' => $item->name,
                'icon' => $image_url,
                'image' => $image_url,
                'status' => $item->status,
                'action' => 1
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($types)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->editColumn('image', function ($row) {
                return '<img style="border-radius: 5px; object-fit: cover;" class="w-8 h-8" src="'.$row['image'].'">';
            })

            ->editColumn('status', function ($row) {
                $span = '<span class="btn btn-danger btn-sm" style="width:80px;color:#fff;">In Active</span>';
                if($row['status'] == 'Active')
                {
                    $span = '<span class="btn btn-success btn-sm" style="width:50px;color:#fff;">Active</span>';
                }
                return $span;
            })
            ->rawColumns(['action', 'image','status'])
            ->make(true);
        }
        return view('rest.type.index')
            ->with('types', json_encode($types, JSON_NUMERIC_CHECK));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('type.create')) {
            abort(403, 'Unauthorized action.');
        }
        $types = new Type();
        $types->name = $request->name;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $new_file_name = time() . '_' . $request->image->getClientOriginalName();
            $image_path = config('constants.type_img_path');
            $path = $request->image->storeAs($image_path, $new_file_name);
            if ($path) {
                $types->image = $new_file_name;
            }
        }

        $types->status = $request->has('status') ? 'Active' : 'Inactive';
        $types->save();

        return redirect()->route('types.index')
            ->with('success', 'types successfully Created!!');
    
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('type.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id; // id
        $types = Type::find($id);
        $types->name = $request->name;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $new_file_name = time() . '_' . $request->image->getClientOriginalName();
            $image_path = config('constants.type_img_path');
            $path = $request->image->storeAs($image_path, $new_file_name);
            if ($path) {
                $types->image = $new_file_name;
            }
        }

        $types->status = $request->has('status') ? 'Active' : 'Inactive';
        $types->save();
        return redirect()->route('types.index')
            ->with('success', 'types successfully Updated!!');
    }

    public function show($id)
    {
        $type = Type::find($id);
        if (!empty($type->image)) {
            $image_url = asset(Storage::url(config('constants.type_img_path') . '/' . $type->image));
        } else {
            $image_url = asset('/img/default.png');
        }
        $type->setAttribute('icon', $image_url);
        return $type;
    }
    
    public function delete(Request $request)
    {
        if (!auth()->user()->can('type.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $types =  Type::whereIn('id', $ids)->delete();;

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
