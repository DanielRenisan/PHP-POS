<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

use DB;

class ManageUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('user.view') && !auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $user_id = auth()->user()->id;

            $users = User::where('users.id', '!=', $user_id)
                        ->select(['users.id', 'users.username',
                            DB::raw("CONCAT(COALESCE(users.surname, ''), ' ', COALESCE(users.first_name, ''), ' ', COALESCE(users.last_name, '')) as full_name"), 'users.email']);

            return Datatables::of($users)
                ->addColumn(
                    'role',
                    '{{isset(App\Models\User::find($id)->getRoleNames()[0]) ? App\Models\User::find($id)->getRoleNames()[0] : ""}}'
                )
                ->addColumn(
                    'action',
                    '@can("user.update")
                    <a href="{{action(\'ManageUserController@edit\', [$id])}}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                        &nbsp;
                        @endcan
                        @can("user.delete")
                        <button data-href="{{action(\'ManageUserController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_user_button"><i class="glyphicon glyphicon-trash"></i>Delete</button>
                        @endcan'
                )
                ->removeColumn('id')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }

        $roles_array = Role::get()->pluck('name', 'id');
        
        $roles = [];
        foreach ($roles_array as $key => $value) {
            $roles[$key] = $value;
        }

        
        return view('user.create')
                    ->with(compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('user.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $user_details = $request->only(['surname', 'first_name', 'last_name', 'username', 'email', 'password']);
            $user_details['password'] = bcrypt($user_details['password']);  
            $user = User::create($user_details);

            $role_id = $request->input('role');
            $role = Role::findOrFail($role_id);
            $user->assignRole($role->name);

            $output = ['success' => 1,
                        'msg' => __("User Added")
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => $e->getMessage()
                        ];
        }

        return redirect('users')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('user.view')) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('user.update')) {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($id);

        $roles_array = Role::get()->pluck('name', 'id');
        $roles = [];
        foreach ($roles_array as $key => $value) {
            $roles[$key] = $value;
        }
        return view('user.edit')
                    ->with(compact('roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('user.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $user_data = $request->only(['surname', 'first_name', 'last_name', 'email']);

            if (!empty($request->input('password'))) {
                $user_data['password'] = bcrypt($request->input('password'));
            }

            //Sales commission percentage
            if ($request->has('cmmsn_percent')) {
                $user_data['cmmsn_percent'] = $request->get('cmmsn_percent');
            } else {
                $user_data['cmmsn_percent'] = 0;
            }

            if ($request->has('commission')) {
                $user_data['commission'] = $request->get('commission');
            } else {
                $user_data['commission'] = 0;
            }
            
            $user = User::findOrFail($id);
            $extra_details_id = $user->business_user_details_id;
           
            $user->update($user_data);

            $role_id = $request->input('role');
            $user_role = $user->roles->first();

            if ($user_role->id != $role_id) {
                $user->removeRole($user_role->name);

                $role = Role::findOrFail($role_id);
                $user->assignRole($role->name);
            }

            $output = ['success' => 1,
                        'msg' => __("user.user_update_success")
                    ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __("messages.something_went_wrong")
                        ];
        }

        return redirect('users')->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('user.delete')) {
            abort(403, 'Unauthorized action.');
        }


        if (request()->ajax()) {
            try {
                $user = User::where('id', $id)->first();
                
                $extra_details_id = $user->business_user_details_id;
                $extra_detail = BusinessUserDetail::where('id', $extra_details_id)->first();

                if($extra_detail->image != null){
                    Storage::disk('public')->delete('user_img/'.$extra_detail->image);
                }
                
                if($user != null){
                $user->delete();

                $output = ['success' => true,
                                'msg' => __("user.user_delete_success")
                                ];
                }

            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("messages.something_went_wrong")
                        ];
            }

            return $output;
        }
    }

    public function postCheckUsername(Request $request)
    {
        $username = $request->input('username');
        $count = User::where('username', $username)->count();
        if ($count == 0) {
            echo "true";
            exit;
        } else {
            echo "false";
            exit;
        }
    }
}
