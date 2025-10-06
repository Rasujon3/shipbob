<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AccessController extends Controller
{
    public function adminLogin(Request $request)
    {
    	try
        {
        	$data = $request->all();
		    	if(Auth::attempt(['username' => $data['username'], 'password' => $data['password']])){

		    		$notification = array(
		                     'message' => 'Successfully Logged In',
		                     'alert-type' => 'success'
		                    );

		           return redirect('/dashboard')->with($notification);
		    	} else {
		    		$notification = array(
		                     'message' => 'Username or Password Invalid',
		                     'alert-type' => 'error'
		                    );

		          return Redirect()->back()->with($notification);
	    	}
	   } catch(Exception $e){
            // Log the error
            Log::error('Error in Login: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );
            return Redirect()->back()->with($notification);
        }
    }

    public function Logout()
    {
    	try
    	{
            $redirectUrl = Auth::user()->role === 'user' ? '/user/login' : '/admin/login';
    		Auth::logout();
    		return redirect($redirectUrl);
    	} catch(Exception $e) {
            // Log the error
            Log::error('Error in Logout: ', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $notification=array(
                'message' => 'Something went wrong!!!',
                'alert-type' => 'error'
            );
            return Redirect()->back()->with($notification);
        }
    }
}
