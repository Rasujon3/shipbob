<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Package;
use App\Models\Product;
use App\Models\RTTProduct;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Auth;
class DashboardController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth_check');
    }
    public function Dashboard()
    {
    	try
    	{
            $userCount = User::where('role', 'user')->count();
            $packageCount = Package::count();
            $productCount = Product::count();
            $eventCount = Event::count();
            $rttProductCount = RTTProduct::count();
    		return view('layouts.app', compact('userCount', 'packageCount', 'productCount', 'eventCount', 'rttProductCount'));
    	} catch(Exception $e) {

                $message = $e->getMessage();

                $code = $e->getCode();

                $string = $e->__toString();
                return response()->json(['message'=>$message, 'execption_code'=>$code, 'execption_string'=>$string]);
        }
    }
}
