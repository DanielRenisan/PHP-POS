<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('user.setting');
    }
    public function update(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = bcrypt($request->input('password'));
                $user->save();
                $output = ['success' => true,
                                'msg' => 'Password updated successfully'
                            ];
            } else {
                $output = ['success' => false,
                                'msg' => 'You have entered wrong password'
                            ];
            }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => false,
                            'msg' => 'Something went wrong, please try again'
                        ];
        }
        return $output;
    }

}
