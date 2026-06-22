<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\AttendanceType;
use Illuminate\Http\Request;

class AttendanceTypeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('attendance-type.view') && !auth()->user()->can('attendance-type.create')) {
            abort(403, 'Unauthorized action.');
        }

        $items = $request->items ?? 25;
        $attendances = AttendanceType::orderBy('id', 'DESC')->paginate($items);
        $attendances->transform(function ($item) {
            $item->action = 1;
            return [
                'id' => $item->id,
                'name' => $item->name,
                'start_time' => $item->start_time,
                'end_time' => $item->end_time,
                'status' => $item->status,
                'action' => 1
            ];
        });
        return view('rest.attedance_type.index', compact('attendances'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('attendance-type.create')) {
            abort(403, 'Unauthorized action.');
        };

        $Attendance = new AttendanceType();
        $Attendance->name = $request->name;
        $Attendance->start_time = $request->start_time;
        $Attendance->end_time = $request->end_time;
        $Attendance->status = $request->has('status') ? 'Active' : 'Inactive';
        $Attendance->save();

        return redirect()->route('attendance.index')
                ->with('success', 'customerGroup successfully Created!!');
        
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('attendance-type.update')) {
            abort(403, 'Unauthorized action.');
        }
        $id = $request->id;
        $Attendance = AttendanceType::find($id);
        $Attendance->name = $request->name;
        $Attendance->start_time = $request->start_time;
        $Attendance->end_time = $request->end_time;
        $Attendance->status = $request->has('status') ? 'Active' : 'Inactive';
        $Attendance->save();
        return redirect()->route('attendance.index')
            ->with('success', 'customerGroup successfully Updated!!');
    }


    //delete
    public function delete(Request $request)
    {
        if (!auth()->user()->can('attendance-type.delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $Attendance =  AttendanceType::whereIn('id', $ids)->delete();;

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
        $attendance = AttendanceType::find($id);

        return $attendance;
    }
}

