<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomFacility;
use App\Models\RoomFacilityDetail;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class RoomDetailController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('room-details.view') && !auth()->user()->can('room-details.create')) {
            abort(403, 'Unauthorized action.');
        }
        $details = RoomFacilityDetail::
                    join('room_facilities', 'room_facility_details.room_facility_id', '=', 'room_facilities.id')
                    ->select([
                        'room_facility_details.name', 
                        'room_facilities.name as facility',
                        'room_facility_details.image as facility_image',
                        'room_facility_details.room_facility_id', 
                        'room_facility_details.id'])->get();
        $details  = $details->transform(function($item){
            if (!empty($item->facility_image)) {
                $image_url = asset(Storage::url(config('constants.facility_img_path') . '/' . $item->facility_image));
            } else {
                $image_url = asset('/img/default.png');
            }
            return [
                'id' => $item->id,
                'name' => $item->name,
                'facility' => $item->facility,
                'image' => $image_url,
                'action' => 1,
                'facility_id' => $item->room_facility_id
            ];
        })->toArray();
        $facilities = RoomFacility::forDropdown();
        return view('room_detail.index', compact('facilities'))
        ->with('details',json_encode($details,JSON_NUMERIC_CHECK));
    }

    public function create()
    {
        if (!auth()->user()->can('room-details.create')) {
            abort(403, 'Unauthorized action.');
        }
        $facilities = RoomFacility::forDropdown();
        return view('room_detail.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('room-details.create')) {
            abort(403, 'Unauthorized action.');
        }
        $detail = new RoomFacilityDetail();
        $msg  = 'Created';
        $detail->room_facility_id = $request->room_facility_id;
        $detail->name = $request->name;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // if ($request->image->getSize() <= config('constants.image_size_limit')) {
                $new_file_name = time() . '_' . $request->image->getClientOriginalName();
                $image_path = config('constants.facility_img_path');
                $path = $request->image->storeAs($image_path, $new_file_name);
                if ($path) {
                    $detail->image = $new_file_name;
                }
            // }
        }
        $detail->save();

        return redirect("room-details")->with("msg",$msg);
    }

    public function edit($id)
    {
        if (!auth()->user()->can('room-details.update')) {
            abort(403, 'Unauthorized action.');
        }
        $detail =  RoomFacilityDetail::findOrFail($id);
        $facilities = RoomFacility::forDropdown();
        return view('room_detail.edit', compact('detail', 'facilities'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('room-details.update')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            
            $msg  = 'Updated';
            $detail =  RoomFacilityDetail::findOrFail($id);
            $detail->room_facility_id = $request->room_facility_id;
            $detail->name = $request->name;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                if ($request->image->getSize() <= config('constants.image_size_limit')) {
                    $new_file_name = time() . '_' . $request->image->getClientOriginalName();
                    $image_path = config('constants.facility_img_path');
                    $path = $request->image->storeAs($image_path, $new_file_name);
                    if ($path) {
                        $detail->image = $new_file_name;
                    }
                }
            }
            $detail->save();
            $output = ['success' => true,
                            'msg' => $msg
                            ];
            return $output;
        }
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('room-details.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $detail =  RoomFacilityDetail::whereIn('id', $ids)->delete();

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
