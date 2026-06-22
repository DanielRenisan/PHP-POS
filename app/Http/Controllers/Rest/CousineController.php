<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Cousine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
class CousineController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('cousine.view') && !auth()->user()->can('cousine.create')) {
            abort(403, 'Unauthorized action.');
        }
        $cousine = Cousine::get();
        $cousine = $cousine->transform(function ($item) {
            $item->action = 1;

            if (!empty($item->image)) {
                $image_url = asset(Storage::url(config('constants.cousine_img_path') . '/' . $item->image));
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
            return Datatables::of($cousine)
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
        return view('rest.cousine.index');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('cousine.create')) {
            abort(403, 'Unauthorized action.');
        }
        $cousine = new Cousine();
        $cousine->name = $request->name;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $new_file_name = time() . '_' . $request->image->getClientOriginalName();
            $image_path = config('constants.cousine_img_path');
            $path = $request->image->storeAs($image_path, $new_file_name);
            if ($path) {
                $cousine->image = $new_file_name;
            }
        }

        $cousine->status = $request->has('status') ? 'Active' : 'Inactive';
        $cousine->save();

        return redirect()->route('cousine.index')
            ->with('success', 'Cousine successfully Created!!');
        
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('cousine.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id;
        $cousine = Cousine::find($id);
        $cousine->name = $request->name;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $new_file_name = time() . '_' . $request->image->getClientOriginalName();
            $image_path = config('constants.cousine_img_path');
            $path = $request->image->storeAs($image_path, $new_file_name);
            if ($path) {
                $cousine->image = $new_file_name;
            }
        }

        $cousine->status = $request->has('status') ? 'Active' : 'Inactive';
        $cousine->save();
        return redirect()->route('cousine.index')
            ->with('success', 'Cousine successfully Updated!!');
    }

    public function delete(Request $request)
    {
        if (!auth()->user()->can('cousine.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $cousine =  Cousine::whereIn('id', $ids)->delete();;

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
        $cousine = Cousine::find($id);
        if (!empty($cousine->image)) {
            $image_url = asset(Storage::url(config('constants.cousine_img_path') . '/' . $cousine->image));
        } else {
            $image_url = asset('/img/default.png');
        }
        $cousine->setAttribute('icon', $image_url);
        return $cousine;
    }

}
