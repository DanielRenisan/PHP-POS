<?php

namespace App\Http\Controllers\Rest;
use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\TableLocation;
use App\Models\BusinessLocation;
use App\Models\DepartmentPoss;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
class TableController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('table.view') && !auth()->user()->can('table.create')) {
            abort(403, 'Unauthorized action.');
        }
        $location_list = Floor::all();
        $table = Table::leftjoin('department_posses as dp', 'tables.department_id', '=', 'dp.id')
        ->select('tables.*','floors.name as location_name', 'floors.id as floor_id', 'dp.name as location')
        ->leftJoin('floors', 'floors.id','=','tables.table_location_id')
        ->get();
        
        $table = $table->transform(function ($item) {
            $item->action = 1;
            if (!empty($item->icon)) {
                $image_url = asset(Storage::url(config('constants.table_img_path') . '/' . $item->icon));
            } else {
                $image_url = asset('/img/default.png');
            }
            return [
                'id' => $item->id,
                'location_name' => $item->location_name,
                'table_name' => $item->table_name,
                'table_code' => $item->table_code,
                'location' => $item->location,
                'chair_no' => $item->chair_no,
                'image' => $image_url,
                'status' => $item->status,
                'action' => 1,
                'department_id' => $item->department_id,
                'floor_id' => $item->floor_id,
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($table)
            ->addColumn('action', function ($row) {
                $html = '<input type="checkbox" id="check-box" class="form-checkbox check-box" name="check_box[]" value="'.$row['id'].'"/>';
                return $html;
            })
            ->editColumn('image', function ($row) {
                return '<img style="border-radius: 5px; object-fit: cover;" class="w-8 h-8" src="'.$row['image'].'">';
            })
            ->editColumn('status', function ($row) {
                $span = '<span class="btn btn-danger btn-sm" style="width:80px;color:#fff;">InActive</span>';
                if($row['status'] == 'Active')
                {
                    $span = '<span class="btn btn-success btn-sm" style="width:50px;color:#fff;">Active</span>';
                }
                return $span;
            })
            ->rawColumns(['action', 'status','image'])
            ->make(true);
        }
        $business_locations = DepartmentPoss::where('status', 'Active')->pluck('name', 'id')->toArray();
        return view('rest.table.index', compact('location_list', 'business_locations'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('table.create')) {
            abort(403, 'Unauthorized action.');
        }
        $table = new Table();
        $table->table_location_id = $request->tablelocation_id;
        $table->table_name = $request->tablename;
        $table->table_code = $request->tablecode;
        $table->department_id = $request->department_id;
        $table->chair_no = $request->chairno;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $new_file_name = time() . '_' . $request->image->getClientOriginalName();
            $image_path = config('constants.table_img_path');
            $path = $request->image->storeAs($image_path, $new_file_name);
            if ($path) {
                $table->icon = $new_file_name;
            }
        }

        $table->status = $request->has('status') ? 'Active' : 'Inactive';
        $table->save();

        return redirect()->route('table.index')
            ->with('success', 'table successfully Created!!');
      
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('table.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id; // id
        $table = Table::find($id);
        $table->table_location_id = $request->tablelocation_id;
        $table->table_name = $request->tablename;
        $table->table_code = $request->tablecode;
        $table->department_id = $request->department_id;
        $table->chair_no = $request->chairno;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $new_file_name = time() . '_' . $request->image->getClientOriginalName();
            $image_path = config('constants.table_img_path');
            $path = $request->image->storeAs($image_path, $new_file_name);
            if ($path) {
                $table->icon = $new_file_name;
            }
        }

        $table->status = $request->has('status') ? 'Active' : 'Inactive';
        $table->save();
        return redirect()->route('table.index')
                ->with('success', 'table successfully Updated!!');
        
    }

    public function show($id)
    {
        $table = Table::find($id);
        if (!empty($table->icon)) {
            $image_url = asset(Storage::url(config('constants.table_img_path') . '/' . $table->icon));
        } else {
            $image_url = asset('/img/default.png');
        }
        $table->setAttribute('icons', $image_url);
        return $table;
    }

    public function delete(Request $request)
    {
        if (!auth()->user()->can('table.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $table =  Table::whereIn('id', $ids)->delete();;

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
    public function getTable(Request $request)
    {
        $department_id = $request->get('department_id');
        $tables = Table::where('status', 'Active');
        if(isset($department_id) && $department_id != 'all')
        {   
            $tables->where('department_id', $department_id);
        }
        $tables = $tables->get();
        $tables = $tables->transform(function($item) {
            $background = 'background-color: rgb(192, 219, 226)';
            $title = '';
            if($item->available_status == 0)
            {
                $background = 'background-color: rgb(192, 219, 226)';
                $title = 'Available';
            }
            if($item->available_status == 1)
            {
                $background = 'background-color: rgb(242, 149, 245)';
                $title = 'Booked';
            }

            if($item->available_status == 2)
            {
                $background = 'background-color: rgb(193, 192, 226)';
                $title = 'Order';
            }
            return [
                'id' => $item->id,
                'label' => $item->table_name,
                'style' => $background,
                'title' => $title,
            ];
        })->toArray();
        return $tables;
    }

}
