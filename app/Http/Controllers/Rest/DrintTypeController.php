<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\DrintType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
class DrintTypeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('drink-type.view') && !auth()->user()->can('drink-type.create')) {
            abort(403, 'Unauthorized action.');
        }
        $drintType = DrintType::get();
        $drintType->transform(function ($item) {
            $item->action = 1;
            if (!empty($item->image)) {
                $image_url = asset(Storage::url(config('constants.drinttype_img_path') . '/' . $item->image));
            } else {
                $image_url = asset('/img/default.png');
            }
            return [
                'id' => $item->id,
                'name' => $item->name,
                'image' => $image_url,
                'status' => $item->status,
                'action' => 1
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($drintType)
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
        return view('rest.drint-type.index');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('drink-type.create')) {
            abort(403, 'Unauthorized action.');
        }
        $drintType = new DrintType();
        $drintType->name = $request->name;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $new_file_name = time() . '_' . $request->image->getClientOriginalName();
            $image_path = config('constants.drinttype_img_path');
            $path = $request->image->storeAs($image_path, $new_file_name);
            if ($path) {
                $drintType->image = $new_file_name;
            }
        }

        $drintType->status = $request->has('status') ? 'Active' : 'Inactive';
        $drintType->save();

        return redirect()->route('drinttype.index')
            ->with('success', 'drintType successfully Created!!');
    }

    
    public function update(Request $request)
    {
        if (!auth()->user()->can('drink-type.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id; // id
        $drintType = DrintType::find($id);
        $drintType->name = $request->name;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $new_file_name = time() . '_' . $request->image->getClientOriginalName();
            $image_path = config('constants.drinttype_img_path');
            $path = $request->image->storeAs($image_path, $new_file_name);
            if ($path) {
                $drintType->image = $new_file_name;
            }
        }

        $drintType->status = $request->has('status') ? 'Active' : 'Inactive';
        $drintType->save();
        return redirect()->route('drinttype.index')
            ->with('success', 'drintType successfully Updated!!');
    }

    public function delete(Request $request)
    {
        if (!auth()->user()->can('drink-type.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $drintType =  DrintType::whereIn('id', $ids)->delete();;

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

    public function show($id)
    {
        $drintType =  DrintType::find($id);
        if (!empty($drintType->image)) {
            $image_url = asset(Storage::url(config('constants.drinttype_img_path') . '/' . $drintType->image));
        } else {
            $image_url = asset('/img/default.png');
        }
        $drintType->setAttribute('icon', $image_url);
        return $drintType;
    }
}
