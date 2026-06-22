<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'surname', 'first_name', 'last_name', 'username', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function permitted_locations()
    {
        if (auth()->user()->can('access_all_locations')) {
            return 'all';
        } else {
            $permitted_locations = [];
            $all_locations = BusinessLocation::get();
            foreach ($all_locations as $location) {
                if (auth()->user()->can('location.' . $location->id)) {
                    $permitted_locations[] = $location->id;
                }
            }
            
            return $permitted_locations;
        }
    }

    public static function permitted_users()
    {
        if (auth()->user()->can('all-users')) {
            return 'all';
        } elseif (auth()->user()->can('specific-user')) {
            
            $permission = Permission::where('name', 'specific-user')
                        ->where('guard_name', 'web')
                        ->first();
            $role = auth()->user()->roles()->first();
           
            $permitted_users = UserAccess::where('role_id', $role->id)->where('permission_id', $permission->id)->pluck('user_id')->toArray();
            
            if(empty($permitted_users))
            {
                $permitted_users[] = auth()->user()->id;
            }
            return $permitted_users;
        }else {
            $permitted_users[] = auth()->user()->id;
            return $permitted_users;
        }

    }
}
