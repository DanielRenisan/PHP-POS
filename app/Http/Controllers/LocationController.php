<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\BusinessLocation;
use App\Models\DepartmentPoss;
    
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Permission;
class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('business-location.index') || !auth()->user()->can('business-location.create')) {
            abort(403, 'Unauthorized action.');
        }

        $locations = BusinessLocation::select([
            'name', 'landmark', 'city', 'zip_code', 'state',
            'country', 'id', 'tin_number', 'reg_doc_no', 'fax_no',
            'mobile', 'alternate_number', 'email', 'country']);

        $permitted_locations = auth()->user()->permitted_locations();
        if ($permitted_locations != 'all') {
            $locations->whereIn('business_locations.id', $permitted_locations);
        }
        $locations =  $locations->get();

        $locations->transform(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'landmark' => $item->landmark,
                'city' => $item->city,
                'zip_code' => $item->zip_code,
                'state' => $item->state,
                'tin_number' => $item->tin_number,
                'reg_doc_no' => $item->reg_doc_no,
                'fax_no' => $item->fax_no,
                'mobile' => $item->mobile,
                'alternate_number' => $item->alternate_number,
                'email' => $item->email,
                'country' => $item->country,
            ];
        });
        if (request()->ajax()) {
            return Datatables::of($locations)
            ->make(true);
        }

        return view('business_location.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('business-location.create')) {
            abort(403, 'Unauthorized action.');
        }
        return view('business_location.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('business-location.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['name', 'landmark', 'city', 'state', 'country', 'zip_code', 'tin_number', 'reg_doc_no', 'fax_no',
                 'mobile', 'alternate_number', 'email']);
            $location = BusinessLocation::create($input);

            //Create a new permission related to the created location
            Permission::create(['name' => 'locations.' . $location->id ]);

            $output = ['success' => true,
                            'msg' => __("Business Location Added Success")
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' => __("Something Went Wrong")
                        ];
        }

        return redirect()->back();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StoreFront  $storeFront
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('business-location.update')) {
            abort(403, 'Unauthorized action.');
        }
        $location = BusinessLocation::find($id);

        return view('business_location.edit')
                ->with(compact('location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StoreFront  $storeFront
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!auth()->user()->can('business-location.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $id = $request->id;
            $input = $request->only(['name', 'landmark', 'city', 'state', 'country', 'zip_code', 'tin_number', 'reg_doc_no', 'fax_no',
                 'mobile', 'alternate_number', 'email']);
            BusinessLocation::where('id', $id)
                            ->update($input);

            $output = ['success' => true,
                            'msg' => __('Business Location Updated Success')
                        ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' => __("Something Went Wrong")
                        ];
        }

        return redirect('/business-locations');
    }

    
    
    public function checkLocationId(Request $request)
    {
        $location_id = $request->input('location_id');

        $valid = 'true';
        if (!empty($location_id)) {
            $hidden_id = $request->input('hidden_id');

            $query = BusinessLocation::where('location_id', $location_id);
            if (!empty($hidden_id)) {
                $query->where('id', '!=', $hidden_id);
            }
            $count = $query->count();
            if ($count > 0) {
                $valid = 'false';
            }
        }
        echo $valid;
        exit;
    }

    public function destroy(Request $request)
    {
        if (!auth()->user()->can('business-location.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $ids = $request->get('ids');
                $brand = Brands::whereIn('id', $ids)->delete();

                $output = ['success' => true,
                            'msg' => __("brand.deleted_success")
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

    public function getDeparment(Request $request)
    {
        $location_id = $request->get('location_id');
        $departments = DepartmentPoss::where('location_id', $location_id)->where('status', 'Active')
                        ->get();
        $departments = $departments->transform( function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name
            ];
        })->toArray();               
        return  $departments;                
    }

    public function show($id)
    {
        $location = BusinessLocation::find($id);

        return $location;
    }
}
