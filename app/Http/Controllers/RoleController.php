<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\UserAccess;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use App\Models\BusinessLocation;
use DB;
class RoleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('role.view') && !auth()->user()->can('role.create')) {
            abort(403, 'Unauthorized action.');
        }
        $items = $request->items ?? 25;
        $roles_data = Role::select(['name', 'id', 'is_default'])->paginate($items);
        $roles_data->getCollection()->transform(function($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'edit_url' => action('RoleController@edit', $item->id)
            ];
        });

        return view('role.index', compact('roles_data'));
    }

    public function create()
    {
        if (!auth()->user()->can('role.create')) {
            abort(403, 'Unauthorized action.');
        }
        $roles = Role::pluck('name', 'id')->toArray();
        $locations = BusinessLocation::all();
        return view('role.create', compact('locations', 'roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('role.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $role_id = $request->input('role_id');
            $permissions = $request->input('permissions');

            // $count = Role::where('i', $role_name)
                        // ->count();
            // if ($count == 0) {
                $role = Role::find($role_id);

                $location_permissions = $request->input('location_permissions');
                if (!in_array('access_all_locations', $permissions) &&
                    !empty($location_permissions)) {
                    foreach ($location_permissions as $location_permission) {
                        $permissions[] = $location_permission;
                    }
                }
                if (!empty($permissions)) {
                    $role->syncPermissions($permissions);
                }
                $output = ['success' => 1,
                            'msg' => __("Role Added")
                        ];
            // } else {
            //     $output = ['success' => 0,
            //                 'msg' => __("Role Already Exists")
            //             ];
            // }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __("Something Went Wrong")
                        ];
        }
        return redirect('roles')->with('status', $output);
        
    }

    public function edit($id)
    {
        if (!auth()->user()->can('role.update')) {
            abort(403, 'Unauthorized action.');
        }

        $role = Role::with(['permissions'])
                    ->find($id);
        $roles = Role::pluck('name', 'id')->toArray();
        $role_permissions = [];
        foreach ($role->permissions as $role_perm) {
            $role_permissions[] = $role_perm->name;
        }
        $employees =  User::get();
        $locations = BusinessLocation::get();
        $accesses = UserAccess::where('role_id', $role->id)
                                ->pluck('user_id')->toArray();
        return view('role.edit')
            ->with(compact('role', 'role_permissions', 'locations', 'roles', 'employees', 'accesses'));

    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('role.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $permissions = $request->input('permissions');
            // if ($count == 0) {
                $role = Role::findOrFail($id);
                if (!$role->is_default) {

                    // Include location permissions
                    $location_permissions = $request->input('location_permissions');
                    if (!in_array('access_all_locations', $permissions) &&
                        !empty($location_permissions)) {
                        foreach ($location_permissions as $location_permission) {
                            $permissions[] = $location_permission;
                        }
                    }
                    $employees =  $request->employees;
                    
                    if(isset($employees) && $employees[0] != null && !empty($permissions) && in_array('specific-user', $permissions))
                    {
                        $permission = Permission::where('name', 'specific-user')
                        ->where('guard_name', 'web')
                        ->first();
                        UserAccess::where('role_id', $role->id)->delete();
                        foreach($employees as $ky => $employee) {
                            $permiss = new UserAccess();
                            $permiss->role_id = $role->id;
                            $permiss->permission_id = $permission->id;
                            $permiss->user_id = $employee;
                            $permiss->save();
                        }

                    }

                    if (!empty($permissions)) {
                        $role->syncPermissions($permissions);
                    }
                    
                    $output = ['success' => 1,
                            'msg' => __("Role Updated")
                        ];
                } else {
                    $output = ['success' => 0,
                            'msg' => __("Role  is default")
                        ];
                }
            // } else {
            //     $output = ['success' => 0,
            //                 'msg' => __("Role Already Exists")
            //             ];
            // }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __("Something Went Wrong")
                        ];
        }

        return redirect('roles')->with('status', $output);

    }


    public function destroy($id)
    {
        if (!auth()->user()->can('role.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $role = Role::find($id);

                if (!$role->is_default) {
                    $role->delete();
                    $output = ['success' => true,
                            'msg' => __("Role Deleted")
                            ];
                } else {
                    $output = ['success' => 0,
                            'msg' => __("Role is Default")
                        ];
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("Something Went Wrong")
                        ];
            }

            return $output;
        }
    }

    public function deleteItem(Request $request)
    {
        if (!auth()->user()->can('role.delete')) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
           
            try {
                $ids = $request->get('ids');
                $role = Role::whereIn('id', $ids)->where('is_default', 0)->delete();
                

                $output = ['success' => true,
                            'msg' => __("Role Deleted")
                            ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("Something Went Wrong")
                        ];
            }

            return $output;
        }
    }

    public function show($id)
    {
    }
}
